<?php

class Database {
    private string $host = "MYSQL8002.site4now.net";
    private string $user = "ac6da9_counter";
    private string $db = "db_ac6da9_counter";
    private string $pwd = "counter123";
    public PDO $conn ;

    private string $timeZone = "SET time_zone = 'Asia/Ho_Chi_Minh'";

    public function __construct()
    {
        $this->conn = $this->connect();
    }

    private function connect(): PDO
    {

        try{
            $this->conn = new PDO("mysql:host=$this->host;dbname=$this->db", $this->user, $this->pwd);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->conn->setAttribute(PDO::MYSQL_ATTR_INIT_COMMAND, $this->timeZone);
        } catch(PDOException $exp) {
            echo "Connection Error: " . $exp->getMessage();
        }

        return $this->conn;
    }
}