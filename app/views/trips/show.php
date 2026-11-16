<?php require __DIR__ . '/../../../includes/header.php'; ?>

<section class="max-w-4xl mx-auto px-4 py-10">
    <a href="/pothik/trips.php" class="text-sm text-pothik-green hover:underline">&larr; My trips</a>

    <div class="flex items-center justify-between mt-4">
        <h1 class="text-3xl font-bold text-pothik-green"><?= htmlspecialchars($trip['title']) ?></h1>
        <a class="text-sm text-pothik-green underline" href="/pothik/trips.php?action=edit&id=<?= (int) $trip['id'] ?>">Edit</a>
    </div>
    <p class="text-gray-600 mt-1">
        <?= $trip['start_date'] ? htmlspecialchars($trip['start_date']) : 'No start date' ?>
        &rarr;
        <?= $trip['end_date'] ? htmlspecialchars($trip['end_date']) : 'No end date' ?>
    </p>

    <div class="grid gap-4 sm:grid-cols-3 mt-6">
        <div class="border rounded-xl p-4">
            <p class="text-xs text-gray-500">Budget</p>
            <p class="font-semibold">
                <?= $trip['budget'] !== null ? 'BDT ' . number_format((float) $trip['budget']) : 'Not set' ?>
            </p>
        </div>
        <div class="border rounded-xl p-4">
            <p class="text-xs text-gray-500">Estimated cost (sum of stops' daily cost)</p>
            <p class="font-semibold">BDT <?= number_format($estimated) ?></p>
        </div>
        <div class="border rounded-xl p-4">
            <p class="text-xs text-gray-500">Status</p>
            <?php if ($trip['budget'] === null): ?>
                <p class="font-semibold">Set a budget to compare</p>
            <?php elseif ($estimated <= (float) $trip['budget']): ?>
                <p class="font-semibold text-green-700">
                    Within budget (BDT <?= number_format((float) $trip['budget'] - $estimated) ?> left)
                </p>
            <?php else: ?>
                <p class="font-semibold text-red-700">
                    Over budget by BDT <?= number_format($estimated - (float) $trip['budget']) ?>
                </p>
            <?php endif; ?>
        </div>
    </div>

    <h2 class="text-2xl font-bold text-pothik-green mt-10 mb-4">Itinerary</h2>

    <?php if (count($stops) === 0): ?>
        <p class="text-gray-600">No stops yet. Add your first destination below.</p>
    <?php else: ?>
        <div class="space-y-3">
            <?php foreach ($stops as $s): ?>
                <div class="border rounded-xl p-4 flex items-start justify-between gap-4">
                    <div>
                        <p class="text-xs uppercase text-pothik-gold font-semibold">Day <?= (int) $s['day_number'] ?></p>
                        <p class="font-bold">
                            <a class="hover:underline" href="/pothik/destination.php?id=<?= (int) $s['destination_id'] ?>">
                                <?= htmlspecialchars($s['name']) ?>
                            </a>
                        </p>
                        <?php if ($s['notes'] !== null && $s['notes'] !== ''): ?>
                            <p class="text-sm text-gray-700 mt-1"><?= nl2br(htmlspecialchars($s['notes'])) ?></p>
                        <?php endif; ?>
                    </div>
                    <form method="post" action="/pothik/trips.php?action=remove_stop&id=<?= (int) $trip['id'] ?>">
                        <input type="hidden" name="stop_id" value="<?= (int) $s['id'] ?>">
                        <button class="text-sm text-red-600 underline" type="submit">Remove</button>
                    </form>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <h3 class="text-lg font-bold mt-10 mb-3">Add a stop</h3>

    <?php if ($stopErrors): ?>
        <div class="mb-4 rounded-lg border border-red-300 bg-red-50 p-4 text-sm text-red-700">
            <ul class="list-disc pl-5">
                <?php foreach ($stopErrors as $e): ?>
                    <li><?= htmlspecialchars($e) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <form method="post" action="/pothik/trips.php?action=add_stop&id=<?= (int) $trip['id'] ?>"
          class="border rounded-xl p-5 space-y-4">
        <div>
            <label class="block text-sm font-medium mb-1" for="destination_id">Destination</label>
            <select class="w-full border rounded-lg px-3 py-2" id="destination_id" name="destination_id" required>
                <option value="">Choose</option>
                <?php foreach ($options as $o): ?>
                    <option value="<?= (int) $o['id'] ?>"><?= htmlspecialchars($o['name']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div>
            <label class="block text-sm font-medium mb-1" for="day_number">Day number</label>
            <input class="w-full border rounded-lg px-3 py-2" type="number" min="1" max="60" id="day_number"
                   name="day_number" value="1" required>
        </div>
        <div>
            <label class="block text-sm font-medium mb-1" for="notes">Notes (optional)</label>
            <textarea class="w-full border rounded-lg px-3 py-2" id="notes" name="notes" rows="2"></textarea>
        </div>
        <button class="bg-pothik-green text-white font-semibold px-5 py-2 rounded-lg hover:opacity-90" type="submit">
            Add stop
        </button>
    </form>
</section>

<?php require __DIR__ . '/../../../includes/footer.php'; ?>