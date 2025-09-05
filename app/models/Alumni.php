<?php


class Alumni
{
    use Model;

    protected $table = 'alumni'; // Your alumni table name
    protected $allowedColumns = ['name', 'email', 'alumni_id', 'faculty', 'graduation_year', 'password'];
    protected $id_column = 'alumni_id';
    protected $order_column = 'alumni_id';
    
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