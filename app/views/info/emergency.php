<?php
require __DIR__ . '/../../../includes/header.php';

$typeLabels = ['police' => 'Police', 'hospital' => 'Hospital', 'fire' => 'Fire service', 'other' => 'Other'];
?>

<section class="max-w-6xl mx-auto px-4 py-10">
    <h1 class="text-3xl font-bold text-pothik-green mb-2">Emergency and essentials</h1>
    <p class="text-gray-600 mb-8">Keep these numbers handy while you travel.</p>

    <h2 class="text-xl font-bold mb-4">Nationwide</h2>
    <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3 mb-10">
        <?php foreach ($national as $c): ?>
            <div class="border rounded-xl p-5">
                <p class="text-xs uppercase text-pothik-gold font-semibold">
                    <?= htmlspecialchars($typeLabels[$c['type']] ?? $c['type']) ?>
                </p>
                <p class="font-semibold mt-1"><?= htmlspecialchars($c['name']) ?></p>
                <a class="block text-2xl font-bold text-pothik-green mt-2"
                   href="tel:<?= htmlspecialchars(preg_replace('/[^0-9+]/', '', $c['phone'])) ?>">
                    <?= htmlspecialchars($c['phone']) ?>
                </a>
            </div>
        <?php endforeach; ?>
    </div>

    <?php foreach ($local as $destination => $list): ?>
        <h2 class="text-xl font-bold mb-4"><?= htmlspecialchars($destination) ?></h2>
        <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3 mb-10">
            <?php foreach ($list as $c): ?>
                <div class="border rounded-xl p-5">
                    <p class="text-xs uppercase text-pothik-gold font-semibold">
                        <?= htmlspecialchars($typeLabels[$c['type']] ?? $c['type']) ?>
                    </p>
                    <p class="font-semibold mt-1"><?= htmlspecialchars($c['name']) ?></p>
                    <a class="block text-xl font-bold text-pothik-green mt-2"
                       href="tel:<?= htmlspecialchars(preg_replace('/[^0-9+]/', '', $c['phone'])) ?>">
                        <?= htmlspecialchars($c['phone']) ?>
                    </a>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endforeach; ?>
</section>

<?php require __DIR__ . '/../../../includes/footer.php'; ?>