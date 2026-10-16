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
$user_name = isset($_SESSION['fullname']) ? $_SESSION['fullname'] : (isset($_SESSION['user_name']) ? $_SESSION['user_name'] : 'User');
$success_msg = '';
$error_msg   = '';

// Fetch existing user details
$user_data = array('fullname' => '', 'email' => '', 'phone' => '', 'role' => 'patient');
if ($conn) {
    $stmt = mysqli_query($conn, "SELECT * FROM users WHERE id = '$user_id'");
    if ($stmt && $row = mysqli_fetch_assoc($stmt)) {
        $user_data = $row;
    }
}

// Handle Profile Update Request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fullname = mysqli_real_escape_string($conn, trim($_POST['fullname']));
    $phone    = mysqli_real_escape_string($conn, trim($_POST['phone']));

    if (!empty($fullname) && !empty($phone)) {
        $update_query = "UPDATE users SET fullname = '$fullname', phone = '$phone' WHERE id = '$user_id'";
        if (mysqli_query($conn, $update_query)) {
            $_SESSION['fullname']  = $fullname;
            $_SESSION['user_name'] = $fullname;
            $user_data['fullname'] = $fullname;
            $user_data['phone']    = $phone;
            $success_msg = "Profile updated successfully!";
        } else {
            $error_msg = "Failed to update profile in database.";
        }
    } else {
        $error_msg = "Please fill in all required fields.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Profile - MediCare</title>
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
        <!-- Sidebar -->
        <div class="col-md-3 col-lg-2 sidebar">
            <h3 class="text-center fw-bold mb-4"><i class="fa-solid fa-hospital me-2"></i>MediCare</h3>
            <a href="dashboard.php"><i class="fa-solid fa-house me-2"></i>Dashboard</a>
            <a href="book_appointment.php"><i class="fa-solid fa-calendar-plus me-2"></i>Book Appointment</a>
            <a href="my_appointments.php"><i class="fa-solid fa-calendar-check me-2"></i>My Appointments</a>
            <a href="video_call.php"><i class="fa-solid fa-video me-2"></i>Video Call</a>
            <a href="profile.php" class="active"><i class="fa-solid fa-user me-2"></i>My Profile</a>
            <hr class="my-4">
            <a href="logout.php" class="text-danger-light"><i class="fa-solid fa-right-from-bracket me-2"></i>Logout</a>
        </div>

        <!-- Main Content -->
        <div class="col-md-9 col-lg-10 p-4">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h4 class="fw-bold mb-0"><i class="fa-solid fa-id-card me-2"></i>User Profile</h4>
                <span class="badge bg-primary fs-6 px-3 py-2"><i class="fa-solid fa-user me-2"></i><?php echo htmlspecialchars($user_name); ?></span>
            </div>

            <?php if (!empty($success_msg)): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fa-solid fa-circle-check me-2"></i><?php echo htmlspecialchars($success_msg); ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <?php if (!empty($error_msg)): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fa-solid fa-circle-exclamation me-2"></i><?php echo htmlspecialchars($error_msg); ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <div class="row">
                <div class="col-md-8">
                    <div class="card card-custom p-4 bg-white">
                        <h5 class="fw-bold mb-4 text-primary"><i class="fa-solid fa-user-pen me-2"></i>Edit Profile Information</h5>
                        <form action="profile.php" method="POST">
                            <div class="mb-3">
                                <label class="form-label font-weight-bold">Full Name</label>
                                <input type="text" name="fullname" class="form-control" value="<?php echo htmlspecialchars($user_data['fullname']); ?>" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label font-weight-bold">Email Address</label>
                                <input type="email" class="form-control" value="<?php echo htmlspecialchars($user_data['email']); ?>" disabled>
                                <small class="text-muted">Email cannot be changed.</small>
                            </div>
                            <div class="mb-3">
                                <label class="form-label font-weight-bold">Phone Number</label>
                                <input type="text" name="phone" class="form-control" value="<?php echo htmlspecialchars($user_data['phone']); ?>" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label font-weight-bold">Account Role</label>
                                <input type="text" class="form-control text-capitalize" value="<?php echo htmlspecialchars($user_data['role']); ?>" disabled>
                            </div>
                            <button type="submit" class="btn btn-primary px-4 fw-bold mt-2"><i class="fa-solid fa-floppy-disk me-2"></i>Save Changes</button>
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
