<?php

class SharedResource
{
	use Model;
	private $resourceReportsTableExists = null;

	protected $table = 'resources';
	protected $order_column = 'created_at';
	protected $id_column = 'resource_id';

	protected $allowedColumns = [
		'user_id',
		'title',
		'description',
		'category',
		'file_name',
		'file_path',
		'file_size',
		'file_type',
		'downloads',
		'likes',
		'status',
		'faculty_id',
		'created_at',
		'updated_at',
	];

	private function reportsTableExists()
	{
		if ($this->resourceReportsTableExists !== null) {
			return $this->resourceReportsTableExists;
		}

		$result = $this->query(
			"SELECT COUNT(*) AS total
			 FROM information_schema.TABLES
			 WHERE TABLE_SCHEMA = DATABASE()
			   AND TABLE_NAME = :table_name",
			['table_name' => 'resource_reports']
		);

		$this->resourceReportsTableExists = is_array($result)
			&& !empty($result)
			&& ((int)($result[0]->total ?? 0) > 0);

		return $this->resourceReportsTableExists;
	}

	private function buildViewerHiddenClause($resourceAlias = 'r', $viewerParam = 'viewer_user_id')
	{
		if (!$this->reportsTableExists()) {
			return '';
		}

		return " AND NOT EXISTS (
			SELECT 1 FROM resource_reports rr
			WHERE rr.resource_id = {$resourceAlias}.resource_id
			  AND rr.reporter_user_id = :{$viewerParam}
		)";
	}

	private function buildGlobalThresholdClause($resourceAlias = 'r', $thresholdParam = 'report_threshold')
	{
		if (!$this->reportsTableExists()) {
			return " AND (COALESCE({$resourceAlias}.is_reported, 0) = 0)";
		}

		return " AND (
			SELECT COUNT(*)
			FROM resource_reports rr_all
			WHERE rr_all.resource_id = {$resourceAlias}.resource_id
		) < :{$thresholdParam}";
	}

	public function getResourceCategories()
	{
		return [
			[
				'value' => 'lecture-notes',
				'label' => 'Lecture Notes',
				'icon' => 'fa-file-alt',
				'description' => 'Class notes, summaries, and study guides',
			],
			[
				'value' => 'al',
				'label' => 'AL',
				'icon' => 'fa-language',
				'description' => 'Automata, languages, and formal grammar material',
			],
			[
				'value' => 'computational-model-theory',
				'label' => 'Computational Model Theory',
				'icon' => 'fa-diagram-project',
				'description' => 'Models, proofs, and theoretical computation resources',
			],
			[
				'value' => 'algorithms',
				'label' => 'Algorithms',
				'icon' => 'fa-sitemap',
				'description' => 'Algorithm design, analysis, and problem-solving notes',
			],
			[
				'value' => 'programming',
				'label' => 'Programming',
				'icon' => 'fa-code',
				'description' => 'Code samples, templates, and language references',
			],
			[
				'value' => 'software-tools',
				'label' => 'Software & Tools',
				'icon' => 'fa-laptop-code',
				'description' => 'Utilities, apps, and development tooling',
			],
		];
	}

	protected function normalizeSearchTerms($search)
	{
		$search = trim((string)$search);
		if ($search === '') {
			return [];
		}

		$parts = preg_split('/[\,;\n]+/', $search);
		if (!is_array($parts) || empty($parts)) {
			$parts = [$search];
		}

		$terms = [];
		foreach ($parts as $part) {
			$term = trim((string)$part);
			if ($term === '') {
				continue;
			}
			$term = ltrim($term, '#');
			if ($term !== '') {
				$terms[strtolower($term)] = $term;
			}
		}

		return array_values($terms);
	}

	protected function buildSearchClause($search, array &$params, $paramPrefix = 'search')
	{
		$terms = $this->normalizeSearchTerms($search);
		if (empty($terms)) {
			return '';
		}

		$clauses = [];
		foreach ($terms as $index => $term) {
			$paramKey = $paramPrefix . $index;
			$clauses[] = "(LOWER(r.title) LIKE LOWER(:{$paramKey}) OR LOWER(r.description) LIKE LOWER(:{$paramKey}) OR LOWER(COALESCE(r.category, '')) LIKE LOWER(:{$paramKey}) OR LOWER(REPLACE(REPLACE(COALESCE(r.category, ''), '-', ' '), '_', ' ')) LIKE LOWER(:{$paramKey}))";
			$params[$paramKey] = '%' . $term . '%';
		}

		return '(' . implode(' OR ', $clauses) . ')';
	}

	/**
	 * Get resources with filtering for deleted users
	 * Only returns resources from active (non-deleted) users
	 */
	public function getActiveResources($conditions = [])
	{
		$where_clauses = [];
		$params = [];

		// Add custom conditions
		foreach ($conditions as $key => $value) {
			$where_clauses[] = "r.$key = :$key";
			$params[$key] = $value;
		}

		$where_sql = !empty($where_clauses) ? 'AND ' . implode(' AND ', $where_clauses) : '';

		$query = "SELECT r.*, u.name as author_name
				  FROM {$this->table} r
				  JOIN users u ON r.user_id = u.user_id
				  LEFT JOIN students s ON u.user_id = s.user_id
				  LEFT JOIN alumnis a ON u.user_id = a.user_id
				  WHERE ((u.role = 'student' AND (s.is_deleted IS NULL OR s.is_deleted = 0))
				     OR (u.role = 'alumni' AND (a.is_deleted IS NULL OR a.is_deleted = 0))
				     OR (u.role NOT IN ('student', 'alumni')))
				  $where_sql
				  ORDER BY r.created_at DESC";

		return $this->query($query, $params);
	}

	/**
	 * Get recent resources visible to a user based on their faculty_id
	 * Shows resources with matching faculty_id OR faculty_id = 999 (All Faculties)
	 * Excludes resources uploaded by the current user
	 */
	public function getRecentResourcesByFaculty($user_faculty_id, $limit = 3, $viewer_user_id = null)
	{
		// Cast limit to integer for SQL safety
		$limit = (int)$limit;
		$viewerClause = '';
		$excludeOwnClause = '';
		$params = [
			'faculty_id' => $user_faculty_id,
		];
		if ($viewer_user_id !== null && (int)$viewer_user_id > 0) {
			$viewerClause = $this->buildViewerHiddenClause('r', 'viewer_user_id');
			$excludeOwnClause = 'AND r.user_id <> :viewer_user_id';
			$params['viewer_user_id'] = (int)$viewer_user_id;
		}
		$thresholdClause = $this->buildGlobalThresholdClause('r', 'report_threshold');
		if (strpos($thresholdClause, ':report_threshold') !== false) {
			$params['report_threshold'] = 5;
		}
		
		$query = "SELECT r.*, u.name as author_name
				  FROM {$this->table} r
				  JOIN users u ON r.user_id = u.user_id
				  LEFT JOIN students s ON u.user_id = s.user_id
				  LEFT JOIN alumnis a ON u.user_id = a.user_id
				  WHERE ((u.role = 'student' AND (s.is_deleted IS NULL OR s.is_deleted = 0))
				     OR (u.role = 'alumni' AND (a.is_deleted IS NULL OR a.is_deleted = 0))
				     OR (u.role NOT IN ('student', 'alumni')))
				  AND (r.faculty_id = :faculty_id OR r.faculty_id = 999)
				  AND r.status = 'approved'
				  {$excludeOwnClause}
				  {$thresholdClause}
				  {$viewerClause}
				  ORDER BY r.created_at DESC
				  LIMIT {$limit}";

		return $this->query($query, $params);
	}

	/**
	 * Browse resources with optional category and search filters
	 * Shows resources visible to user based on faculty_id
	 */
	public function browseResources($user_faculty_id, $category = '', $search = '', $viewer_user_id = null)
	{
		$where_clauses = [];
		$params = ['faculty_id' => $user_faculty_id];
		$viewerClause = '';
		if ($viewer_user_id !== null && (int)$viewer_user_id > 0) {
			$viewerClause = $this->buildViewerHiddenClause('r', 'viewer_user_id');
			if ($viewerClause !== '') {
				$params['viewer_user_id'] = (int)$viewer_user_id;
			}
		}
		$thresholdClause = $this->buildGlobalThresholdClause('r', 'report_threshold');
		if (strpos($thresholdClause, ':report_threshold') !== false) {
			$params['report_threshold'] = 5;
		}

		// Add category filter
		if (!empty($category)) {
			$where_clauses[] = "r.category = :category";
			$params['category'] = $category;
		}

		// Add search filter across title, description, and tags
		$search_clause = $this->buildSearchClause($search, $params);
		if ($search_clause !== '') {
			$where_clauses[] = $search_clause;
		}

		$additional_where = !empty($where_clauses) ? 'AND ' . implode(' AND ', $where_clauses) : '';

		$query = "SELECT r.*, u.name as author_name
				  FROM {$this->table} r
				  JOIN users u ON r.user_id = u.user_id
				  LEFT JOIN students s ON u.user_id = s.user_id
				  LEFT JOIN alumnis a ON u.user_id = a.user_id
				  WHERE ((u.role = 'student' AND (s.is_deleted IS NULL OR s.is_deleted = 0))
				     OR (u.role = 'alumni' AND (a.is_deleted IS NULL OR a.is_deleted = 0))
				     OR (u.role NOT IN ('student', 'alumni')))
				  AND (r.faculty_id = :faculty_id OR r.faculty_id = 999)
				  AND r.status = 'approved'
				  {$thresholdClause}
				  {$viewerClause}
				  {$additional_where}
				  ORDER BY r.created_at DESC";

		return $this->query($query, $params);
	}

	/**
	 * Increment download count for a resource
	 */
	public function incrementDownloads($resource_id)
	{
		$query = "UPDATE {$this->table} SET downloads = downloads + 1 WHERE resource_id = :resource_id";
		return $this->query($query, ['resource_id' => $resource_id]);
	}

	/**
	 * Get count of resources by category for a specific faculty
	 */
	public function getCategoryCounts($user_faculty_id, $viewer_user_id = null)
	{
		$viewerClause = '';
		$params = ['faculty_id' => $user_faculty_id];
		if ($viewer_user_id !== null && (int)$viewer_user_id > 0) {
			$viewerClause = $this->buildViewerHiddenClause('r', 'viewer_user_id');
			if ($viewerClause !== '') {
				$params['viewer_user_id'] = (int)$viewer_user_id;
			}
		}
		$thresholdClause = $this->buildGlobalThresholdClause('r', 'report_threshold');
		if (strpos($thresholdClause, ':report_threshold') !== false) {
			$params['report_threshold'] = 5;
		}

		$query = "SELECT r.category, COUNT(*) as count
				  FROM {$this->table} r
				  JOIN users u ON r.user_id = u.user_id
				  LEFT JOIN students s ON u.user_id = s.user_id
				  LEFT JOIN alumnis a ON u.user_id = a.user_id
				  WHERE ((u.role = 'student' AND (s.is_deleted IS NULL OR s.is_deleted = 0))
				     OR (u.role = 'alumni' AND (a.is_deleted IS NULL OR a.is_deleted = 0))
				     OR (u.role NOT IN ('student', 'alumni')))
				  AND (r.faculty_id = :faculty_id OR r.faculty_id = 999)
				  AND r.status = 'approved'
				  {$thresholdClause}
				  {$viewerClause}
				  GROUP BY r.category";

		$result = $this->query($query, $params);
		
		// Convert to associative array
		$counts = [];
		if (is_array($result)) {
			foreach ($result as $row) {
				$counts[$row->category] = (int)$row->count;
			}
		}
		
		return $counts;
	}

	/**
	 * Report a resource
	 */
	public function reportResource($resource_id, $reason)
	{
		$query = "UPDATE {$this->table} 
				  SET is_reported = 1, rep_reason = :reason 
				  WHERE resource_id = :resource_id";
		return $this->query($query, [
			'resource_id' => $resource_id,
			'reason' => $reason
		]);
	}

	public function submitResourceReport($resource_id, $reporter_user_id, $reason, $threshold = 5)
	{
		$resource_id = (int)$resource_id;
		$reporter_user_id = (int)$reporter_user_id;
		$threshold = max(1, (int)$threshold);

		if ($resource_id <= 0 || $reporter_user_id <= 0) {
			return ['success' => false, 'message' => 'Invalid report data'];
		}

		if (!$this->reportsTableExists()) {
			return ['success' => false, 'message' => 'Reporting storage is not configured'];
		}

		$existing = $this->query(
			"SELECT report_id FROM resource_reports WHERE resource_id = :resource_id AND reporter_user_id = :reporter_user_id LIMIT 1",
			[
				'resource_id' => $resource_id,
				'reporter_user_id' => $reporter_user_id,
			]
		);

		if (is_array($existing) && !empty($existing)) {
			return ['success' => false, 'message' => 'You have already reported this resource'];
		}

		$insertOk = $this->query(
			"INSERT INTO resource_reports (resource_id, reporter_user_id, reason, created_at) VALUES (:resource_id, :reporter_user_id, :reason, :created_at)",
			[
				'resource_id' => $resource_id,
				'reporter_user_id' => $reporter_user_id,
				'reason' => $reason,
				'created_at' => date('Y-m-d H:i:s'),
			]
		);

		if ($insertOk === false) {
			return ['success' => false, 'message' => 'Failed to save report'];
		}

		$countResult = $this->query(
			"SELECT COUNT(*) as total FROM resource_reports WHERE resource_id = :resource_id",
			['resource_id' => $resource_id]
		);
		$totalReports = (is_array($countResult) && isset($countResult[0])) ? (int)$countResult[0]->total : 0;

		$globallyHidden = $totalReports >= $threshold;
		if ($globallyHidden) {
			$this->query(
				"UPDATE {$this->table} SET is_reported = 1, rep_reason = :reason WHERE resource_id = :resource_id",
				[
					'resource_id' => $resource_id,
					'reason' => 'Auto hidden after ' . $totalReports . ' reports',
				]
			);
		}

		return [
			'success' => true,
			'total_reports' => $totalReports,
			'threshold' => $threshold,
			'globally_hidden' => $globallyHidden,
		];
	}

	/**
	 * Get resource statistics for a user
	 */
	public function getUserStats($user_id, $user_faculty_id, $viewer_user_id = null)
	{
		// Get count of user's resources
		$my_resources_query = "SELECT COUNT(*) as count FROM {$this->table} WHERE user_id = :user_id AND status = 'approved'";
		$my_resources_result = $this->query($my_resources_query, ['user_id' => $user_id]);
		$my_resources_count = is_array($my_resources_result) && isset($my_resources_result[0]) ? (int)$my_resources_result[0]->count : 0;

		// Get total downloads of user's resources
		$my_downloads_query = "SELECT SUM(downloads) as total FROM {$this->table} WHERE user_id = :user_id AND status = 'approved'";
		$my_downloads_result = $this->query($my_downloads_query, ['user_id' => $user_id]);
		$my_downloads_count = is_array($my_downloads_result) && isset($my_downloads_result[0]) ? (int)$my_downloads_result[0]->total : 0;

		// Get total number of resources available to user (based on faculty)
		$viewerClause = '';
		$params = ['faculty_id' => $user_faculty_id];
		if ($viewer_user_id !== null && (int)$viewer_user_id > 0) {
			$viewerClause = $this->buildViewerHiddenClause('r', 'viewer_user_id');
			if ($viewerClause !== '') {
				$params['viewer_user_id'] = (int)$viewer_user_id;
			}
		}
		$thresholdClause = $this->buildGlobalThresholdClause('r', 'report_threshold');
		if (strpos($thresholdClause, ':report_threshold') !== false) {
			$params['report_threshold'] = 5;
		}

		$total_resources_query = "SELECT COUNT(*) as count
								  FROM {$this->table} r
								  JOIN users u ON r.user_id = u.user_id
								  LEFT JOIN students s ON u.user_id = s.user_id
								  LEFT JOIN alumnis a ON u.user_id = a.user_id
								  WHERE ((u.role = 'student' AND (s.is_deleted IS NULL OR s.is_deleted = 0))
								     OR (u.role = 'alumni' AND (a.is_deleted IS NULL OR a.is_deleted = 0))
								     OR (u.role NOT IN ('student', 'alumni')))
								  AND (r.faculty_id = :faculty_id OR r.faculty_id = 999)
								  AND r.status = 'approved'
								  {$thresholdClause}
								  {$viewerClause}";
		$total_resources_result = $this->query($total_resources_query, $params);
		$total_resources_count = is_array($total_resources_result) && isset($total_resources_result[0]) ? (int)$total_resources_result[0]->count : 0;

		return [
			'total_resources' => $total_resources_count,
			'my_resources' => $my_resources_count,
			'my_downloads' => $my_downloads_count
		];
	}

	/**
	 * Get reported resources for a specific faculty
	 */
	public function getReportedResourcesByFaculty($faculty_id)
	{
		$query = "SELECT r.*, u.name as author_name, u.email as author_email,
					 s.student_id, a.alumni_id, u.role as uploader_role,
					 CASE
						 WHEN u.role = 'student' THEN COALESCE(s.is_suspended, 0)
						 WHEN u.role = 'alumni' THEN COALESCE(a.is_suspended, 0)
						 ELSE 0
					 END as user_is_suspended,
					 CASE 
						 WHEN u.role = 'student' THEN 'Student'
						 WHEN u.role = 'alumni' THEN 'Alumni'
						 ELSE u.role
					 END as user_role
				  FROM {$this->table} r
				  JOIN users u ON r.user_id = u.user_id
				  LEFT JOIN students s ON u.user_id = s.user_id
				  LEFT JOIN alumnis a ON u.user_id = a.user_id
				  WHERE r.is_reported = 1
				  AND r.faculty_id = :faculty_id
				  ORDER BY r.updated_at DESC, r.created_at DESC";

		return $this->query($query, ['faculty_id' => $faculty_id]);
	}

	/**
	 * Get resource statistics for faculty admin
	 */
	public function getFacultyResourceStats($faculty_id)
	{
		// Total resources for this faculty
		$total_query = "SELECT COUNT(*) as count FROM {$this->table} WHERE faculty_id = :faculty_id AND status = 'approved'";
		$total_result = $this->query($total_query, ['faculty_id' => $faculty_id]);
		$total_resources = is_array($total_result) && isset($total_result[0]) ? (int)$total_result[0]->count : 0;

		// Reported resources for this faculty
		$reported_query = "SELECT COUNT(*) as count FROM {$this->table} WHERE faculty_id = :faculty_id AND is_reported = 1";
		$reported_result = $this->query($reported_query, ['faculty_id' => $faculty_id]);
		$reported_resources = is_array($reported_result) && isset($reported_result[0]) ? (int)$reported_result[0]->count : 0;

		// Total downloads for this faculty
		$downloads_query = "SELECT SUM(downloads) as total FROM {$this->table} WHERE faculty_id = :faculty_id AND status = 'approved'";
		$downloads_result = $this->query($downloads_query, ['faculty_id' => $faculty_id]);
		$total_downloads = is_array($downloads_result) && isset($downloads_result[0]) ? (int)$downloads_result[0]->total : 0;

		return [
			'total_resources' => $total_resources,
			'reported_resources' => $reported_resources,
			'pending_reports' => $reported_resources, // Same as reported for now
			'approved_resources' => $total_resources - $reported_resources,
			'total_downloads' => $total_downloads
		];
	}

	/**
	 * Remove report flag from a resource
	 */
	public function removeFlagFromResource($resource_id)
	{
		$query = "UPDATE {$this->table} 
				  SET is_reported = 0, rep_reason = NULL 
				  WHERE resource_id = :resource_id";
		return $this->query($query, [
			'resource_id' => $resource_id
		]);
	}

	/**
	 * Get all reported resources (for super admin - no faculty filter)
	 */
	public function getAllReportedResources()
	{
		$query = "SELECT r.*, u.name as author_name, u.email as author_email,
					 s.student_id, a.alumni_id, u.role as uploader_role,
					 CASE
						 WHEN u.role = 'student' THEN COALESCE(s.is_suspended, 0)
						 WHEN u.role = 'alumni' THEN COALESCE(a.is_suspended, 0)
						 ELSE 0
					 END as user_is_suspended,
					 CASE 
						 WHEN u.role = 'student' THEN 'Student'
						 WHEN u.role = 'alumni' THEN 'Alumni'
						 WHEN u.role = 'faculty_admin' THEN 'Faculty Admin'
						 ELSE u.role
					 END as user_role,
					 f.faculty_name
				  FROM {$this->table} r
				  JOIN users u ON r.user_id = u.user_id
				  LEFT JOIN students s ON u.user_id = s.user_id
				  LEFT JOIN alumnis a ON u.user_id = a.user_id
				  LEFT JOIN faculties f ON r.faculty_id = f.faculty_id
				  WHERE r.is_reported = 1 
				    AND r.status = 'approved'
				  ORDER BY r.updated_at DESC";
		return $this->query($query);
	}

	/**
	 * Get recent resources from all faculties (for super admin)
	 */
	public function getAllRecentResources($limit = 3)
	{
		$query = "SELECT r.*, u.name as author_name, f.faculty_name
				  FROM {$this->table} r
				  JOIN users u ON r.user_id = u.user_id
				  LEFT JOIN faculties f ON r.faculty_id = f.faculty_id
				  WHERE r.is_reported = 0 
				    AND r.status = 'approved'
				  ORDER BY r.created_at DESC 
				  LIMIT " . (int)$limit;
		return $this->query($query);
	}

	/**
	 * Browse resources from all faculties with optional category and search filters
	 */
	public function browseAllResources($category = '', $search = '')
	{
		$params = [];
		$conditions = ["r.is_reported = 0", "r.status = 'approved'"];

		if (!empty($category)) {
			$conditions[] = "r.category = :category";
			$params['category'] = $category;
		}

		$search_clause = $this->buildSearchClause($search, $params);
		if ($search_clause !== '') {
			$conditions[] = $search_clause;
		}

		$where = implode(' AND ', $conditions);

		$query = "SELECT r.*, u.name as author_name, f.faculty_name
				  FROM {$this->table} r
				  JOIN users u ON r.user_id = u.user_id
				  LEFT JOIN faculties f ON r.faculty_id = f.faculty_id
				  WHERE {$where}
				  ORDER BY r.created_at DESC";

		return $this->query($query, $params);
	}

	/**
	 * Get category counts for all faculties
	 */
	public function getAllCategoryCounts()
	{
		$categories = array_column($this->getResourceCategories(), 'value');
		$counts = [];

		foreach ($categories as $cat) {
			$query = "SELECT COUNT(*) as count 
					  FROM {$this->table} r
					  JOIN users u ON r.user_id = u.user_id
					  WHERE r.category = :category 
					    AND r.is_reported = 0 
					    AND r.status = 'approved'";
			$result = $this->query($query, ['category' => $cat]);
			$counts[$cat] = is_array($result) && isset($result[0]) ? (int)$result[0]->count : 0;
		}

		return $counts;
	}

	/**
	 * Get resource statistics for all faculties
	 */
	public function getAllResourceStats()
	{
		// Get total resources count
		$total_query = "SELECT COUNT(*) as count 
						FROM {$this->table} r
						JOIN users u ON r.user_id = u.user_id
						WHERE r.status = 'approved'";
		$total_result = $this->query($total_query);
		$total_resources = is_array($total_result) && isset($total_result[0]) ? (int)$total_result[0]->count : 0;

		// Get reported resources count
		$reported_query = "SELECT COUNT(*) as count 
						   FROM {$this->table} r
						   JOIN users u ON r.user_id = u.user_id
						   WHERE r.is_reported = 1 
						     AND r.status = 'approved'";
		$reported_result = $this->query($reported_query);
		$reported_resources = is_array($reported_result) && isset($reported_result[0]) ? (int)$reported_result[0]->count : 0;

		// Get total downloads
		$downloads_query = "SELECT SUM(downloads) as total 
							FROM {$this->table} r
							JOIN users u ON r.user_id = u.user_id
							WHERE r.status = 'approved'";
		$downloads_result = $this->query($downloads_query);
		$total_downloads = is_array($downloads_result) && isset($downloads_result[0]) ? (int)$downloads_result[0]->total : 0;

		return [
			'total_resources' => $total_resources,
			'reported_resources' => $reported_resources,
			'pending_reports' => $reported_resources, // Same as reported for now
			'approved_resources' => $total_resources - $reported_resources,
			'total_downloads' => $total_downloads
		];
	}
}
