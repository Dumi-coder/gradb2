<?php

class Student
{
    use Model;

    protected $table = 'student'; // Your student table name
    protected $allowedColumns = ['name', 'email', 'student_id', 'faculty', 'password'];
    protected $id_column = 'student_id';
    protected $order_column = 'student_id';

    
    // You can add a validate() method similar to User if needed
    public function validate($data)
    {
        $this->errors = [];

        if(empty($this->errors))
        {
            return true;
        }
        return false;
    }
}