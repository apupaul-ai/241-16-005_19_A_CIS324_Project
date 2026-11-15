<?php
$pageTitle = 'Destinations';
require __DIR__ . '/includes/header.php';

$categories = $pdo->query("SELECT id, name FROM categories ORDER BY name")->fetchAll();

$categoryId = isset($_GET['category']) ? (int) $_GET['category'] : 0;

$sql = "SELECT d.id, d.name, d.division, d.description, c.name AS category
        FROM destinations d
        JOIN categories c ON d.category_id = c.id";

if ($categoryId > 0) {
    $stmt = $pdo->prepare($sql . " WHERE d.category_id = ? ORDER BY d.name");
    $stmt->execute([$categoryId]);
} else {
    $stmt = $pdo->query($sql . " ORDER BY d.name");
}
$destinations = $stmt->fetchAll();
?>

<section class="max-w-6xl mx-auto px-4 py-10">
    <h1 class="text-3xl font-bold text-pothik-green mb-6">Destinations</h1>

    <div class="flex flex-wrap gap-2 mb-8">
        <a href="/pothik/destinations.php"
           class="px-4 py-2 rounded-full text-sm border <?= $categoryId === 0 ? 'bg-pothik-green text-white' : 'hover:bg-gray-100' ?>">
            All
        </a>
        <?php foreach ($categories as $c): ?>
            <a href="/pothik/destinations.php?category=<?= (int) $c['id'] ?>"
               class="px-4 py-2 rounded-full text-sm border <?= $categoryId === (int) $c['id'] ? 'bg-pothik-green text-white' : 'hover:bg-gray-100' ?>">
                <?= htmlspecialchars($c['name']) ?>
            </a>
        <?php endforeach; ?>
    </div>

    <?php if (count($destinations) === 0): ?>
        <p class="text-gray-600">No destinations found in this category.</p>
    <?php else: ?>
        <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
            <?php foreach ($destinations as $d): ?>
                <a href="/pothik/destination.php?id=<?= (int) $d['id'] ?>"
                   class="block border rounded-xl p-5 shadow-sm hover:shadow-md transition">
                    <p class="text-xs uppercase text-pothik-gold font-semibold">
                        <?= htmlspecialchars($d['category']) ?> &middot; <?= htmlspecialchars($d['division']) ?>
                    </p>
                    <h2 class="text-lg font-bold mt-1"><?= htmlspecialchars($d['name']) ?></h2>
                    <p class="text-sm text-gray-600 mt-2">
                        <?= htmlspecialchars(mb_strimwidth($d['description'], 0, 110, '...')) ?>
                    </p>
                </a>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</section>

<?php require __DIR__ . '/includes/footer.php'; ?>