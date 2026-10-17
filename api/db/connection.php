<?php
// Aiven Database Connection Details
$host = "mysql-1dfe4216-maazahmadyousafzai557-2097.h.aivencloud.com";
$port = 10616;
$user = "avnadmin";
$pass = "AVNS_hiw64noz2J6_KL3ws6H"; 
$db   = "defaultdb";

// Initialize Connection
$conn = mysqli_init();

// Set timeouts
mysqli_options($conn, MYSQLI_OPT_CONNECT_TIMEOUT, 10);

// Set SSL flag (Very important for Aiven Cloud)
mysqli_ssl_set($conn, NULL, NULL, NULL, NULL, NULL);

// Connect
$connected = @mysqli_real_connect(
    $conn, 
    $host, 
    $user, 
    $pass, 
    $db, 
    $port, 
    NULL, 
    MYSQLI_CLIENT_SSL_DONT_VERIFY_SERVER_CERT
);

if (!$connected) {
    die("Database Connection Failed: " . mysqli_connect_error());
}
?>
