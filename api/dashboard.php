<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Redirect if not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Fixed include path for Vercel
require_once __DIR__ . '/connection.php';

$user_id   = $_SESSION['user_id'];
$user_role = isset($_SESSION['role']) ? $_SESSION['role'] : (isset($_SESSION['user_role']) ? $_SESSION['user_role'] : 'patient');
$user_name = isset($_SESSION['fullname']) ? $_SESSION['fullname'] : (isset($_SESSION['user_name']) ? $_SESSION['user_name'] : 'User');

// Fetch counts for patient dashboard
$total_appointments = 0;
$pending_appointments = 0;
$completed_appointments = 0;

if ($conn) {
    if ($user_role === 'patient') {
        $stmt1 = mysqli_query($conn, "SELECT COUNT(*) as cnt FROM appointments WHERE patient_id = '$user_id'");
        if ($stmt1 && $row = mysqli_fetch_assoc($stmt1)) {
            $total_appointments = $row['cnt'];
        }

        $stmt2 = mysqli_query($conn, "SELECT COUNT(*) as cnt FROM appointments WHERE patient_id = '$user_id' AND status = 'pending'");
        if ($stmt2 && $row = mysqli_fetch_assoc($stmt2)) {
            $pending_appointments = $row['cnt'];
        }

        $stmt3 = mysqli_query($conn, "SELECT COUNT(*) as cnt FROM appointments WHERE patient_id = '$user_id' AND status = 'completed'");
        if ($stmt3 && $row = mysqli_fetch_assoc($stmt3)) {
            $completed_appointments = $row['cnt'];
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Patient Dashboard - MediCare</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { font-family: 'Poppins', sans-serif; background-color: #f4f7fe; }
        .sidebar { min-height: 100vh; background: linear-gradient(180deg, #0d6efd 0%, #0a58ca 100%); color: white; padding: 20px 10px; }
        .sidebar a { color: rgba(255,255,255,0.8); text-decoration: none; padding: 12px 15px; display: block; border-radius: 8px; margin-bottom: 5px; font-weight: 500; }
        .sidebar a:hover, .sidebar a.active { background: rgba(255,255,255,0.2); color: white; }
        .card-stat { border: none; border-radius: 12px; box-shadow: 0 4px 12px rgba(0,0,0,0.05); transition: transform 0.2s; }
        .card-stat:hover { transform: translateY(-3px); }
        .welcome-banner { background: linear-gradient(90deg, #0d6efd 0%, #0d99ff 100%); color: white; border-radius: 12px; padding: 25px; margin-bottom: 25px; }
    </style>
</head>
<body>
<div class="container-fluid">
    <div class="row">
        <!-- Sidebar -->
        <div class="col-md-3 col-lg-2 sidebar">
            <h3 class="text-center fw-bold mb-4"><i class="fa-solid fa-hospital me-2"></i>MediCare</h3>
            <a href="dashboard.php" class="active"><i class="fa-solid fa-house me-2"></i>Dashboard</a>
            <a href="book_appointment.php"><i class="fa-solid fa-calendar-plus me-2"></i>Book Appointment</a>
            <a href="my_appointments.php"><i class="fa-solid fa-calendar-check me-2"></i>My Appointments</a>
            <a href="video_call.php"><i class="fa-solid fa-video me-2"></i>Video Call</a>
            <a href="profile.php"><i class="fa-solid fa-user me-2"></i>My Profile</a>
            <hr class="my-4">
            <a href="logout.php" class="text-danger-light"><i class="fa-solid fa-right-from-bracket me-2"></i>Logout</a>
        </div>

        <!-- Main Content -->
        <div class="col-md-9 col-lg-10 p-4">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h4 class="fw-bold mb-0"><i class="fa-solid fa-house me-2"></i>Patient Dashboard</h4>
                <div class="dropdown">
                    <span class="badge bg-primary fs-6 px-3 py-2"><i class="fa-solid fa-user me-2"></i><?php echo htmlspecialchars($user_name); ?></span>
                </div>
            </div>

            <!-- Welcome Banner -->
            <div class="welcome-banner">
                <h3 class="fw-bold">👋 Welcome back, <?php echo htmlspecialchars($user_name); ?>!</h3>
                <p class="mb-0">Manage your appointments and consult doctors online via video call.</p>
            </div>

            <!-- Statistics Cards -->
            <div class="row g-4 mb-4">
                <div class="col-md-4">
                    <div class="card card-stat p-3 bg-white">
                        <div class="d-flex align-items-center">
                            <div class="rounded-3 bg-primary text-white p-3 me-3 fs-3"><i class="fa-solid fa-calendar"></i></div>
                            <div>
                                <h3 class="fw-bold mb-0"><?php echo $total_appointments; ?></h3>
                                <small class="text-muted">Total Appointments</small>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card card-stat p-3 bg-white">
                        <div class="d-flex align-items-center">
                            <div class="rounded-3 bg-warning text-white p-3 me-3 fs-3"><i class="fa-solid fa-clock"></i></div>
                            <div>
                                <h3 class="fw-bold mb-0"><?php echo $pending_appointments; ?></h3>
                                <small class="text-muted">Pending</small>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card card-stat p-3 bg-white">
                        <div class="d-flex align-items-center">
                            <div class="rounded-3 bg-success text-white p-3 me-3 fs-3"><i class="fa-solid fa-circle-check"></i></div>
                            <div>
                                <h3 class="fw-bold mb-0"><?php echo $completed_appointments; ?></h3>
                                <small class="text-muted">Completed</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <h5 class="fw-bold mb-3">Quick Actions</h5>
            <div class="row g-4">
                <div class="col-md-4">
                    <a href="book_appointment.php" class="text-decoration-none">
                        <div class="card card-stat p-4 text-center bg-white h-100">
                            <i class="fa-solid fa-calendar-plus text-primary display-5 mb-3"></i>
                            <h6 class="fw-bold text-dark">Book Appointment</h6>
                            <small class="text-muted">Schedule a visit with your preferred doctor</small>
                        </div>
                    </a>
                </div>
                <div class="col-md-4">
                    <a href="my_appointments.php" class="text-decoration-none">
                        <div class="card card-stat p-4 text-center bg-white h-100">
                            <i class="fa-solid fa-list-check text-success display-5 mb-3"></i>
                            <h6 class="fw-bold text-dark">My Appointments</h6>
                            <small class="text-muted">View all your upcoming and past appointments</small>
                        </div>
                    </a>
                </div>
                <div class="col-md-4">
                    <a href="video_call.php" class="text-decoration-none">
                        <div class="card card-stat p-4 text-center bg-white h-100">
                            <i class="fa-solid fa-video text-info display-5 mb-3"></i>
                            <h6 class="fw-bold text-dark">Video Consultation</h6>
                            <small class="text-muted">Consult your doctor via secure video call</small>
                        </div>
                    </a>
                </div>
            </div>

        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
