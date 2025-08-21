<!--  For approving alumni into faculty groups -->

<?php
class Membership extends Controller
{    
    public function index()
    {        
        $this->view('faculty_admin/membership_queue');
    }
}