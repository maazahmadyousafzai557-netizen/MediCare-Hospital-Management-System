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
$user_name = isset($_SESSION['fullname']) ? $_SESSION['fullname'] : (isset($_SESSION['user_name']) ? $_SESSION['user_name'] : 'User');

$appointments = [];
if ($conn) {
    $query = "SELECT a.*, u.fullname as doctor_name, d.specialization 
              FROM appointments a 
              JOIN users u ON a.doctor_id = u.id 
              LEFT JOIN doctors d ON u.id = d.user_id 
              WHERE a.patient_id = '$user_id' 
              ORDER BY a.id DESC";
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
    <title>My Appointments - MediCare</title>
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
            <a href="my_appointments.php" class="active"><i class="fa-solid fa-calendar-check me-2"></i>My Appointments</a>
            <a href="video_call.php"><i class="fa-solid fa-video me-2"></i>Video Call</a>
            <a href="profile.php"><i class="fa-solid fa-user me-2"></i>My Profile</a>
            <hr class="my-4">
            <a href="logout.php" class="text-danger-light"><i class="fa-solid fa-right-from-bracket me-2"></i>Logout</a>
        </div>

        <div class="col-md-9 col-lg-10 p-4">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h4 class="fw-bold mb-0"><i class="fa-solid fa-calendar-check me-2"></i>My Appointment Records</h4>
                <span class="badge bg-primary fs-6 px-3 py-2"><i class="fa-solid fa-user me-2"></i><?php echo htmlspecialchars($user_name); ?></span>
            </div>

            <div class="card card-custom p-4 bg-white">
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>Doctor</th>
                                <th>Specialization</th>
                                <th>Date</th>
                                <th>Time</th>
                                <th>Type</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($appointments)): ?>
                                <tr>
                                    <td colspan="8" class="text-center text-muted py-4">No appointments found.</td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($appointments as $index => $app): ?>
                                    <tr>
                                        <td><?php echo $index + 1; ?></td>
                                        <td class="fw-bold">Dr. <?php echo htmlspecialchars($app['doctor_name']); ?></td>
                                        <td><?php echo htmlspecialchars($app['specialization'] ? $app['specialization'] : 'General'); ?></td>
                                        <td><?php echo htmlspecialchars($app['appointment_date']); ?></td>
                                        <td><?php echo htmlspecialchars($app['appointment_time']); ?></td>
                                        <td>
                                            <span class="badge <?php echo $app['type'] === 'video-call' ? 'bg-info' : 'bg-secondary'; ?>">
                                                <?php echo htmlspecialchars($app['type']); ?>
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge <?php echo $app['status'] === 'confirmed' ? 'bg-success' : ($app['status'] === 'pending' ? 'bg-warning text-dark' : 'bg-primary'); ?>">
                                                <?php echo htmlspecialchars($app['status']); ?>
                                            </span>
                                        </td>
                                        <td>
                                            <?php if ($app['status'] === 'pending'): ?>
                                                <a href="cancel_appointment.php?id=<?php echo $app['id']; ?>" class="btn btn-sm btn-danger"><i class="fa-solid fa-xmark me-1"></i>Cancel</a>
                                            <?php elseif ($app['status'] === 'confirmed'): ?>
                                                <a href="payment.php?appointment_id=<?php echo $app['id']; ?>" class="btn btn-sm btn-success"><i class="fa-solid fa-credit-card me-1"></i>Pay Now</a>
                                            <?php else: ?>
                                                <span class="text-muted small">None</span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
