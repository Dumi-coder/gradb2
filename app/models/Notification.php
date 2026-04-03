<?php

class Notification
{
	use Model;

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