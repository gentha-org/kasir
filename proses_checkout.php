<?php
session_start();
include 'db/database.php';

if (isset($_POST['bayar'])) {
    $tgl = date('Y-m-d');
    $total = $_POST['total_hidden'];
    $uang = $_POST['uang_bayar'];
    $kembali = $uang - $total;
    $id_user = $_SESSION['id_user'] ?? 1;

    if ($uang < $total) {
        echo "<script>alert('Uang kurang!'); history.back();</script>";
        exit;
    } else {
        mysqli_query($connections, "INSERT INTO penjualan (tanggal, total_bayar, bayar, kembali, id_user) VALUES ('$tgl', '$total', '$uang', '$kembali', '$id_user')");

        $id_transaksi = mysqli_insert_id($connections);

        foreach ($_SESSION['keranjang'] as $isi) {
            $id_produk = $isi['id'];
            $qty = $isi['qty'];

            mysqli_query($connections, "INSERT INTO detail_penjualan (id_penjualan, id_produk, jumlah) VALUES ('$id_transaksi', '$id_produk', '$qty')");

            mysqli_query($connections, "UPDATE products SET stok = stok - $qty WHERE id='$id_produk'");
        }

        unset($_SESSION['keranjang']);

        header("location:kasir.php?print_id=$id_transaksi");
        exit;
    }
}
?>