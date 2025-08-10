<?php

class Signup extends Controller{

    public function index()
    {
        $data=[];
        
        if($_SERVER['REQUEST_METHOD']=="POST")
        {
            $user= new User;

            if($user->validate($_POST))
            {
                $user->insert($_POST);
                redirect('login');
            }
    
            $data['errors'] = $user->errors;

        }
        // show($_POST);
        $this->view('auth/signup',$data);// This loads the 'signup' view.
    }
}