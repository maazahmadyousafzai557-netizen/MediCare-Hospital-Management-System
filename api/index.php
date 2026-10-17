<?php
// Display errors
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Fix Vercel Sessions (Taa ke login out na ho)
if (session_status() === PHP_SESSION_NONE) {
    $session_dir = '/tmp/sessions';
    if (!file_exists($session_dir)) {
        @mkdir($session_dir, 0777, true);
    }
    session_save_path($session_dir);
    session_start();
}

// Get the requested path
$path = isset($_GET['path']) ? $_GET['path'] : '';
$path = trim($path, '/');

// Clean up any extra parameters
if (($pos = strpos($path, '?')) !== false) {
    $path = substr($path, 0, $pos);
}

// Strip 'api/' prefix if Vercel added it
if (strpos($path, 'api/') === 0) {
    $path = substr($path, 4);
}

// 1. Landing Page Handler
if (empty($path) || $path === 'index' || $path === 'index.php') {
    if (file_exists(__DIR__ . '/home.php')) {
        require __DIR__ . '/home.php';
        exit();
    }
    // Simple Fallback Page
    echo "<h2 style='text-align:center;margin-top:50px;'>Welcome to Medicare (Please add home.php)</h2>";
    echo "<p style='text-align:center;'><a href='/login.php'>Login Here</a></p>";
    exit();
}

// 2. Direct File Path Mapping
$target = __DIR__ . '/' . $path;
if (!is_file($target) && is_file($target . '.php')) {
    $target = $target . '.php';
}

// 3. Subfolder Fallback (if file is in patient, doctor, admin etc.)
if (!is_file($target)) {
    $subfolders = ['patient', 'doctor', 'admin', 'db'];
    foreach ($subfolders as $folder) {
        $sub_target = __DIR__ . '/' . $folder . '/' . $path;
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
    chdir(dirname($target)); // Important for relative paths (like connection.php)
    require $target;
    exit();
}

// 5. 404 Error Handler
http_response_code(404);
echo "<div style='text-align:center;margin-top:50px;font-family:sans-serif;'>";
echo "<h2 style='color:red;'>404 - Page Not Found</h2>";
echo "<p>Path: " . htmlspecialchars($path) . "</p>";
echo "</div>";
?>
