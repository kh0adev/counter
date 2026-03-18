<?php
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');

    include_once '../config/Database.php';
    include_once '../models/Counter.php';

    if ($_SERVER['REQUEST_METHOD'] === 'GET') {

        $db = new Database();
        $db = $db->connect();
        $counter = new Counter($db);

        if($counter->fetchOne()) {



            print_r(json_encode(array(
                'id' => $counter->id,
                'quantity' => $counter->quantity,
            )));

            $counter->quantity += 1;

            $counter->putData();

        } else {
            echo json_encode(array('message' => "No records found!"));
        }

    } else {
        echo json_encode(array('message' => "Error: incorrect Method!"));
    }