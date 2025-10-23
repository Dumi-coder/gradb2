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
                'status' => 'pending_verification'
            ]);

            $newRequestId = $pdo->lastInsertId();

            // 2. Insert into the child 'mentorship_requests' table
            $category = $data['mentorship_category'] ?? '';
            $otherCategory = $data['other_mentorship_category'] ?? '';
            $reason = $data['request_reason'] ?? '';
            
            $query2 = "INSERT INTO mentorship_requests (request_id, mentorship_category, other_category, request_reason) VALUES (:request_id, :mentorship_category, :other_category, :request_reason)";
            $stmt2 = $pdo->prepare($query2);
            $stmt2->execute([
                'request_id' => $newRequestId,
                'mentorship_category' => $category,
                'other_category' => $category === 'other' ? $otherCategory : null,
                'request_reason' => $reason
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
            WHERE r.student_user_id = :student_id AND r.request_type = 'mentorship'
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
            JOIN alumnis a ON a.user_id = :alumnus_id
            WHERE r.request_type = 'mentorship' 
            AND r.status = 'pending_verification'
            AND s.faculty_id = a.faculty_id
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
            
            if ($request['status'] !== 'pending_verification') {
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
            
            if ($request['status'] !== 'pending_verification') {
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
}