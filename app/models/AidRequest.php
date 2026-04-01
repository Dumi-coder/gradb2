<?php

class AidRequest
{
    use Model;

    protected $table = 'aid_requests';
    protected $id_column = 'aid_request_id';

    protected $allowedColumns = [
        'request_id',
        'mobile_number',
        'aid_type',
        'amount',
        'reason',
        'student_id_pdf_path',
        'income_statement_path',
        'gramaseva_cert_path',
    ];
}
