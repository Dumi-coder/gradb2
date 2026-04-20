<?php
//  echo "Database.php loaded<br> ";
// error_reporting(E_ALL);
// ini_set('display_errors', 1);

trait Database// This trait provides basic database operations for models
{
    private function connect()
{
    try {
        $string = "mysql:host=".DBHOST.";port=".DBPORT.";dbname=".DBNAME.";charset=utf8";
        $con = new PDO($string, DBUSER, DBPASS, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ,
        ]);
        return $con;
    } catch (Throwable $e) {
        if(defined('DEBUG') && DEBUG)
        {
            echo "Database connection error: " . $e->getMessage() . "<br>";
        }
        return null;
    }
}

    
    public function query($query,$data=[])// This function is used to execute a query and return the results
    {
        $con=$this->connect();                     // 1. Get a PDO connection
        if(!$con)
        {
            return false;
        }

        $stm=$con->prepare($query);                // 2. Prepare the SQL statement

        $check=$stm->execute($data);               // 3. Execute the statement with data
        if($check)                                 // 4. If execution was successful
        {
            if(stripos($query,"SELECT") === 0) // Check if the query is a SELECT statement
            {
                $result=$stm->fetchAll(PDO::FETCH_OBJ); // 5. Fetch all results as objects
                if(is_array($result) && count($result))
                    {
                        return $result;
                
                    }
            }
            return true; // For non-SELECT queries, return true to indicate success
        }
        return false;// 6. Return false if no results found or execution failed
    }
    public function get_row($query,$data=[])// This function is used to get a single row from the database
    {
        $con=$this->connect();                     // 1. Get a PDO connection
        if(!$con)
        {
            return false;
        }

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