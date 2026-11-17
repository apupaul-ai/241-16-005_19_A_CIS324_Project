<?php require __DIR__ . '/../../../includes/header.php'; ?>

<section class="max-w-3xl mx-auto px-4 py-10">
    <h1 class="text-3xl font-bold text-pothik-green mb-2">Trip cost estimator</h1>
    <p class="text-gray-600 mb-6">Tell us where you start and where you want to go. We estimate the cost and build a plan you can edit.</p>

    <?php if ($errors): ?>
        <div class="mb-4 rounded-lg border border-red-300 bg-red-50 p-4 text-sm text-red-700">
            <ul class="list-disc pl-5">
                <?php foreach ($errors as $e): ?>
                    <li><?= htmlspecialchars($e) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <form method="post" action="/pothik/estimate.php" class="grid gap-4 sm:grid-cols-2 border rounded-xl p-5">
        <div>
            <label class="block text-sm font-medium mb-1" for="from">I am in</label>
            <select class="w-full border rounded-lg px-3 py-2" id="from" name="from" required>
                <option value="">Choose a city</option>
                <?php foreach ($cityOptions as $c): ?>
                    <option value="<?= (int) $c['id'] ?>" <?= $values['from'] === (int) $c['id'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($c['name']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div>
            <label class="block text-sm font-medium mb-1" for="to">I want to go to</label>
            <select class="w-full border rounded-lg px-3 py-2" id="to" name="to" required>
                <option value="">Choose a destination</option>
                <?php foreach ($destinationOptions as $d): ?>
                    <option value="<?= (int) $d['id'] ?>" <?= $values['to'] === (int) $d['id'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($d['name']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div>
            <label class="block text-sm font-medium mb-1" for="travelers">Travelers</label>
            <input class="w-full border rounded-lg px-3 py-2" type="number" min="1" max="20" id="travelers" name="travelers"
                   value="<?= (int) $values['travelers'] ?>" required>
        </div>
        <div>
            <label class="block text-sm font-medium mb-1" for="days">Days at the destination</label>
            <input class="w-full border rounded-lg px-3 py-2" type="number" min="1" max="30" id="days" name="days"
                   value="<?= (int) $values['days'] ?>" required>
        </div>
        <div class="sm:col-span-2">
            <label class="block text-sm font-medium mb-1" for="mode">Transport</label>
            <select class="w-full border rounded-lg px-3 py-2" id="mode" name="mode" required>
                <option value="">Choose</option>
                <?php foreach ($modes as $m): ?>
                    <option value="<?= (int) $m['id'] ?>" <?= $values['mode'] === (int) $m['id'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($m['name']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="sm:col-span-2">
            <button class="bg-pothik-green text-white font-semibold px-5 py-2 rounded-lg hover:opacity-90" type="submit">
                Estimate
            </button>
        </div>
    </form>

    <?php if ($result): ?>
        <div class="mt-8 border rounded-xl p-5">
            <h2 class="text-xl font-bold text-pothik-green">
                <?= htmlspecialchars($result['from']) ?> to <?= htmlspecialchars($result['to']) ?>
            </h2>
            <p class="text-sm text-gray-600 mt-1">
                Straight line about <?= number_format($result['straightKm']) ?> km,
                road distance estimated at about <?= number_format($result['roadKm']) ?> km.
            </p>

            <h3 class="font-bold mt-5 mb-2">Transport options (round trip, <?= (int) $result['travelers'] ?> traveler(s))</h3>
            <div class="space-y-2">
                <?php foreach ($result['options'] as $o): ?>
                    <div class="border rounded-lg px-4 py-2 text-sm <?= $o['name'] === $result['selected'] ? 'border-pothik-green bg-green-50' : '' ?>">
                        <div class="flex justify-between">
                            <span class="font-semibold"><?= htmlspecialchars($o['name']) ?></span>
                            <span>BDT <?= number_format($o['roundTrip']) ?></span>
                        </div>
                        <p class="text-xs text-gray-500">
                            One way per person: BDT <?= number_format($o['oneWay']) ?>
                            <?php if (!empty($o['note'])): ?> &middot; <?= htmlspecialchars($o['note']) ?><?php endif; ?>
                        </p>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

        <div class="mt-6 border rounded-xl p-5">
            <h2 class="text-xl font-bold text-pothik-green mb-1">Your budget plan</h2>
            <p class="text-sm text-gray-600 mb-4">
                Suggested for <?= (int) $result['days'] ?> day(s) with <?= htmlspecialchars($result['selected']) ?>.
                Change any amount and the total updates.
            </p>

            <div class="space-y-3">
                <?php
                $items = [
                    ['Transport (round trip)', $result['transport']],
                    ['Stay and food', $result['stay']],
                    ['Shopping and extras', $result['extras']],
                    ['Emergency buffer', $result['buffer']],
                ];
                foreach ($items as [$label, $amount]):
                ?>
                    <div class="flex items-center justify-between gap-4">
                        <label class="text-sm"><?= htmlspecialchars($label) ?></label>
                        <input class="plan-field w-36 border rounded-lg px-3 py-2 text-right" type="number" min="0" step="1"
                               value="<?= (int) $amount ?>" data-suggested="<?= (int) $amount ?>">
                    </div>
                <?php endforeach; ?>
            </div>

            <div class="flex justify-between border-t mt-4 pt-3 text-lg font-bold">
                <span>Total</span>
                <span id="plan-total">BDT 0</span>
            </div>
            <div class="flex justify-between text-sm text-gray-600">
                <span>Per person</span>
                <span id="plan-person" data-travelers="<?= (int) $result['travelers'] ?>">BDT 0</span>
            </div>

            <button id="plan-reset" type="button" class="mt-4 text-sm text-pothik-green underline">
                Reset to suggested amounts
            </button>
            <p class="mt-3 text-xs text-gray-500">
                This is an estimate. Boat or ferry tickets and local transfers are not included.
            </p>
        </div>

        <script>
        (function () {
            var fields = document.querySelectorAll('.plan-field');
            var totalEl = document.getElementById('plan-total');
            var personEl = document.getElementById('plan-person');
            var travelers = parseInt(personEl.dataset.travelers, 10) || 1;

            function fmt(n) {
                return 'BDT ' + Math.round(n).toLocaleString('en-US');
            }

            function update() {
                var sum = 0;
                fields.forEach(function (f) {
                    var v = parseFloat(f.value);
                    if (!isNaN(v) && v > 0) {
                        sum += v;
                    }
                });
                totalEl.textContent = fmt(sum);
                personEl.textContent = fmt(sum / travelers);
            }

            fields.forEach(function (f) {
                f.addEventListener('input', update);
            });

            document.getElementById('plan-reset').addEventListener('click', function () {
                fields.forEach(function (f) {
                    f.value = f.dataset.suggested;
                });
                update();
            });

            update();
        })();
        </script>
    <?php endif; ?>
</section>

<?php require __DIR__ . '/../../../includes/footer.php'; ?>