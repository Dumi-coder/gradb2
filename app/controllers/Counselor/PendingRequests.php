<?php
class PendingRequests extends Controller
{
    
    public function index()
    {        
        $this->view('counselor/pending-requests');
    }
}