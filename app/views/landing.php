<?php
// Expects $slides = [['url' => '...', 'name' => '...'], ...] from index.php
$hasSlides = !empty($slides);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pothik | Discover Bangladesh</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: { extend: {
                colors: { pothik: { green: '#14532d', gold: '#d97706' } },
                fontFamily: { display: ['"Playfair Display"', 'serif'], sans: ['Poppins', 'sans-serif'] }
            } }
        };
    </script>
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

        .scroll-reveal { opacity: 0; transform: translateY(28px); transition: opacity .8s ease, transform .8s ease; }
        .scroll-reveal.show { opacity: 1; transform: none; }

        @media (prefers-reduced-motion: reduce) {
            .slide.active, .reveal { animation: none; opacity: 1; transform: none; }
            .scroll-reveal { opacity: 1; transform: none; transition: none; }
        }
    </style>
</head>
<body class="font-sans text-gray-800 bg-white">

<!-- HERO -->
<section class="relative h-screen min-h-[560px] overflow-hidden bg-gradient-to-br from-pothik-green to-green-800">
    <?php if ($hasSlides): ?>
        <?php foreach ($slides as $i => $s): ?>
            <div class="slide <?= $i === 0 ? 'active' : '' ?>"
                 data-name="<?= htmlspecialchars($s['name']) ?>"
                 style="background-image:url('<?= htmlspecialchars($s['url']) ?>')"></div>
        <?php endforeach; ?>
    <?php endif; ?>
    <div class="absolute inset-0 bg-gradient-to-b from-black/60 via-black/35 to-black/70"></div>

    <!-- Top bar -->
    <nav class="absolute top-0 inset-x-0 z-10 max-w-6xl mx-auto flex items-center justify-between px-4 py-5 text-white">
        <a href="/pothik/index.php" class="font-display text-2xl font-bold">Pothik</a>
        <div class="flex items-center gap-3 sm:gap-5 text-sm">
            <a href="#features" class="hover:text-amber-300">Features</a>
            <a href="/pothik/login.php" class="hover:text-amber-300">Login</a>
            <a href="/pothik/register.php" class="bg-pothik-gold hover:bg-amber-700 px-4 py-2 rounded-full font-medium">Register</a>
        </div>
    </nav>

    <!-- Text that appears one by one -->
    <div class="relative z-10 h-full max-w-6xl mx-auto px-4 flex flex-col justify-center text-white">
        <p class="reveal text-amber-300 tracking-[.3em] text-xs sm:text-sm uppercase mb-5" style="animation-delay:.3s">
            Welcome to Bangladesh
        </p>
        <h1 class="font-display font-bold leading-tight text-4xl sm:text-6xl lg:text-7xl">
            <span class="reveal block" style="animation-delay:1s">Discover Bangladesh.</span>
            <span class="reveal block" style="animation-delay:2s">Plan every trip.</span>
            <span class="reveal block text-amber-300" style="animation-delay:3s">Spend smart. Travel safe.</span>
        </h1>
        <p class="reveal mt-6 max-w-xl text-base sm:text-lg text-gray-100" style="animation-delay:4s">
            Destinations, budgets, packing lists and emergency contacts, all in one place.
        </p>
        <div class="reveal mt-8 flex flex-wrap gap-3" style="animation-delay:4.8s">
            <a href="/pothik/register.php" class="bg-pothik-gold hover:bg-amber-700 px-7 py-3 rounded-full font-semibold">Get started</a>
            <a href="/pothik/login.php" class="border border-white/80 hover:bg-white hover:text-pothik-green px-7 py-3 rounded-full font-semibold transition">Login</a>
        </div>
    </div>

    <!-- Slide caption + dots -->
    <?php if ($hasSlides): ?>
    <div class="absolute bottom-6 inset-x-0 z-10 max-w-6xl mx-auto px-4 flex items-center justify-between text-white">
        <p class="text-sm"><span class="text-amber-300">&#9679;</span> <span id="slideName"><?= htmlspecialchars($slides[0]['name']) ?></span></p>
        <div class="flex gap-2" id="dots">
            <?php foreach ($slides as $i => $s): ?>
                <button type="button" aria-label="Slide <?= $i + 1 ?>"
                        class="dot h-2 rounded-full transition-all <?= $i === 0 ? 'w-8 bg-amber-400' : 'w-2 bg-white/60' ?>"></button>
            <?php endforeach; ?>
        </div>
    </div>
    <?php endif; ?>
</section>

<!-- FEATURES -->
<section id="features" class="max-w-6xl mx-auto px-4 py-16">
    <h2 class="font-display text-3xl sm:text-4xl font-bold text-pothik-green text-center">Everything you need for your trip</h2>
    <p class="text-center text-gray-600 mt-3">Create a free account to unlock every feature.</p>

    <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3 mt-10">
        <?php
        $features = [
            ['&#127958;', 'Destinations', 'Explore 10 top spots with best season, cost and location.'],
            ['&#128230;', 'Trip Planner', 'Create trips, add stops and compare budget with estimate.'],
            ['&#128176;', 'Budget Planner', 'Tell us your budget and see which trips fit it.'],
            ['&#128652;', 'Trip Cost Estimator', 'Pick where you start and where you go; get real transport cost.'],
            ['&#127890;', 'Packing Checklist', 'Never forget the essentials. Your ticks are saved.'],
            ['&#128680;', 'Emergency Hub', 'Hospitals, police and helplines when you need them.'],
        ];
        foreach ($features as $k => $f): ?>
            <div class="scroll-reveal border rounded-2xl p-6 shadow-sm hover:shadow-lg hover:-translate-y-1 transition"
                 style="transition-delay: <?= $k * 80 ?>ms">
                <div class="text-3xl"><?= $f[0] ?></div>
                <h3 class="font-semibold text-lg mt-3 text-pothik-green"><?= $f[1] ?></h3>
                <p class="text-sm text-gray-600 mt-1"><?= $f[2] ?></p>
            </div>
        <?php endforeach; ?>
    </div>
</section>

<!-- CTA -->
<section class="bg-pothik-green text-white">
    <div class="max-w-6xl mx-auto px-4 py-14 text-center scroll-reveal">
        <h2 class="font-display text-3xl font-bold">Ready for your next journey?</h2>
        <p class="mt-2 text-green-100">Create a free account and start planning in minutes.</p>
        <a href="/pothik/register.php" class="inline-block mt-6 bg-pothik-gold hover:bg-amber-700 px-8 py-3 rounded-full font-semibold">Create account</a>
    </div>
</section>

<footer class="bg-gray-900 text-gray-400 text-sm text-center py-5 px-4">
    &copy; <?= date('Y') ?> Pothik: Bangladesh Tourism Information and Tour Planning Portal
    &middot; Developed by <span class="text-amber-400 font-semibold">Apu Paul</span>
</footer>

<script>
    // Slideshow: change picture every 5 seconds
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

    // Cards fade in when they scroll into view
    (function () {
        var els = document.querySelectorAll('.scroll-reveal');
        if (!('IntersectionObserver' in window)) {
            els.forEach(function (e) { e.classList.add('show'); });
            return;
        }
        var io = new IntersectionObserver(function (entries) {
            entries.forEach(function (en) {
                if (en.isIntersecting) { en.target.classList.add('show'); io.unobserve(en.target); }
            });
        }, { threshold: 0.15 });
        els.forEach(function (e) { io.observe(e); });
    })();
</script>
</body>
</html>