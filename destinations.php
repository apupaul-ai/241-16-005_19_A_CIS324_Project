<?php
require_once __DIR__ . '/includes/functions.php';
$pageTitle = 'Destinations';
require __DIR__ . '/includes/header.php';

const PER_PAGE = 6;

$categories = $pdo->query("SELECT id, name FROM categories ORDER BY name")->fetchAll();

$categoryId = isset($_GET['category']) ? (int) $_GET['category'] : 0;
$q          = trim($_GET['q'] ?? '');
$page       = max(1, (int) ($_GET['page'] ?? 1));

$where  = [];
$params = [];

if ($categoryId > 0) {
    $where[]  = 'd.category_id = ?';
    $params[] = $categoryId;
}
if ($q !== '') {
    $where[] = '(d.name LIKE ? OR d.division LIKE ? OR d.district LIKE ?)';
    $like    = '%' . $q . '%';
    array_push($params, $like, $like, $like);
}
$whereSql = $where ? ' WHERE ' . implode(' AND ', $where) : '';

$countStmt = $pdo->prepare("SELECT COUNT(*) FROM destinations d" . $whereSql);
$countStmt->execute($params);
$total      = (int) $countStmt->fetchColumn();
$totalPages = max(1, (int) ceil($total / PER_PAGE));
$page       = min($page, $totalPages);
$offset     = ($page - 1) * PER_PAGE;

$sql = "SELECT d.id, d.name, d.division, d.description, d.image, c.name AS category
        FROM destinations d
        JOIN categories c ON d.category_id = c.id"
        . $whereSql .
        " ORDER BY d.name LIMIT " . PER_PAGE . " OFFSET " . $offset;
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$destinations = $stmt->fetchAll();

function pageUrl(array $override = []): string
{
    global $categoryId, $q, $page;
    $query = array_merge(
        ['category' => $categoryId ?: null, 'q' => $q !== '' ? $q : null, 'page' => $page],
        $override
    );
    $query = array_filter($query, fn($v) => $v !== null && $v !== '');
    return '/pothik/destinations.php' . ($query ? '?' . http_build_query($query) : '');
}
?>

<section class="max-w-6xl mx-auto px-4 py-10">
    <h1 class="text-3xl font-bold text-pothik-green mb-6">Destinations</h1>

    <form method="get" class="flex flex-col sm:flex-row gap-2 mb-6">
        <?php if ($categoryId > 0): ?>
            <input type="hidden" name="category" value="<?= $categoryId ?>">
        <?php endif; ?>
        <input type="text" name="q" value="<?= htmlspecialchars($q) ?>"
               placeholder="Search by name, division or district..."
               class="flex-1 border rounded-lg px-4 py-2">
        <button class="bg-pothik-green text-white px-5 py-2 rounded-lg">Search</button>
        <?php if ($q !== ''): ?>
            <a href="<?= htmlspecialchars(pageUrl(['q' => null, 'page' => null])) ?>"
               class="px-4 py-2 border rounded-lg text-center">Clear</a>
        <?php endif; ?>
    </form>

    <div class="flex flex-wrap gap-2 mb-8">
        <a href="<?= htmlspecialchars(pageUrl(['category' => null, 'page' => null])) ?>"
           class="px-4 py-2 rounded-full text-sm border <?= $categoryId === 0 ? 'bg-pothik-green text-white' : 'hover:bg-gray-100' ?>">
            All
        </a>
        <?php foreach ($categories as $c): ?>
            <a href="<?= htmlspecialchars(pageUrl(['category' => (int) $c['id'], 'page' => null])) ?>"
               class="px-4 py-2 rounded-full text-sm border <?= $categoryId === (int) $c['id'] ? 'bg-pothik-green text-white' : 'hover:bg-gray-100' ?>">
                <?= htmlspecialchars($c['name']) ?>
            </a>
        <?php endforeach; ?>
    </div>

    <p class="text-sm text-gray-500 mb-4"><?= $total ?> destination(s) found</p>

    <?php if (count($destinations) === 0): ?>
        <p class="text-gray-600">No destinations match your search.</p>
    <?php else: ?>
        <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
            <?php foreach ($destinations as $d): ?>
                <?php $img = destination_image_url($d['image'] ?? null); ?>
                <a href="/pothik/destination.php?id=<?= (int) $d['id'] ?>"
                   class="block border rounded-xl overflow-hidden shadow-sm hover:shadow-md transition">
                    <?php if ($img): ?>
                        <img src="<?= htmlspecialchars($img) ?>" alt="<?= htmlspecialchars($d['name']) ?>"
                             loading="lazy" class="w-full h-44 object-cover">
                    <?php else: ?>
                        <div class="w-full h-44 bg-gradient-to-br from-pothik-green to-green-700 flex items-center justify-center text-white text-5xl font-bold">
                            <?= htmlspecialchars(mb_substr($d['name'], 0, 1)) ?>
                        </div>
                    <?php endif; ?>
                    <div class="p-5">
                        <p class="text-xs uppercase text-pothik-gold font-semibold">
                            <?= htmlspecialchars($d['category']) ?> &middot; <?= htmlspecialchars($d['division']) ?>
                        </p>
                        <h2 class="text-lg font-bold mt-1"><?= htmlspecialchars($d['name']) ?></h2>
                        <p class="text-sm text-gray-600 mt-2">
                            <?= htmlspecialchars(mb_strimwidth((string) $d['description'], 0, 110, '...')) ?>
                        </p>
                    </div>
                </a>
            <?php endforeach; ?>
        </div>

        <?php if ($totalPages > 1): ?>
            <nav class="flex flex-wrap items-center justify-center gap-2 mt-10">
                <?php if ($page > 1): ?>
                    <a href="<?= htmlspecialchars(pageUrl(['page' => $page - 1])) ?>"
                       class="px-3 py-2 border rounded hover:bg-gray-100">&laquo; Prev</a>
                <?php endif; ?>

                <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                    <a href="<?= htmlspecialchars(pageUrl(['page' => $i])) ?>"
                       class="px-3 py-2 border rounded <?= $i === $page ? 'bg-pothik-green text-white' : 'hover:bg-gray-100' ?>">
                        <?= $i ?>
                    </a>
                <?php endfor; ?>

                <?php if ($page < $totalPages): ?>
                    <a href="<?= htmlspecialchars(pageUrl(['page' => $page + 1])) ?>"
                       class="px-3 py-2 border rounded hover:bg-gray-100">Next &raquo;</a>
                <?php endif; ?>
            </nav>
        <?php endif; ?>
    <?php endif; ?>
</section>

<?php require __DIR__ . '/includes/footer.php'; ?>