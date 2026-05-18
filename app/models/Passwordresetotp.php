<?php

class Passwordresetotp
{
    use Model;

    protected $table = 'password_resets';
    protected $order_column = 'id';
    protected $id_column = 'id';

    protected $allowedColumns = [
        'user_id',
        'otp_hash',
        'expires_at',
        'created_at',
        'used_at',
        'attempts',
        'request_ip'
    ];
}
