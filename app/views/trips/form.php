<?php require __DIR__ . '/../../../includes/header.php'; ?>

<section class="max-w-md mx-auto px-4 py-10">
    <h1 class="text-3xl font-bold text-pothik-green mb-6"><?= htmlspecialchars($formTitle) ?></h1>

    <?php if ($errors): ?>
        <div class="mb-4 rounded-lg border border-red-300 bg-red-50 p-4 text-sm text-red-700">
            <ul class="list-disc pl-5">
                <?php foreach ($errors as $e): ?>
                    <li><?= htmlspecialchars($e) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <form method="post" action="<?= htmlspecialchars($formAction) ?>" class="space-y-4">
        <div>
            <label class="block text-sm font-medium mb-1" for="title">Trip title</label>
            <input class="w-full border rounded-lg px-3 py-2" type="text" id="title" name="title"
                   value="<?= htmlspecialchars($values['title']) ?>" required>
        </div>
        <div>
            <label class="block text-sm font-medium mb-1" for="start_date">Start date</label>
            <input class="w-full border rounded-lg px-3 py-2" type="date" id="start_date" name="start_date"
                   value="<?= htmlspecialchars($values['start']) ?>">
        </div>
        <div>
            <label class="block text-sm font-medium mb-1" for="end_date">End date</label>
            <input class="w-full border rounded-lg px-3 py-2" type="date" id="end_date" name="end_date"
                   value="<?= htmlspecialchars($values['end']) ?>">
        </div>
        <div>
            <label class="block text-sm font-medium mb-1" for="budget">Budget (BDT)</label>
            <input class="w-full border rounded-lg px-3 py-2" type="number" step="0.01" min="0" id="budget" name="budget"
                   value="<?= htmlspecialchars((string) $values['budget']) ?>">
        </div>
        <div class="flex gap-3">
            <button class="bg-pothik-green text-white font-semibold px-5 py-2 rounded-lg hover:opacity-90" type="submit">
                Save trip
            </button>
            <a class="px-5 py-2 rounded-lg border" href="/pothik/trips.php">Cancel</a>
        </div>
    </form>
</section>

<?php require __DIR__ . '/../../../includes/footer.php'; ?>