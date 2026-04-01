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

	public function getPendingAidRequestsForCounselor()
	{
		$query = "SELECT r.request_id, r.student_user_id, r.status, r.created_at,
						 u.name AS student_name, u.email AS student_email,
						 s.student_id, s.academic_year,
						 f.faculty_name,
						 ar.mobile_number, ar.aid_type, ar.amount, ar.reason,
						 ar.student_id_pdf_path, ar.income_statement_path, ar.gramaseva_cert_path
				  FROM requests r
				  JOIN users u ON u.user_id = r.student_user_id
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

	public function getAidRequestsForCounselorByStatuses($statuses = [])
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

		$query = "SELECT r.request_id, r.student_user_id, r.status, r.created_at,
						 u.name AS student_name, u.email AS student_email,
						 s.student_id, s.academic_year,
						 f.faculty_name,
						 ar.mobile_number, ar.aid_type, ar.amount, ar.reason,
						 ar.student_id_pdf_path, ar.income_statement_path, ar.gramaseva_cert_path
				  FROM requests r
				  JOIN users u ON u.user_id = r.student_user_id
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

	public function getAidRequestsForStudent($studentUserId)
	{
		$query = "SELECT r.request_id, r.status, r.created_at,
						 ar.aid_type, ar.amount, ar.reason,
						 ar.mobile_number,
						 ar.student_id_pdf_path, ar.income_statement_path, ar.gramaseva_cert_path
				  FROM requests r
				  LEFT JOIN aid_requests ar ON ar.request_id = r.request_id
				  WHERE r.request_type = 'aid'
					AND r.student_user_id = :student_user_id
				  ORDER BY r.created_at DESC";

		$result = $this->query($query, ['student_user_id' => $studentUserId]);
		return is_array($result) ? $result : [];
	}

	public function approveAidRequestByCounselor($requestId)
	{
		$query = "UPDATE requests
				  SET status = 'open'
				  WHERE request_id = :request_id
					AND request_type = 'aid'
					AND status = 'pending_verification'";

		return $this->query($query, ['request_id' => $requestId]) !== false;
	}

	public function rejectAidRequestByCounselor($requestId, $note)
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
				'notes' => '[COUNSELOR REJECTED] ' . $note,
			];

			$this->query($logQuery, $logData);
		} catch (Throwable $e) {
			error_log('Counselor rejection note log failed: ' . $e->getMessage());
		}

		return true;
	}
}
