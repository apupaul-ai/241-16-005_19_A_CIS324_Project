<?php
require_once __DIR__ . '/../includes/admin_guard.php';

$pageTitle = 'Admin - Destination Form';
$id = (int) ($_GET['id'] ?? $_POST['id'] ?? 0);
$errors = [];

$d = [
    'category_id' => '', 'name' => '', 'division' => '', 'district' => '',
    'description' => '', 'best_season' => '', 'avg_daily_cost' => '',
    'latitude' => '', 'longitude' => '', 'image' => '',
];

if ($id > 0) {
    $stmt = $pdo->prepare('SELECT * FROM destinations WHERE id = ?');
    $stmt->execute([$id]);
    $found = $stmt->fetch();
    if (!$found) {
        http_response_code(404);
        die('Destination not found.');
    }
    $d = $found;
}

$categories = $pdo->query('SELECT id, name FROM categories ORDER BY name')->fetchAll();
$validCats = array_column($categories, 'id');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_check();

    foreach (array_keys($d) as $k) {
        $d[$k] = trim($_POST[$k] ?? '');
    }

    if (!in_array((int) $d['category_id'], array_map('intval', $validCats), true)) {
        $errors[] = 'Choose a valid category.';
    }
    if ($d['name'] === '' || mb_strlen($d['name']) > 100) {
        $errors[] = 'Name is required (max 100 characters).';
    }
    if ($d['division'] === '' || mb_strlen($d['division']) > 50) {
        $errors[] = 'Division is required (max 50 characters).';
    }
    if (!is_numeric($d['avg_daily_cost']) || $d['avg_daily_cost'] < 0) {
        $errors[] = 'Average daily cost must be a positive number.';
    }
    if ($d['latitude'] !== '' && (!is_numeric($d['latitude']) || abs($d['latitude']) > 90)) {
        $errors[] = 'Latitude must be between -90 and 90.';
    }
    if ($d['longitude'] !== '' && (!is_numeric($d['longitude']) || abs($d['longitude']) > 180)) {
        $errors[] = 'Longitude must be between -180 and 180.';
    }

    if (!$errors) {
        $params = [
            (int) $d['category_id'], $d['name'], $d['division'], $d['district'] ?: null,
            $d['description'] ?: null, $d['best_season'] ?: null, $d['avg_daily_cost'],
            $d['latitude'] === '' ? null : $d['latitude'],
            $d['longitude'] === '' ? null : $d['longitude'],
            $d['image'] ?: null,
        ];
        if ($id > 0) {
            $sql = 'UPDATE destinations SET category_id=?, name=?, division=?, district=?,
                    description=?, best_season=?, avg_daily_cost=?, latitude=?, longitude=?, image=?
                    WHERE id=?';
            $params[] = $id;
            $msg = 'Destination updated.';
        } else {
            $sql = 'INSERT INTO destinations (category_id, name, division, district, description,
                    best_season, avg_daily_cost, latitude, longitude, image)
                    VALUES (?,?,?,?,?,?,?,?,?,?)';
            $msg = 'Destination added.';
        }
        $pdo->prepare($sql)->execute($params);
        $_SESSION['flash'] = $msg;
        header('Location: /pothik/admin/index.php');
        exit;
    }
}

function field(string $label, string $name, array $d, string $type = 'text'): void
{
    echo '<label class="block mb-3"><span class="text-sm font-semibold">' . htmlspecialchars($label) . '</span>';
    echo '<input type="' . $type . '" step="any" name="' . $name . '" value="'
        . htmlspecialchars((string) $d[$name]) . '" class="w-full border rounded p-2 mt-1"></label>';
}

require __DIR__ . '/../includes/header.php';
?>
<section class="max-w-2xl mx-auto px-4 py-8">
    <h1 class="text-2xl font-bold text-pothik-green mb-6"><?= $id ? 'Edit' : 'Add' ?> Destination</h1>

    <?php foreach ($errors as $e): ?>
        <p class="mb-2 p-3 bg-red-100 text-red-800 rounded"><?= htmlspecialchars($e) ?></p>
    <?php endforeach; ?>

    <form method="post">
        <?= csrf_field() ?>
        <input type="hidden" name="id" value="<?= $id ?>">

        <label class="block mb-3"><span class="text-sm font-semibold">Category</span>
            <select name="category_id" class="w-full border rounded p-2 mt-1">
                <?php foreach ($categories as $c): ?>
                    <option value="<?= (int) $c['id'] ?>" <?= (int) $d['category_id'] === (int) $c['id'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($c['name']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </label>

        <?php
        field('Name', 'name', $d);
        field('Division', 'division', $d);
        field('District', 'district', $d);
        field('Best season', 'best_season', $d);
        field('Avg daily cost (BDT)', 'avg_daily_cost', $d, 'number');
        field('Latitude', 'latitude', $d, 'number');
        field('Longitude', 'longitude', $d, 'number');
        field('Image file name', 'image', $d);
        ?>

        <label class="block mb-4"><span class="text-sm font-semibold">Description</span>
            <textarea name="description" rows="5" class="w-full border rounded p-2 mt-1"><?= htmlspecialchars((string) $d['description']) ?></textarea>
        </label>

        <button class="bg-pothik-green text-white px-5 py-2 rounded">Save</button>
        <a href="/pothik/admin/index.php" class="ml-3 underline">Cancel</a>
    </form>
</section>
<?php require __DIR__ . '/../includes/footer.php'; ?>