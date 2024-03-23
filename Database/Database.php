<?php

class Database {

    public $hostname = "localhost";
    public $username = "root";
    public $password = "";
    public $database = "ayushclinic"; // Specify your database na   me here
    public $connection; // No need to initialize it here
    public $error = [];
    public $select = "";
    public $massage = "";
    function __construct()
    {
        $this->connection = mysqli_connect($this->hostname, $this->username, $this->password, $this->database);
        if(!$this->connection)
        {
           $this->error[] = ["message" => "Error connecting to database"]; // Corrected $this->error
        }
    }

    public function selectData($tablename) // Corrected function name selectData
    {
        $this->select = "SELECT * FROM $tablename"; // Table name should be "Users" (case-sensitive)
        $getData = mysqli_query($this->connection, $this->select);
        if ($getData) {
            while ($row = mysqli_fetch_assoc($getData)) {
                // print_r($row);
            }
        } 
    }

    public function insert($table, $data) {
        $keys = array_keys($data);
        $arraykeys = implode(",",$keys);
        $userData = implode(",",$data);
        $insert = "INSERT INTO $table ( $arraykeys ) VALUES ($userData)";
        echo $insert;
        $execute = mysqli_query($this->connection, $insert);
       
    }

    public function delete($table, $id)
    {
        // Check if table has foreign key constraints
        // If so, handle deletion accordingly
        switch($table) {
            case 'users':
                // Delete associated payments
                $deletePayments = "DELETE FROM payments WHERE user_id = '$id'";
                $paymentsQuery = mysqli_query($this->connection, $deletePayments);
                if(!$paymentsQuery) {
                    return "Failed to delete associated payments: " . mysqli_error($this->connection);
                }
                break;
            // Add other cases for additional tables with foreign key constraints as needed
        }
    
        // Delete the user
        $deleteUser = "DELETE FROM $table WHERE id = '$id'";
        $query = mysqli_query($this->connection, $deleteUser);
        if($query)
        {
            return "Record with ID $id successfully deleted";
        }
        else
        {
            return "Failed to delete record with ID $id: " . mysqli_error($this->connection);
        }
    }
    

    public function update($table, $data, $id)
    {
        $setValues = "";
        foreach ($data as $key => $value) {
            $setValues .= "$key = '$value', ";
        }
        $setValues = rtrim($setValues, ', '); // Remove the trailing comma and space
    
        $update = "UPDATE $table SET $setValues WHERE id = '$id'";
        $query = mysqli_query($this->connection, $update);
        if($query)
        {
            return "Record with ID $id successfully updated";
        }
        else
        {
            return "Failed to update record with ID $id: " . mysqli_error($this->connection);
        }
    }
    
}   

    
$userid = "IND".rand(0000,9999);

$user = [
    "userid" => "'$userid'",
    "firstname" => "'shadap'",
    "lastname" => "'sam'",
    "age" => "'21'",
    "gender" => "'male'",
    "phone_number" => "'9370646455'",
    "address" => "'solapure nagar , pandharpur'"
];

$services = [
    "service_name" => "'caterate operation'",
    "pricing" => "'5000'"
];

$database = new Database();
$database->selectData("appointments");
$database->insert("services" , $services);
echo $database->delete("users", 1);
echo $database->update("users", ["firstname" => "shadap", "lastname" => "chaudhari"], 7);



?>
