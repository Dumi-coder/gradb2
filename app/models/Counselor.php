<?php


class Counselor
{
    use Model;

    protected $table = 'counselor';
    protected $id_column = 'user_id';
    protected $order_column = 'user_id';
    protected $allowedColumns = ['user_id', 'name', 'email', 'password', 'profile_photo_url'];
    
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