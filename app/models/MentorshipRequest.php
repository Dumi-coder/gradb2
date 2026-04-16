<?php

class MentorshipRequest
{
    use Model;

    protected $table = 'requests';
    protected $id_column = 'request_id';
    protected $allowedColumns = [
        'student_user_id', 'alumnus_user_id', 'faculty_admin_user_id', 
        'request_type', 'status', 'created_at'
    ];

    private function resolveLatestMentorBadge($sessionsCount)
    {
        $sessions = (int)$sessionsCount;

        $badgeSteps = [
            [
                'min_sessions' => 20,
                'key' => 'mentor_master',
                'label' => 'Mentor Master',
                'icon' => 'fa-crown',
                'class' => 'mentor-badge-master',
            ],
            [
                'min_sessions' => 10,
                'key' => 'mentor_senior',
                'label' => 'Senior Mentor',
                'icon' => 'fa-medal',
                'class' => 'mentor-badge-senior',
            ],
            [
                'min_sessions' => 5,
                'key' => 'mentor_trusted',
                'label' => 'Trusted Mentor',
                'icon' => 'fa-shield-heart',
                'class' => 'mentor-badge-trusted',
            ],
            [
                'min_sessions' => 1,
                'key' => 'mentor_rising',
                'label' => 'Rising Mentor',
                'icon' => 'fa-seedling',
                'class' => 'mentor-badge-rising',
            ],
        ];

        foreach ($badgeSteps as $step) {
            if ($sessions >= (int)$step['min_sessions']) {
                return $step;
            }
        }

        return [
            'min_sessions' => 0,
            'key' => 'mentor_new',
            'label' => 'New Mentor',
            'icon' => 'fa-user-plus',
            'class' => 'mentor-badge-new',
        ];
    }

    public function getAvailableMentorsForStudent($studentUserId)
    {
        $pdo = $this->connect();

        $query = "
            SELECT
                a.user_id AS mentor_user_id,
                a.alumni_id,
                u.name AS mentor_name,
                u.email AS mentor_email,
                COALESCE(a.profile_photo_url, '') AS mentor_profile_photo_url,
                a.mobile AS mentor_mobile,
                a.linkedin_url AS mentor_linkedin_url,
                f.faculty_name,
                COALESCE(a.expertise_area, '') AS expertise_area,
                COALESCE(a.current_job, '') AS current_job,
                COALESCE(a.current_workplace, '') AS current_workplace,
                COALESCE(a.bio, '') AS mentor_bio,
                COALESCE(a.mentorship_availability_status, 'available') AS availability,
                COALESCE(stats.avg_rating, 0) AS avg_rating,
                COALESCE(stats.sessions_count, 0) AS sessions_count
            FROM alumnis a
            INNER JOIN users u ON u.user_id = a.user_id AND u.role = 'alumni'
            LEFT JOIN faculties f ON f.faculty_id = a.faculty_id
            LEFT JOIN (
                SELECT
                    r.alumnus_user_id,
                    ROUND(AVG(mr.rating), 1) AS avg_rating,
                    COUNT(*) AS sessions_count
                FROM requests r
                INNER JOIN mentorship_requests mr ON mr.request_id = r.request_id
                WHERE r.request_type = 'mentorship'
                  AND r.status = 'completed'
                  AND mr.rating IS NOT NULL
                GROUP BY r.alumnus_user_id
            ) stats ON stats.alumnus_user_id = a.user_id
            WHERE a.is_verified_mentor = 1
              AND (a.is_deleted IS NULL OR a.is_deleted = 0)
              AND (a.is_suspended IS NULL OR a.is_suspended = 0)
              AND (a.mentorship_availability_status IS NULL OR a.mentorship_availability_status != 'unavailable')
                            AND NOT EXISTS (
                                    SELECT 1
                                    FROM requests r_block
                                    WHERE r_block.request_type = 'mentorship'
                                        AND r_block.student_user_id = :student_user_id
                                        AND r_block.alumnus_user_id = a.user_id
                                        AND r_block.status IN ('pending', 'accepted', 'pending_review')
                            )
            ORDER BY stats.avg_rating DESC, stats.sessions_count DESC, u.name ASC
        ";

        $stmt = $pdo->prepare($query);
                $stmt->execute(['student_user_id' => (int)$studentUserId]);

        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        if (!$result) {
            return [];
        }

        foreach ($result as &$mentor) {
            $latestBadge = $this->resolveLatestMentorBadge($mentor['sessions_count'] ?? 0);
            $mentor['latest_mentor_badge'] = $latestBadge['label'];
            $mentor['latest_mentor_badge_key'] = $latestBadge['key'];
            $mentor['latest_mentor_badge_icon'] = $latestBadge['icon'];
            $mentor['latest_mentor_badge_class'] = $latestBadge['class'];
        }
        unset($mentor);

        return $result;
    }

    public function hasBlockingRequestWithMentor($studentUserId, $mentorUserId)
    {
        $pdo = $this->connect();

        $query = "
            SELECT 1
            FROM requests
            WHERE request_type = 'mentorship'
              AND student_user_id = :student_user_id
              AND alumnus_user_id = :mentor_user_id
              AND status IN ('pending', 'accepted', 'pending_review')
            LIMIT 1
        ";

        $stmt = $pdo->prepare($query);
        $stmt->execute([
            'student_user_id' => (int)$studentUserId,
            'mentor_user_id' => (int)$mentorUserId,
        ]);

        return (bool)$stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getMentorByUserId($mentorUserId)
    {
        $pdo = $this->connect();

        $query = "
            SELECT
                a.user_id AS mentor_user_id,
                a.alumni_id,
                u.name AS mentor_name,
                u.email AS mentor_email,
                a.mobile AS mentor_mobile,
                a.linkedin_url AS mentor_linkedin_url,
                f.faculty_name,
                COALESCE(a.expertise_area, '') AS expertise_area,
                COALESCE(a.current_job, '') AS current_job,
                COALESCE(a.current_workplace, '') AS current_workplace,
                COALESCE(a.bio, '') AS mentor_bio
            FROM alumnis a
            INNER JOIN users u ON u.user_id = a.user_id AND u.role = 'alumni'
            LEFT JOIN faculties f ON f.faculty_id = a.faculty_id
            WHERE a.user_id = :mentor_user_id
              AND a.is_verified_mentor = 1
              AND (a.is_deleted IS NULL OR a.is_deleted = 0)
              AND (a.is_suspended IS NULL OR a.is_suspended = 0)
            LIMIT 1
        ";

        $stmt = $pdo->prepare($query);
        $stmt->execute(['mentor_user_id' => $mentorUserId]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        return $result ?: false;
    }

    public function createDirectedRequest($studentUserId, $mentorUserId, $topic, $reason)
    {
        $pdo = $this->connect();

        try {
            $pdo->beginTransaction();

            $mentorStmt = $pdo->prepare("SELECT alumni_id FROM alumnis WHERE user_id = :mentor_user_id LIMIT 1");
            $mentorStmt->execute(['mentor_user_id' => $mentorUserId]);
            $mentor = $mentorStmt->fetch(PDO::FETCH_ASSOC);

            if (!$mentor) {
                $pdo->rollBack();
                return false;
            }

            $blockStmt = $pdo->prepare("\n                SELECT 1\n                FROM requests\n                WHERE request_type = 'mentorship'\n                  AND student_user_id = :student_user_id\n                  AND alumnus_user_id = :mentor_user_id\n                  AND status IN ('pending', 'accepted', 'pending_review')\n                LIMIT 1\n            ");
            $blockStmt->execute([
                'student_user_id' => (int)$studentUserId,
                'mentor_user_id' => (int)$mentorUserId,
            ]);

            if ($blockStmt->fetch(PDO::FETCH_ASSOC)) {
                $pdo->rollBack();
                return false;
            }

            $insertRequest = "
                INSERT INTO requests (student_user_id, alumnus_user_id, request_type, status)
                VALUES (:student_user_id, :alumnus_user_id, 'mentorship', 'pending')
            ";
            $stmtRequest = $pdo->prepare($insertRequest);
            $stmtRequest->execute([
                'student_user_id' => $studentUserId,
                'alumnus_user_id' => $mentorUserId,
            ]);

            $newRequestId = $pdo->lastInsertId();

            $insertMentorship = "
                INSERT INTO mentorship_requests
                    (request_id, target_alumnus_id, request_reason, mentorship_category, other_category, topic)
                VALUES
                    (:request_id, :target_alumnus_id, :request_reason, 'other', :other_category, :topic)
            ";
            $stmtMentorship = $pdo->prepare($insertMentorship);
            $stmtMentorship->execute([
                'request_id' => $newRequestId,
                'target_alumnus_id' => $mentor['alumni_id'],
                'request_reason' => $reason,
                'other_category' => $topic,
                'topic' => $topic,
            ]);

            $pdo->commit();
            return $newRequestId;
        } catch (Exception $e) {
            $pdo->rollBack();
            error_log('MentorshipRequest createDirectedRequest error: ' . $e->getMessage());
            return false;
        }
    }

    public function getStudentMentorshipRequests($studentUserId)
    {
        $pdo = $this->connect();

        $query = "
            SELECT
                r.request_id,
                r.status,
                r.created_at,
                mr.topic,
                mr.request_reason,
                mr.rejection_reason,
                mr.rating,
                mr.review_comment,
                mr.reviewed_at,
                u.name AS mentor_name,
                u.email AS mentor_email,
                f.faculty_name,
                COALESCE(a.current_workplace, '') AS current_workplace,
                COALESCE(a.current_job, '') AS current_job
            FROM requests r
            INNER JOIN mentorship_requests mr ON mr.request_id = r.request_id
            LEFT JOIN users u ON u.user_id = r.alumnus_user_id
            LEFT JOIN alumnis a ON a.user_id = r.alumnus_user_id
            LEFT JOIN faculties f ON f.faculty_id = a.faculty_id
            WHERE r.request_type = 'mentorship'
              AND r.student_user_id = :student_user_id
                            AND r.alumnus_user_id IS NOT NULL
            ORDER BY r.created_at DESC
        ";

        $stmt = $pdo->prepare($query);
        $stmt->execute(['student_user_id' => $studentUserId]);
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $result ? $result : [];
    }

    public function getStudentActiveMentorships($studentUserId)
    {
        $all = $this->getStudentMentorshipRequests($studentUserId);
        return array_values(array_filter($all, function ($row) {
            return ($row['status'] ?? '') === 'accepted';
        }));
    }

    public function getStudentPendingReviews($studentUserId)
    {
        $all = $this->getStudentMentorshipRequests($studentUserId);
        return array_values(array_filter($all, function ($row) {
            return ($row['status'] ?? '') === 'pending_review';
        }));
    }

    public function endMentorshipByStudent($requestId, $studentUserId)
    {
        $pdo = $this->connect();

        $query = "
            UPDATE requests
            SET status = 'pending_review'
            WHERE request_id = :request_id
              AND student_user_id = :student_user_id
              AND request_type = 'mentorship'
              AND status = 'accepted'
        ";

        $stmt = $pdo->prepare($query);
        $stmt->execute([
            'request_id' => $requestId,
            'student_user_id' => $studentUserId,
        ]);

        return $stmt->rowCount() > 0;
    }

    public function endMentorshipByMentor($requestId, $mentorUserId)
    {
        $pdo = $this->connect();

        $query = "
            UPDATE requests
            SET status = 'pending_review'
            WHERE request_id = :request_id
              AND alumnus_user_id = :mentor_user_id
              AND request_type = 'mentorship'
              AND status = 'accepted'
        ";

        $stmt = $pdo->prepare($query);
        $stmt->execute([
            'request_id' => $requestId,
            'mentor_user_id' => $mentorUserId,
        ]);

        return $stmt->rowCount() > 0;
    }

    public function submitReviewByStudent($requestId, $studentUserId, $rating, $reviewComment = null)
    {
        $pdo = $this->connect();

        try {
            $pdo->beginTransaction();

            $check = $pdo->prepare("SELECT request_id, alumnus_user_id FROM requests WHERE request_id = :request_id AND student_user_id = :student_user_id AND request_type = 'mentorship' AND status = 'pending_review' LIMIT 1");
            $check->execute([
                'request_id' => $requestId,
                'student_user_id' => $studentUserId,
            ]);
            $request = $check->fetch(PDO::FETCH_ASSOC);

            if (!$request) {
                $pdo->rollBack();
                return false;
            }

            $updateReview = $pdo->prepare("UPDATE mentorship_requests SET rating = :rating, review_comment = :review_comment, reviewed_at = NOW() WHERE request_id = :request_id");
            $updateReview->execute([
                'rating' => $rating,
                'review_comment' => $reviewComment,
                'request_id' => $requestId,
            ]);

            $updateRequest = $pdo->prepare("UPDATE requests SET status = 'completed' WHERE request_id = :request_id");
            $updateRequest->execute(['request_id' => $requestId]);

            $refreshCount = $pdo->prepare("UPDATE alumnis SET mentorships_completed_count = (SELECT COUNT(*) FROM requests WHERE request_type = 'mentorship' AND alumnus_user_id = :mentor_user_id AND status = 'completed') WHERE user_id = :mentor_user_id");
            $refreshCount->execute(['mentor_user_id' => $request['alumnus_user_id']]);

            $pdo->commit();
            return true;
        } catch (Exception $e) {
            $pdo->rollBack();
            error_log('MentorshipRequest submitReviewByStudent error: ' . $e->getMessage());
            return false;
        }
    }

    public function getPendingRequestsForMentor($mentorUserId)
    {
        $pdo = $this->connect();

        $query = "
            SELECT
                r.request_id,
                r.created_at,
                mr.topic,
                mr.request_reason,
                u.name AS student_name,
                u.email AS student_email,
                s.mobile AS student_mobile,
                s.LinkedIn AS student_linkedin_url,
                s.student_id,
                s.academic_year,
                f.faculty_name
            FROM requests r
            INNER JOIN mentorship_requests mr ON mr.request_id = r.request_id
            INNER JOIN users u ON u.user_id = r.student_user_id
            LEFT JOIN students s ON s.user_id = r.student_user_id
            LEFT JOIN faculties f ON f.faculty_id = s.faculty_id
            WHERE r.request_type = 'mentorship'
              AND r.alumnus_user_id = :mentor_user_id
              AND r.status = 'pending'
            ORDER BY r.created_at DESC
        ";

        $stmt = $pdo->prepare($query);
        $stmt->execute(['mentor_user_id' => $mentorUserId]);
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $result ? $result : [];
    }

    public function acceptRequestForMentor($requestId, $mentorUserId)
    {
        $pdo = $this->connect();

        $query = "
            UPDATE requests
            SET status = 'accepted'
            WHERE request_id = :request_id
              AND alumnus_user_id = :mentor_user_id
              AND request_type = 'mentorship'
              AND status = 'pending'
        ";

        $stmt = $pdo->prepare($query);
        $stmt->execute([
            'request_id' => $requestId,
            'mentor_user_id' => $mentorUserId,
        ]);

        return $stmt->rowCount() > 0;
    }

    public function rejectRequestForMentor($requestId, $mentorUserId, $reason)
    {
        $pdo = $this->connect();

        try {
            $pdo->beginTransaction();

            $updateRequest = $pdo->prepare("UPDATE requests SET status = 'rejected' WHERE request_id = :request_id AND alumnus_user_id = :mentor_user_id AND request_type = 'mentorship' AND status = 'pending'");
            $updateRequest->execute([
                'request_id' => $requestId,
                'mentor_user_id' => $mentorUserId,
            ]);

            if ($updateRequest->rowCount() < 1) {
                $pdo->rollBack();
                return false;
            }

            $updateReason = $pdo->prepare("UPDATE mentorship_requests SET rejection_reason = :rejection_reason WHERE request_id = :request_id");
            $updateReason->execute([
                'rejection_reason' => $reason,
                'request_id' => $requestId,
            ]);

            $pdo->commit();
            return true;
        } catch (Exception $e) {
            $pdo->rollBack();
            error_log('MentorshipRequest rejectRequestForMentor error: ' . $e->getMessage());
            return false;
        }
    }

    public function getActiveMentorshipsForMentor($mentorUserId)
    {
        $pdo = $this->connect();

        $query = "
            SELECT
                r.request_id,
                r.created_at,
                mr.topic,
                mr.request_reason,
                u.name AS student_name,
                u.email AS student_email,
                s.mobile AS student_mobile,
                s.LinkedIn AS student_linkedin_url,
                s.student_id,
                s.academic_year,
                f.faculty_name
            FROM requests r
            INNER JOIN mentorship_requests mr ON mr.request_id = r.request_id
            INNER JOIN users u ON u.user_id = r.student_user_id
            LEFT JOIN students s ON s.user_id = r.student_user_id
            LEFT JOIN faculties f ON f.faculty_id = s.faculty_id
            WHERE r.request_type = 'mentorship'
              AND r.alumnus_user_id = :mentor_user_id
              AND r.status = 'accepted'
            ORDER BY r.created_at DESC
        ";

        $stmt = $pdo->prepare($query);
        $stmt->execute(['mentor_user_id' => $mentorUserId]);
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $result ? $result : [];
    }

    public function getCompletedMentorshipsForMentor($mentorUserId)
    {
        $pdo = $this->connect();

        $query = "
            SELECT
                r.request_id,
                r.created_at,
                mr.topic,
                mr.request_reason,
                mr.rating,
                mr.review_comment,
                mr.reviewed_at,
                u.name AS student_name,
                u.email AS student_email,
                s.student_id,
                s.academic_year,
                f.faculty_name
            FROM requests r
            INNER JOIN mentorship_requests mr ON mr.request_id = r.request_id
            INNER JOIN users u ON u.user_id = r.student_user_id
            LEFT JOIN students s ON s.user_id = r.student_user_id
            LEFT JOIN faculties f ON f.faculty_id = s.faculty_id
            WHERE r.request_type = 'mentorship'
              AND r.alumnus_user_id = :mentor_user_id
              AND r.status = 'completed'
            ORDER BY mr.reviewed_at DESC, r.created_at DESC
        ";

        $stmt = $pdo->prepare($query);
        $stmt->execute(['mentor_user_id' => $mentorUserId]);
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $result ? $result : [];
    }

    public function getMentorReputation($mentorUserId)
    {
        $pdo = $this->connect();

        $query = "
            SELECT
                COALESCE(ROUND(AVG(mr.rating), 1), 0) AS avg_rating,
                COUNT(mr.rating) AS total_rated_sessions,
                COUNT(*) AS total_completed_sessions
            FROM requests r
            INNER JOIN mentorship_requests mr ON mr.request_id = r.request_id
            WHERE r.request_type = 'mentorship'
              AND r.alumnus_user_id = :mentor_user_id
              AND r.status = 'completed'
        ";

        $stmt = $pdo->prepare($query);
        $stmt->execute(['mentor_user_id' => $mentorUserId]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$result) {
            return [
                'avg_rating' => 0,
                'total_rated_sessions' => 0,
                'total_completed_sessions' => 0,
            ];
        }

        return $result;
    }

    /**
     * CREATE: Creates a new mentorship request in a transaction.
     * Inserts into both 'requests' and 'mentorship_requests' tables.
     * @param array $data Contains student_user_id, mentorship_category, and request_reason
     * @return bool|int Returns request_id on success, false on failure
     */
    public function create($data)
    {
        $pdo = $this->connect();

        try {
            $pdo->beginTransaction();

            // 1. Insert into the parent 'requests' table
            $query1 = "INSERT INTO requests (student_user_id, request_type, status) VALUES (:student_user_id, :request_type, :status)";
            $stmt1 = $pdo->prepare($query1);
            $stmt1->execute([
                'student_user_id' => $data['student_user_id'],
                'request_type' => 'mentorship',
                'status' => 'pending'
            ]);

            $newRequestId = $pdo->lastInsertId();

            // 2. Insert into the child 'mentorship_requests' table
            $category = $data['mentorship_category'] ?? '';
            $otherCategory = $data['other_mentorship_category'] ?? '';
            $reason = $data['request_reason'] ?? '';
            $topic = $data['topic'] ?? ($data['other_mentorship_category'] ?? 'General Mentorship');
            
            $query2 = "INSERT INTO mentorship_requests (request_id, mentorship_category, other_category, request_reason, topic) VALUES (:request_id, :mentorship_category, :other_category, :request_reason, :topic)";
            $stmt2 = $pdo->prepare($query2);
            $stmt2->execute([
                'request_id' => $newRequestId,
                'mentorship_category' => $category,
                'other_category' => $category === 'other' ? $otherCategory : null,
                'request_reason' => $reason,
                'topic' => $topic,
            ]);

            $pdo->commit();
            return $newRequestId;
        } catch (Exception $e) {
            $pdo->rollBack();
            error_log("MentorshipRequest create error: " . $e->getMessage());
            return false;
        }
    }

    /**
     * READ: Gets all mentorship requests sent BY a specific student.
     */
    public function getRequestsByStudent($studentId)
    {
        $pdo = $this->connect();
        
        $query = "
            SELECT r.request_id, r.status, r.created_at, mr.mentorship_category, mr.other_category, mr.request_reason
            FROM requests r
            JOIN mentorship_requests mr ON r.request_id = mr.request_id
            JOIN students s ON r.student_user_id = s.user_id
            WHERE r.student_user_id = :student_id AND r.request_type = 'mentorship'
            AND (s.is_deleted IS NULL OR s.is_deleted = 0)
            ORDER BY r.created_at DESC
        ";
        
        $stmt = $pdo->prepare($query);
        $stmt->execute(['student_id' => $studentId]);
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        return $result ? $result : [];
    }
    
    /**
     * READ: Gets a specific mentorship request by ID for a student.
     */
    public function getRequestById($requestId, $studentId)
    {
        $pdo = $this->connect();
        
        $query = "
            SELECT r.request_id, r.status, r.created_at, mr.mentorship_category, mr.other_category, mr.request_reason
            FROM requests r
            JOIN mentorship_requests mr ON r.request_id = mr.request_id
            WHERE r.request_id = :request_id AND r.student_user_id = :student_id AND r.request_type = 'mentorship'
        ";
        
        $stmt = $pdo->prepare($query);
        $stmt->execute(['request_id' => $requestId, 'student_id' => $studentId]);
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        return ($result && count($result) > 0) ? $result[0] : false;
    }


    /**
     * UPDATE: Updates a mentorship request.
     */
    public function updateRequest($requestId, $studentId, $data)
    {
        $pdo = $this->connect();

        try {
            $pdo->beginTransaction();

            // Update the mentorship_requests table
            $category = $data['mentorship_category'] ?? '';
            $otherCategory = $data['other_mentorship_category'] ?? '';
            $reason = $data['request_reason'] ?? '';
            
            $query = "UPDATE mentorship_requests SET mentorship_category = :mentorship_category, other_category = :other_category, request_reason = :request_reason WHERE request_id = :request_id";
            $stmt = $pdo->prepare($query);
            $stmt->execute([
                'mentorship_category' => $category,
                'other_category' => $category === 'other' ? $otherCategory : null,
                'request_reason' => $reason,
                'request_id' => $requestId
            ]);

            $pdo->commit();
            return true;
        } catch (Exception $e) {
            $pdo->rollBack();
            error_log("MentorshipRequest update error: " . $e->getMessage());
            return false;
        }
    }

    /**
     * DELETE: Deletes a mentorship request (both parent and child records).
     */
    public function deleteRequest($requestId, $studentId)
    {
        $pdo = $this->connect();

        try {
            $pdo->beginTransaction();

            // First verify the request belongs to the student
            $query1 = "SELECT request_id FROM requests WHERE request_id = :request_id AND student_user_id = :student_id AND request_type = 'mentorship'";
            $stmt1 = $pdo->prepare($query1);
            $stmt1->execute([
                'request_id' => $requestId,
                'student_id' => $studentId
            ]);
            $request = $stmt1->fetch();

            if (!$request) {
                $pdo->rollBack();
                return false;
            }

            // Delete from mentorship_requests table
            $query2 = "DELETE FROM mentorship_requests WHERE request_id = :request_id";
            $stmt2 = $pdo->prepare($query2);
            $stmt2->execute(['request_id' => $requestId]);

            // Delete from requests table
            $query3 = "DELETE FROM requests WHERE request_id = :request_id";
            $stmt3 = $pdo->prepare($query3);
            $stmt3->execute(['request_id' => $requestId]);

            $pdo->commit();
            return true;
        } catch (Exception $e) {
            $pdo->rollBack();
            error_log("MentorshipRequest delete error: " . $e->getMessage());
            return false;
        }
    }

    /**
     * READ: Gets all mentorship requests from students in the same faculty as the alumnus.
     */
    public function getRequestsForAlumnusFaculty($alumnusId)
    {
        $pdo = $this->connect();
        
        $query = "
            SELECT r.request_id, r.status, r.created_at, r.student_user_id,
                   mr.mentorship_category, mr.other_category, mr.request_reason,
                   u.name as student_name, u.email as student_email,
                   s.student_id, s.academic_year,
                   f.faculty_name
            FROM requests r
            JOIN mentorship_requests mr ON r.request_id = mr.request_id
            JOIN users u ON r.student_user_id = u.user_id
            JOIN students s ON r.student_user_id = s.user_id
            JOIN faculties f ON s.faculty_id = f.faculty_id
            WHERE r.request_type = 'mentorship' 
            AND r.status = 'pending'
            AND r.alumnus_user_id = :alumnus_id
            AND (s.is_deleted IS NULL OR s.is_deleted = 0)
            ORDER BY r.created_at DESC
        ";
        
        $stmt = $pdo->prepare($query);
        $stmt->execute(['alumnus_id' => $alumnusId]);
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        return $result ? $result : [];
    }

    /**
     * READ: Gets all requests sent TO a specific alumnus.
     */
    public function getRequestsForAlumnus($alumnusId)
    {
        $query = "
            SELECT r.request_id, r.status, r.created_at, mr.request_reason, u.full_name as student_name
            FROM requests r
            JOIN mentorship_requests mr ON r.request_id = mr.request_id
            JOIN users u ON r.student_user_id = u.user_id
            WHERE r.alumnus_user_id = :alumnus_id AND r.request_type = 'mentorship'
            ORDER BY r.created_at DESC
        ";
        return $this->query($query, ['alumnus_id' => $alumnusId]);
    }

    /**
     * UPDATE: Changes the status of a request (e.g., to 'accepted' or 'rejected').
     */
    public function updateRequestStatus($requestId, $alumnusId, $newStatus)
    {
        $this->table = 'requests';
        return $this->update($requestId, [
            'status' => $newStatus,
            'alumnus_user_id' => $alumnusId
        ], 'request_id');
    }

    /**
     * UPDATE: Alumni accepts a mentorship request.
     */
    public function acceptRequest($requestId, $alumnusId)
    {
        $pdo = $this->connect();
        
        try {
            $pdo->beginTransaction();
            
            // First, check if the request exists and is in pending status
            $checkQuery = "SELECT request_id, status FROM requests WHERE request_id = :request_id";
            $checkStmt = $pdo->prepare($checkQuery);
            $checkStmt->execute(['request_id' => $requestId]);
            $request = $checkStmt->fetch(PDO::FETCH_ASSOC);
            
            if (!$request) {
                error_log("Accept request error: Request not found with ID: $requestId");
                $pdo->rollBack();
                return false;
            }
            
            if ($request['status'] !== 'pending') {
                error_log("Accept request error: Request is not in pending status. Current status: " . $request['status']);
                $pdo->rollBack();
                return false;
            }
            
            // Update the request status and assign alumnus
            $query = "UPDATE requests SET status = 'accepted', alumnus_user_id = :alumnus_id WHERE request_id = :request_id";
            $stmt = $pdo->prepare($query);
            $result = $stmt->execute([
                'alumnus_id' => $alumnusId,
                'request_id' => $requestId
            ]);
            
            if (!$result) {
                error_log("Accept request error: Failed to execute update query");
                $pdo->rollBack();
                return false;
            }
            
            $pdo->commit();
            error_log("Accept request success: Request $requestId accepted by alumnus $alumnusId");
            return true;
        } catch (Exception $e) {
            $pdo->rollBack();
            error_log("Accept request error: " . $e->getMessage());
            return false;
        }
    }

    /**
     * UPDATE: Alumni rejects a mentorship request.
     */
    public function rejectRequest($requestId, $alumnusId)
    {
        $pdo = $this->connect();
        
        try {
            $pdo->beginTransaction();
            
            // First, check if the request exists and is in pending status
            $checkQuery = "SELECT request_id, status FROM requests WHERE request_id = :request_id";
            $checkStmt = $pdo->prepare($checkQuery);
            $checkStmt->execute(['request_id' => $requestId]);
            $request = $checkStmt->fetch(PDO::FETCH_ASSOC);
            
            if (!$request) {
                error_log("Reject request error: Request not found with ID: $requestId");
                $pdo->rollBack();
                return false;
            }
            
            if ($request['status'] !== 'pending') {
                error_log("Reject request error: Request is not in pending status. Current status: " . $request['status']);
                $pdo->rollBack();
                return false;
            }
            
            // Update the request status
            $query = "UPDATE requests SET status = 'rejected', alumnus_user_id = :alumnus_id WHERE request_id = :request_id";
            $stmt = $pdo->prepare($query);
            $result = $stmt->execute([
                'alumnus_id' => $alumnusId,
                'request_id' => $requestId
            ]);
            
            if (!$result) {
                error_log("Reject request error: Failed to execute update query");
                $pdo->rollBack();
                return false;
            }
            
            $pdo->commit();
            error_log("Reject request success: Request $requestId rejected by alumnus $alumnusId");
            return true;
        } catch (Exception $e) {
            $pdo->rollBack();
            error_log("Reject request error: " . $e->getMessage());
            return false;
        }
    }

    /**
     * READ: Gets accepted mentorship requests for an alumnus.
     */
    public function getAcceptedRequestsForAlumnus($alumnusId)
    {
        $pdo = $this->connect();
        
        $query = "
            SELECT r.request_id, r.status, r.created_at, r.student_user_id,
                   mr.mentorship_category, mr.other_category, mr.request_reason,
                   u.name as student_name, u.email as student_email,
                   s.student_id, s.academic_year,
                   f.faculty_name
            FROM requests r
            JOIN mentorship_requests mr ON r.request_id = mr.request_id
            JOIN users u ON r.student_user_id = u.user_id
            JOIN students s ON r.student_user_id = s.user_id
            JOIN faculties f ON s.faculty_id = f.faculty_id
            WHERE r.request_type = 'mentorship' 
            AND r.status = 'accepted'
            AND r.alumnus_user_id = :alumnus_id
            ORDER BY r.created_at DESC
        ";
        
        $stmt = $pdo->prepare($query);
        $stmt->execute(['alumnus_id' => $alumnusId]);
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        return $result ? $result : [];
    }

    public function getMentorshipCardsByStatuses($statuses = [], $facultyId = null)
    {
        if (!is_array($statuses) || empty($statuses)) {
            return [];
        }

        $pdo = $this->connect();

        $placeholders = [];
        $params = [];
        foreach (array_values($statuses) as $i => $status) {
            $key = 'status_' . $i;
            $placeholders[] = ':' . $key;
            $params[$key] = (string)$status;
        }

        $facultyFilter = '';
        if ($facultyId !== null) {
            $facultyFilter = ' AND s.faculty_id = :faculty_id';
            $params['faculty_id'] = (int)$facultyId;
        }

        $query = "
            SELECT
                r.request_id,
                r.student_user_id,
                r.alumnus_user_id,
                r.status,
                r.created_at,
                mr.topic,
                mr.request_reason,
                mr.rating,
                mr.review_comment,
                mr.reviewed_at,
                su.name AS student_name,
                su.email AS student_email,
                s.student_id,
                s.academic_year,
                f.faculty_name,
                mu.name AS mentor_name,
                mu.email AS mentor_email,
                COALESCE(a.current_job, '') AS mentor_current_job,
                COALESCE(a.current_workplace, '') AS mentor_current_workplace
            FROM requests r
            INNER JOIN mentorship_requests mr ON mr.request_id = r.request_id
            LEFT JOIN users su ON su.user_id = r.student_user_id
            LEFT JOIN students s ON s.user_id = r.student_user_id
            LEFT JOIN faculties f ON f.faculty_id = s.faculty_id
            LEFT JOIN users mu ON mu.user_id = r.alumnus_user_id
            LEFT JOIN alumnis a ON a.user_id = r.alumnus_user_id
            WHERE r.request_type = 'mentorship'
                            AND r.alumnus_user_id IS NOT NULL
              AND r.status IN (" . implode(',', $placeholders) . ")
              " . $facultyFilter . "
            ORDER BY
                CASE WHEN r.status = 'completed' THEN COALESCE(mr.reviewed_at, r.created_at) ELSE r.created_at END DESC
        ";

        $stmt = $pdo->prepare($query);
        $stmt->execute($params);
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $rows ? $rows : [];
    }
}