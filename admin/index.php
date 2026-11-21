<?php
require_once __DIR__ . '/../includes/admin_guard.php';

$pageTitle = 'Admin - Destinations';
$rows = $pdo->query(
    'SELECT d.id, d.name, d.division, d.avg_daily_cost, c.name AS category
     FROM destinations d JOIN categories c ON c.id = d.category_id
     ORDER BY d.name'
)->fetchAll();

require __DIR__ . '/../includes/header.php';
?>
<section class="max-w-6xl mx-auto px-4 py-8">
    <div class="flex flex-wrap items-center justify-between gap-3 mb-6">
        <h1 class="text-2xl font-bold text-pothik-green">Manage Destinations</h1>
        <a href="/pothik/admin/destination_form.php"
           class="bg-pothik-gold text-white px-4 py-2 rounded">+ Add Destination</a>
    </div>

    <?php if (!empty($_SESSION['flash'])): ?>
        <p class="mb-4 p-3 bg-green-100 text-green-800 rounded"><?= htmlspecialchars($_SESSION['flash']) ?></p>
        <?php unset($_SESSION['flash']); ?>
    <?php endif; ?>

    <div class="overflow-x-auto">
    <table class="w-full text-sm border">
        <thead class="bg-pothik-green text-white">
            <tr>
                <th class="p-2 text-left">Name</th>
                <th class="p-2 text-left">Division</th>
                <th class="p-2 text-left">Category</th>
                <th class="p-2 text-right">Avg/day (BDT)</th>
                <th class="p-2">Actions</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($rows as $r): ?>
            <tr class="border-t">
                <td class="p-2"><?= htmlspecialchars($r['name']) ?></td>
                <td class="p-2"><?= htmlspecialchars($r['division']) ?></td>
                <td class="p-2"><?= htmlspecialchars($r['category']) ?></td>
                <td class="p-2 text-right"><?= number_format((float) $r['avg_daily_cost']) ?></td>
                <td class="p-2 text-center whitespace-nowrap">
                    <a class="text-pothik-green underline"
                       href="/pothik/admin/destination_form.php?id=<?= (int) $r['id'] ?>">Edit</a>
                    <form method="post" action="/pothik/admin/delete.php" class="inline"
                          onsubmit="return confirm('Delete this destination?');">
                        <?= csrf_field() ?>
                        <input type="hidden" name="id" value="<?= (int) $r['id'] ?>">
                        <button class="text-red-600 underline ml-3">Delete</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
    </div>
</section>
<?php require __DIR__ . '/../includes/footer.php'; ?>