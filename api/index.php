<?php
// Enable full error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Get clean requested URL path
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$uri = ltrim($uri, '/');

// 1. Home / Landing Page Handler
if (empty($uri) || $uri === 'index.php') {
    if (file_exists(__DIR__ . '/home.php')) {
        require __DIR__ . '/home.php';
        exit();
    }
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>MediCare - Online Hospital System</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    </head>
    <body class="bg-primary text-white text-center d-flex align-items-center vh-100">
        <div class="container">
            <h1 class="display-3 fw-bold mb-3"><i class="fa-solid fa-hospital me-2"></i>Welcome to MediCare</h1>
            <p class="lead mb-5 fs-4">Online Hospital Appointment System — Book appointments & consult doctors via video call</p>
            <div class="d-flex justify-content-center gap-3">
                <a href="register.php" class="btn btn-light btn-lg fw-bold rounded-pill px-4">Register as Patient</a>
                <a href="login.php" class="btn btn-outline-light btn-lg fw-bold rounded-pill px-4">Login</a>
            </div>
        </div>
    </body>
    </html>
    <?php
    exit();
}

// 2. Resolve requested page path inside api directory
$target = __DIR__ . '/' . $uri;

// Check if file exists without .php extension
if (!is_file($target) && is_file($target . '.php')) {
    $target = $target . '.php';
}

// 3. Execute requested page & set directory context for relative includes
if (is_file($target)) {
    chdir(dirname($target));
    require $target;
    exit();
}

// 4. Fallback: Search inside subfolders (patient/, doctor/, admin/) if path doesn't specify it
$subfolders = ['patient', 'doctor', 'admin', 'db'];
foreach ($subfolders as $folder) {
    $sub_target = __DIR__ . '/' . $folder . '/' . $uri;
    if (!is_file($sub_target) && is_file($sub_target . '.php')) {
        $sub_target = $sub_target . '.php';
    }
    if (is_file($sub_target)) {
        chdir(dirname($sub_target));
        require $sub_target;
        exit();
    }
}

// 5. 404 Fallback
http_response_code(404);
echo "<h2 style='text-align:center;margin-top:50px;font-family:sans-serif;color:red;'>404 - Page Not Found: " . htmlspecialchars($uri) . "</h2>";
?>
