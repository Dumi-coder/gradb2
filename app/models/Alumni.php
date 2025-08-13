<?php
class Alumni
{
    use Model;

    protected $table = 'alumni';
    protected $allowedColumns = ['name', 'email', 'alumni_id', 'faculty', 'graduation_year', 'password'];
    protected $id_column = 'alumni_id';
    protected $order_column = 'alumni_id';
    
    public function validate($data)
    {
        $this->errors = [];

        // Validate name
        if (empty($data['name'])) {
            $this->errors[] = "Name is required";
        } elseif (strlen(trim($data['name'])) < 2) {
            $this->errors[] = "Name must be at least 2 characters long";
        }

        // Validate email
        if (empty($data['email'])) {
            $this->errors[] = "Email is required";
        } elseif (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $this->errors[] = "Please enter a valid email address";
        } else {
            // Check if email already exists
            $existing = $this->first(['email' => $data['email']]);
            if ($existing) {
                $this->errors[] = "Email already exists";
            }
        }

        // Validate alumni ID
        if (empty($data['alumni_id'])) {
            $this->errors[] = "Alumni ID is required";
        } else {
            // Check if alumni ID already exists
            $existing = $this->first(['alumni_id' => $data['alumni_id']]);
            if ($existing) {
                $this->errors[] = "Alumni ID already exists";
            }
        }

        // Validate faculty
        if (empty($data['faculty'])) {
            $this->errors[] = "Faculty is required";
        }

        // Validate graduation year
        if (empty($data['graduation_year'])) {
            $this->errors[] = "Graduation year is required";
        } elseif (!is_numeric($data['graduation_year']) || $data['graduation_year'] < 1900 || $data['graduation_year'] > date('Y')) {
            $this->errors[] = "Please enter a valid graduation year";
        }

        // Validate password
        if (empty($data['password'])) {
            $this->errors[] = "Password is required";
        } elseif (strlen($data['password']) < 8) {
            $this->errors[] = "Password must be at least 8 characters long";
        }

        // Validate confirm password
        if (empty($data['confirm_password'])) {
            $this->errors[] = "Please confirm your password";
        } elseif ($data['password'] !== $data['confirm_password']) {
            $this->errors[] = "Passwords do not match";
        }

        return empty($this->errors);
    }

    public function signup($data)
    {
        if ($this->validate($data)) {
            // Hash the password before storing
            $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
            
            // Remove confirm_password as it's not needed in database
            unset($data['confirm_password']);
            
            // Insert the user
            return $this->insert($data);
        }
        return false;
    }

    // Get alumni by email for authentication
    public function getByEmail($email)
    {
        return $this->first(['email' => $email]);
    }

    // Get alumni profile information
    public function getProfile($alumni_id)
    {
        return $this->first(['alumni_id' => $alumni_id]);
    }

    // Update alumni profile
    public function updateProfile($alumni_id, $data)
    {
        // Don't allow updating alumni_id
        if (isset($data['alumni_id'])) {
            unset($data['alumni_id']);
        }
        
        // Hash password if being updated
        if (!empty($data['password'])) {
            $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
        } else {
            unset($data['password']); // Don't update if empty
        }

        return $this->update($alumni_id, $data);
    }

    // Get alumni by graduation year
    public function getByGraduationYear($year)
    {
        return $this->where(['graduation_year' => $year]);
    }

    // Get alumni by faculty
    public function getByFaculty($faculty)
    {
        return $this->where(['faculty' => $faculty]);
    }
}

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