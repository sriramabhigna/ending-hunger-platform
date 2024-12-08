<?php
$host = "localhost";  // Server host name
$user = "root";       // Username for the MySQL database
$pass = "";           // Password for the MySQL database
$port = 3306;         // MySQL port number

// Connection to the first database (login)
$db1 = "login";
$conn1 = new mysqli($host, $user, $pass, $db1, $port);
if ($conn1->connect_error) {
    die("Failed to connect to the login database: " . $conn1->connect_error);
}

// Connection to the second database (donars)
$db2 = "donars";
$conn2 = new mysqli($host, $user, $pass, $db2, $port);
if ($conn2->connect_error) {
    die("Failed to connect to the donars database: " . $conn2->connect_error);
}
?>
