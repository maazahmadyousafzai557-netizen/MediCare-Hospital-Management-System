<?php
$servername = "mysql-1dfe4216-maazahmadyousafzai557-2097.h.aivencloud.com";
$username = "avnadmin";
$password = "AVNS_Ti6OAe63CSPK9c8FP0P";
$dbname = "defaultdb";
$port = 10616;

$conn = mysqli_init();
mysqli_ssl_set($conn, NULL, NULL, NULL, NULL, NULL);
mysqli_real_connect($conn, $servername, $username, $password, $dbname, $port, NULL, MYSQLI_CLIENT_SSL);

if (!$conn) {
    die("Database Connection Failed: " . mysqli_connect_error());
}
?>