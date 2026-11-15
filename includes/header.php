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
    <nav class="max-w-6xl mx-auto flex items-center justify-between px-4 py-4">
        <a href="/pothik/index.php" class="text-2xl font-bold">Pothik</a>
        <ul class="flex gap-6 text-sm">
            <li><a href="/pothik/index.php" class="hover:text-amber-300">Home</a></li>
            <li><a href="/pothik/destinations.php" class="hover:text-amber-300">Destinations</a></li>
            <li><a href="/pothik/trips.php" class="hover:text-amber-300">Trip Planner</a></li>
            <li><a href="/pothik/login.php" class="hover:text-amber-300">Login</a></li>
        </ul>
    </nav>
</header>
<main class="min-h-screen">