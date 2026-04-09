<?php

class MentorshipChat
{
    use Model;

    public function getThreadPayload($requestId, $userId)
    {
        $pdo = $this->connect();

        $threadQuery = "
            SELECT
                r.request_id,
                r.status,
                r.student_user_id,
                r.alumnus_user_id,
                su.name AS student_name,
                su.email AS student_email,
                s.student_id,
                s.academic_year,
                s.mobile AS student_mobile,
                s.LinkedIn AS student_linkedin_url,
                au.name AS alumnus_name,
                au.email AS alumnus_email,
                a.mobile AS mentor_mobile,
                a.linkedin_url AS mentor_linkedin_url,
                a.current_job AS mentor_current_job,
                a.current_workplace AS mentor_current_workplace,
                a.bio AS mentor_bio,
                f.faculty_name
            FROM requests r
            INNER JOIN users su ON su.user_id = r.student_user_id
            INNER JOIN users au ON au.user_id = r.alumnus_user_id
            LEFT JOIN students s ON s.user_id = r.student_user_id
            LEFT JOIN alumnis a ON a.user_id = r.alumnus_user_id
            LEFT JOIN faculties f ON f.faculty_id = COALESCE(s.faculty_id, a.faculty_id)
            WHERE r.request_id = :request_id
              AND r.request_type = 'mentorship'
              AND (r.student_user_id = :user_id OR r.alumnus_user_id = :user_id)
            LIMIT 1
        ";

        $threadStmt = $pdo->prepare($threadQuery);
        $threadStmt->execute([
            'request_id' => $requestId,
            'user_id' => $userId,
        ]);
        $thread = $threadStmt->fetch(PDO::FETCH_ASSOC);

        if (!$thread) {
            return false;
        }

        $allowedStatuses = ['accepted', 'pending_review', 'completed'];
        if (!in_array($thread['status'], $allowedStatuses, true)) {
            return false;
        }

        $messagesQuery = "
            SELECT
                m.message_id,
                m.request_id,
                m.sender_user_id,
                m.message_body,
                m.created_at,
                u.name AS sender_name,
                u.role AS sender_role
            FROM mentorship_messages m
            INNER JOIN users u ON u.user_id = m.sender_user_id
            WHERE m.request_id = :request_id
            ORDER BY m.created_at ASC, m.message_id ASC
        ";

        $messagesStmt = $pdo->prepare($messagesQuery);
        $messagesStmt->execute(['request_id' => $requestId]);
        $messages = $messagesStmt->fetchAll(PDO::FETCH_ASSOC);

        return [
            'thread' => $thread,
            'messages' => $messages ? $messages : [],
        ];
    }

    public function sendMessage($requestId, $senderUserId, $messageBody)
    {
        $pdo = $this->connect();

        $payload = $this->getThreadPayload($requestId, $senderUserId);
        if (!$payload) {
            return false;
        }

        $messageBody = trim($messageBody);
        if ($messageBody === '') {
            return false;
        }

        if (strlen($messageBody) > 1000) {
            $messageBody = substr($messageBody, 0, 1000);
        }

        try {
            $stmt = $pdo->prepare("INSERT INTO mentorship_messages (request_id, sender_user_id, message_body, created_at) VALUES (:request_id, :sender_user_id, :message_body, NOW())");
            $stmt->execute([
                'request_id' => $requestId,
                'sender_user_id' => $senderUserId,
                'message_body' => $messageBody,
            ]);

            return $pdo->lastInsertId();
        } catch (Exception $e) {
            error_log('MentorshipChat sendMessage error: ' . $e->getMessage());
            return false;
        }
    }
}
