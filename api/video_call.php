<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

require_once __DIR__ . '/connection.php';

$user_id   = $_SESSION['user_id'];
$user_role = isset($_SESSION['role']) ? $_SESSION['role'] : (isset($_SESSION['user_role']) ? $_SESSION['user_role'] : 'patient');
$user_name = isset($_SESSION['fullname']) ? $_SESSION['fullname'] : (isset($_SESSION['user_name']) ? $_SESSION['user_name'] : 'User');

$appointments = [];
if ($conn) {
    if ($user_role === 'patient') {
        $query = "SELECT a.*, u.fullname as doctor_name, d.specialization 
                  FROM appointments a 
                  JOIN users u ON a.doctor_id = u.id 
                  LEFT JOIN doctors d ON u.id = d.user_id 
                  WHERE a.patient_id = '$user_id' AND a.type = 'video-call' AND a.status = 'confirmed'";
    } else {
        $query = "SELECT a.*, u.fullname as patient_name 
                  FROM appointments a 
                  JOIN users u ON a.patient_id = u.id 
                  WHERE a.doctor_id = '$user_id' AND a.type = 'video-call' AND a.status = 'confirmed'";
    }
    $res = mysqli_query($conn, $query);
    if ($res) {
        while ($row = mysqli_fetch_assoc($res)) {
            $appointments[] = $row;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Video Consultation - MediCare</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { font-family: 'Poppins', sans-serif; background-color: #f4f7fe; }
        .sidebar { min-height: 100vh; background: linear-gradient(180deg, #0d6efd 0%, #0a58ca 100%); color: white; padding: 20px 10px; }
        .sidebar a { color: rgba(255,255,255,0.8); text-decoration: none; padding: 12px 15px; display: block; border-radius: 8px; margin-bottom: 5px; font-weight: 500; }
        .sidebar a:hover, .sidebar a.active { background: rgba(255,255,255,0.2); color: white; }
        .card-custom { border: none; border-radius: 12px; box-shadow: 0 4px 12px rgba(0,0,0,0.05); }
    </style>
</head>
<body>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-3 col-lg-2 sidebar">
            <h3 class="text-center fw-bold mb-4"><i class="fa-solid fa-hospital me-2"></i>MediCare</h3>
            <a href="dashboard.php"><i class="fa-solid fa-house me-2"></i>Dashboard</a>
            <a href="book_appointment.php"><i class="fa-solid fa-calendar-plus me-2"></i>Book Appointment</a>
            <a href="my_appointments.php"><i class="fa-solid fa-calendar-check me-2"></i>My Appointments</a>
            <a href="video_call.php" class="active"><i class="fa-solid fa-video me-2"></i>Video Call</a>
            <a href="profile.php"><i class="fa-solid fa-user me-2"></i>My Profile</a>
            <hr class="my-4">
            <a href="logout.php" class="text-danger-light"><i class="fa-solid fa-right-from-bracket me-2"></i>Logout</a>
        </div>

        <div class="col-md-9 col-lg-10 p-4">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h4 class="fw-bold mb-0"><i class="fa-solid fa-video me-2"></i>Video Consultation Rooms</h4>
                <span class="badge bg-primary fs-6 px-3 py-2"><i class="fa-solid fa-user me-2"></i><?php echo htmlspecialchars($user_name); ?></span>
            </div>

            <div class="row g-4">
                <?php if (empty($appointments)): ?>
                    <div class="col-12">
                        <div class="card card-custom p-5 text-center bg-white">
                            <i class="fa-solid fa-video-slash text-muted display-4 mb-3"></i>
                            <h5 class="fw-bold text-secondary">No Confirmed Video Call Appointments Found</h5>
                            <p class="text-muted mb-0">Once your video appointment is confirmed, the call room button will appear here.</p>
                        </div>
                    </div>
                <?php else: ?>
                    <?php foreach ($appointments as $app): ?>
                        <div class="col-md-6">
                            <div class="card card-custom p-4 bg-white">
                                <h5 class="fw-bold text-primary mb-2">
                                    <i class="fa-solid fa-user-doctor me-2"></i>
                                    <?php echo htmlspecialchars($user_role === 'patient' ? $app['doctor_name'] : $app['patient_name']); ?>
                                </h5>
                                <p class="text-muted mb-2"><i class="fa-solid fa-calendar-day me-2"></i>Date: <?php echo htmlspecialchars($app['appointment_date']); ?> | Time: <?php echo htmlspecialchars($app['appointment_time']); ?></p>
                                <a href="video_room.php?room_id=<?php echo $app['id']; ?>" class="btn btn-success fw-bold mt-2"><i class="fa-solid fa-headset me-2"></i>Join Video Call Room</a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>

        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
