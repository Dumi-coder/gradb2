<?php

class Student
{
    use Model;
    
    protected $table = 'students';
    protected $order_column = 'student_id';
    protected $id_column = 'student_id';
    
    protected $allowedColumns = [
        'student_id',
        'user_id',
        'faculty_id',
        'academic_year',
        'bio',
        'profile_photo_url',
        'mobile',
        'LinkedIn',
        'GitHub',
        'is_deleted'
    ];
    
    public function validate($data)
    {
        $this->errors = [];

        // Validate student_id
        if (empty($data['student_id'])) {
            $this->errors[] = "Student ID is required";
        } else {
            // Check if student_id already exists (for new students)
            if (!isset($data['current_student_id'])) {
                $existing = $this->first(['student_id' => $data['student_id']]);
                if ($existing) {
                    $this->errors[] = "Student ID already exists";
                }
            }
        }

        // Validate user_id
        if (empty($data['user_id']) || !is_numeric($data['user_id'])) {
            $this->errors[] = "Valid user ID is required";
        }

        // Validate faculty_id
        if (empty($data['faculty_id']) || !is_numeric($data['faculty_id'])) {
            $this->errors[] = "Valid faculty ID is required";
        }
        
        // Validate academic_year
        if (empty($data['academic_year']) || !is_numeric($data['academic_year'])) {
            $this->errors[] = "Academic year is required";
        } elseif ($data['academic_year'] < 1 || $data['academic_year'] > 5) {
            $this->errors[] = "Academic year must be between 1 and 5";
        }

        // Validate mobile (optional)
        if (!empty($data['mobile']) && !is_numeric($data['mobile'])) {
            $this->errors[] = "Mobile number must be numeric";
        }

        return empty($this->errors);
    }

    /**
     * Get student data along with user data for login
     */

    public function getStudentWithUser($student_id)
    {
        $query = "SELECT s.*, u.name, u.email, u.password, u.role, u.created_at, u.updated_at
                  FROM $this->table s
                  JOIN users u ON s.user_id = u.user_id
                  WHERE s.student_id = :student_id
                  AND (s.is_deleted IS NULL OR s.is_deleted = 0)";
        
        $result = $this->query($query, ['student_id' => $student_id]);
        return $result ? $result[0] : false;
    }

    /**
     * Get complete student profile with faculty information
     */

    public function getStudentProfile($student_id)
    {
        $query = "SELECT s.*, u.name, u.email, f.faculty_name as faculty
                  FROM $this->table s
                  JOIN users u ON s.user_id = u.user_id
                  JOIN faculties f ON s.faculty_id = f.faculty_id
                  WHERE s.student_id = :student_id
                  AND (s.is_deleted IS NULL OR s.is_deleted = 0)";
        
        $result = $this->query($query, ['student_id' => $student_id]);
        return $result ? $result[0] : false;
    }

    /**
     * Get students by faculty
     */

    public function getStudentsByFaculty($faculty_id)
    {
        $query = "SELECT s.*, u.name, u.email
                  FROM $this->table s
                  JOIN users u ON s.user_id = u.user_id
                  WHERE s.faculty_id = :faculty_id
                  AND (s.is_deleted IS NULL OR s.is_deleted = 0)
                  ORDER BY u.name";
        
        return $this->query($query, ['faculty_id' => $faculty_id]);
    }

    /**
     * Get students by academic year
     */

    public function getStudentsByYear($academic_year)
    {
        $query = "SELECT s.*, u.name, u.email, f.faculty_name
                  FROM $this->table s
                  JOIN users u ON s.user_id = u.user_id
                  JOIN faculties f ON s.faculty_id = f.faculty_id
                  WHERE s.academic_year = :academic_year
                  AND (s.is_deleted IS NULL OR s.is_deleted = 0)
                  ORDER BY u.name";
        
        return $this->query($query, ['academic_year' => $academic_year]);
    }
    
    /**
     * Search students
     */

    public function searchStudents($search_term)
    {
        $query = "SELECT s.*, u.name, u.email, f.faculty_name
                  FROM $this->table s
                  JOIN users u ON s.user_id = u.user_id
                  JOIN faculties f ON s.faculty_id = f.faculty_id
                  WHERE (u.name LIKE :search OR s.student_id LIKE :search)
                  AND (s.is_deleted IS NULL OR s.is_deleted = 0)
                  ORDER BY u.name";
        
        $search_param = '%' . $search_term . '%';
        return $this->query($query, ['search' => $search_param]);
    }
    
    /**
     * Update student profile
     */

    public function updateProfile($student_id, $data)
    {
        // Remove unwanted data
        $allowed_fields = ['faculty_id', 'academic_year', 'bio', 'profile_photo_url', 'mobile'];
        $filtered_data = [];
        
        foreach ($data as $key => $value) {
            if (in_array($key, $allowed_fields)) {
                $filtered_data[$key] = $value;
            }
        }

        if (!empty($filtered_data)) {
            return $this->update($student_id, $filtered_data, 'student_id');
        }
        
        return false;
    }
    
    /**
     * Get total student count
     */

    public function getStudentCount()
    {
        $query = "SELECT COUNT(*) as total FROM $this->table";
        $result = $this->query($query);
        return $result ? $result[0]->total : 0;
    }
     
    /**
     * Get student count by faculty
     */
     
    public function getStudentCountByFaculty()
    {
        $query = "SELECT f.faculty_name, COUNT(s.student_id) as student_count
                  FROM faculties f
                  LEFT JOIN $this->table s ON f.faculty_id = s.faculty_id
                  GROUP BY f.faculty_id, f.faculty_name
                  ORDER BY f.faculty_name";
        
        return $this->query($query);
    }
    
    // Add debug method for updates
    public function logUpdate($id, $data, $id_column)
    {
        error_log("Student update attempted - ID: $id, Column: $id_column, Data: " . print_r($data, true));
        $result = $this->update($id, $data, $id_column);
        error_log("Student update result: " . ($result ? 'success' : 'failed'));
        return $result;
    }
    
    /**
     * Check if a student exists in records
     */

    // public function existsInRecords($student_id, $email = null, $faculty_id = null)
    // {
    //     $params = ['student_id' => $student_id];
    //     $query = "SELECT 1 FROM student_rechords WHERE student_id = :student_id";
    //     if (!empty($email)) {
    //         $query .= " AND email = :email";
    //         $params['email'] = $email;
    //     }
    //     if (!empty($faculty_id)) {
    //         $query .= " AND faculty_id = :faculty_id";
    //         $params['faculty_id'] = $faculty_id;
    //     }
    //     $res = $this->query($query, $params);
    //     return !empty($res);
    // }

    /**
 * Check if a student exists in student_records table
 * This verifies that the student is a legitimate university student
 * @param string $student_id The student ID to verify
 * @param int|null $faculty_id The faculty ID to verify (optional but recommended)
 * @return bool True if student exists in records, false otherwise
 */
public function existsInRecords($student_id, $faculty_id = null)
{
    $student_id = trim($student_id);
    if ($student_id === '') {
        error_log("[Student::existsInRecords] Empty student_id provided");
        return false;
    }

    $params = ['student_id' => $student_id];
    $query = "SELECT 1 FROM student_records WHERE student_id = :student_id";
    
    if (!empty($faculty_id)) {
        $query .= " AND faculty_id = :faculty_id";
        $params['faculty_id'] = (int)$faculty_id;
    }
    
    error_log("[Student::existsInRecords] SQL: $query | params: " . json_encode($params));
    
    try {
        $result = $this->query($query, $params);
        
        error_log("[Student::existsInRecords] Query executed. Result: " . var_export($result, true));
        
        // Check if result is an array with at least one element
        $exists = !empty($result) && is_array($result) && count($result) > 0;
        
        error_log("[Student::existsInRecords] Final result - exists: " . ($exists ? 'YES' : 'NO'));
        
        return $exists;
    } catch (Exception $e) {
        error_log("[Student::existsInRecords] Exception: " . $e->getMessage());
        return false;
    }
  }
}