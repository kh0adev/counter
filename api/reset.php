<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../models/CounterService.php';

if ($_SERVER['REQUEST_METHOD'] === 'PUT' || $_SERVER['REQUEST_METHOD'] === 'POST') {
    $db = new Database();
    $service = new CounterService($db);
    $res = $service->reset();
    echo json_encode($res);
} else {
    echo json_encode(array('message' => 'Error: incorrect Method!'));
}