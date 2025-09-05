<?php

class Signup extends Controller{

    public function index()
    {
        $data=[];
        
        if($_SERVER['REQUEST_METHOD']=="POST")
        {
            $user= new Alumni;

            if($user->validate($_POST))
            {
                $user->insert($_POST);

                redirect('alumni/login');
            }
    
            $data['errors'] = $user->errors;

        }
        // show($_POST);
        $this->view('auth/alumni_signup',$data);// This loads the 'signup' view.
    }
}