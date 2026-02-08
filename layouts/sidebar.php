<div class="sidebar">
    <h3>Welcome</h3>
    <ul>
        <?php if (isset($_SESSION['id_role']) && $_SESSION['id_role'] == 1): ?>
            <li><a href="admin.php">Dashboard</a></li>
            <li><a href="laporan.php">Laporan Penjualan</a></li>
            <li><a href="kasir.php">Kasir Panel</a></li>
            <li><a href="barang.php">Data Barang</a></li>
        <?php elseif (isset($_SESSION['id_role']) && $_SESSION['id_role'] == 2): ?>
            <li><a href="kasir.php">Kasir Panel</a></li>
            <li><a href="barang.php">Data Barang</a></li>
        <?php endif; ?>
        <?php if (isset($_SESSION['id_role'])): ?>
            <li><a href="auth/logout.php">Logout</a></li>
        <?php endif; ?>
    </ul>
</div>

<div class="content-area">