<?php
$host     = "mysql-1dfe4216-maazahmadyousafzai557-2097.h.aivencloud.com";
$username = "avnadmin";
$password = "AVNST160Ae63CSPK9c8FP0P";
$database = "defaultdb";
$port     = 10616;

$conn = mysqli_init();
mysqli_ssl_set($conn, NULL, NULL, NULL, NULL, NULL);

if (!mysqli_real_connect($conn, $host, $username, $password, $database, $port, NULL, MYSQLI_CLIENT_SSL)) {
    die("Database Connection Failed: " . mysqli_connect_error());
}
?>
