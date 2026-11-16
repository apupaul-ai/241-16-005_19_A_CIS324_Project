<?php
require_once __DIR__ . '/config/config.php';
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/app/models/Trip.php';
require_once __DIR__ . '/app/controllers/TripController.php';

$controller = new TripController($pdo);
$action = $_GET['action'] ?? 'index';

switch ($action) {
    case 'create':
        $controller->create();
        break;
    case 'index':
    default:
        $controller->index();
}