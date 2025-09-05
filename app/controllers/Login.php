<?php

class Login extends Controller
{

    public function index()
    {                 
        $this->view('auth/login');// This loads the 'login' view.
    }
}
    