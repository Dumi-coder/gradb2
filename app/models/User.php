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
         
        if(empty($data['email']))
        {
            $this->errors['email'] = "Email is required";
        }else
        if(!filter_var($data['email'],FILTER_VALIDATE_EMAIL))
        {
            $this->errors['email']="Email is NOT VALID";
        }
        
        if(empty($data['password']))
        {
            $this->errors['password']="Password is required";
        }

        // if(empty($data['terms']))
        // {
        //     $this->errors['terms']="You must accept the terms and conditions";
        // }
        
        
        if(empty($this->errors))
        {
            return true;
        }
        return false;
    }   
}