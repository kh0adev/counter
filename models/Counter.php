<?php

class Counter
{
    private $conn;

    public $id;
    public $quantity;

    public function __construct($db){
        $this->conn = $db;
    }


    public function fetchOne() {

        $stmt = $this->conn->prepare('SELECT  * FROM counter WHERE Id = 1');
        $stmt->execute();

        if($stmt->rowCount() > 0) {

            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            $this->id = $row['Id'];
            $this->quantity = $row['Quantity'];

            return TRUE;

        }

        return FALSE;
    }


    public function putData() {

        $stmt = $this->conn->prepare('UPDATE counter SET Quantity = :quantity WHERE id = 1');

        $stmt->bindParam(':quantity', $this->quantity);

        if($stmt->execute()) {
            return TRUE;
        }

        return FALSE;
    }
}