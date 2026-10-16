<?php
session_start();
include __DIR__ . '/connection.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$role = isset($_SESSION['user_role']) ? strtolower($_SESSION['user_role']) : (isset($_SESSION['role']) ? strtolower($_SESSION['role']) : 'patient');
$user_name = isset($_SESSION['fullname']) ? $_SESSION['fullname'] : (isset($_SESSION['user_name']) ? $_SESSION['user_name'] : 'User');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MediCare - <?php echo ucfirst($role); ?> Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body { background-color: #f8f9fa; }
        .sidebar { min-height: 100vh; background: <?php echo ($role === 'doctor') ? '#198754' : '#0d6efd'; ?>; color: white; position: fixed; width: 250px; }
        .sidebar a { color: white; text-decoration: none; display: block; padding: 12px 20px; transition: 0.2s; border-radius: 4px; margin: 4px 10px; }
        .sidebar a:hover { background: rgba(255,255,255,0.2); }
        .main-content { margin-left: 250px; padding: 30px; }
        @media(max-width: 768px) { .sidebar { width: 100%; position: relative; min-height: auto; } .main-content { margin-left: 0; } }
    </style>
</head>
<body>
    <!-- Sidebar Navigation -->
    <div class="sidebar d-flex flex-column p-3 shadow">
        <h4 class="text-center py-3"><i class="fa-solid fa-hospital me-2"></i>MediCare</h4>
        <hr>
        <ul class="nav flex-column mb-auto">
            <li class="nav-item mb-2"><a href="dashboard.php" class="active bg-white bg-opacity-25"><i class="fa-solid fa-house me-2"></i> Dashboard</a></li>
            <?php if ($role === 'doctor'): ?>
                <li class="nav-item mb-2"><a href="doctor_action.php"><i class="fa-solid fa-calendar-check me-2"></i> Patient Requests</a></li>
                <li class="nav-item mb-2"><a href="video_call.php"><i class="fa-solid fa-video me-2"></i> Video Consultation</a></li>
            <?php else: ?>
                <li class="nav-item mb-2"><a href="book_appointment.php"><i class="fa-solid fa-calendar-plus me-2"></i> Book Appointment</a></li>
                <li class="nav-item mb-2"><a href="my_appointments.php"><i class="fa-solid fa-list-check me-2"></i> My Appointments</a></li>
            <?php endif; ?>
            <li class="nav-item mb-2"><a href="profile.php"><i class="fa-solid fa-user me-2"></i> Profile Settings</a></li>
        </ul>
        <hr>
        <a href="logout.php" class="btn btn-outline-light mt-auto"><i class="fa-solid fa-right-from-bracket me-2"></i> Logout</a>
    </div>

    <!-- Main Content Area -->
    <div class="main-content">
        <div class="container-fluid">
            <div class="row mb-4">
                <div class="col-12 bg-white p-4 rounded shadow-sm d-flex justify-content-between align-items-center">
                    <div>
                        <h2 class="fw-bold text-<?php echo ($role === 'doctor') ? 'success' : 'primary'; ?>"><?php echo ucfirst($role); ?> Dashboard</h2>
                        <p class="text-muted mb-0">Welcome back, <strong><?php echo htmlspecialchars($user_name); ?></strong>!</p>
                    </div>
                    <div>
                        <span class="badge bg-<?php echo ($role === 'doctor') ? 'success' : 'primary'; ?> fs-6 p-2 text-uppercase"><?php echo $role; ?> Portal</span>
                    </div>
                </div>
            </div>

            <div class="row">
                <?php if ($role === 'doctor'): ?>
                    <!-- Doctor Cards -->
                    <div class="col-md-6 mb-4">
                        <div class="card border-0 shadow-sm p-4 bg-success text-white">
                            <h3>Manage Appointments</h3>
                            <p>Approve or manage upcoming patient consultation requests.</p>
                            <a href="doctor_action.php" class="btn btn-light text-success fw-bold mt-2">Open Requests</a>
                        </div>
                    </div>
                    <div class="col-md-6 mb-4">
                        <div class="card border-0 shadow-sm p-4 bg-dark text-white">
                            <h3>Video Consultation</h3>
                            <p>Connect with your patients securely via live video rooms.</p>
                            <a href="video_call.php" class="btn btn-outline-light fw-bold mt-2">Start Call</a>
                        </div>
                    </div>
                <?php else: ?>
                    <!-- Patient Cards -->
                    <div class="col-md-6 mb-4">
                        <div class="card border-0 shadow-sm p-4 bg-primary text-white">
                            <h3>Book an Appointment</h3>
                            <p>Consult with available specialist doctors and schedule your visit.</p>
                            <a href="book_appointment.php" class="btn btn-light text-primary fw-bold mt-2">Book Now</a>
                        </div>
                    </div>
                    <div class="col-md-6 mb-4">
                        <div class="card border-0 shadow-sm p-4 bg-info text-white">
                            <h3>My Appointments</h3>
                            <p>View your scheduled bookings and digital prescriptions.</p>
                            <a href="my_appointments.php" class="btn btn-light text-info fw-bold mt-2">View History</a>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</body>
</html>
