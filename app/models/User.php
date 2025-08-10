<?php   
//  echo "User.php model loaded<br>";
//core user data,auth logic
class User // Model class for User
{
    use Model;// Use the Model trait for database operations

    protected $table = 'users';// Specify the table name
    protected $allowedColumns = ['name','email','student_id','faculty','password'];// Define the columns that can be inserted or updated
    
    public function validate($data)
    {
        $this->errors = [];
         

        if (empty($data['name'])) {// Validate name
           $this->errors['name'] = "Name is required";
        }

        if(empty($data['email']))// Validate email
        {
            $this->errors['email'] = "Email is required";
        }else
        if(!filter_var($data['email'],FILTER_VALIDATE_EMAIL))
        {
            $this->errors['email']="Email is NOT VALID";
         }else{
            $existing=$this->first(['email'=>$data['email']]);
            if($existing)
            {
                $this->errors['email']="Email already registered";
            }
        }

        // Student ID validation & uniqueness
        if (empty($data['student_id'])) {
            $this->errors['student_id'] = "Student ID is required";
        } else {
            $existing = $this->first(['student_id' => $data['student_id']]);
            if ($existing) {
                $this->errors['student_id'] = "Student ID already registered";
            }
       }
        // Faculty validation
        if (empty($data['faculty'])) {
            $this->errors['faculty'] = "Faculty is required";
        }

        // Confirm password validation
        if(empty($data['password']))
        {
            $this->errors['password']="Password is required";
        }else if (strlen($data['password']) < 6) {
            $this->errors['password'] = "Password must be at least 6 characters";
        }else{
            $existing=$this->first(['password'=>$data['password']]);
            if($existing)
            {
                $this->errors['password']="use another password";
            }
        }
        if ($data['password'] !== $data['confirm_password']) {
            $this->errors['confirm_password'] = "Passwords do not match";
        }

        // Check if there are any validation errors
        if(empty($this->errors))
        {
            return true;
        }
        return false;
    }   
}