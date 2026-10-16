<?php
// Aiven Database Connection Details
$host = "mysql-1dfe4216-maazahmadyousafzai557-2097.h.aivencloud.com";
$port = 10616;
$user = "avnadmin";
$pass = "AVNS_zavGy6oFha14v6sZRRB";
$db   = "defaultdb";

$conn = mysqli_init();

// Enable SSL Connection for Aiven MySQL
mysqli_ssl_set($conn, NULL, NULL, NULL, NULL, NULL);

// Connect with SSL flag
if (!@mysqli_real_connect($conn, $host, $user, $pass, $db, $port, NULL, MYSQLI_CLIENT_SSL_DONT_VERIFY_SERVER_CERT)) {
    // Fallback if SSL fails
    $conn = mysqli_connect($host, $user, $pass, $db, $port);
}

if (!$conn) {
    die("Database Connection Failed: " . mysqli_connect_error());
}
?>
