<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/csrf.php';   // starts the session

// Only these pages are open to visitors who are not logged in.
$publicScripts = ['index.php', 'login.php', 'register.php'];
$loggedIn = isset($_SESSION['user_id']);

if (!$loggedIn && !in_array(basename($_SERVER['SCRIPT_NAME']), $publicScripts, true)) {
    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        $_SESSION['login_next'] = $_SERVER['REQUEST_URI'];   // come back here after login
    }
    header('Location: /pothik/login.php?required=1');
    exit;
}

$pageTitle = $pageTitle ?? 'Pothik';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($pageTitle) ?> | Pothik</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: { extend: { colors: { pothik: { green: '#14532d', gold: '#d97706' } } } }
        };
    </script>
</head>
<body class="bg-white text-gray-800">
<header class="bg-pothik-green text-white">
    <nav class="max-w-6xl mx-auto flex flex-wrap items-center justify-between gap-y-2 px-4 py-4">
        <a href="/pothik/index.php" class="text-2xl font-bold">Pothik</a>
        <ul class="flex flex-wrap items-center gap-x-6 gap-y-2 text-sm">
            <?php if ($loggedIn): ?>
                <li><a href="/pothik/index.php" class="hover:text-amber-300">Home</a></li>
                <li><a href="/pothik/destinations.php" class="hover:text-amber-300">Destinations</a></li>
                <li><a href="/pothik/directory.php" class="hover:text-amber-300">Directory</a></li>
                <li><a href="/pothik/emergency.php" class="hover:text-amber-300">Emergency</a></li>
                <li><a href="/pothik/budget.php" class="hover:text-amber-300">Budget</a></li>
                <li><a href="/pothik/estimate.php" class="hover:text-amber-300">Trip Cost</a></li>
                <li><a href="/pothik/packing.php" class="hover:text-amber-300">Packing</a></li>
                <li><a href="/pothik/trips.php" class="hover:text-amber-300">Trip Planner</a></li>
                <?php if (($_SESSION['role'] ?? '') === 'admin'): ?>
                    <li><a href="/pothik/admin/index.php" class="hover:text-amber-300">Admin</a></li>
                <?php endif; ?>
                <li class="text-amber-300">Hi, <?= htmlspecialchars($_SESSION['user_name']) ?></li>
                <li>
                    <form method="post" action="/pothik/logout.php">
                        <?= csrf_field() ?>
                        <button type="submit" class="hover:text-amber-300">Logout</button>
                    </form>
                </li>
            <?php else: ?>
                <li><a href="/pothik/login.php" class="hover:text-amber-300">Login</a></li>
                <li><a href="/pothik/register.php" class="bg-pothik-gold hover:bg-amber-700 px-4 py-2 rounded-full">Register</a></li>
            <?php endif; ?>
        </ul>
    </nav>
</header>
<main class="min-h-screen">