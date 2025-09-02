<?php
class Profile extends Controller
{    
    public function index()
    {        
        $this->view('alumni/profile/show');
    }

    // Front-end only edit page (no persistence logic)
    public function edit()
    {
        $this->view('alumni/profile/edit');
    }
}