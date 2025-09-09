<?php


// class Alumni
// {
//     use Model;

//     protected $table = 'alumni'; // Your alumni table name
//     protected $allowedColumns = ['name', 'email', 'alumni_id', 'faculty', 'graduation_year', 'password'];
//     protected $id_column = 'alumni_id';
//     protected $order_column = 'alumni_id';
    
//     // You can add a validate() method similar to User if needed
//     public function validate($data)
//     {
//         $this->errors = [];

//         if(empty($this->errors))
//         {
//             return true;
//         }
//         return false;
//     }

// }


class Alumni
{
    use Model;
    
    protected $table = 'alumnis';
    protected $order_column = 'alumni_id';
    protected $id_column = 'alumni_id';
    
    protected $allowedColumns = [
        'alumni_id',
        'user_id',
        'faculty_id',
        'expertise_area',
        'linkedin_url',
        'graduated_year',
        'bio',
        'profile_photo_url',
        'mobile'
    ];

    public function validate($data)
    {
        $this->errors = [];

        // Validate alumni_id
        if (empty($data['alumni_id'])) {
            $this->errors[] = "alumni ID is required";
        } else {
            // Check if alumni_id already exists (for new alumnis)
            if (!isset($data['current_alumni_id'])) {
                $existing = $this->first(['alumni_id' => $data['alumni_id']]);
                if ($existing) {
                    $this->errors[] = "alumni ID already exists";
                }
            }
        }

        // Validate user_id
        // if (empty($data['user_id']) || !is_numeric($data['user_id'])) {
        //     $this->errors[] = "Valid user ID is required";
        // }

        // // Validate faculty_id
        // if (empty($data['faculty_id']) || !is_numeric($data['faculty_id'])) {
        //     $this->errors[] = "Valid faculty ID is required";
        // }

        // Validate graduated_year
        // if (empty($data['graduated_year']) || !is_numeric($data['graduated_year'])) {
        //     $this->errors[] = "Graduated year is required";
        // } elseif ($data['graduated_year'] < 1900 || $data['graduated_year'] > date("Y")) {
        //     $this->errors[] = "Graduated year must be a valid year";
        // }

        // Validate mobile (optional)
        if (!empty($data['mobile']) && !is_numeric($data['mobile'])) {
            $this->errors[] = "Mobile number must be numeric";
        }

        return empty($this->errors);
    }

    /**
     * Get alumni data along with user data for login
     */
    public function getalumniWithUser($alumni_id)
    {
        $query = "SELECT a.*, u.name, u.email, u.password, u.role, u.created_at, u.updated_at
                  FROM $this->table a
                  JOIN users u ON a.user_id = u.user_id
                  WHERE a.alumni_id = :alumni_id";
        
        $result = $this->query($query, ['alumni_id' => $alumni_id]);
        return $result ? $result[0] : false;
    }

    /**
     * Get complete alumni profile with faculty information
     */
    public function getalumniProfile($alumni_id)
    {
        $query = "SELECT a.*, u.name, u.email, f.faculty_name as faculty
                  FROM $this->table a
                  JOIN users u ON a.user_id = u.user_id
                  JOIN faculties f ON a.faculty_id = f.faculty_id
                  WHERE a.alumni_id = :alumni_id";

        $result = $this->query($query, ['alumni_id' => $alumni_id]);
        return $result ? $result[0] : false;
    }

    /**
     * Get alumnis by faculty
     */
    public function getalumnisByFaculty($faculty_id)
    {
        $query = "SELECT a.*, u.name, u.email
                  FROM $this->table a
                  JOIN users u ON a.user_id = u.user_id
                  WHERE a.faculty_id = :faculty_id
                  ORDER BY u.name";
        
        return $this->query($query, ['faculty_id' => $faculty_id]);
    }

    /**
     * Get alumnis by academic year
     */
    public function getalumnisByYear($academic_year)
    {
        $query = "SELECT a.*, u.name, u.email, f.faculty_name
                  FROM $this->table a
                  JOIN users u ON a.user_id = u.user_id
                  JOIN faculties f ON a.faculty_id = f.faculty_id
                  WHERE a.academic_year = :academic_year
                  ORDER BY u.name";
        
        return $this->query($query, ['academic_year' => $academic_year]);
    }

    /**
     * Search alumnis
     */
    public function searchalumnis($search_term)
    {
        $query = "SELECT a.*, u.name, u.email, f.faculty_name
                  FROM $this->table a
                  JOIN users u ON a.user_id = u.user_id
                  JOIN faculties f ON a.faculty_id = f.faculty_id
                  WHERE u.name LIKE :search OR a.alumni_id LIKE :search
                  ORDER BY u.name";
        
        $search_param = '%' . $search_term . '%';
        return $this->query($query, ['search' => $search_param]);
    }

    /**
     * Update alumni profile
     */
    public function updateProfile($alumni_id, $data)
    {
        // Remove unwanted data
        $allowed_fields = ['faculty_id','expertise_area','linkedin_url','graduated_year', 'bio', 'profile_photo_url', 'mobile'];
        $filtered_data = [];
        
        foreach ($data as $key => $value) {
            if (in_array($key, $allowed_fields)) {
                $filtered_data[$key] = $value;
            }
        }

        if (!empty($filtered_data)) {
            return $this->update($alumni_id, $filtered_data, 'alumni_id');
        }
        
        return false;
    }

    /**
     * Get total alumni count
     */
    public function getalumniCount()
    {
        $query = "SELECT COUNT(*) as total FROM $this->table";
        $result = $this->query($query);
        return $result ? $result[0]->total : 0;
    }

    /**
     * Get alumni count by faculty
     */
    public function getalumniCountByFaculty()
    {
        $query = "SELECT f.faculty_name, COUNT(a.alumni_id) as alumni_count
                  FROM faculties f
                  LEFT JOIN $this->table a ON f.faculty_id = a.faculty_id
                  GROUP BY f.faculty_id, f.faculty_name
                  ORDER BY f.faculty_name";
        
        return $this->query($query);
    }
    
    // Add debug method for updates
    public function logUpdate($id, $data, $id_column)
    {
        error_log("alumni update attempted - ID: $id, Column: $id_column, Data: " . print_r($data, true));
        $result = $this->update($id, $data, $id_column);
        error_log("alumni update result: " . ($result ? 'success' : 'failed'));
        return $result;
    }
}