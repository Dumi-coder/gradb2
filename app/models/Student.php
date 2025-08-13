<?php
class Student
{
    use Model;

    protected $table = 'student';
    protected $allowedColumns = ['name', 'email', 'student_id', 'faculty', 'password'];
    public $id_column = 'student_id';
    public $order_column = 'student_id';

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

        // Validate student ID
        if (empty($data['student_id'])) {
            $this->errors[] = "Student ID is required";
        } else {
            // Check if student ID already exists
            $existing = $this->first(['student_id' => $data['student_id']]);
            if ($existing) {
                $this->errors[] = "Student ID already exists";
            }
        }

        // Validate faculty
        if (empty($data['faculty'])) {
            $this->errors[] = "Faculty is required";
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

    // Get student by email for authentication
    public function getByEmail($email)
    {
        return $this->first(['email' => $email]);
    }

    // Get student profile information
    public function getProfile($student_id)
    {
        return $this->first(['student_id' => $student_id]);
    }

    // Update student profile
    public function updateProfile($student_id, $data)
    {
        // Don't allow updating student_id
        if (isset($data['student_id'])) {
            unset($data['student_id']);
        }
        
        // Hash password if being updated
        if (!empty($data['password'])) {
            $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
        } else {
            unset($data['password']); // Don't update if empty
        }

        return $this->update($student_id, $data);
    }
}

// class Student
// {
//     use Model;

//     protected $table = 'student'; // Your student table name
//     protected $allowedColumns = ['name', 'email', 'student_id', 'faculty', 'password'];
//     public $id_column = 'student_id';
//     public $order_column = 'student_id';

    
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