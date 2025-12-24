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
}
