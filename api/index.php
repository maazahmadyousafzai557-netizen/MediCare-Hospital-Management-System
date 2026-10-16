<?php
session_start();

// Get the requested URL path
$request_uri = $_SERVER['REQUEST_URI'];
$path = parse_url($request_uri, PHP_URL_PATH);
$path = trim($path, '/');

// Default to index.php if path is empty
if (empty($path) || $path === 'index.php') {
    $target = __DIR__ . '/index.php';
    // If index.php is calling itself, load main template directly
    if (file_exists($target)) {
        // Prevent infinite loop if index.php includes itself
        include_once __DIR__ . '/home.php'; 
        exit();
    }
}

// Build target file path inside api folder
$file = __DIR__ . '/' . $path;

// Check if file exists (e.g. login.php, register.php, patient/dashboard.php)
if (file_exists($file) && is_file($file)) {
    require $file;
    exit();
} 

// Fallback: Check if file exists without extension
if (file_exists($file . '.php') && is_file($file . '.php')) {
    require $file . '.php';
    exit();
}

// 404 Page if file not found
http_response_code(404);
echo "<h2 style='text-align:center;margin-top:50px;font-family:sans-serif;'>404 - Page Not Found: " . htmlspecialchars($path) . "</h2>";
?>
