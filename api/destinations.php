<?php
require_once __DIR__ . '/../config/config.php';

header('Content-Type: application/json; charset=utf-8');

function respond(int $status, array $body): void
{
    http_response_code($status);
    echo json_encode($body, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    header('Allow: GET');
    respond(405, ['error' => 'Method not allowed. Use GET.']);
}

$cols = 'd.id, d.name, d.division, d.district, d.description, d.best_season,
         d.avg_daily_cost, d.latitude, d.longitude, c.name AS category';
$from = 'FROM destinations d JOIN categories c ON c.id = d.category_id';

// GET /api/destinations.php?id=3  -> one destination
if (isset($_GET['id'])) {
    $id = (int) $_GET['id'];
    $stmt = $pdo->prepare("SELECT $cols $from WHERE d.id = ?");
    $stmt->execute([$id]);
    $row = $stmt->fetch();
    if (!$row) {
        respond(404, ['error' => 'Destination not found.']);
    }
    respond(200, ['data' => $row]);
}

// GET /api/destinations.php?category=2&q=sylhet&page=1&limit=5 -> list
$where = [];
$params = [];

$category = (int) ($_GET['category'] ?? 0);
if ($category > 0) {
    $where[] = 'd.category_id = ?';
    $params[] = $category;
}
$q = trim($_GET['q'] ?? '');
if ($q !== '') {
    $where[] = '(d.name LIKE ? OR d.division LIKE ? OR d.district LIKE ?)';
    array_push($params, "%$q%", "%$q%", "%$q%");
}
$whereSql = $where ? ' WHERE ' . implode(' AND ', $where) : '';

$limit = min(50, max(1, (int) ($_GET['limit'] ?? 10)));
$page  = max(1, (int) ($_GET['page'] ?? 1));
$offset = ($page - 1) * $limit;

$count = $pdo->prepare("SELECT COUNT(*) FROM destinations d $whereSql");
$count->execute($params);
$total = (int) $count->fetchColumn();

$stmt = $pdo->prepare("SELECT $cols $from $whereSql ORDER BY d.name LIMIT $limit OFFSET $offset");
$stmt->execute($params);

respond(200, [
    'meta' => [
        'total' => $total,
        'page'  => $page,
        'limit' => $limit,
        'pages' => (int) ceil($total / $limit),
    ],
    'data' => $stmt->fetchAll(),
]);