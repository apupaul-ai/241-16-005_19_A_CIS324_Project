<?php require __DIR__ . '/../../../includes/header.php'; ?>

<section class="max-w-3xl mx-auto px-4 py-10">
    <h1 class="text-3xl font-bold text-pothik-green mb-2">Packing checklist</h1>
    <p class="text-gray-600 mb-6">Tick items as you pack. Your ticks are saved in this browser.</p>

    <div class="mb-8">
        <div class="flex justify-between text-sm mb-1">
            <span id="progress-text">0 of 0 packed</span>
            <button id="reset" type="button" class="text-pothik-green underline">Reset</button>
        </div>
        <div class="h-3 rounded-full bg-gray-200 overflow-hidden">
            <div id="progress-bar" class="h-3 bg-pothik-gold" style="width: 0%"></div>
        </div>
    </div>

    <?php foreach ($grouped as $category => $items): ?>
        <h2 class="text-lg font-bold mt-6 mb-2"><?= htmlspecialchars($category) ?></h2>
        <div class="space-y-2">
            <?php foreach ($items as $item): ?>
                <label class="flex items-center gap-3 border rounded-lg px-4 py-2">
                    <input type="checkbox" data-item="<?= (int) $item['id'] ?>">
                    <span><?= htmlspecialchars($item['item_name']) ?></span>
                </label>
            <?php endforeach; ?>
        </div>
    <?php endforeach; ?>
</section>

<script>
(function () {
    var KEY = 'pothik_packing';
    var boxes = document.querySelectorAll('input[data-item]');
    var saved = [];

    try {
        saved = JSON.parse(localStorage.getItem(KEY)) || [];
    } catch (e) {
        saved = [];
    }

    function update() {
        var done = 0;
        var ids = [];
        boxes.forEach(function (b) {
            if (b.checked) {
                done++;
                ids.push(b.dataset.item);
            }
        });
        document.getElementById('progress-text').textContent = done + ' of ' + boxes.length + ' packed';
        document.getElementById('progress-bar').style.width = (boxes.length ? (done / boxes.length * 100) : 0) + '%';
        try {
            localStorage.setItem(KEY, JSON.stringify(ids));
        } catch (e) {}
    }

    boxes.forEach(function (b) {
        b.checked = saved.indexOf(b.dataset.item) !== -1;
        b.addEventListener('change', update);
    });

    document.getElementById('reset').addEventListener('click', function () {
        boxes.forEach(function (b) { b.checked = false; });
        update();
    });

    update();
})();
</script>

<?php require __DIR__ . '/../../../includes/footer.php'; ?>