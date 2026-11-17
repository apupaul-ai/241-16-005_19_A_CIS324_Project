<?php require __DIR__ . '/../../../includes/header.php'; ?>

<section class="max-w-6xl mx-auto px-4 py-10">
    <h1 class="text-3xl font-bold text-pothik-green mb-6">Travel directory</h1>

    <form method="get" action="/pothik/directory.php" class="flex flex-wrap gap-3 mb-8">
        <select name="destination" class="border rounded-lg px-3 py-2">
            <option value="0">All destinations</option>
            <?php foreach ($options as $o): ?>
                <option value="<?= (int) $o['id'] ?>" <?= $destinationId === (int) $o['id'] ? 'selected' : '' ?>>
                    <?= htmlspecialchars($o['name']) ?>
                </option>
            <?php endforeach; ?>
        </select>
        <select name="type" class="border rounded-lg px-3 py-2">
            <option value="">All types</option>
            <?php foreach (['hotel' => 'Hotels', 'restaurant' => 'Restaurants', 'transport' => 'Transport'] as $val => $label): ?>
                <option value="<?= $val ?>" <?= $type === $val ? 'selected' : '' ?>><?= $label ?></option>
            <?php endforeach; ?>
        </select>
        <button class="bg-pothik-green text-white font-semibold px-5 py-2 rounded-lg hover:opacity-90" type="submit">
            Filter
        </button>
    </form>

    <?php if (count($items) === 0): ?>
        <p class="text-gray-600">No listings found.</p>
    <?php else: ?>
        <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
            <?php foreach ($items as $i): ?>
                <div class="border rounded-xl p-5 shadow-sm">
                    <p class="text-xs uppercase text-pothik-gold font-semibold">
                        <?= htmlspecialchars($i['type']) ?> &middot; <?= htmlspecialchars($i['destination']) ?>
                    </p>
                    <h2 class="text-lg font-bold mt-1"><?= htmlspecialchars($i['name']) ?></h2>
                    <?php if (!empty($i['address'])): ?>
                        <p class="text-sm text-gray-600 mt-2"><?= htmlspecialchars($i['address']) ?></p>
                    <?php endif; ?>
                    <?php if (!empty($i['price_range'])): ?>
                        <p class="text-sm mt-1">Price: <?= htmlspecialchars($i['price_range']) ?></p>
                    <?php endif; ?>
                    <?php if (!empty($i['phone'])): ?>
                        <p class="text-sm mt-2">
                            <a class="text-pothik-green underline"
                               href="tel:<?= htmlspecialchars(preg_replace('/[^0-9+]/', '', $i['phone'])) ?>">
                                <?= htmlspecialchars($i['phone']) ?>
                            </a>
                        </p>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</section>

<?php require __DIR__ . '/../../../includes/footer.php'; ?>