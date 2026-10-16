<?php
session_start();
include __DIR__ . '/connection.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$role = isset($_SESSION['user_role']) ? strtolower($_SESSION['user_role']) : (isset($_SESSION['role']) ? strtolower($_SESSION['role']) : 'patient');

// Role-based routing
if ($role === 'doctor') {
    $doctor_file = __DIR__ . '/doctor_dashboard.php';
    if (file_exists($doctor_file)) {
        require_once $doctor_file;
        exit();
    } else {
        die("Error: doctor_dashboard.php file missing in api directory.");
    }
} elseif ($role === 'admin') {
    $admin_file = __DIR__ . '/admin_dashboard.php';
    if (file_exists($admin_file)) {
        require_once $admin_file;
        exit();
    } else {
        die("Error: admin_dashboard.php file missing in api directory.");
    }
} else {
    // Default Patient Dashboard logic can follow here or load patient view
    $patient_file = __DIR__ . '/patient_dashboard.php'; // or whatever your patient main view file is named
    // If patient dashboard code is already below, let it execute
}
?>
