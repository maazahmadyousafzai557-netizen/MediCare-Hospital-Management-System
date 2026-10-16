<?php
session_start();
include 'connection.php';
include 'email.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $fullname = mysqli_real_escape_string($conn, $_POST['fullname']);
    $email    = mysqli_real_escape_string($conn, $_POST['email']);
    $phone    = mysqli_real_escape_string($conn, $_POST['phone']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $role     = 'patient';

    if ($password !== $confirm_password) {
        echo "<script>alert('Passwords do not match!'); window.location='register.php';</script>";
        exit();
    }

    // Check if email already exists
    $check_email = mysqli_query($conn, "SELECT id FROM users WHERE email='$email'");
    if (mysqli_num_rows($check_email) > 0) {
        echo "<script>alert('Email already registered! Please login.'); window.location='login.php';</script>";
        exit();
    }

    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    $sql = "INSERT INTO users (fullname, email, phone, password, role, status) 
            VALUES ('$fullname', '$email', '$phone', '$hashed_password', '$role', 'active')";

    if (mysqli_query($conn, $sql)) {
        // Send Welcome Email
        $subject = "Welcome to MediCare!";
        $message = "
        <h2 style='color:#0d6efd;'>Registration Successful</h2>
        <p>Dear <strong>$fullname</strong>,</p>
        <p>Welcome to MediCare Hospital Management System. Your account has been created successfully.</p>
        <a href='https://medicare-hospital-management-system-sepia.vercel.app/login.php' 
           style='display:inline-block;background:#0d6efd;color:white;padding:12px 20px;border-radius:6px;text-decoration:none;'>
           Login Now
        </a>";

        sendEmail($email, $fullname, $subject, $message);

        echo "<script>alert('Registration Successful! Please login.'); window.location='login.php';</script>";
    } else {
        echo "<script>alert('Error registering user!'); window.location='register.php';</script>";
    }
} else {
    header("Location: register.php");
    exit();
}
?>
