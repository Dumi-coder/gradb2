<?php
class Api extends Controller
{
    public function getUserData($id)
    {
        $this->view('api/user', ['user' => $userData]);
    }
}