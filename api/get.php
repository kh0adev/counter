<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

require_once __DIR__ . '/../models/Counter.php';
require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../models/CounterService.php';


if ($_SERVER['REQUEST_METHOD'] === 'GET') {

    $db = new Database();
    $service = new CounterService($db);

    $data = $service->fetch();
    if ($data !== null) {
        echo json_encode($data);
    } else {
        echo json_encode(array('message' => "No records found!"));
    }

} else {
    echo json_encode(array('message' => "Error: incorrect Method!"));
}