<?php
//  echo "Model.php loaded<br> ";
Trait Model // This trait provides basic database operations for models
{    
    use Database;// Use the Database trait for database connection and query execution
    // protected $table        = 'users';
    protected $limit        = 10;// Default limit for pagination
    protected $offset       = 0;// Default offset for pagination
    protected $order_type   = "desc"; // Default order type for sorting
    // public $order_column='id';// Default order column for sorting
    public $errors          = [];// Array to hold validation errors

    public function findAll()
    {
        $query = "select * from $this->table order by $this->order_column $this->order_type limit $this->limit offset $this->offset";
    
        return $this->query($query);// Execute the query and return the result
    }
    public function where($data,$data_not=[])// This method retrieves records based on the provided conditions
    {
        $keys=array_keys($data);// Get the keys of the data array
        $keys_not=array_keys($data_not);// Get the keys of the not data array
        $query="select * from $this->table where ";// Start building the query

        foreach($keys as $key)
        {
            $query .= $key . " = :". $key . " && ";// Loop through each key and append to the query
        }
        foreach($keys_not as $key)
        {
            $query .= $key . " != :". $key . " && ";// Loop through each not key and append to the query
        }

        $query=trim($query," && "); // Remove the trailing ' && ' from the query
        $query .= " order by $this->order_column $this->order_type limit $this->limit offset $this->offset";// Append order and limit to the query
        $data = array_merge($data,$data_not);// Merge the data and not data arrays
        return $this->query($query,$data);// Execute the query and return the result
    }


    public function first($data,$data_not=[])// This method retrieves the first record that matches the provided conditions
    {
        $keys=array_keys($data);
        $keys_not=array_keys($data_not);
        $query="select * from $this->table where ";

        foreach($keys as $key)
        {
            $query .= $key . " = :". $key . " && ";// Loop through each key and append to the query
        }
        foreach($keys_not as $key)
        {
            $query .= $key . " != :". $key . " && ";// Loop through each not key and append to the query
        }

        $query=trim($query," && "); // Remove the trailing ' && ' from the query
        $query .= " limit $this->limit offset $this->offset";// Append limit and offset to the query
        $data = array_merge($data,$data_not);// Merge the data and not data arrays
        $result = $this->query($query,$data);// Execute the query and get the result
        if($result)
        {
            return $result[0];// Return the first record if available
        }
        return false;// Return false if no records found
    }
    
    public function insert($data)
    {
        /**remove unwanted data */
        if(!empty($this->allowedColumns))
        {
            foreach($data as $key => $value)
            {
                if(!in_array($key,$this->allowedColumns))
                {
                    unset($data[$key]);
                }
            }
        }
        $keys=array_keys($data);// Get the keys of the data array
        $query="insert into $this->table (".implode(",", $keys).") values (:".implode(",:",$keys).")";// Start building the insert query
        $this->query($query,$data);// Execute the query with the provided data

        return false;// Return false to indicate the operation is complete
    }

    public function update($id,$data,$id_column=null)
    {
        if (!$id_column) {
            $id_column = $this->id_column;
        }
        /**remove unwanted data */
        if(!empty($this->allowedColumns))
        {
            foreach($data as $key => $value)
            {
                if(!in_array($key,$this->allowedColumns))
                {
                    unset($data[$key]);// Remove keys that are not allowed
                }
            }
        }

        
        $keys=array_keys($data);// Get the keys of the data array
        $query="update $this->table set ";// Start building the update query

        foreach($keys as $key)// Loop through each key
        {
            $query .= $key . " = :". $key . ", ";
        }
         
        $query=trim($query,", "); // Remove the trailing comma and space
        $query .= " where $id_column = :$id_column ";// Append the where clause to the query
        $data[$id_column]=$id;
        // echo $query;
        $this->query($query,$data);// Execute the query with the provided data
        return false;// Return false to indicate the operation is complete
    }

    public function delete($id,$id_column=null)// This method deletes a record based on the provided ID and ID column
    {
        if (!$id_column) {
            $id_column = $this->id_column;
        }
        
        $data[$id_column]=$id;
        $query="delete from $this->table where $id_column = :$id_column ";
        $this->query($query,$data);// Execute the query

        return false;// Return false to indicate the operation is complete
    }
}