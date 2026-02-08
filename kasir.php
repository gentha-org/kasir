<?php
session_start();

if (!isset($_SESSION['id_role']) || ($_SESSION['id_role'] != 1 && $_SESSION['id_role'] != 2)) {
    header('location: auth/login.php');
    exit;
}

include 'db/database.php';

if (!isset($_SESSION['keranjang'])) {
    $_SESSION['keranjang'] = [];
}

if (isset($_POST['tambah'])) {
    $id_produk = $_POST['id_produk'];
    $qty = $_POST['qty'];

    if ($qty <= 0) {
        header("location:kasir.php");
        exit;
    }

    $cek_stok = mysqli_query($connections, "SELECT stok FROM products WHERE id='$id_produk'");
    $stok_data = mysqli_fetch_assoc($cek_stok);

    if ($qty > $stok_data['stok']) {
        echo "<script>alert('Stok tidak mencukupi!'); history.back();</script>";
        exit;
    }

    $sql = mysqli_query($connections, "SELECT * FROM products WHERE id='$id_produk'");
    $d = mysqli_fetch_assoc($sql);

    $found = false;
    foreach ($_SESSION['keranjang'] as $key => $item) {
        if ($item['id'] == $id_produk) {
            $_SESSION['keranjang'][$key]['qty'] += $qty;
            $found = true;
            break;
        }
    }

    if (!$found) {
        $barang = [
            'id' => $d['id'],
            'nama' => $d['nama_barang'],
            'harga' => $d['harga'],
            'qty' => $qty
        ];
        $_SESSION['keranjang'][] = $barang;
    }

    header("location:kasir.php");
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
    }

    .tabel-barang {
        flex: 1;
        max-height: 650px;
        overflow-y: auto;
    }

    table {
        width: 100%;
        border-collapse: collapse;
    }

    thead {
        background: #f1f4fb;
        position: sticky;
        top: 0;
    }

    th,
    td {
        padding: 12px;
        text-align: left;
        border-bottom: 1px solid #eee;
        color: black;
    }

    .btn-tambah {
        background-color: var(--success);
        cursor: pointer;
        border: none;
        padding: 8px 15px;
        border-radius: 5px;
        color: white;
    }

    .btn-hapus {
        background-color: var(--danger);
        cursor: pointer;
        text-decoration: none;
        padding: 5px 10px;
        border-radius: 5px;
        color: white;
        font-size: 12px;
    }

    .card-transaksi {
        box-shadow: var(--shadow);
        padding: 20px;
        width: 40%;
    }

    .daftar {
        color: grey;
        display: flex;
        justify-content: center;
        margin-top: 5%;
    }

    hr {
        margin: 15px 0;
        border: none;
        border-top: 1px dashed #ccc;
    }

    .total-harga {
        display: flex;
        justify-content: space-between;
        font-size: 18px;
        font-weight: bold;
        margin: 15px 0;
    }

    .isi-transaksi {
        max-height: 350px;
        overflow-y: auto;
    }

    .keranjang-table {
        width: 100%;
        font-size: 14px;
    }

    .keranjang-table th {
        background: #f1f4fb;
    }

    .form-bayar {
        margin-top: 15px;
    }

    .form-bayar input {
        width: 100%;
        padding: 10px;
        border: 1px solid #ddd;
        border-radius: 5px;
        margin-bottom: 10px;
        font-size: 16px;
    }

    .btn-checkout {
        background-color: var(--success);
        width: 100%;
        padding: 12px;
        border: none;
        border-radius: 5px;
        color: white;
        font-size: 16px;
        cursor: pointer;
    }

    .btn-checkout:hover {
        opacity: 0.9;
    }

    .input-qty {
        width: 60px;
        padding: 5px;
        border: 1px solid #ddd;
        border-radius: 3px;
        text-align: center;
    }

    .form-tambah {
        display: flex;
        gap: 5px;
        align-items: center;
    }

    @media print {
        body * {
            visibility: hidden;
        }

        .struk-print,
        .struk-print * {
            visibility: visible;
        }

        .struk-print {
            position: absolute;
            left: 0;
            top: 0;
            width: 100%;
        }

        .no-print {
            display: none !important;
        }
    }

    .struk-print {
        display: none;
        max-width: 300px;
        background: white;
        padding: 20px;
        border: 1px dashed #ccc;
        font-family: 'Courier New', monospace;
    }

    <?php if (isset($_GET['print_id'])): ?>
        .struk-print {
            display: block;
            margin: 20px auto;
        }

    <?php endif; ?>

    .header-struk {
        text-align: center;
        border-bottom: 1px dashed #000;
        padding-bottom: 10px;
        margin-bottom: 10px;
    }

    .item-struk {
        margin-bottom: 8px;
        font-size: 12px;
    }

    .item-struk-detail {
        display: flex;
        justify-content: space-between;
    }

    .footer-struk {
        border-top: 1px dashed #000;
        padding-top: 10px;
        margin-top: 10px;
    }

    .row-struk {
        display: flex;
        justify-content: space-between;
        font-size: 14px;
        margin-bottom: 5px;
    }
</style>

<div class="container no-print">
    <div class="tabel-barang">
        <h3>Daftar Barang</h3>
        <table>
            <thead>
                <tr>
                    <th>Nama Barang</th>
                    <th>Harga</th>
                    <th>Stok</th>
                    <th>Jumlah</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = mysqli_fetch_assoc($result_product)) { ?>
                    <tr>
                        <td><?= htmlspecialchars($row['nama_barang']) ?></td>
                        <td>Rp. <?= number_format($row['harga'], 0, ",", ".") ?></td>
                        <td><?= $row['stok'] ?></td>
                        <td colspan="2">
                            <form method="POST" action="" class="form-tambah">
                                <input type="hidden" name="id_produk" value="<?= $row['id'] ?>">
                                <input type="number" name="qty" value="1" min="1" max="<?= $row['stok'] ?>"
                                    class="input-qty">
                                <button type="submit" name="tambah" class="btn-tambah" <?= $row['stok'] <= 0 ? 'disabled' : '' ?>>+</button>
                            </form>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>

    <div class="card-transaksi">
        <h3>Daftar Pembelian</h3>
        <hr>
        <div class="isi-transaksi">
            <?php if (empty($_SESSION['keranjang'])): ?>
                <p class="daftar">Belum ada barang dipilih</p>
            <?php else: ?>
                <table class="keranjang-table">
                    <thead>
                        <tr>
                            <th>Nama</th>
                            <th>Harga</th>
                            <th>Jumlah</th>
                            <th>Subtotal</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $total_bayar = 0;
                        foreach ($_SESSION['keranjang'] as $key => $isi) {
                            $subtotal = $isi['harga'] * $isi['qty'];
                            $total_bayar += $subtotal;
                            ?>
                            <tr>
                                <td><?= htmlspecialchars($isi['nama']) ?></td>
                                <td>Rp <?= number_format($isi['harga'], 0, ',', '.') ?></td>
                                <td><?= $isi['qty'] ?></td>
                                <td>Rp <?= number_format($subtotal, 0, ',', '.') ?></td>
                                <td><a href="hapus_keranjang.php?id=<?= $key ?>" class="btn-hapus">Batal</a></td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>
        <hr>

        <div class="total-harga">
            <span>Total Harga</span>
            <span>Rp <?= number_format($total_bayar ?? 0, 0, ',', '.') ?></span>
        </div>

        <?php if (!empty($_SESSION['keranjang'])): ?>
            <form action="proses_checkout.php" method="POST" class="form-bayar">
                <input type="hidden" name="total_hidden" value="<?= $total_bayar ?>">
                <input type="number" name="uang_bayar" placeholder="Masukkan uang bayar" required min="<?= $total_bayar ?>">
                <button type="submit" name="bayar" class="btn-checkout" onclick="return confirm('Proses pembayaran?')">
                    Bayar Sekarang
                </button>
            </form>
        <?php else: ?>
            <button class="btn-checkout" disabled style="opacity: 0.5; cursor: not-allowed;">
                Keranjang Kosong
            </button>
        <?php endif; ?>
    </div>
</div>

<?php
if (isset($_GET['print_id'])) {
    $id = $_GET['print_id'];
    $transaksi = mysqli_fetch_assoc(mysqli_query($connections, "SELECT * FROM penjualan WHERE id_penjualan='$id'"));
    $detail = mysqli_query($connections, "SELECT dp.*, p.nama_barang, p.harga FROM detail_penjualan dp JOIN products p ON dp.id_produk = p.id WHERE dp.id_penjualan='$id'");
    if ($transaksi) {
        ?>
        <div class="struk-print">
            <div class="header-struk">
                <h3>TOKO KASIR</h3>
                <p>Tanggal: <?= $transaksi['tanggal'] ?></p>
                <p>No. Transaksi: <?= str_pad($id, 5, '0', STR_PAD_LEFT) ?></p>
            </div>
            <div class="items-struk">
                <?php while ($d = mysqli_fetch_assoc($detail)) { ?>
                    <div class="item-struk">
                        <div style="font-weight:bold"><?= $d['nama_barang'] ?></div>
                        <div class="item-struk-detail">
                            <span><?= $d['jumlah'] ?> x Rp <?= number_format($d['harga'], 0, ',', '.') ?></span>
                            <span>Rp <?= number_format($d['jumlah'] * $d['harga'], 0, ',', '.') ?></span>
                        </div>
                    </div>
                <?php } ?>
            </div>
            <div class="footer-struk">
                <div class="row-struk" style="font-weight:bold">
                    <span>Total</span>
                    <span>Rp <?= number_format($transaksi['total_bayar'], 0, ',', '.') ?></span>
                </div>
                <div class="row-struk">
                    <span>Bayar</span>
                    <span>Rp <?= number_format($transaksi['bayar'], 0, ',', '.') ?></span>
                </div>
                <div class="row-struk">
                    <span>Kembali</span>
                    <span>Rp <?= number_format($transaksi['kembali'], 0, ',', '.') ?></span>
                </div>
            </div>
            <div style="text-align:center; margin-top:15px; font-size:12px;">
                <p>Terima Kasih</p>
                <button class="no-print" onclick="window.location.href='kasir.php'"
                    style="margin-top:10px; padding:5px 10px; cursor:pointer;">Selesai</button>
            </div>
        </div>
        <script>
            window.print();
        </script>
        <?php
    }
}
?>