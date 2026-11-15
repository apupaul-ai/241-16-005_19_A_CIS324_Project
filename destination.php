<?php
require_once __DIR__ . '/config/config.php';
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

$stmt = $pdo->prepare(
    "SELECT d.*, c.name AS category
     FROM destinations d
     JOIN categories c ON d.category_id = c.id
     WHERE d.id = ?"
);
$stmt->execute([$id]);
$destination = $stmt->fetch();

if (!$destination) {
    http_response_code(404);
    $pageTitle = 'Not found';
    require __DIR__ . '/includes/header.php';
    echo '<section class="max-w-6xl mx-auto px-4 py-10"><p>Destination not found.</p></section>';
    require __DIR__ . '/includes/footer.php';
    exit;
}

$reviewErrors = [];
$rating = 0;
$comment = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_SESSION['user_id'])) {
        header('Location: /pothik/login.php');
        exit;
    }

    $rating = (int) ($_POST['rating'] ?? 0);
    $comment = trim($_POST['comment'] ?? '');

    if ($rating < 1 || $rating > 5) {
        $reviewErrors[] = 'Choose a rating from 1 to 5.';
    }
    if (mb_strlen($comment) > 1000) {
        $reviewErrors[] = 'Comment must be 1000 characters or less.';
    }

    if (!$reviewErrors) {
        $check = $pdo->prepare("SELECT id FROM reviews WHERE user_id = ? AND destination_id = ?");
        $check->execute([$_SESSION['user_id'], $id]);

        if ($check->fetch()) {
            $reviewErrors[] = 'You have already reviewed this destination.';
        } else {
            $insert = $pdo->prepare(
                "INSERT INTO reviews (user_id, destination_id, rating, comment) VALUES (?, ?, ?, ?)"
            );
            $insert->execute([$_SESSION['user_id'], $id, $rating, $comment]);
            header('Location: /pothik/destination.php?id=' . $id . '&reviewed=1');
            exit;
        }
    }
}

$statsStmt = $pdo->prepare(
    "SELECT COUNT(*) AS total, AVG(rating) AS average FROM reviews WHERE destination_id = ?"
);
$statsStmt->execute([$id]);
$stats = $statsStmt->fetch();

$listStmt = $pdo->prepare(
    "SELECT r.rating, r.comment, r.created_at, u.name
     FROM reviews r
     JOIN users u ON r.user_id = u.id
     WHERE r.destination_id = ?
     ORDER BY r.created_at DESC"
);
$listStmt->execute([$id]);
$reviews = $listStmt->fetchAll();

$pageTitle = $destination['name'];
require __DIR__ . '/includes/header.php';
?>

<section class="max-w-4xl mx-auto px-4 py-10">
    <a href="/pothik/destinations.php" class="text-sm text-pothik-green hover:underline">&larr; Back to destinations</a>

    <p class="mt-6 text-xs uppercase text-pothik-gold font-semibold">
        <?= htmlspecialchars($destination['category']) ?>
    </p>
    <h1 class="text-4xl font-bold text-pothik-green mt-1"><?= htmlspecialchars($destination['name']) ?></h1>
    <p class="text-gray-600 mt-1">
        <?= htmlspecialchars($destination['district']) ?>, <?= htmlspecialchars($destination['division']) ?>
    </p>

    <p class="mt-6 leading-relaxed"><?= nl2br(htmlspecialchars($destination['description'])) ?></p>

    <div class="grid gap-4 sm:grid-cols-3 mt-8">
        <div class="border rounded-xl p-4">
            <p class="text-xs text-gray-500">Best season</p>
            <p class="font-semibold"><?= htmlspecialchars($destination['best_season']) ?></p>
        </div>
        <div class="border rounded-xl p-4">
            <p class="text-xs text-gray-500">Average daily cost</p>
            <p class="font-semibold">BDT <?= number_format((float) $destination['avg_daily_cost']) ?></p>
        </div>
        <div class="border rounded-xl p-4">
            <p class="text-xs text-gray-500">Location</p>
            <p class="font-semibold"><?= htmlspecialchars($destination['latitude']) ?>, <?= htmlspecialchars($destination['longitude']) ?></p>
        </div>
    </div>
</section>

<section class="max-w-4xl mx-auto px-4 pb-10">
    <h2 class="text-2xl font-bold text-pothik-green">Reviews</h2>
    <p class="text-gray-600 mt-1">
        <?php if ((int) $stats['total'] > 0): ?>
            <?= number_format((float) $stats['average'], 1) ?> / 5 from <?= (int) $stats['total'] ?> review(s)
        <?php else: ?>
            No reviews yet.
        <?php endif; ?>
    </p>

    <?php if (isset($_GET['reviewed'])): ?>
        <div class="mt-4 rounded-lg border border-green-300 bg-green-50 p-4 text-sm text-green-700">
            Thank you, your review was added.
        </div>
    <?php endif; ?>

    <?php if (isset($_SESSION['user_id'])): ?>
        <?php if ($reviewErrors): ?>
            <div class="mt-4 rounded-lg border border-red-300 bg-red-50 p-4 text-sm text-red-700">
                <ul class="list-disc pl-5">
                    <?php foreach ($reviewErrors as $e): ?>
                        <li><?= htmlspecialchars($e) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <form method="post" action="/pothik/destination.php?id=<?= (int) $id ?>" class="mt-6 space-y-4 border rounded-xl p-5">
            <div>
                <label class="block text-sm font-medium mb-1" for="rating">Rating</label>
                <select class="border rounded-lg px-3 py-2" id="rating" name="rating" required>
                    <option value="">Choose</option>
                    <?php for ($i = 5; $i >= 1; $i--): ?>
                        <option value="<?= $i ?>" <?= $rating === $i ? 'selected' : '' ?>><?= $i ?> / 5</option>
                    <?php endfor; ?>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium mb-1" for="comment">Comment (optional)</label>
                <textarea class="w-full border rounded-lg px-3 py-2" id="comment" name="comment" rows="3"><?= htmlspecialchars($comment) ?></textarea>
            </div>
            <button class="bg-pothik-green text-white font-semibold px-5 py-2 rounded-lg hover:opacity-90" type="submit">
                Submit review
            </button>
        </form>
    <?php else: ?>
        <p class="mt-4 text-sm">
            <a class="text-pothik-green underline" href="/pothik/login.php">Login</a> to write a review.
        </p>
    <?php endif; ?>

    <div class="mt-8 space-y-4">
        <?php foreach ($reviews as $r): ?>
            <div class="border rounded-xl p-4">
                <div class="flex justify-between text-sm">
                    <span class="font-semibold"><?= htmlspecialchars($r['name']) ?></span>
                    <span class="text-pothik-gold font-semibold"><?= (int) $r['rating'] ?> / 5</span>
                </div>
                <?php if ($r['comment'] !== null && $r['comment'] !== ''): ?>
                    <p class="mt-2 text-gray-700"><?= nl2br(htmlspecialchars($r['comment'])) ?></p>
                <?php endif; ?>
                <p class="mt-2 text-xs text-gray-500"><?= htmlspecialchars($r['created_at']) ?></p>
            </div>
        <?php endforeach; ?>
    </div>
</section>

<?php require __DIR__ . '/includes/footer.php'; ?>