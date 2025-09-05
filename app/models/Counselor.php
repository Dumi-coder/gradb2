<?php


class Counselor
{
    use Model;

    protected $table = 'counselor'; // Your alumni table name
    protected $allowedColumns = ['name', 'email', 'password'];
    // protected $id_column = 'alumni_id';
    // protected $order_column = 'alumni_id';
    
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