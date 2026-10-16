<?php
session_start();

// Debugging errors if any file breaks
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Get requested URL path
$request_uri = $_SERVER['REQUEST_URI'];
$path = parse_url($request_uri, PHP_URL_PATH);
$path = trim($path, '/');

// ---------------------------------------------------------
// ROUTER: If user requests a specific page (e.g. login.php, patient/dashboard.php)
// ---------------------------------------------------------
if (!empty($path) && $path !== 'index.php') {
    $target = __DIR__ . '/' . $path;

    // Check if file exists with or without .php extension
    if (!is_file($target) && is_file($target . '.php')) {
        $target = $target . '.php';
    }

    if (is_file($target)) {
        // CRITICAL FIX: Change working directory to the target file's folder
        // so relative includes like "../db/connection.php" work seamlessly!
        chdir(dirname($target));
        require $target;
        exit();
    } else {
        http_response_code(404);
        echo "<h2 style='text-align:center;margin-top:50px;font-family:sans-serif;'>404 - Page Not Found: " . htmlspecialchars($path) . "</h2>";
        exit();
    }
}

// ---------------------------------------------------------
// LANDING PAGE: Shown when user visits "/" or "index.php"
// ---------------------------------------------------------
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MediCare - Online Hospital System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body { background: #0d6efd; color: white; min-height: 100vh; display: flex; align-items: center; }
        .btn-custom-white { background: white; color: #0d6efd; font-weight: 600; padding: 12px 30px; border-radius: 50px; text-decoration: none; }
        .btn-custom-white:hover { background: #f8f9fa; color: #0b5ed7; }
        .btn-custom-outline { border: 2px solid white; color: white; font-weight: 600; padding: 12px 30px; border-radius: 50px; text-decoration: none; }
        .btn-custom-outline:hover { background: white; color: #0d6efd; }
    </style>
</head>
<body>
    <div class="container text-center">
        <div class="row justify-content-center">
            <div class="col-md-9">
                <h2 class="fw-bold mb-5"><i class="fa-solid fa-hospital me-2"></i>MediCare</h2>
                <div class="my-4">
                    <i class="fa-solid fa-heart-pulse display-1 mb-4"></i>
                    <h1 class="display-4 fw-bold mb-3">Welcome to MediCare</h1>
                    <p class="lead mb-5 fs-4">Online Hospital Appointment System — Book appointments & consult doctors via video call</p>
                </div>
                <div class="d-flex justify-content-center gap-3 flex-wrap mt-4">
                    <a href="register.php" class="btn btn-custom-white btn-lg"><i class="fa-solid fa-user-plus me-2"></i>Register as Patient</a>
                    <a href="login.php" class="btn btn-custom-outline btn-lg"><i class="fa-solid fa-right-to-bracket me-2"></i>Login</a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
