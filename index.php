<?php
require_once __DIR__ . '/includes/csrf.php';       // starts the session safely
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/includes/functions.php';

// Pictures for the slideshow (only images that exist on disk)
$rows = $pdo->query(
    "SELECT name, image FROM destinations
     WHERE image IS NOT NULL AND image <> ''
     ORDER BY RAND() LIMIT 6"
)->fetchAll();

$slides = [];
foreach ($rows as $r) {
    $url = destination_image_url($r['image']);
    if ($url) {
        $slides[] = ['url' => $url, 'name' => $r['name']];
    }
}

// ---- Guest: show the landing page ----
if (empty($_SESSION['user_id'])) {
    require __DIR__ . '/app/views/landing.php';
    exit;
}

// ---- Logged in: home page ----
$featured = $pdo->query(
    "SELECT d.id, d.name, d.division, d.image, d.description, c.name AS category
     FROM destinations d JOIN categories c ON c.id = d.category_id
     ORDER BY RAND() LIMIT 6"
)->fetchAll();

$hasSlides = !empty($slides);
$pageTitle = 'Home';
require __DIR__ . '/includes/header.php';

$quick = [
    ['&#127958;', 'Destinations', '/pothik/destinations.php'],
    ['&#128230;', 'My Trips', '/pothik/trips.php'],
    ['&#128176;', 'Budget', '/pothik/budget.php'],
    ['&#128652;', 'Trip Cost', '/pothik/estimate.php'],
    ['&#127890;', 'Packing', '/pothik/packing.php'],
    ['&#128680;', 'Emergency', '/pothik/emergency.php'],
];
?>

<style>
    .slide {
        position: absolute; inset: 0;
        background-size: cover; background-position: center;
        opacity: 0; transition: opacity 1.6s ease;
    }
    .slide.active { opacity: 1; animation: zoom 9s ease-out forwards; }
    @keyframes zoom { from { transform: scale(1); } to { transform: scale(1.1); } }

    .reveal { opacity: 0; transform: translateY(24px); animation: rise .9s ease forwards; }
    @keyframes rise { to { opacity: 1; transform: none; } }

    @media (prefers-reduced-motion: reduce) {
        .slide.active, .reveal { animation: none; opacity: 1; transform: none; }
    }
</style>

<!-- HERO with slideshow -->
<section class="relative h-[460px] sm:h-[520px] overflow-hidden bg-gradient-to-br from-pothik-green to-green-800">
    <?php if ($hasSlides): ?>
        <?php foreach ($slides as $i => $s): ?>
            <div class="slide <?= $i === 0 ? 'active' : '' ?>"
                 data-name="<?= htmlspecialchars($s['name']) ?>"
                 style="background-image:url('<?= htmlspecialchars($s['url']) ?>')"></div>
        <?php endforeach; ?>
    <?php endif; ?>
    <div class="absolute inset-0 bg-gradient-to-b from-black/55 via-black/30 to-black/65"></div>

    <div class="relative z-10 h-full max-w-6xl mx-auto px-4 flex flex-col justify-center text-white pb-10">
        <p class="reveal text-amber-300 tracking-[.3em] text-xs sm:text-sm uppercase mb-3" style="animation-delay:.3s">
            Welcome back
        </p>
        <h1 class="reveal text-4xl sm:text-6xl font-bold" style="animation-delay:1s">
            Hello, <?= htmlspecialchars($_SESSION['user_name']) ?>
        </h1>
        <p class="reveal mt-4 max-w-xl text-base sm:text-lg text-gray-100" style="animation-delay:2s">
            Where to next? Plan a trip, check your budget or explore a new destination.
        </p>
        <div class="reveal mt-6 flex flex-wrap gap-3" style="animation-delay:3s">
            <a href="/pothik/destinations.php"
               class="bg-pothik-gold hover:bg-amber-700 px-6 py-3 rounded-full font-semibold">Explore destinations</a>
            <a href="/pothik/budget.php"
               class="border border-white/80 hover:bg-white hover:text-pothik-green px-6 py-3 rounded-full font-semibold transition">Plan by budget</a>
        </div>
    </div>

    <?php if ($hasSlides): ?>
    <div class="absolute bottom-14 inset-x-0 z-10 max-w-6xl mx-auto px-4 flex items-center justify-between text-white">
        <p class="text-sm"><span class="text-amber-300">&#9679;</span> <span id="slideName"><?= htmlspecialchars($slides[0]['name']) ?></span></p>
        <div class="flex gap-2">
            <?php foreach ($slides as $i => $s): ?>
                <button type="button" aria-label="Slide <?= $i + 1 ?>"
                        class="dot h-2 rounded-full transition-all <?= $i === 0 ? 'w-8 bg-amber-400' : 'w-2 bg-white/60' ?>"></button>
            <?php endforeach; ?>
        </div>
    </div>
    <?php endif; ?>
</section>

<!-- Quick links -->
<section class="relative z-10 max-w-6xl mx-auto px-4 -mt-10">
    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-3">
        <?php foreach ($quick as $q): ?>
            <a href="<?= $q[2] ?>"
               class="bg-white border rounded-xl p-4 text-center shadow-md hover:shadow-lg hover:-translate-y-0.5 transition">
                <div class="text-2xl"><?= $q[0] ?></div>
                <p class="text-sm font-medium mt-1 text-pothik-green"><?= $q[1] ?></p>
            </a>
        <?php endforeach; ?>
    </div>
</section>

<section class="max-w-6xl mx-auto px-4 py-12">
    <div class="flex items-end justify-between mb-6">
        <h2 class="text-2xl font-bold text-pothik-green">Featured destinations</h2>
        <a href="/pothik/destinations.php" class="text-sm text-pothik-gold hover:underline">See all &rarr;</a>
    </div>

    <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
        <?php foreach ($featured as $d): ?>
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
                    <h3 class="text-lg font-bold mt-1"><?= htmlspecialchars($d['name']) ?></h3>
                    <p class="text-sm text-gray-600 mt-2">
                        <?= htmlspecialchars(mb_strimwidth((string) $d['description'], 0, 100, '...')) ?>
                    </p>
                </div>
            </a>
        <?php endforeach; ?>
    </div>
</section>

<script>
    // Change the hero picture every 5 seconds
    (function () {
        var slides = document.querySelectorAll('.slide');
        var dots = document.querySelectorAll('.dot');
        var label = document.getElementById('slideName');
        if (slides.length < 2) return;
        var i = 0, timer;

        function show(n) {
            slides[i].classList.remove('active');
            dots[i].className = 'dot h-2 rounded-full transition-all w-2 bg-white/60';
            i = n;
            slides[i].classList.add('active');
            dots[i].className = 'dot h-2 rounded-full transition-all w-8 bg-amber-400';
            label.textContent = slides[i].dataset.name;
        }
        function start() { timer = setInterval(function () { show((i + 1) % slides.length); }, 5000); }

        dots.forEach(function (d, n) {
            d.addEventListener('click', function () { clearInterval(timer); show(n); start(); });
        });
        start();
    })();
</script>

<?php require __DIR__ . '/includes/footer.php'; ?>