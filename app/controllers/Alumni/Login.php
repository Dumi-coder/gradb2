<?php

class Login extends Controller{

    public function index()
    {
        $data=[];
        
        if($_SERVER['REQUEST_METHOD']=="POST")
        {
            $user= new Alumni; 
            $arr['alumni_id']=$_POST['alumni_id'];
            $row = $user->first($arr);
            if($row)
            {
                if(password_verify($_POST['password'], $row->password))
                {
                    $_SESSION['USER'] = $row; // Store user ID in session
                    redirect('alumni/dashboard');
                }
            }
            // $user->errors['alumni_id'] = "Invalid id or password";
        }
        $data['errors'] = $user->errors;
        $this->view('auth/alumni_login',$data);// This loads the 'login' view.
    }
}