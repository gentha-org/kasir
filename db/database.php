<?php
$hostname = "localhost";
$username = "root";
$password = "";
$db_name = "kasir";

$connections = mysqli_connect($hostname, $username, $password, $db_name);

if (!$connections) {
    die("koneksi gagal");
}
?>