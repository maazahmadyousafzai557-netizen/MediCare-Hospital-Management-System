<?php
session_start();
include __DIR__ . '/connection.php';

// Check if logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$role = isset($_SESSION['user_role']) ? $_SESSION['user_role'] : (isset($_SESSION['role']) ? $_SESSION['role'] : 'patient');

// Redirect Doctor or Admin to their own layout if they land on main dashboard
if (strtolower($role) === 'doctor') {
    if (file_exists(__DIR__ . '/doctor_dashboard.php')) {
        include __DIR__ . '/doctor_dashboard.php';
        exit();
    }
} elseif (strtolower($role) === 'admin') {
    if (file_exists(__DIR__ . '/admin_dashboard.php')) {
        include __DIR__ . '/admin_dashboard.php';
        exit();
    }
}
?>
