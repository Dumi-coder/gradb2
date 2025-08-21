<!-- handles all user crud -->
<?php
class UserManager extends Controller
{    
    public function index()
    {        
        $this->view('super_admin/user_management');
    }
}