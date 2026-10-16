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

$doctors = [];
if ($conn) {
    $query = "SELECT u.id, u.fullname, d.specialization, d.qualification, d.experience 
              FROM users u 
              JOIN doctors d ON u.id = d.user_id 
              WHERE u.role = 'doctor' AND d.status = 'active'";
    $res = mysqli_query($conn, $query);
    if ($res) {
        while ($row = mysqli_fetch_assoc($res)) {
            $doctors[] = $row;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Appointment - MediCare</title>
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
            <a href="book_appointment.php" class="active"><i class="fa-solid fa-calendar-plus me-2"></i>Book Appointment</a>
            <a href="my_appointments.php"><i class="fa-solid fa-calendar-check me-2"></i>My Appointments</a>
            <a href="video_call.php"><i class="fa-solid fa-video me-2"></i>Video Call</a>
            <a href="profile.php"><i class="fa-solid fa-user me-2"></i>My Profile</a>
            <hr class="my-4">
            <a href="logout.php" class="text-danger-light"><i class="fa-solid fa-right-from-bracket me-2"></i>Logout</a>
        </div>

        <div class="col-md-9 col-lg-10 p-4">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h4 class="fw-bold mb-0"><i class="fa-solid fa-calendar-plus me-2"></i>Book Doctor Appointment</h4>
                <span class="badge bg-primary fs-6 px-3 py-2"><i class="fa-solid fa-user me-2"></i><?php echo htmlspecialchars($user_name); ?></span>
            </div>

            <div class="row">
                <div class="col-md-8">
                    <div class="card card-custom p-4 bg-white">
                        <form action="book_process.php" method="POST">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Select Doctor</label>
                                <select name="doctor_id" class="form-select" required>
                                    <option value="">-- Choose Doctor --</option>
                                    <?php foreach ($doctors as $doc): ?>
                                        <option value="<?php echo $doc['id']; ?>">
                                            Dr. <?php echo htmlspecialchars($doc['fullname']); ?> (<?php echo htmlspecialchars($doc['specialization']); ?>)
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-bold">Appointment Date</label>
                                <input type="date" name="appointment_date" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-bold">Preferred Time</label>
                                <input type="time" name="appointment_time" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-bold">Consultation Type</label>
                                <select name="type" class="form-select" required>
                                    <option value="in-person">In-Person Visit</option>
                                    <option value="video-call">Video Call Consultation</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-bold">Notes / Symptoms (Optional)</label>
                                <textarea name="notes" class="form-control" rows="3" placeholder="Briefly explain your health issues..."></textarea>
                            </div>
                            <button type="submit" class="btn btn-primary fw-bold px-4"><i class="fa-solid fa-check-circle me-2"></i>Confirm Booking</button>
                        </form>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
