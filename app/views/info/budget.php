<?php require __DIR__ . '/../../../includes/header.php'; ?>

<section class="max-w-3xl mx-auto px-4 py-10">
    <h1 class="text-3xl font-bold text-pothik-green mb-6">Budget calculator</h1>

    <?php if ($errors): ?>
        <div class="mb-4 rounded-lg border border-red-300 bg-red-50 p-4 text-sm text-red-700">
            <ul class="list-disc pl-5">
                <?php foreach ($errors as $e): ?>
                    <li><?= htmlspecialchars($e) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <form method="post" action="/pothik/budget.php" class="space-y-4 border rounded-xl p-5">
        <div>
            <label class="block text-sm font-medium mb-1" for="destination">Destination</label>
            <select class="w-full border rounded-lg px-3 py-2" id="destination" name="destination" required>
                <option value="">Choose</option>
                <?php foreach ($options as $o): ?>
                    <option value="<?= (int) $o['id'] ?>" <?= $values['destination'] === (int) $o['id'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($o['name']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="grid gap-4 sm:grid-cols-2">
            <div>
                <label class="block text-sm font-medium mb-1" for="days">Number of days</label>
                <input class="w-full border rounded-lg px-3 py-2" type="number" min="1" max="30" id="days" name="days"
                       value="<?= (int) $values['days'] ?>" required>
            </div>
            <div>
                <label class="block text-sm font-medium mb-1" for="travelers">Number of travelers</label>
                <input class="w-full border rounded-lg px-3 py-2" type="number" min="1" max="20" id="travelers" name="travelers"
                       value="<?= (int) $values['travelers'] ?>" required>
            </div>
        </div>
        <div class="grid gap-4 sm:grid-cols-2">
            <div>
                <label class="block text-sm font-medium mb-1" for="transport">Transport to and from (BDT, total)</label>
                <input class="w-full border rounded-lg px-3 py-2" type="number" min="0" step="0.01" id="transport" name="transport"
                       value="<?= htmlspecialchars($values['transport']) ?>">
            </div>
            <div>
                <label class="block text-sm font-medium mb-1" for="extra">Shopping and extras (BDT, total)</label>
                <input class="w-full border rounded-lg px-3 py-2" type="number" min="0" step="0.01" id="extra" name="extra"
                       value="<?= htmlspecialchars($values['extra']) ?>">
            </div>
        </div>
        <button class="bg-pothik-green text-white font-semibold px-5 py-2 rounded-lg hover:opacity-90" type="submit">
            Calculate
        </button>
    </form>

    <?php if ($result): ?>
        <div class="mt-8 border rounded-xl p-5">
            <h2 class="text-xl font-bold text-pothik-green mb-4">Estimate for <?= htmlspecialchars($result['destination']) ?></h2>
            <dl class="space-y-2 text-sm">
                <div class="flex justify-between">
                    <dt>Average daily cost per person</dt>
                    <dd>BDT <?= number_format($result['daily']) ?></dd>
                </div>
                <div class="flex justify-between">
                    <dt>Stay and food (<?= (int) $values['days'] ?> days, <?= (int) $values['travelers'] ?> traveler(s))</dt>
                    <dd>BDT <?= number_format($result['stay']) ?></dd>
                </div>
                <div class="flex justify-between">
                    <dt>Transport</dt>
                    <dd>BDT <?= number_format($result['transport']) ?></dd>
                </div>
                <div class="flex justify-between">
                    <dt>Shopping and extras</dt>
                    <dd>BDT <?= number_format($result['extra']) ?></dd>
                </div>
                <div class="flex justify-between border-t pt-2 text-base font-bold">
                    <dt>Total</dt>
                    <dd>BDT <?= number_format($result['total']) ?></dd>
                </div>
                <div class="flex justify-between text-gray-600">
                    <dt>Per person</dt>
                    <dd>BDT <?= number_format($result['perPerson']) ?></dd>
                </div>
            </dl>
            <p class="mt-4 text-xs text-gray-500">This is an estimate based on the average daily cost saved for the destination.</p>
        </div>
    <?php endif; ?>
</section>

<?php require __DIR__ . '/../../../includes/footer.php'; ?>