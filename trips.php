<?php
require_once __DIR__ . '/config/config.php';
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/app/models/Trip.php';
require_once __DIR__ . '/app/models/Destination.php';
require_once __DIR__ . '/app/controllers/TripController.php';

$controller = new TripController($pdo);
$action = $_GET['action'] ?? 'index';

switch ($action) {
    case 'create':
        $controller->create();
        break;
    case 'show':
        $controller->show();
        break;
    case 'edit':
        $controller->edit();
        break;
    case 'delete':
        $controller->delete();
        break;
    case 'add_stop':
        $controller->addStop();
        break;
    case 'remove_stop':
        $controller->removeStop();
        break;
    case 'index':
    default:
        $controller->index();
}