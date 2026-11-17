<?php
require_once __DIR__ . '/../config/config.php';
if (session_status() === PHP_SESSION_NONE) {
    session_start();
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
        <ul class="flex flex-wrap gap-x-6 gap-y-2 text-sm">
            <li><a href="/pothik/index.php" class="hover:text-amber-300">Home</a></li>
            <li><a href="/pothik/destinations.php" class="hover:text-amber-300">Destinations</a></li>
            <li><a href="/pothik/directory.php" class="hover:text-amber-300">Directory</a></li>
            <li><a href="/pothik/emergency.php" class="hover:text-amber-300">Emergency</a></li>
            <li><a href="/pothik/trips.php" class="hover:text-amber-300">Trip Planner</a></li>
            <?php if (isset($_SESSION['user_id'])): ?>
                <li class="text-amber-300">Hi, <?= htmlspecialchars($_SESSION['user_name']) ?></li>
                <li><a href="/pothik/logout.php" class="hover:text-amber-300">Logout</a></li>
            <?php else: ?>
                <li><a href="/pothik/login.php" class="hover:text-amber-300">Login</a></li>
                <li><a href="/pothik/register.php" class="hover:text-amber-300">Register</a></li>
            <?php endif; ?>
        </ul>
    </nav>
</header>
<main class="min-h-screen">