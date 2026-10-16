<?php
session_start();
include __DIR__ . '/connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email    = mysqli_real_escape_string($conn, $_POST['email']);
    $password = $_POST['password'];

    $sql    = "SELECT * FROM users WHERE email='$email'";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) === 1) {
        $user = mysqli_fetch_assoc($result);

        if ($user['status'] === 'blocked' || $user['status'] === 'pending') {
            echo "<script>alert('Account is either pending approval or blocked.'); window.location='login.php';</script>";
            exit();
        }

        if (password_verify($password, $user['password'])) {
            // Normalize role to lowercase
            $db_role = strtolower(trim($user['role']));

            $_SESSION['user_id']   = $user['id'];
            $_SESSION['fullname']  = $user['fullname'];
            $_SESSION['user_name'] = $user['fullname'];
            $_SESSION['email']     = $user['email'];
            $_SESSION['user_role'] = $db_role;
            $_SESSION['role']      = $db_role;

            // Redirection based on database role
            if ($db_role === 'admin') {
                header("Location: admin_dashboard.php");
            } elseif ($db_role === 'doctor') {
                header("Location: doctor_dashboard.php");
            } else {
                header("Location: dashboard.php");
            }
            exit();
        } else {
            echo "<script>alert('Invalid password!'); window.location='login.php';</script>";
            exit();
        }
    } else {
        echo "<script>alert('User not found!'); window.location='login.php';</script>";
        exit();
    }
} else {
    header("Location: login.php");
    exit();
}
?>
