<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Fix Vercel Serverless Session Storage
if (session_status() === PHP_SESSION_NONE) {
    $session_dir = '/tmp/sessions';
    if (!file_exists($session_dir)) {
        @mkdir($session_dir, 0777, true);
    }
    session_save_path($session_dir);
    session_start();
}

// Get clean route parameter from vercel.json
$route = $_GET['route'] ?? '';
$route = trim($route, '/');

// Clean query strings if present
if (($pos = strpos($route, '?')) !== false) {
    $route = substr($route, 0, $pos);
}

// Strip leading 'api/' if prepended by Vercel
if (strpos($route, 'api/') === 0) {
    $route = substr($route, 4);
}

// 1. Landing Page / Home Screen
if (empty($route) || $route === 'index' || $route === 'index.php') {
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

// 2. Direct match inside api folder
$target = __DIR__ . '/' . $route;
if (!is_file($target) && is_file($target . '.php')) {
    $target = $target . '.php';
}

// 3. Fallback search inside subfolders (patient/, doctor/, admin/, db/)
if (!is_file($target)) {
    $subfolders = ['patient', 'doctor', 'admin', 'db'];
    foreach ($subfolders as $folder) {
        $sub_target = __DIR__ . '/' . $folder . '/' . $route;
        if (!is_file($sub_target) && is_file($sub_target . '.php')) {
            $sub_target = $sub_target . '.php';
        }
        if (is_file($sub_target)) {
            $target = $sub_target;
            break;
        }
    }
}

// 4. Render Target Page
if (is_file($target)) {
    chdir(dirname($target));
    require $target;
    exit();
}

// 5. 404 Fallback
http_response_code(404);
echo "<div style='text-align:center;margin-top:50px;font-family:sans-serif;'>";
echo "<h2 style='color:red;'>404 - Page Not Found</h2>";
echo "<p>Requested: <strong>" . htmlspecialchars($route) . "</strong></p>";
echo "<a href='/'>Go to Home</a>";
echo "</div>";
?>
