<?php

class Notification
{
	use Model;

	private const ANNOUNCEMENT_TYPE_FACULTY = 0;
	private const ANNOUNCEMENT_TYPE_UNIVERSITY = 1;
	private const ANNOUNCEMENT_TYPE_ADMIN = 2;

	protected $table = 'notifications';
	protected $order_column = 'created_at';
	protected $id_column = 'notification_id';

	protected $allowedColumns = [
		'recipient_user_id',
		'actor_user_id',
		'title',
		'message',
		'action_url',
		'action_label',
		'context_type',
		'context_id',
		'is_read',
		'read_at',
		'created_at',
	];

	private function buildFundraiserActionUrlForRole($role, $fundraiserId)
	{
		$fundraiserId = (int)$fundraiserId;
		if ($fundraiserId <= 0) {
			return null;
		}

		$normalizedRole = strtolower(trim((string)$role));
		if ($normalizedRole === 'student') {
			return ROOT . '/student/fundraising?highlight=' . $fundraiserId;
		}

		if ($normalizedRole === 'alumni') {
			return ROOT . '/alumni/fundraising?highlight=' . $fundraiserId;
		}

		if ($normalizedRole === 'faculty_admin') {
			return ROOT . '/admin/fundraiser-moderation';
		}

		if ($normalizedRole === 'super_admin') {
			return ROOT . '/superadmin/fundraiser-moderation';
		}

		return null;
	}

	private function getUserRoleById($userId)
	{
		if ((int)$userId <= 0) {
			return null;
		}

		$row = $this->get_row(
			"SELECT role FROM users WHERE user_id = :user_id LIMIT 1",
			['user_id' => (int)$userId]
		);

		if (!$row || !isset($row->role)) {
			return null;
		}

		return strtolower((string)$row->role);
	}

	public function createAnnouncementPublishedNotifications($announcementType, $announcementTitle, $actorUserId = null, $facultyId = null)
	{
		$type = (int)$announcementType;
		$now = date('Y-m-d H:i:s');
		$notificationTitle = 'New Announcement Published';
		$message = 'You got a new university announcement.';
		if ($type === self::ANNOUNCEMENT_TYPE_FACULTY) {
			$message = 'You got a new faculty announcement.';
		} elseif ($type === self::ANNOUNCEMENT_TYPE_ADMIN) {
			$message = 'You got a new admin announcement.';
		}

		[$recipientQuery, $params] = $this->buildAnnouncementRecipientQuery($type, $facultyId !== null ? (int)$facultyId : null);
		if ($recipientQuery === '') {
			return 0;
		}

		$actor = ($actorUserId !== null && (int)$actorUserId > 0) ? (int)$actorUserId : null;

		$insertQuery = "INSERT INTO notifications (
					recipient_user_id,
					actor_user_id,
					title,
					message,
					is_read,
					read_at,
					created_at
				)
				SELECT DISTINCT r.user_id, :actor_user_id, :title, :message, 0, NULL, :created_at
				FROM (" . $recipientQuery . ") r
				WHERE r.user_id IS NOT NULL
				  AND r.user_id > 0
				  AND (:actor_filter IS NULL OR r.user_id <> :actor_filter)";

		$params['actor_user_id'] = $actor;
		$params['actor_filter'] = $actor;
		$params['title'] = $notificationTitle;
		$params['message'] = $message;
		$params['created_at'] = $now;

		$ok = $this->query($insertQuery, $params);
		return $ok ? 1 : 0;
	}

	private function buildAnnouncementRecipientQuery($announcementType, $facultyId = null)
	{
		$type = (int)$announcementType;

		if ($type === self::ANNOUNCEMENT_TYPE_ADMIN) {
			return [
				"SELECT u.user_id
				 FROM users u
				 WHERE u.role IN ('faculty_admin', 'super_admin')",
				[]
			];
		}

		if ($type === self::ANNOUNCEMENT_TYPE_UNIVERSITY) {
			return [
				"SELECT u.user_id
				 FROM users u",
				[]
			];
		}

		if ($type === self::ANNOUNCEMENT_TYPE_FACULTY) {
			$params = [];
			$studentFilter = '';
			$alumniFilter = '';
			$facultyAdminFilter = '';

			if ($facultyId !== null && $facultyId > 0) {
				$studentFilter = ' AND s.faculty_id = :faculty_id_students';
				$alumniFilter = ' AND a.faculty_id = :faculty_id_alumnis';
				$facultyAdminFilter = ' AND fa.faculty_id = :faculty_id_admins';
				$params['faculty_id_students'] = (int)$facultyId;
				$params['faculty_id_alumnis'] = (int)$facultyId;
				$params['faculty_id_admins'] = (int)$facultyId;
			}

			$query = "SELECT s.user_id
					  FROM students s
					  WHERE (s.is_deleted IS NULL OR s.is_deleted = 0)" . $studentFilter . "

					UNION

					SELECT a.user_id
					FROM alumnis a
					WHERE (a.is_deleted IS NULL OR a.is_deleted = 0)" . $alumniFilter . "

					UNION

					SELECT fa.user_id
					FROM faculty_admins fa
					WHERE (fa.is_deactivated IS NULL OR fa.is_deactivated = 0)" . $facultyAdminFilter;

			return [$query, $params];
		}

		return ['', []];
	}

	public function createResourceReportedNotification($recipientUserId, $actorUserId, $resourceName, $reason)
	{
		$resourceName = trim((string)$resourceName);
		$reason = trim((string)$reason);

		if ($recipientUserId <= 0 || $resourceName === '' || $reason === '') {
			return false;
		}

		$reportedAt = date('Y-m-d H:i:s');
		$title = 'One of your resources got reported';
		$message = 'Your reported resource (' . $resourceName . ') got reported on (' . $reason . ') on (' . $reportedAt . ')';

		return $this->insert([
			'recipient_user_id' => (int)$recipientUserId,
			'actor_user_id' => ($actorUserId !== null && (int)$actorUserId > 0) ? (int)$actorUserId : null,
			'title' => $title,
			'message' => $message,
			'is_read' => 0,
			'read_at' => null,
			'created_at' => $reportedAt,
		]);
	}

	public function createResourceFlagRemovedNotification($recipientUserId, $actorUserId, $resourceName, $actorRoleLabel = 'Admin')
	{
		$resourceName = trim((string)$resourceName);

		if ($recipientUserId <= 0 || $resourceName === '') {
			return false;
		}

		$actionAt = date('Y-m-d H:i:s');
		$title = 'Report flag removed from your resource';
		$message = 'Your reported resource (' . $resourceName . ') had its report flag removed on (' . $actionAt . ')';

		return $this->insert([
			'recipient_user_id' => (int)$recipientUserId,
			'actor_user_id' => ($actorUserId !== null && (int)$actorUserId > 0) ? (int)$actorUserId : null,
			'title' => $title,
			'message' => $message,
			'is_read' => 0,
			'read_at' => null,
			'created_at' => $actionAt,
		]);
	}

	public function createResourceDeletedNotification($recipientUserId, $actorUserId, $resourceName, $actorRoleLabel = 'Admin')
	{
		$resourceName = trim((string)$resourceName);

		if ($recipientUserId <= 0 || $resourceName === '') {
			return false;
		}

		$actionAt = date('Y-m-d H:i:s');
		$title = 'Your resource was removed';
		$message = 'Your reported resource (' . $resourceName . ') was removed on (' . $actionAt . ')';

		return $this->insert([
			'recipient_user_id' => (int)$recipientUserId,
			'actor_user_id' => ($actorUserId !== null && (int)$actorUserId > 0) ? (int)$actorUserId : null,
			'title' => $title,
			'message' => $message,
			'is_read' => 0,
			'read_at' => null,
			'created_at' => $actionAt,
		]);
	}

	public function createResourceWarningNotification($recipientUserId, $actorUserId, $resourceName, $warningMessage = '')
	{
		$resourceName = trim((string)$resourceName);
		$warningMessage = trim((string)$warningMessage);

		if ($recipientUserId <= 0 || $resourceName === '') {
			return false;
		}

		$warnedAt = date('Y-m-d H:i:s');
		$title = 'Warning about your resource';
		$message = 'Your resource (' . $resourceName . ') has received a warning.' . ($warningMessage !== '' ? ' Reason: ' . $warningMessage : '') . ' Warned on (' . $warnedAt . ')';

		return $this->insert([
			'recipient_user_id' => (int)$recipientUserId,
			'actor_user_id' => ($actorUserId !== null && (int)$actorUserId > 0) ? (int)$actorUserId : null,
			'title' => $title,
			'message' => $message,
			'is_read' => 0,
			'read_at' => null,
			'created_at' => $warnedAt,
		]);
	}

	public function createResourcePermanentlyHiddenNotification($recipientUserId, $resourceName, $reportCount = 5)
	{
		$resourceName = trim((string)$resourceName);

		if ($recipientUserId <= 0 || $resourceName === '') {
			return false;
		}

		$hiddenAt = date('Y-m-d H:i:s');
		$title = 'Your resource has been permanently hidden';
		$message = 'Your resource (' . $resourceName . ') has received ' . (int)$reportCount . ' reports and is now hidden from all users on (' . $hiddenAt . ')';

		return $this->insert([
			'recipient_user_id' => (int)$recipientUserId,
			'actor_user_id' => null,
			'title' => $title,
			'message' => $message,
			'is_read' => 0,
			'read_at' => null,
			'created_at' => $hiddenAt,
		]);
	}

	public function createAidCompletionNotificationForAlumnus($recipientUserId, $actorUserId, $requestId, $studentName, $aidType, $completionNote)
	{
		$recipientUserId = (int)$recipientUserId;
		$requestId = (int)$requestId;
		$studentName = trim((string)$studentName);
		$aidType = trim((string)$aidType);
		$completionNote = trim((string)$completionNote);

		if ($recipientUserId <= 0 || $requestId <= 0 || $completionNote === '') {
			return false;
		}

		$actorId = ($actorUserId !== null && (int)$actorUserId > 0) ? (int)$actorUserId : null;
		$studentLabel = $studentName !== '' ? $studentName : 'the student';
		$aidLabel = $aidType !== '' ? ucfirst($aidType) : 'Aid';
		$title = 'Aid marked as completed by counsellor';
		$message = $aidLabel . ' request #' . $requestId . ' for ' . $studentLabel . ' was marked completed by counsellor. Note: ' . $completionNote;
		$actionUrl = ROOT . '/alumni/aidrequests';

		return $this->insert([
			'recipient_user_id' => $recipientUserId,
			'actor_user_id' => $actorId,
			'title' => $title,
			'message' => $message,
			'action_url' => $actionUrl,
			'action_label' => 'View aid requests',
			'context_type' => 'aid_request',
			'context_id' => $requestId,
			'is_read' => 0,
			'read_at' => null,
			'created_at' => date('Y-m-d H:i:s'),
		]);
	}

	public function createFundraiserRejectedNotification($recipientUserId, $actorUserId, $fundraiserTitle, $note, $fundraiserId = null)
	{
		$fundraiserTitle = trim((string)$fundraiserTitle);
		$note = trim((string)$note);
		if ($recipientUserId <= 0 || $fundraiserTitle === '') {
			return false;
		}

		$recipientRole = $this->getUserRoleById($recipientUserId);
		$actionUrl = $this->buildFundraiserActionUrlForRole($recipientRole, $fundraiserId);

		$title = 'Fundraiser request rejected';
		$message = 'Your fundraiser ("' . $fundraiserTitle . '") was rejected.' . ($note !== '' ? ' Admin note: ' . $note : '');

		return $this->insert([
			'recipient_user_id' => (int)$recipientUserId,
			'actor_user_id' => ($actorUserId !== null && (int)$actorUserId > 0) ? (int)$actorUserId : null,
			'title' => $title,
			'message' => $message,
			'action_url' => $actionUrl,
			'action_label' => $actionUrl ? 'View fundraiser' : null,
			'context_type' => $fundraiserId ? 'fundraiser' : null,
			'context_id' => $fundraiserId ? (int)$fundraiserId : null,
			'is_read' => 0,
			'read_at' => null,
			'created_at' => date('Y-m-d H:i:s'),
		]);
	}

	public function createFundraiserApprovedNotification($recipientUserId, $actorUserId, $fundraiserTitle, $note = '', $fundraiserId = null)
	{
		$fundraiserTitle = trim((string)$fundraiserTitle);
		$note = trim((string)$note);
		if ($recipientUserId <= 0 || $fundraiserTitle === '') {
			return false;
		}

		$recipientRole = $this->getUserRoleById($recipientUserId);
		$actionUrl = $this->buildFundraiserActionUrlForRole($recipientRole, $fundraiserId);

		$title = 'Fundraiser request approved';
		$message = 'Your fundraiser ("' . $fundraiserTitle . '") has been approved and is now visible for donations.'
			. ($note !== '' ? ' Admin note: ' . $note : '');

		return $this->insert([
			'recipient_user_id' => (int)$recipientUserId,
			'actor_user_id' => ($actorUserId !== null && (int)$actorUserId > 0) ? (int)$actorUserId : null,
			'title' => $title,
			'message' => $message,
			'action_url' => $actionUrl,
			'action_label' => $actionUrl ? 'View fundraiser' : null,
			'context_type' => $fundraiserId ? 'fundraiser' : null,
			'context_id' => $fundraiserId ? (int)$fundraiserId : null,
			'is_read' => 0,
			'read_at' => null,
			'created_at' => date('Y-m-d H:i:s'),
		]);
	}

	public function createFundraiserClosedByAdminNotification($recipientUserId, $actorUserId, $fundraiserTitle, $fundraiserId = null)
	{
		$fundraiserTitle = trim((string)$fundraiserTitle);
		if ((int)$recipientUserId <= 0 || $fundraiserTitle === '') {
			return false;
		}

		$recipientRole = $this->getUserRoleById($recipientUserId);
		$actionUrl = $this->buildFundraiserActionUrlForRole($recipientRole, $fundraiserId);

		return $this->insert([
			'recipient_user_id' => (int)$recipientUserId,
			'actor_user_id' => ($actorUserId !== null && (int)$actorUserId > 0) ? (int)$actorUserId : null,
			'title' => 'Fundraiser ended by admin',
			'message' => 'Your fundraiser ("' . $fundraiserTitle . '") has been closed by admin.',
			'action_url' => $actionUrl,
			'action_label' => $actionUrl ? 'View fundraiser' : null,
			'context_type' => $fundraiserId ? 'fundraiser' : null,
			'context_id' => $fundraiserId ? (int)$fundraiserId : null,
			'is_read' => 0,
			'read_at' => null,
			'created_at' => date('Y-m-d H:i:s'),
		]);
	}

	public function createFundraiserGoalReachedNotification($recipientUserId, $fundraiserTitle, $fundraiserId)
	{
		$fundraiserTitle = trim((string)$fundraiserTitle);
		$fundraiserId = (int)$fundraiserId;
		if ((int)$recipientUserId <= 0 || $fundraiserTitle === '' || $fundraiserId <= 0) {
			return false;
		}

		$recipientRole = $this->getUserRoleById($recipientUserId);
		$actionUrl = $this->buildFundraiserActionUrlForRole($recipientRole, $fundraiserId);

		return $this->insert([
			'recipient_user_id' => (int)$recipientUserId,
			'actor_user_id' => null,
			'title' => 'Fundraiser goal achieved',
			'message' => 'Great news. Your fundraiser ("' . $fundraiserTitle . '") reached its target amount.',
			'action_url' => $actionUrl,
			'action_label' => $actionUrl ? 'View fundraiser' : null,
			'context_type' => 'fundraiser',
			'context_id' => $fundraiserId,
			'is_read' => 0,
			'read_at' => null,
			'created_at' => date('Y-m-d H:i:s'),
		]);
	}

	public function createFundraiserLiveBroadcastNotifications($fundraiserId, $fundraiserTitle, $creatorUserId, $actorUserId = null)
	{
		$fundraiserId = (int)$fundraiserId;
		$creatorUserId = (int)$creatorUserId;
		$fundraiserTitle = trim((string)$fundraiserTitle);
		if ($fundraiserId <= 0 || $creatorUserId <= 0 || $fundraiserTitle === '') {
			return 0;
		}

		$createdAt = date('Y-m-d H:i:s');
		$actor = ($actorUserId !== null && (int)$actorUserId > 0) ? (int)$actorUserId : null;

		$query = "INSERT INTO notifications (
					recipient_user_id,
					actor_user_id,
					title,
					message,
					action_url,
					action_label,
					context_type,
					context_id,
					is_read,
					read_at,
					created_at
				)
				SELECT u.user_id,
						 :actor_user_id,
						 :title,
						 :message,
						 CASE
								 WHEN u.role = 'student' THEN :student_url
								 WHEN u.role = 'alumni' THEN :alumni_url
								 ELSE NULL
						 END,
						 :action_label,
						 'fundraiser',
						 :context_id,
						 0,
						 NULL,
						 :created_at
				FROM users u
				WHERE u.role IN ('student', 'alumni')
					AND u.user_id <> :creator_user_id";

		$params = [
			'actor_user_id' => $actor,
			'title' => 'New fundraiser is now live',
			'message' => 'A new fundraiser ("' . $fundraiserTitle . '") is now live for donations.',
			'student_url' => ROOT . '/student/fundraising?highlight=' . $fundraiserId,
			'alumni_url' => ROOT . '/alumni/fundraising?highlight=' . $fundraiserId,
			'action_label' => 'View fundraiser',
			'context_id' => $fundraiserId,
			'created_at' => $createdAt,
			'creator_user_id' => $creatorUserId,
		];

		$ok = $this->query($query, $params);
		return $ok ? 1 : 0;
	}

	public function markAsRead($notificationId)
	{
		if ((int)$notificationId <= 0) {
			return false;
		}

		$readAt = date('Y-m-d H:i:s');
		return $this->query(
			"UPDATE notifications
			 SET is_read = 1, read_at = :read_at
			 WHERE notification_id = :notification_id",
			['notification_id' => (int)$notificationId, 'read_at' => $readAt]
		);
	}

	public function markMultipleAsRead($notificationIds = [])
	{
		if (empty($notificationIds) || !is_array($notificationIds)) {
			return false;
		}

		$readAt = date('Y-m-d H:i:s');
		$placeholders = implode(',', array_fill(0, count($notificationIds), '?'));
		
		return $this->query(
			"UPDATE notifications
			 SET is_read = 1, read_at = :read_at
			 WHERE notification_id IN (" . str_repeat('?,', count($notificationIds) - 1) . "?)",
			$notificationIds + ['read_at' => $readAt]
		);
	}
}