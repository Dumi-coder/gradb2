<?php
//  echo "Database.php loaded<br> ";
// error_reporting(E_ALL);
// ini_set('display_errors', 1);

trait Database// This trait provides basic database operations for models
{
    private function connect()// This function is used to establish a connection to the database
    {
        // $string = "mysql:hostname=" . DBHOST . ";dbname=" . DBNAME . ";charset=utf8";
        $string="mysql:hostname=".DBHOST.";dbname=".DBNAME;// Create a DSN string for the database connection
        $con = new PDO($string,DBUSER,DBPASS);// Create a new PDO instance with the DSN, username, and password
        return $con;// Return the PDO connection object
    }
    
    public function query($query,$data=[])// This function is used to execute a query and return the results
    {
        $con=$this->connect();                     // 1. Get a PDO connection
        $stm=$con->prepare($query);                // 2. Prepare the SQL statement

        $check=$stm->execute($data);               // 3. Execute the statement with data
        if($check)                                 // 4. If execution was successful
        {<?php
if(empty($data['confirm_password']) || $data['password'] !== $data['confirm_password']) {
    $this->errors['confirm_password'] = "Passwords do not match";
}
           $result=$stm->fetchAll(PDO::FETCH_OBJ); // 5. Fetch all results as objects
           if(is_array($result) && count($result))
           {
            return $result;
           }
        }
        return false;// 6. Return false if no results found or execution failed
    }
    public function get_row($query,$data=[])// This function is used to get a single row from the database
    {
        $con=$this->connect();                     // 1. Get a PDO connection
        $stm=$con->prepare($query);                // 2. Prepare the SQL statement

        $check=$stm->execute($data);               // 3. Execute the statement with data
        if($check)                                 // 4. If execution was successful
        {
           $result=$stm->fetchAll(PDO::FETCH_OBJ); // 5. Fetch all results as objects
           if(is_array($result) && count($result))// 6. Check if results are an array and not empty
           {
            return $result[0]; // 7. Return the first result object
           }
        }
        return false;// 8. Return false if no results found or execution failed
    }
}