<?php
session_start();

$id = $_GET['id'];

unset($_SESSION['keranjang'][$id]);

$_SESSION['keranjang'] = array_values($_SESSION['keranjang']);

header("location:kasir.php");
exit;
?>