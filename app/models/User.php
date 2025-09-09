<?php

class User
{
    use Model;
    
    protected $table = 'users';
    protected $order_column = 'user_id';
    protected $id_column = 'user_id';
    
    protected $allowedColumns = [
        'name',
        'email', 
        'password',
        'role'
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
            
            if (defined('DEBUG') && DEBUG) {
                echo "Query: " . $query . "<br>";
                echo "Data: ";
                $debug_data = $data;
                $debug_data['password'] = '[HIDDEN]'; // Don't show password in debug
                print_r($debug_data);
                echo "<br>";
            }
            
            $con = $this->connect();
            $stm = $con->prepare($query);
            $result = $stm->execute($data);
            
            if (!$result) {
                $errorInfo = $stm->errorInfo();
                if (defined('DEBUG') && DEBUG) {
                    echo "SQL Error: ";
                    print_r($errorInfo);
                    echo "<br>";
                }
                return false;
            }
            
            return true;
            
        } catch (Exception $e) {
            if (defined('DEBUG') && DEBUG) {
                echo "Exception in User insert: " . $e->getMessage() . "<br>";
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
            if (defined('DEBUG') && DEBUG) {
                echo "Database connection error: " . $e->getMessage() . "<br>";
            }
            return null;
        }
    }

    // Add debug method for updates
    public function logUpdate($id, $data, $id_column)
    {
        error_log("User update attempted - ID: $id, Column: $id_column, Data: " . print_r($data, true));
        $result = $this->update($id, $data, $id_column);
        error_log("User update result: " . ($result ? 'success' : 'failed'));
        return $result;
    }
}