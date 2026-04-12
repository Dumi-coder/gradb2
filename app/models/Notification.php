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
		'is_read',
		'read_at',
		'created_at',
	];

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