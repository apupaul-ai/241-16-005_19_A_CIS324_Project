<?php
// Include at the very top of any page that has POST forms.
// 1) Verifies the token on every POST request.
// 2) Adds the hidden token field to every <form method="post"> in the output.
require_once __DIR__ . '/csrf.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_check();
}

ob_start(function (string $html): string {
    return preg_replace(
        '/(<form\b[^>]*\bmethod=["\']post["\'][^>]*>)/i',
        '$1' . csrf_field(),
        $html
    );
});