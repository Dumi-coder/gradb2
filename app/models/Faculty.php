<?php

class Faculty
{
    use Model;
    
    protected $table = 'faculties';
    protected $order_column = 'faculty_id';
    protected $id_column = 'faculty_id';
    
    protected $allowedColumns = [
        'faculty_name'
    ];

    public function insert($data)
    {
        try {
            // Remove unwanted data
            if (!empty($this->allowedColumns)) {
                foreach ($data as $key => $value) {
                    if (!in_array($key, $this->allowedColumns)) {
                        unset($data[$key]);
                    }
                }
            }

            $keys = array_keys($data);
            $query = "INSERT INTO $this->table (" . implode(",", $keys) . ") VALUES (:" . implode(",:", $keys) . ")";
            
            $con = $this->connect();
            $stm = $con->prepare($query);
            $result = $stm->execute($data);
            
            return $result;
        } catch (Exception $e) {
            if (DEBUG) {
                echo "Error in Faculty insert: " . $e->getMessage();
            }
            return false;
        }
    }

    private function connect()
    {
        try {
            $string = "mysql:host=" . DBHOST . ";dbname=" . DBNAME . ";charset=utf8";
            $con = new PDO($string, DBUSER, DBPASS);
            $con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $con;
        } catch (PDOException $e) {
            if (DEBUG) {
                echo "Database connection error: " . $e->getMessage();
            }
            die("Database connection failed");
        }
    }
}