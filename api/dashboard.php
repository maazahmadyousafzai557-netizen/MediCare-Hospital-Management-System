<?php
session_start();
include __DIR__ . '/connection.php';

// Enable error tracking to debug immediately if anything fails
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$role = isset($_SESSION['user_role']) ? strtolower($_SESSION['user_role']) : (isset($_SESSION['role']) ? strtolower($_SESSION['role']) : 'patient');
$user_name = isset($_SESSION['fullname']) ? $_SESSION['fullname'] : (isset($_SESSION['user_name']) ? $_SESSION['user_name'] : 'User');

// If Admin -> redirect or show admin view
if ($role === 'admin') {
    $target = __DIR__ . '/admin_dashboard.php';
    if (file_exists($target)) {
        include $target;
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MediCare - Portal</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="<?php echo ($role === 'doctor') ? 'bg-success-subtle' : 'bg-primary-subtle'; ?>">
    <div class="container mt-5">
        <div class="card shadow-lg p-5">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h2 class="text-primary fw-bold">Welcome, <?php echo htmlspecialchars($user_name); ?>!</h2>
                    <p class="text-muted mb-0">Logged in as: <span class="badge bg-<?php echo ($role === 'doctor') ? 'success' : 'primary'; ?> text-uppercase"><?php echo $role; ?></span></p>
                </div>
                <a href="logout.php" class="btn btn-outline-danger">Logout</a>
            </div>
            <hr>
            <div class="row mt-4">
                <?php if ($role === 'doctor'): ?>
                    <!-- Doctor Quick Actions -->
                    <div class="col-md-6 mb-3">
                        <div class="card bg-success text-white p-4 h-100 shadow-sm">
                            <h4>Doctor Schedule & Actions</h4>
                            <p>Manage incoming patient appointments, write prescriptions, and join video consultations.</p>
                            <a href="doctor_action.php" class="btn btn-light mt-auto">Open Doctor Actions</a>
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <div class="card bg-dark text-white p-4 h-100 shadow-sm">
                            <h4>Video Consultation</h4>
                            <p>Connect with patients securely via ZegoCloud SDK video calls.</p>
                            <a href="video_call.php" class="btn btn-outline-light mt-auto">Join Video Room</a>
                        </div>
                    </div>
                <?php else: ?>
                    <!-- Patient Quick Actions -->
                    <div class="col-md-6 mb-3">
                        <div class="card bg-primary text-white p-4 h-100 shadow-sm">
                            <h4>Book an Appointment</h4>
                            <p>Choose from available specialist doctors, pick time slots, and schedule consultations.</p>
                            <a href="book_appointment.php" class="btn btn-light mt-auto">Book Now</a>
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <div class="card bg-info text-white p-4 h-100 shadow-sm">
                            <h4>My Appointments & Prescriptions</h4>
                            <p>View your past bookings, download payment receipts, and check digital prescriptions.</p>
                            <a href="my_appointments.php" class="btn btn-light mt-auto">View Appointments</a>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</body>
</html>
