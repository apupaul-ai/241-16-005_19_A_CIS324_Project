<?php
require_once __DIR__ . '/../includes/admin_guard.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    die('POST only.');
}
csrf_check();

$id = (int) ($_POST['id'] ?? 0);
try {
    $stmt = $pdo->prepare('DELETE FROM destinations WHERE id = ?');
    $stmt->execute([$id]);
    $_SESSION['flash'] = 'Destination deleted.';
} catch (PDOException $e) {
    // Foreign key (e.g. directory items) can block the delete
    $_SESSION['flash'] = 'Cannot delete: other data still depends on this destination.';
}
header('Location: /pothik/admin/index.php');
exit;