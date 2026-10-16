<?php
// Aiven Database Connection Details
$host = "mysql-1dfe4216-maazahmadyousafzai557-2097.h.aivencloud.com";
$port = 10616;
$user = "avnadmin";
$pass = "AVNS_zavGy6oFha14v6sZRRB"; // Make sure there are NO spaces inside the quotes
$db   = "defaultdb";

// Initialize MySQLi
$conn = mysqli_init();

// Set timeout to prevent hanging
mysqli_options($conn, MYSQLI_OPT_CONNECT_TIMEOUT, 10);

// Aiven requires SSL, so we set the flag to use SSL but bypass certificate verification (since Vercel doesn't have the CA file natively)
mysqli_ssl_set($conn, NULL, NULL, NULL, NULL, NULL);

// Attempt connection
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
    // If it fails, print the exact reason
    die("Database Connection Failed: " . mysqli_connect_error());
}
?>
