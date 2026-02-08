<?php
session_start();

if (!isset($_SESSION['id_role']) || ($_SESSION['id_role'] != 1 && $_SESSION['id_role'] != 2)) {
    header('location: auth/login.php');
}

include 'db/database.php';
if (isset($_POST['tambah'])) {
    $namaBarang = $_POST['nama_barang'];
    $hargaBarang = $_POST['harga'];
    $stokBarang = $_POST['stok'];
    $tanggalbeli = $_POST['tanggal_beli'];

    $data_barang = "INSERT INTO products (nama_barang, harga, stok, tanggal_beli) VALUES ('$namaBarang', '$hargaBarang', '$stokBarang', '$tanggalbeli')";
    $result_tambah = mysqli_query($connections, $data_barang);

    if ($result_tambah) {
        header('Location: barang.php');
        exit;
    }
}

if (isset($_GET['hapus'])) {
    $id = ($_GET['hapus']);
    mysqli_query($connections, "DELETE FROM products WHERE id=$id");
    header('Location: barang.php');
    exit;
}

$products = "SELECT * FROM products ORDER BY id ASC";
$result_product = mysqli_query($connections, $products);

include 'layouts/header.php';
include 'layouts/sidebar.php';
?>

<style>
    .container {
        display: flex;
        background-color: white;
        box-shadow: var(--shadow);
        padding: 20px;
        gap: 20px;
        align-items: flex-start;
    }

    .tabel-barang {
        flex: 1;
        max-height: 650px;
        overflow-y: auto;
    }

    table {
        width: 100%;
        position: sticky;
        top: 0;
    }

    thead {
        background: #f1f4fb;
    }


    th,
    td {
        padding: 12px;
        text-align: left;
        border-bottom: 1px solid #eee;
        color: black;
    }

    .btn-edit {
        background-color: var(--warning);
    }

    .btn-hapus {
        background-color: var(--danger);
    }

    .card-barang {
        box-shadow: var(--shadow);
        padding: 20px;
        width: 35%;
    }

    button {
        border: none;
        padding: 8px;
        border-radius: 5px;
        color: white;
    }

    .daftar {
        color: grey;
        display: flex;
        justify-content: center;
        margin-top: 5%;
    }

    hr {
        margin-top: 7%;
    }

    .total-harga {
        display: flex;
        justify-content: space-between;
    }

    .form-grup {
        margin-bottom: 15px;
    }

    .form-grup input,
    select {
        width: 97%;
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
</style>

<div class="container">
    <div class="tabel-barang">
        <table>
            <thead>
                <h3>Daftar Barang</h3>
                <tr>
                    <td>Nama Barang</td>
                    <td>Harga</td>
                    <td>Stok</td>
                    <td>Tanggal Beli</td>
                    <td>Aksi</td>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = mysqli_fetch_assoc($result_product)) {
                    echo "<tr>";
                    echo "<td>" . $row['nama_barang'] . "</td>";
                    echo "<td> Rp. " . number_format($row['harga'], 0, ",", ".") . "</td>";
                    echo "<td>" . $row['stok'] . "</td>";
                    echo "<td>" . $row['tanggal_beli'] . "</td>";
                    echo "<td>
                         <a href='editBarang.php?id=" . $row['id'] . "'> 
                               <button class='btn-edit'>edit</button>
                         </a>
                            <button class='btn-hapus' onclick='hapusUsers(" . $row['id'] . ")'>hapus</button>                        
                        </td>";
                    echo "</tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

    <div class="card-barang">
        <h3>Tambah Barang</h3>
        <form action="" method="post" name="tambahBarang">
            <div class="form-grup">
                <label for="namaBarang">Nama Barang: </label>
                <input type="text" name="nama_barang" placeholder="Masukan Nama Barang" required>
            </div>
            <div class="form-grup">
                <label for="hargaBarang">Harga Barang: </label>
                <input type="number" name="harga" placeholder="Masukan Harga Barang" required>
            </div>
            <div class="form-grup">
                <label for="stokBarang">Stok Barang: </label>
                <input type="number" name="stok" placeholder="Masukan Stok Barang" required>
            </div>
            <div class="form-grup">
                <label for="namaBarang">Tanggal Beli: </label>
                <input type="date" name="tanggal_beli">
            </div>
            <button type="submit" name="tambah" style="width: 100%; background-color: var(--success);">Tambah
                Barang</button>
        </form>
    </div>
</div>

<script>
    function hapusUsers(id) {
        if (confirm("apakah anda yakin? "))
            window.location.href = 'barang.php?hapus=' + id;
    }
</script>