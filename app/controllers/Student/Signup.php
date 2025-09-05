<?php

class Signup extends Controller{

    public function index()
    {
        $data=[];
        
        if($_SERVER['REQUEST_METHOD']=="POST")
        {
            $user= new Student;

            if($user->validate($_POST))
            {
                $user->insert($_POST);

                redirect('student/login');
            }
    
            $data['errors'] = $user->errors;

        }
        // show($_POST);
        $this->view('auth/student_signup',$data);// This loads the 'signup' view.
    }
}