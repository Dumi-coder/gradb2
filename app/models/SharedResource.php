<?php

class SharedResource
{
	use Model;

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
	public function getRecentResourcesByFaculty($user_faculty_id, $limit = 3)
	{
		// Cast limit to integer for SQL safety
		$limit = (int)$limit;
		
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
				  AND (r.is_reported IS NULL OR r.is_reported = 0)
				  ORDER BY r.created_at DESC
				  LIMIT {$limit}";

		$params = [
			'faculty_id' => $user_faculty_id
		];

		return $this->query($query, $params);
	}

	/**
	 * Browse resources with optional category and search filters
	 * Shows resources visible to user based on faculty_id
	 */
	public function browseResources($user_faculty_id, $category = '', $search = '')
	{
		$where_clauses = [];
		$params = ['faculty_id' => $user_faculty_id];

		// Add category filter
		if (!empty($category)) {
			$where_clauses[] = "r.category = :category";
			$params['category'] = $category;
		}

		// Add search filter
		if (!empty($search)) {
			$where_clauses[] = "(r.title LIKE :search OR r.description LIKE :search)";
			$params['search'] = '%' . $search . '%';
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
				  AND (r.is_reported IS NULL OR r.is_reported = 0)
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
	public function getCategoryCounts($user_faculty_id)
	{
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
				  AND (r.is_reported IS NULL OR r.is_reported = 0)
				  GROUP BY r.category";

		$result = $this->query($query, ['faculty_id' => $user_faculty_id]);
		
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

	/**
	 * Get resource statistics for a user
	 */
	public function getUserStats($user_id, $user_faculty_id)
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
								  AND (r.is_reported IS NULL OR r.is_reported = 0)";
		$total_resources_result = $this->query($total_resources_query, ['faculty_id' => $user_faculty_id]);
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
					 s.student_id, a.alumni_id,
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
					 s.student_id, a.alumni_id,
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

		if (!empty($search)) {
			$conditions[] = "(r.title LIKE :search OR r.description LIKE :search)";
			$params['search'] = '%' . $search . '%';
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
		$categories = ['lecture-notes', 'assignments', 'textbooks', 'software'];
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
