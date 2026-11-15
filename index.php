<?php
$pageTitle = 'Home';
require __DIR__ . '/includes/header.php';

$stmt = $pdo->query("SELECT id, name, division, description FROM destinations ORDER BY id LIMIT 6");
$featured = $stmt->fetchAll();
?>

<section class="bg-pothik-green text-white">
    <div class="max-w-6xl mx-auto px-4 py-20 text-center">
        <h1 class="text-4xl md:text-5xl font-bold">Discover Bangladesh with Pothik</h1>
        <p class="mt-4 text-lg text-green-100">Explore destinations, plan your trip and travel smart.</p>
        <a href="/pothik/destinations.php"
           class="inline-block mt-8 bg-pothik-gold hover:bg-amber-700 text-white font-semibold px-6 py-3 rounded-lg">
            Explore destinations
        </a>
    </div>
</section>

<section class="max-w-6xl mx-auto px-4 py-12">
    <h2 class="text-2xl font-bold text-pothik-green mb-6">Popular destinations</h2>
    <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
        <?php foreach ($featured as $d): ?>
            <div class="border rounded-xl p-5 shadow-sm">
                <p class="text-xs uppercase text-pothik-gold font-semibold"><?= htmlspecialchars($d['division']) ?></p>
                <h3 class="text-lg font-bold mt-1"><?= htmlspecialchars($d['name']) ?></h3>
                <p class="text-sm text-gray-600 mt-2">
                    <?= htmlspecialchars(mb_strimwidth($d['description'], 0, 110, '...')) ?>
                </p>
            </div>
        <?php endforeach; ?>
    </div>
</section>

<?php require __DIR__ . '/includes/footer.php'; ?>