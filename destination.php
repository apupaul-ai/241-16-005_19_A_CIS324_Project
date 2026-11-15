<?php
require_once __DIR__ . '/config/config.php';

$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

$stmt = $pdo->prepare(
    "SELECT d.*, c.name AS category
     FROM destinations d
     JOIN categories c ON d.category_id = c.id
     WHERE d.id = ?"
);
$stmt->execute([$id]);
$destination = $stmt->fetch();

if (!$destination) {
    http_response_code(404);
    $pageTitle = 'Not found';
    require __DIR__ . '/includes/header.php';
    echo '<section class="max-w-6xl mx-auto px-4 py-10"><p>Destination not found.</p></section>';
    require __DIR__ . '/includes/footer.php';
    exit;
}

$pageTitle = $destination['name'];
require __DIR__ . '/includes/header.php';
?>

<section class="max-w-4xl mx-auto px-4 py-10">
    <a href="/pothik/destinations.php" class="text-sm text-pothik-green hover:underline">&larr; Back to destinations</a>

    <p class="mt-6 text-xs uppercase text-pothik-gold font-semibold">
        <?= htmlspecialchars($destination['category']) ?>
    </p>
    <h1 class="text-4xl font-bold text-pothik-green mt-1"><?= htmlspecialchars($destination['name']) ?></h1>
    <p class="text-gray-600 mt-1">
        <?= htmlspecialchars($destination['district']) ?>, <?= htmlspecialchars($destination['division']) ?>
    </p>

    <p class="mt-6 leading-relaxed"><?= nl2br(htmlspecialchars($destination['description'])) ?></p>

    <div class="grid gap-4 sm:grid-cols-3 mt-8">
        <div class="border rounded-xl p-4">
            <p class="text-xs text-gray-500">Best season</p>
            <p class="font-semibold"><?= htmlspecialchars($destination['best_season']) ?></p>
        </div>
        <div class="border rounded-xl p-4">
            <p class="text-xs text-gray-500">Average daily cost</p>
            <p class="font-semibold">BDT <?= number_format((float) $destination['avg_daily_cost']) ?></p>
        </div>
        <div class="border rounded-xl p-4">
            <p class="text-xs text-gray-500">Location</p>
            <p class="font-semibold"><?= htmlspecialchars($destination['latitude']) ?>, <?= htmlspecialchars($destination['longitude']) ?></p>
        </div>
    </div>
</section>

<?php require __DIR__ . '/includes/footer.php'; ?>