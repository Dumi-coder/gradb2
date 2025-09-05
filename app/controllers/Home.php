<?php

class Home extends Controller
{
    
    public function index()// This is the index method of the Home controller
    {
        $data['username'] = empty($_SESSION['USER']) ? 'User' : $_SESSION['USER']->name; // Check if the user is logged in and set the username accordingly
        
        $this->view('home',$data);// This loads the 'home' view.
    }
}

