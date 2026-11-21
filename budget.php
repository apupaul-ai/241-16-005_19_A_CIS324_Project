<?php
require_once __DIR__ . '/includes/csrf_auto.php';
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/app/controllers/BudgetController.php';

(new BudgetController($pdo))->index();