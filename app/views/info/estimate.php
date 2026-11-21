<?php require __DIR__ . '/../../../includes/header.php'; ?>

<section class="max-w-3xl mx-auto px-4 py-10">
    <h1 class="text-3xl font-bold text-pothik-green mb-2">Trip cost estimator</h1>
    <p class="text-gray-600 mb-2">Pick where you start and where you go. We estimate the cost, and you can change any amount.</p>
    <p class="text-sm mb-6">
        Not sure where to go?
        <a href="/pothik/budget.php" class="text-pothik-gold underline">Plan by your budget &rarr;</a>
    </p>

    <?php foreach ($errors as $e): ?>
        <div class="mb-3 rounded-lg border border-red-300 bg-red-50 p-3 text-sm text-red-700">
            <?= htmlspecialchars($e) ?>
        </div>
    <?php endforeach; ?>

    <form method="post" action="/pothik/estimate.php" class="grid gap-4 sm:grid-cols-2 border rounded-xl p-5">
        <label class="block">
            <span class="text-sm font-medium">Starting from</span>
            <select name="from" class="mt-1 w-full border rounded-lg px-3 py-2" required>
                <option value="">Choose city</option>
                <?php foreach ($cityOptions as $c): ?>
                    <option value="<?= (int) $c['id'] ?>" <?= $values['from'] === (int) $c['id'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($c['name']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </label>

        <label class="block">
            <span class="text-sm font-medium">Destination</span>
            <select name="to" class="mt-1 w-full border rounded-lg px-3 py-2" required>
                <option value="">Choose destination</option>
                <?php foreach ($destinationOptions as $d): ?>
                    <option value="<?= (int) $d['id'] ?>" <?= $values['to'] === (int) $d['id'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($d['name']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </label>

        <label class="block">
            <span class="text-sm font-medium">Travelers</span>
            <input type="number" name="travelers" min="1" max="20" value="<?= (int) $values['travelers'] ?>"
                   class="mt-1 w-full border rounded-lg px-3 py-2" required>
        </label>

        <label class="block">
            <span class="text-sm font-medium">Days</span>
            <input type="number" name="days" min="1" max="30" value="<?= (int) $values['days'] ?>"
                   class="mt-1 w-full border rounded-lg px-3 py-2" required>
        </label>

        <label class="block">
            <span class="text-sm font-medium">Transport</span>
            <select name="mode" class="mt-1 w-full border rounded-lg px-3 py-2" required>
                <?php foreach ($modes as $m): ?>
                    <option value="<?= (int) $m['id'] ?>" <?= $values['mode'] === (int) $m['id'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($m['name']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </label>

        <label class="block">
            <span class="text-sm font-medium">Travel style</span>
            <select name="style" class="mt-1 w-full border rounded-lg px-3 py-2">
                <?php foreach ($styles as $key => $s): ?>
                    <option value="<?= htmlspecialchars($key) ?>" <?= $values['style'] === $key ? 'selected' : '' ?>>
                        <?= htmlspecialchars($s['label']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </label>

        <label class="block">
            <span class="text-sm font-medium">Your budget (optional, BDT)</span>
            <input type="number" name="budget" min="0" step="100"
                   value="<?= $values['budget'] > 0 ? (int) $values['budget'] : '' ?>"
                   class="mt-1 w-full border rounded-lg px-3 py-2" placeholder="e.g. 15000">
        </label>

        <label class="flex items-center gap-2 mt-6">
            <input type="checkbox" name="peak" value="1" <?= $values['peak'] ? 'checked' : '' ?>>
            <span class="text-sm">Peak season or holiday (hotels about 20% more)</span>
        </label>

        <div class="sm:col-span-2">
            <button class="bg-pothik-green text-white font-semibold px-6 py-2 rounded-lg hover:opacity-90">
                Estimate cost
            </button>
        </div>
    </form>

<?php if ($result): ?>
    <div class="mt-8 border rounded-xl p-5">
        <h2 class="text-xl font-bold text-pothik-green">
            <?= htmlspecialchars($result['from']) ?> &rarr; <?= htmlspecialchars($result['to']) ?>
        </h2>
        <p class="text-sm text-gray-600 mt-1">
            About <?= round($result['roadKm']) ?> km by road
            (<?= round($result['straightKm']) ?> km in a straight line) &middot;
            <?= (int) $result['travelers'] ?> traveler(s) &middot; <?= (int) $result['days'] ?> day(s) &middot;
            <?= htmlspecialchars($result['style']) ?> style<?= $result['peak'] ? ' &middot; peak season' : '' ?>
        </p>

        <h3 class="font-semibold mt-5 mb-2">Transport options (round trip)</h3>
        <ul class="divide-y text-sm">
            <?php foreach ($result['options'] as $o): ?>
                <li class="py-2 flex items-start justify-between gap-4 <?= $o['name'] === $result['selected'] ? 'font-semibold' : '' ?>">
                    <div>
                        <?= htmlspecialchars($o['name']) ?>
                        <?php if ((int) $o['roundTrip'] === (int) $result['cheapest']): ?>
                            <span class="ml-1 text-xs bg-green-100 text-green-800 px-2 py-0.5 rounded-full">Cheapest</span>
                        <?php endif; ?>
                        <?php if ($o['name'] === $result['selected']): ?>
                            <span class="ml-1 text-xs bg-amber-100 text-amber-800 px-2 py-0.5 rounded-full">Selected</span>
                        <?php endif; ?>
                        <p class="text-xs text-gray-500 font-normal"><?= htmlspecialchars((string) $o['note']) ?></p>
                    </div>
                    <div class="text-right whitespace-nowrap">
                        BDT <?= number_format($o['roundTrip']) ?>
                        <?php if ($result['travelers'] > 1): ?>
                            <p class="text-xs text-gray-500 font-normal">BDT <?= number_format($o['perPerson']) ?> each</p>
                        <?php endif; ?>
                    </div>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>

    <div class="mt-6 border rounded-xl p-5">
        <h2 class="text-xl font-bold text-pothik-green">Your budget plan</h2>
        <p class="text-sm text-gray-600 mb-4">Change any amount and the total updates.</p>

        <div class="space-y-3">
            <?php foreach ($result['items'] as $it): ?>
                <div class="flex items-center justify-between gap-4">
                    <div>
                        <p><?= htmlspecialchars($it['label']) ?></p>
                        <p class="text-xs text-gray-500"><?= htmlspecialchars($it['note']) ?></p>
                    </div>
                    <input type="number" min="0" step="50"
                           value="<?= (int) $it['amount'] ?>" data-default="<?= (int) $it['amount'] ?>"
                           class="plan-input w-32 border rounded-lg px-3 py-2 text-right"
                           aria-label="<?= htmlspecialchars($it['label']) ?>">
                </div>
            <?php endforeach; ?>
        </div>

        <div class="border-t mt-5 pt-4 flex items-center justify-between">
            <span class="text-lg font-bold">Total</span>
            <span id="planTotal" class="text-lg font-bold"
                  data-travelers="<?= (int) $result['travelers'] ?>"
                  data-budget="<?= (int) $result['budget'] ?>">BDT 0</span>
        </div>
        <div class="flex items-center justify-between text-sm text-gray-600">
            <span>Per person</span><span id="planPerPerson">BDT 0</span>
        </div>
        <p id="planStatus" class="mt-3 text-sm font-medium"></p>

        <button type="button" id="planReset" class="mt-4 text-sm text-pothik-green underline">Reset to suggested amounts</button>
        <p class="text-xs text-gray-500 mt-4">
            This is an estimate. Boat or ferry fares are included only where we have a fixed minimum fare;
            other local transfers are not included. Check current prices before you book.
        </p>
    </div>

    <script>
    (function () {
        var inputs = document.querySelectorAll('.plan-input');
        var totalEl = document.getElementById('planTotal');
        var perEl = document.getElementById('planPerPerson');
        var statusEl = document.getElementById('planStatus');
        var travelers = parseInt(totalEl.dataset.travelers, 10) || 1;
        var budget = parseInt(totalEl.dataset.budget, 10) || 0;

        function fmt(n) { return 'BDT ' + Math.round(n).toLocaleString('en-US'); }

        function update() {
            var sum = 0;
            inputs.forEach(function (i) { sum += Math.max(0, parseFloat(i.value) || 0); });
            totalEl.textContent = fmt(sum);
            perEl.textContent = fmt(sum / travelers);

            if (budget > 0) {
                var diff = budget - sum;
                if (diff >= 0) {
                    statusEl.textContent = 'Within your budget by ' + fmt(diff) + '.';
                    statusEl.className = 'mt-3 text-sm font-medium text-green-700';
                } else {
                    statusEl.textContent = 'Over your budget by ' + fmt(-diff) + '. Try Budget style or a cheaper transport.';
                    statusEl.className = 'mt-3 text-sm font-medium text-red-700';
                }
            }
        }

        inputs.forEach(function (i) { i.addEventListener('input', update); });
        document.getElementById('planReset').addEventListener('click', function () {
            inputs.forEach(function (i) { i.value = i.dataset.default; });
            update();
        });
        update();
    })();
    </script>
<?php endif; ?>
</section>

<?php require __DIR__ . '/../../../includes/footer.php'; ?>