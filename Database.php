<?php
class Database {
    
    private $host = "localhost";
    private $db = "pamaloy"; 
    private $username = "root";
    private $password = "";
    public $conn;

    public function connect() {
        try {
            $this->conn = new PDO("mysql:host=". 
            $this->host. ";dbname=". 
            $this->db,  
            $this->username, 
            $this->password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $this->conn;
        }
        catch (PDOException $exception) {
            echo "Connection error: " . $exception->getMessage();
            return null;
        }   
    }
}
?>