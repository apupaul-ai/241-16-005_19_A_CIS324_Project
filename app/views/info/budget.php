<?php require __DIR__ . '/../../../includes/header.php'; ?>

<section class="max-w-4xl mx-auto px-4 py-10">
    <h1 class="text-3xl font-bold text-pothik-green mb-2">Budget planner</h1>
    <p class="text-gray-600 mb-2">Tell us your budget. We show where you can go and what each trip would cost.</p>
    <p class="text-sm mb-6">
        Already know where you are going?
        <a href="/pothik/estimate.php" class="text-pothik-gold underline">Use the cost estimator &rarr;</a>
    </p>

    <?php foreach ($errors as $e): ?>
        <div class="mb-3 rounded-lg border border-red-300 bg-red-50 p-3 text-sm text-red-700">
            <?= htmlspecialchars($e) ?>
        </div>
    <?php endforeach; ?>

    <form method="post" action="/pothik/budget.php" class="grid gap-4 sm:grid-cols-2 border rounded-xl p-5">
        <label class="block">
            <span class="text-sm font-medium">Your total budget (BDT)</span>
            <input type="number" name="budget" min="1000" step="100"
                   value="<?= $values['budget'] > 0 ? (int) $values['budget'] : '' ?>"
                   class="mt-1 w-full border rounded-lg px-3 py-2" placeholder="e.g. 15000" required>
        </label>

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
            <select name="mode" class="mt-1 w-full border rounded-lg px-3 py-2">
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

        <label class="flex items-center gap-2 sm:col-span-2">
            <input type="checkbox" name="peak" value="1" <?= $values['peak'] ? 'checked' : '' ?>>
            <span class="text-sm">Peak season or holiday (hotels about 20% more)</span>
        </label>

        <div class="sm:col-span-2">
            <button class="bg-pothik-green text-white font-semibold px-6 py-2 rounded-lg hover:opacity-90">
                Find trips for my budget
            </button>
        </div>
    </form>

<?php if ($plans !== null): ?>
    <div class="mt-8">
        <h2 class="text-xl font-bold text-pothik-green">
            <?= (int) $summary['fit'] ?> of <?= (int) $summary['total'] ?> destinations fit BDT <?= number_format($values['budget']) ?>
        </h2>
        <?php if ($summary['fit'] === 0 && $summary['cheapest']): ?>
            <p class="mt-2 text-sm text-gray-700">
                Nothing fits yet. The cheapest option is <strong><?= htmlspecialchars($summary['cheapest']['name']) ?></strong>
                at BDT <?= number_format($summary['cheapest']['total']) ?>.
                Try fewer days, Budget style or a cheaper transport.
            </p>
        <?php endif; ?>

        <div class="mt-5 space-y-4">
            <?php foreach ($plans as $p): ?>
                <?php
                $img = destination_image_url($p['image'] ?? null);
                $pct = min(100, (int) round($p['total'] / $values['budget'] * 100));
                $badge = [
                    'comfortable' => ['Comfortable', 'bg-green-100 text-green-800', 'bg-green-600'],
                    'tight'       => ['Tight fit',   'bg-amber-100 text-amber-800', 'bg-amber-500'],
                    'over'        => ['Over budget', 'bg-red-100 text-red-800',     'bg-red-500'],
                ][$p['status']];
                ?>
                <div class="border rounded-xl overflow-hidden flex flex-col sm:flex-row <?= $p['status'] === 'over' ? 'opacity-75' : '' ?>">
                    <?php if ($img): ?>
                        <img src="<?= htmlspecialchars($img) ?>" alt="<?= htmlspecialchars($p['name']) ?>"
                             loading="lazy" class="w-full sm:w-44 h-32 sm:h-auto object-cover">
                    <?php else: ?>
                        <div class="w-full sm:w-44 h-32 sm:h-auto bg-gradient-to-br from-pothik-green to-green-700 flex items-center justify-center text-white text-4xl font-bold">
                            <?= htmlspecialchars(mb_substr($p['name'], 0, 1)) ?>
                        </div>
                    <?php endif; ?>

                    <div class="p-4 flex-1">
                        <div class="flex items-start justify-between gap-3">
                            <div>
                                <p class="text-xs uppercase text-pothik-gold font-semibold">
                                    <?= htmlspecialchars($p['category']) ?> &middot; <?= htmlspecialchars($p['division']) ?>
                                </p>
                                <h3 class="text-lg font-bold"><?= htmlspecialchars($p['name']) ?></h3>
                                <p class="text-xs text-gray-500">About <?= (int) $p['roadKm'] ?> km each way</p>
                            </div>
                            <span class="text-xs px-2 py-1 rounded-full whitespace-nowrap <?= $badge[1] ?>"><?= $badge[0] ?></span>
                        </div>

                        <div class="mt-3 h-2 bg-gray-100 rounded-full overflow-hidden">
                            <div class="h-2 rounded-full <?= $badge[2] ?>" style="width: <?= $pct ?>%"></div>
                        </div>

                        <div class="mt-2 flex flex-wrap items-center justify-between gap-2 text-sm">
                            <div>
                                <span class="font-bold">BDT <?= number_format($p['total']) ?></span>
                                <span class="text-gray-500">
                                    &middot; travel <?= number_format($p['transport']) ?>
                                    &middot; stay and food <?= number_format($p['stay']) ?>
                                </span>
                                <p class="text-xs <?= $p['left'] >= 0 ? 'text-green-700' : 'text-red-700' ?>">
                                    <?= $p['left'] >= 0
                                        ? 'BDT ' . number_format($p['left']) . ' left'
                                        : 'BDT ' . number_format(-$p['left']) . ' over' ?>
                                </p>
                            </div>

                            <form method="post" action="/pothik/estimate.php">
                                <input type="hidden" name="from" value="<?= (int) $values['from'] ?>">
                                <input type="hidden" name="to" value="<?= (int) $p['id'] ?>">
                                <input type="hidden" name="travelers" value="<?= (int) $values['travelers'] ?>">
                                <input type="hidden" name="days" value="<?= (int) $values['days'] ?>">
                                <input type="hidden" name="mode" value="<?= (int) $values['mode'] ?>">
                                <input type="hidden" name="style" value="<?= htmlspecialchars($values['style']) ?>">
                                <input type="hidden" name="budget" value="<?= (int) $values['budget'] ?>">
                                <?php if ($values['peak']): ?><input type="hidden" name="peak" value="1"><?php endif; ?>
                                <button class="text-pothik-green border border-pothik-green px-3 py-1 rounded-lg hover:bg-pothik-green hover:text-white transition">
                                    Plan this trip
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <p class="text-xs text-gray-500 mt-5">
            Estimates use road distance, published bus and train fares, and average daily costs per destination.
            Check current prices before you book.
        </p>
    </div>
<?php endif; ?>
</section>

<?php require __DIR__ . '/../../../includes/footer.php'; ?>