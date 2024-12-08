<?php

$host = "localhost";
$user = "root";
$pass = "";
$db = "donars";
$port = 3306; // Specify the port number

$con = mysqli_connect($host, $user, $pass, $db, $port);

if (!$con) {
    die('Connection Failed: ' . mysqli_connect_error());
}

?>
