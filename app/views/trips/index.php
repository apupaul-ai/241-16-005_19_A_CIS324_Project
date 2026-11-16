<?php require __DIR__ . '/../../../includes/header.php'; ?>

<section class="max-w-4xl mx-auto px-4 py-10">
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-3xl font-bold text-pothik-green">My trips</h1>
        <a href="/pothik/trips.php?action=create"
           class="bg-pothik-gold text-white font-semibold px-4 py-2 rounded-lg hover:opacity-90">
            New trip
        </a>
    </div>

    <?php if (count($trips) === 0): ?>
        <p class="text-gray-600">You have no trips yet. Create your first trip plan.</p>
    <?php else: ?>
        <div class="space-y-4">
            <?php foreach ($trips as $t): ?>
                <div class="border rounded-xl p-5">
                    <h2 class="text-lg font-bold">
                        <a class="hover:underline" href="/pothik/trips.php?action=show&id=<?= (int) $t['id'] ?>">
                            <?= htmlspecialchars($t['title']) ?>
                        </a>
                    </h2>
                    <p class="text-sm text-gray-600 mt-1">
                        <?= $t['start_date'] ? htmlspecialchars($t['start_date']) : 'No start date' ?>
                        &rarr;
                        <?= $t['end_date'] ? htmlspecialchars($t['end_date']) : 'No end date' ?>
                    </p>
                    <p class="text-sm mt-1">
                        Budget: <?= $t['budget'] !== null ? 'BDT ' . number_format((float) $t['budget']) : 'Not set' ?>
                    </p>
                    <div class="flex gap-3 mt-3 text-sm">
                        <a class="text-pothik-green underline" href="/pothik/trips.php?action=show&id=<?= (int) $t['id'] ?>">Open</a>
                        <a class="text-pothik-green underline" href="/pothik/trips.php?action=edit&id=<?= (int) $t['id'] ?>">Edit</a>
                        <form method="post" action="/pothik/trips.php?action=delete&id=<?= (int) $t['id'] ?>"
                              onsubmit="return confirm('Delete this trip?');">
                            <button class="text-red-600 underline" type="submit">Delete</button>
                        </form>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</section>

<?php require __DIR__ . '/../../../includes/footer.php'; ?>