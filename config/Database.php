<?php

class Database {
    private $host = "MYSQL8002.site4now.net";
    private $user = "ac6da9_counter";
    private $db = "db_ac6da9_counter";
    private $pwd = "counter123";
    private $conn = NULL;

    public function connect() {

        try{
            $this->conn = new PDO("mysql:host=$this->host;dbname=$this->db", $this->user, $this->pwd);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch(PDOException $exp) {
            echo "Connection Error: " . $exp->getMessage();
        }

        return $this->conn;
    }
}