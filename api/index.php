<?php
// Single Entry Point Router for Vercel Serverless Function Limit Fix

$request_uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$path = ltrim($request_uri, '/');

// Default Route (Homepage / Base URL)
if ($path === '' || $path === 'index.php') {
    if (file_exists(__DIR__ . '/dashboard.php')) {
        require __DIR__ . '/dashboard.php';
    } else {
        require __DIR__ . '/login.php';
    }
    exit();
}

// Target File Path Setup
$target_file = __DIR__ . '/' . $path;

// 1. Direct File Request Match (e.g., login.php, register_process.php)
if (file_exists($target_file) && is_file($target_file)) {
    require $target_file;
    exit();
}

// 2. URL Without Extension Match (e.g., /login -> login.php)
if (file_exists($target_file . '.php')) {
    require $target_file . '.php';
    exit();
}

// 3. Fallback for 404
http_response_code(404);
echo "404 - Page Not Found";
?>
