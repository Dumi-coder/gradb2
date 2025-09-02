<?php

class Mentorshiprequests extends Controller
{
    public function index()
    {
        // Check if user is logged in and is an alumni
        // For now, we'll just load the view
        
        $this->view('alumni/mentorshiprequests/index');
    }
    
    public function show($id = null)
    {
        // Handle showing individual mentorship request
        if($id) {
            $this->view('alumni/mentorshiprequests/show', ['id' => $id]);
        } else {
            $this->index();
        }
    }
}
