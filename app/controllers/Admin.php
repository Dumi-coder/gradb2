<?php

class Admin extends Controller
{
    public function index()
    {
        $this->view('auth/admin_login');
    }
}

