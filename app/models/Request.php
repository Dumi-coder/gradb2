<?php

class Request
{
	use Model;

	protected $table = 'requests';
	protected $id_column = 'request_id';
	protected $allowedColumns = [
		'student_user_id',
		'alumnus_user_id',
		'request_type',
		'status',
		'created_at',
	];

	public function getPendingAidRequestsForCounsellor()
	{
		$query = "SELECT r.request_id, r.student_user_id, r.alumnus_user_id, r.status, r.created_at,
						 u.name AS student_name, u.email AS student_email,
						 au.name AS alumnus_name, au.email AS alumnus_email,
						 a.mobile AS alumnus_mobile,
						 s.student_id, s.academic_year,
						 f.faculty_name,
						 ar.mobile_number, ar.aid_type, ar.amount, ar.reason,
						 ar.student_id_pdf_path, ar.income_statement_path, ar.gramaseva_cert_path,
						 (
							 SELECT REPLACE(rl.notes, '[COUNSELLOR REJECTED] ', '')
							 FROM request_logs rl
							 WHERE rl.request_id = r.request_id
							   AND rl.notes LIKE '[COUNSELLOR REJECTED] %'
							 ORDER BY rl.log_timestamp DESC, rl.log_id DESC
							 LIMIT 1
						 ) AS rejection_reason,
						 (
							 SELECT REPLACE(rl.notes, '[COUNSELLOR COMPLETED] ', '')
							 FROM request_logs rl
							 WHERE rl.request_id = r.request_id
							   AND rl.notes LIKE '[COUNSELLOR COMPLETED] %'
							 ORDER BY rl.log_timestamp DESC, rl.log_id DESC
							 LIMIT 1
						 ) AS completion_note
				  FROM requests r
				  JOIN users u ON u.user_id = r.student_user_id
				  LEFT JOIN users au ON au.user_id = r.alumnus_user_id
				  LEFT JOIN alumnis a ON a.user_id = r.alumnus_user_id
				  LEFT JOIN students s ON s.user_id = r.student_user_id
				  LEFT JOIN faculties f ON f.faculty_id = s.faculty_id
				  LEFT JOIN aid_requests ar ON ar.request_id = r.request_id
				  WHERE r.request_type = 'aid'
					AND r.status = 'pending_verification'
					AND (s.is_deleted IS NULL OR s.is_deleted = 0)
				  ORDER BY r.created_at DESC";

		$result = $this->query($query);
		return is_array($result) ? $result : [];
	}

	public function getAidRequestsForCounsellorByStatuses($statuses = [])
	{
		if (!is_array($statuses) || empty($statuses)) {
			return [];
		}

		$placeholders = [];
		$data = [];
		foreach (array_values($statuses) as $index => $status) {
			$key = 'status_' . $index;
			$placeholders[] = ':' . $key;
			$data[$key] = $status;
		}

		$query = "SELECT r.request_id, r.student_user_id, r.alumnus_user_id, r.status, r.created_at,
						 u.name AS student_name, u.email AS student_email,
						 au.name AS alumnus_name, au.email AS alumnus_email,
						 a.mobile AS alumnus_mobile,
						 s.student_id, s.academic_year,
						 f.faculty_name,
						 ar.mobile_number, ar.aid_type, ar.amount, ar.reason,
						 ar.student_id_pdf_path, ar.income_statement_path, ar.gramaseva_cert_path,
						 (
							 SELECT REPLACE(rl.notes, '[COUNSELLOR REJECTED] ', '')
							 FROM request_logs rl
							 WHERE rl.request_id = r.request_id
							   AND rl.notes LIKE '[COUNSELLOR REJECTED] %'
							 ORDER BY rl.log_timestamp DESC, rl.log_id DESC
							 LIMIT 1
						 ) AS rejection_reason,
						 (
							 SELECT REPLACE(rl.notes, '[COUNSELLOR COMPLETED] ', '')
							 FROM request_logs rl
							 WHERE rl.request_id = r.request_id
							   AND rl.notes LIKE '[COUNSELLOR COMPLETED] %'
							 ORDER BY rl.log_timestamp DESC, rl.log_id DESC
							 LIMIT 1
						 ) AS completion_note
				  FROM requests r
				  JOIN users u ON u.user_id = r.student_user_id
				  LEFT JOIN users au ON au.user_id = r.alumnus_user_id
				  LEFT JOIN alumnis a ON a.user_id = r.alumnus_user_id
				  LEFT JOIN students s ON s.user_id = r.student_user_id
				  LEFT JOIN faculties f ON f.faculty_id = s.faculty_id
				  LEFT JOIN aid_requests ar ON ar.request_id = r.request_id
				  WHERE r.request_type = 'aid'
					AND r.status IN (" . implode(',', $placeholders) . ")
					AND (s.is_deleted IS NULL OR s.is_deleted = 0)
				  ORDER BY r.created_at DESC";

		$result = $this->query($query, $data);
		return is_array($result) ? $result : [];
	}

	public function getAidRequestsForStatuses($statuses = [], $facultyId = null)
	{
		if (!is_array($statuses) || empty($statuses)) {
			return [];
		}

		$placeholders = [];
		$data = [];
		foreach (array_values($statuses) as $index => $status) {
			$key = 'status_' . $index;
			$placeholders[] = ':' . $key;
			$data[$key] = (string)$status;
		}

		$facultyFilter = '';
		if ($facultyId !== null) {
			$facultyFilter = ' AND s.faculty_id = :faculty_id';
			$data['faculty_id'] = (int)$facultyId;
		}

		$query = "SELECT r.request_id, r.student_user_id, r.alumnus_user_id, r.status, r.created_at,
						 u.name AS student_name, u.email AS student_email,
						 au.name AS alumnus_name, au.email AS alumnus_email,
						 a.mobile AS alumnus_mobile,
						 s.student_id, s.academic_year,
						 f.faculty_name,
						 ar.mobile_number, ar.aid_type, ar.amount, ar.reason,
						 ar.student_id_pdf_path, ar.income_statement_path, ar.gramaseva_cert_path,
						 (
							 SELECT REPLACE(rl.notes, '[COUNSELLOR REJECTED] ', '')
							 FROM request_logs rl
							 WHERE rl.request_id = r.request_id
							   AND rl.notes LIKE '[COUNSELLOR REJECTED] %'
							 ORDER BY rl.log_timestamp DESC, rl.log_id DESC
							 LIMIT 1
						 ) AS rejection_reason,
						 (
							 SELECT REPLACE(rl.notes, '[COUNSELLOR COMPLETED] ', '')
							 FROM request_logs rl
							 WHERE rl.request_id = r.request_id
							   AND rl.notes LIKE '[COUNSELLOR COMPLETED] %'
							 ORDER BY rl.log_timestamp DESC, rl.log_id DESC
							 LIMIT 1
						 ) AS completion_note
				  FROM requests r
				  JOIN users u ON u.user_id = r.student_user_id
				  LEFT JOIN users au ON au.user_id = r.alumnus_user_id
				  LEFT JOIN alumnis a ON a.user_id = r.alumnus_user_id
				  LEFT JOIN students s ON s.user_id = r.student_user_id
				  LEFT JOIN faculties f ON f.faculty_id = s.faculty_id
				  LEFT JOIN aid_requests ar ON ar.request_id = r.request_id
				  WHERE r.request_type = 'aid'
					AND r.status IN (" . implode(',', $placeholders) . ")
					AND (s.is_deleted IS NULL OR s.is_deleted = 0)
					" . $facultyFilter . "
				  ORDER BY r.created_at DESC";

		$result = $this->query($query, $data);
		return is_array($result) ? $result : [];
	}

	public function getAidRequestsForStudent($studentUserId)
	{
		$query = "SELECT r.request_id, r.status, r.created_at,
						 ar.aid_type, ar.amount, ar.reason,
						 ar.mobile_number,
						 ar.student_id_pdf_path, ar.income_statement_path, ar.gramaseva_cert_path,
						 (
							 SELECT REPLACE(rl.notes, '[COUNSELLOR REJECTED] ', '')
							 FROM request_logs rl
							 WHERE rl.request_id = r.request_id
							   AND rl.notes LIKE '[COUNSELLOR REJECTED] %'
							 ORDER BY rl.log_timestamp DESC, rl.log_id DESC
							 LIMIT 1
						 ) AS rejection_reason
				  FROM requests r
				  LEFT JOIN aid_requests ar ON ar.request_id = r.request_id
				  WHERE r.request_type = 'aid'
					AND r.student_user_id = :student_user_id
				  ORDER BY r.created_at DESC";

		$result = $this->query($query, ['student_user_id' => $studentUserId]);
		return is_array($result) ? $result : [];
	}

	public function approveAidRequestByCounsellor($requestId)
	{
		$query = "UPDATE requests
				  SET status = 'open'
				  WHERE request_id = :request_id
					AND request_type = 'aid'
					AND status = 'pending_verification'";

		return $this->query($query, ['request_id' => $requestId]) !== false;
	}

	public function rejectAidRequestByCounsellor($requestId, $note)
	{
		$note = trim((string)$note);
		if ($note === '') {
			return false;
		}

		$updateQuery = "UPDATE requests
						SET status = 'rejected'
						WHERE request_id = :request_id
						  AND request_type = 'aid'
						  AND status = 'pending_verification'";

		$updated = $this->query($updateQuery, ['request_id' => $requestId]);
		if ($updated === false) {
			return false;
		}

		try {
			$nextLogIdQuery = "SELECT COALESCE(MAX(log_id), 0) + 1 AS next_id FROM request_logs";
			$nextLogIdRow = $this->get_row($nextLogIdQuery);
			$nextLogId = isset($nextLogIdRow->next_id) ? (int)$nextLogIdRow->next_id : 1;

			$logQuery = "INSERT INTO request_logs (log_id, request_id, actor_user_id, action, log_timestamp, notes)
						 VALUES (:log_id, :request_id, :actor_user_id, :action, NOW(), :notes)";

			$logData = [
				'log_id' => $nextLogId,
				'request_id' => $requestId,
				'actor_user_id' => $_SESSION['user_id'] ?? 0,
				'action' => 'ALUMNUS_ACCEPTED',
				'notes' => '[COUNSELLOR REJECTED] ' . $note,
			];

			$this->query($logQuery, $logData);
		} catch (Throwable $e) {
			error_log('Counsellor rejection note log failed: ' . $e->getMessage());
		}

		return true;
	}

	public function approveAidRequestByAlumni($requestId, $alumnusUserId)
	{
		$updateQuery = "UPDATE requests
						SET status = 'approved', alumnus_user_id = :alumnus_user_id
						WHERE request_id = :request_id
						  AND request_type = 'aid'
						  AND status = 'open'";

		$this->query($updateQuery, [
			'request_id' => (int)$requestId,
			'alumnus_user_id' => (int)$alumnusUserId,
		]);

		$verifyQuery = "SELECT request_id
						FROM requests
						WHERE request_id = :request_id
						  AND request_type = 'aid'
						  AND status = 'approved'
						  AND alumnus_user_id = :alumnus_user_id
						LIMIT 1";

		$verified = $this->get_row($verifyQuery, [
			'request_id' => (int)$requestId,
			'alumnus_user_id' => (int)$alumnusUserId,
		]);

		return $verified !== false;
	}

	public function markAidRequestCompletedByCounsellor($requestId, $counsellorUserId, $completionNote)
	{
		$completionNote = trim((string)$completionNote);
		if ($completionNote === '') {
			return false;
		}

		$updateQuery = "UPDATE requests
						SET status = 'completed'
						WHERE request_id = :request_id
						  AND request_type = 'aid'
						  AND alumnus_user_id IS NOT NULL
						  AND status IN ('approved', 'accepted')";

		$this->query($updateQuery, [
			'request_id' => (int)$requestId,
		]);

		$verifyQuery = "SELECT request_id
						FROM requests
						WHERE request_id = :request_id
						  AND request_type = 'aid'
						  AND status = 'completed'
						LIMIT 1";

		$verified = $this->get_row($verifyQuery, [
			'request_id' => (int)$requestId,
		]);

		if ($verified !== false) {
			try {
				$logNote = '[COUNSELLOR COMPLETED] ' . $completionNote;
				if (strlen($logNote) > 1000) {
					$logNote = substr($logNote, 0, 1000);
				}

				$nextLogIdQuery = "SELECT COALESCE(MAX(log_id), 0) + 1 AS next_id FROM request_logs";
				$nextLogIdRow = $this->get_row($nextLogIdQuery);
				$nextLogId = isset($nextLogIdRow->next_id) ? (int)$nextLogIdRow->next_id : 1;

				$logQuery = "INSERT INTO request_logs (log_id, request_id, actor_user_id, action, log_timestamp, notes)
							 VALUES (:log_id, :request_id, :actor_user_id, :action, NOW(), :notes)";

				$this->query($logQuery, [
					'log_id' => $nextLogId,
					'request_id' => (int)$requestId,
					'actor_user_id' => (int)$counsellorUserId,
					'action' => 'COUNSELLOR_COMPLETED',
					'notes' => $logNote,
				]);
			} catch (Throwable $e) {
				error_log('Counsellor completion log failed: ' . $e->getMessage());
			}
		}

		return $verified !== false;
	}

	public function getAidCompletionContextByRequestId($requestId)
	{
		$query = "SELECT r.request_id, r.alumnus_user_id,
						 u.name AS student_name,
						 ar.aid_type
				  FROM requests r
				  JOIN users u ON u.user_id = r.student_user_id
				  LEFT JOIN aid_requests ar ON ar.request_id = r.request_id
				  WHERE r.request_id = :request_id
					AND r.request_type = 'aid'
				  LIMIT 1";

		return $this->get_row($query, [
			'request_id' => (int)$requestId,
		]);
	}

	public function getAidAnalyticsSummary($days = 30)
	{
		$days = max(1, (int)$days);

		$query = "SELECT
						COUNT(*) AS total_requests,
						SUM(CASE WHEN r.status IN ('open', 'approved', 'accepted') THEN 1 ELSE 0 END) AS approved_requests,
						SUM(CASE WHEN r.status = 'rejected' THEN 1 ELSE 0 END) AS rejected_requests,
						SUM(CASE WHEN r.status = 'pending_verification' THEN 1 ELSE 0 END) AS pending_requests,
						SUM(CASE WHEN r.status IN ('open', 'approved', 'accepted') THEN COALESCE(ar.amount, 0) ELSE 0 END) AS total_disbursed,
						AVG(CASE WHEN r.status IN ('open', 'approved', 'accepted', 'rejected') THEN TIMESTAMPDIFF(DAY, r.created_at, NOW()) ELSE NULL END) AS avg_processing_days
				  FROM requests r
				  LEFT JOIN aid_requests ar ON ar.request_id = r.request_id
				  WHERE r.request_type = 'aid'
					AND r.created_at >= DATE_SUB(NOW(), INTERVAL :days DAY)";

		$row = $this->get_row($query, ['days' => $days]);
		if (!$row) {
			return [
				'total_requests' => 0,
				'approved_requests' => 0,
				'rejected_requests' => 0,
				'pending_requests' => 0,
				'total_disbursed' => 0,
				'avg_processing_days' => 0,
				'approval_rate' => 0,
			];
		}

		$totalRequests = (int)($row->total_requests ?? 0);
		$approvedRequests = (int)($row->approved_requests ?? 0);
		$rejectedRequests = (int)($row->rejected_requests ?? 0);
		$processed = $approvedRequests + $rejectedRequests;
		$approvalRate = $processed > 0 ? round(($approvedRequests / $processed) * 100, 1) : 0;

		return [
			'total_requests' => $totalRequests,
			'approved_requests' => $approvedRequests,
			'rejected_requests' => $rejectedRequests,
			'pending_requests' => (int)($row->pending_requests ?? 0),
			'total_disbursed' => (float)($row->total_disbursed ?? 0),
			'avg_processing_days' => round((float)($row->avg_processing_days ?? 0), 1),
			'approval_rate' => $approvalRate,
		];
	}

	public function getAidAnalyticsBreakdown($days = 30)
	{
		$days = max(1, (int)$days);

		$query = "SELECT
						COALESCE(ar.aid_type, 'other') AS aid_type,
						COUNT(*) AS total_count,
						SUM(CASE WHEN r.status IN ('open', 'approved', 'accepted') THEN 1 ELSE 0 END) AS approved_count,
						SUM(CASE WHEN r.status = 'rejected' THEN 1 ELSE 0 END) AS rejected_count
				  FROM requests r
				  LEFT JOIN aid_requests ar ON ar.request_id = r.request_id
				  WHERE r.request_type = 'aid'
					AND r.created_at >= DATE_SUB(NOW(), INTERVAL :days DAY)
				  GROUP BY COALESCE(ar.aid_type, 'other')
				  ORDER BY total_count DESC";

		$rows = $this->query($query, ['days' => $days]);
		if (!is_array($rows) || empty($rows)) {
			return [];
		}

		$maxTotal = 0;
		foreach ($rows as $row) {
			$maxTotal = max($maxTotal, (int)($row->total_count ?? 0));
		}

		$result = [];
		foreach ($rows as $row) {
			$total = (int)($row->total_count ?? 0);
			$result[] = [
				'aid_type' => (string)($row->aid_type ?? 'other'),
				'total_count' => $total,
				'approved_count' => (int)($row->approved_count ?? 0),
				'rejected_count' => (int)($row->rejected_count ?? 0),
				'bar_percentage' => $maxTotal > 0 ? (int)round(($total / $maxTotal) * 100) : 0,
			];
		}

		return $result;
	}

	public function getRecentAidRequestsForAnalytics($limit = 8)
	{
		$limit = max(1, (int)$limit);

		$query = "SELECT r.request_id, r.status, r.created_at,
						 u.name AS student_name,
						 s.student_id,
						 ar.aid_type, ar.amount
				  FROM requests r
				  JOIN users u ON u.user_id = r.student_user_id
				  LEFT JOIN students s ON s.user_id = r.student_user_id
				  LEFT JOIN aid_requests ar ON ar.request_id = r.request_id
				  WHERE r.request_type = 'aid'
				  ORDER BY r.created_at DESC
				  LIMIT $limit";

		$rows = $this->query($query);
		return is_array($rows) ? $rows : [];
	}

	public function getAidAnalyticsTimeline($days = 30)
	{
		$days = max(1, (int)$days);

		$query = "SELECT
						DATE(r.created_at) AS day,
						COUNT(*) AS total_requests,
						SUM(CASE WHEN r.status = 'pending_verification' THEN 1 ELSE 0 END) AS pending_count,
						SUM(CASE WHEN r.status IN ('open', 'approved', 'accepted') THEN 1 ELSE 0 END) AS approved_count,
						SUM(CASE WHEN r.status = 'completed' THEN 1 ELSE 0 END) AS completed_count,
						SUM(CASE WHEN r.status = 'rejected' THEN 1 ELSE 0 END) AS rejected_count,
						SUM(CASE WHEN r.status IN ('open', 'approved', 'accepted', 'completed') THEN COALESCE(ar.amount, 0) ELSE 0 END) AS disbursed_amount
				  FROM requests r
				  LEFT JOIN aid_requests ar ON ar.request_id = r.request_id
				  WHERE r.request_type = 'aid'
					AND r.created_at >= DATE_SUB(CURDATE(), INTERVAL :days DAY)
				  GROUP BY DATE(r.created_at)
				  ORDER BY day ASC";

		$rows = $this->query($query, ['days' => $days]);
		if (!is_array($rows) || empty($rows)) {
			return [];
		}

		$result = [];
		foreach ($rows as $row) {
			$key = (string)($row->day ?? '');
			if ($key === '') {
				continue;
			}

			$result[$key] = [
				'total' => (int)($row->total_requests ?? 0),
				'pending' => (int)($row->pending_count ?? 0),
				'approved' => (int)($row->approved_count ?? 0),
				'completed' => (int)($row->completed_count ?? 0),
				'rejected' => (int)($row->rejected_count ?? 0),
				'disbursed' => (float)($row->disbursed_amount ?? 0),
			];
		}

		return $result;
	}

	public function getAidAnalyticsStatusMix($days = 30)
	{
		$days = max(1, (int)$days);

		$query = "SELECT
						SUM(CASE WHEN r.status = 'pending_verification' THEN 1 ELSE 0 END) AS pending_count,
						SUM(CASE WHEN r.status IN ('open', 'approved', 'accepted') THEN 1 ELSE 0 END) AS approved_count,
						SUM(CASE WHEN r.status = 'completed' THEN 1 ELSE 0 END) AS completed_count,
						SUM(CASE WHEN r.status = 'rejected' THEN 1 ELSE 0 END) AS rejected_count
				  FROM requests r
				  WHERE r.request_type = 'aid'
					AND r.created_at >= DATE_SUB(CURDATE(), INTERVAL :days DAY)";

		$row = $this->get_row($query, ['days' => $days]);
		if (!$row) {
			return [
				'pending' => 0,
				'approved' => 0,
				'completed' => 0,
				'rejected' => 0,
			];
		}

		return [
			'pending' => (int)($row->pending_count ?? 0),
			'approved' => (int)($row->approved_count ?? 0),
			'completed' => (int)($row->completed_count ?? 0),
			'rejected' => (int)($row->rejected_count ?? 0),
		];
	}
}
