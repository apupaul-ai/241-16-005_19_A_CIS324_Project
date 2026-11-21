<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/csrf.php';

// Role is re-checked from the DB, not trusted from the session alone.
if (empty($_SESSION['user_id'])) {
    header('Location: /pothik/login.php');
    exit;
}

$stmt = $pdo->prepare('SELECT role FROM users WHERE id = ?');
$stmt->execute([(int) $_SESSION['user_id']]);
$row = $stmt->fetch();

if (!$row || $row['role'] !== 'admin') {
    http_response_code(403);
    die('403 Forbidden: admin only.');
}