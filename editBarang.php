<?php
include 'db/database.php';

if (isset($_POST['edit'])) {
    $id = intval($_POST['id']);
    $namaBarang = mysqli_real_escape_string($connections, $_POST['nama_barang']);
    $hargaBarang = intval($_POST['harga']);
    $stokBarang = intval($_POST['stok']);
    $tanggalBeli = mysqli_real_escape_string($connections, $_POST['tanggal_beli']);

    $data = "UPDATE products SET nama_barang='$namaBarang', harga='$hargaBarang', stok='$stokBarang', tanggal_beli='$tanggalBeli' WHERE id=$id";
    mysqli_query($connections, $data);
    header('Location: barang.php');
    exit;
}

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $queryData = "SELECT * FROM products WHERE id=$id";
    $result = mysqli_query($connections, $queryData);
    $products = mysqli_fetch_assoc($result);
}

if (!$products) {
    header('Location: barang.php');
    exit();
}


include 'layouts/header.php';
?>

<style>
    .card {
        background: #fff;
        padding: 10px;
        border-radius: 10px;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.08);
        margin-bottom: 20px;
        width: 40%;
        margin-left: 200px;
        margin-top: 150px;
    }

    .card-body {
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        padding: 20px;
    }

    .form-grup {
        margin-bottom: 8px;
        width: 120%;
    }

    .form-grup input,
    select {
        padding: 10px;
        border-radius: 6px;
        border: 1px solid #dcebf8;
        outline: none;
    }

    .form-grup input:focus,
    select:focus {
        border-color: var(--primary);
    }


    input {
        border-radius: 5px;
        border: 1px solid #dcebf8;
    }

    button {
        width: 100%;
        background-color: var(--primary);
        color: white;
        border: none;
        padding: 10px;
        border-radius: 8px;
        cursor: pointer;
        margin-top: 20px;
    }
</style>

<div class="card">
    <div class="card-body">
        <h3 style="color: black;">Edit Product</h3>
        <form action="" method="post">
            <input type="hidden" name="id" value="<?= $products['id'] ?>">
            <div class="form-grup">
                <input type="text" name="nama_barang" placeholder="NamaBarang" value="<?= $products['nama_barang'] ?>">
            </div>
            <div class="form-grup">
                <input type="number" name="harga" placeholder="Harga Barang" value="<?= $products['harga'] ?>">
            </div>
            <div class="form-grup">
                <input type="number" name="stok" placeholder="Stok Barang" value="<?= $products['stok'] ?>">
            </div>
            <div class="form-grup">
                <input type="date" name="tanggal_beli" placeholder="Tanggal Beli" value="<?= $products['tanggal_beli'] ?>">
            </div>
            <button type="submit" name="edit" onclick="return confirm('Apakah Anda Yakin?')">Edit Products</button>
        </form>
    </div>
</div>