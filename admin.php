<?php
session_start();

if (!isset($_SESSION['id_role']) || $_SESSION['id_role'] != 1) {
    header('location: auth/login.php');
    exit;
}

include('db/database.php');

if (isset($_POST['tambah'])) {
    $nama = $_POST['nama'] ?? '';
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';
    $role = $_POST['role'] ?? '';

    $data = "INSERT INTO users (nama, username, password, id_role) VALUES ('$nama', '$username', '$password', '$role')";
    $result = mysqli_query($connections, $data);

    if ($result) {
        header("location: admin.php");
        exit;
    }
}

if (isset($_GET['hapus'])) {
    $id = intval($_GET['hapus']);
    mysqli_query($connections, "DELETE FROM users WHERE id=$id");
    header('location:admin.php?msg=deleted');
    exit();
}

include 'layouts/header.php';
include 'layouts/sidebar.php';
?>

<style>
    .card {
        background: #fff;
        padding: 20px;
        border-radius: 10px;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.08);
        margin-bottom: 20px;
        margin-top: 20px;
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

    .tabel-users {
        background: #fff;
        padding: 20px;
        border-radius: 10px;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.08);
    }

    table {
        width: 100%;
        border-collapse: collapse;
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

    .btn-group {
        display: flex;
        gap: 5px;
    }

    .btn-edit {
        background-color: var(--warning);
        padding: 6px 3px;
        font-size: 13px;
    }

    .btn-delete {
        background-color: var(--danger);
        padding: 6px 12px;
        font-size: 13px;
    }
</style>

<div class="card">
    <h3 style="color: black;">Tambah Users</h3>
    <form action="" method="post">
        <div class="form-grup">
            <input type="text" name="nama" placeholder="nama" required>
        </div>
        <div class="form-grup">
            <input type="text" name="username" placeholder="username" required>
        </div>
        <div class="form-grup">
            <input type="password" name="password" placeholder="password" required>
        </div>
        <div>
            <select name="role">
                <option value="1">Admin</option>
                <option value="2">Kasir</option>
            </select>
        </div>
        <button type="submit" name="tambah">Tambah</button>
    </form>
</div>


<div class="tabel-users">
    <table style="width: 100%; max-width: none; margin: 0;">
        <thead>
            <tr>
                <th style="color: black;">Nama</th>
                <th style="color: black;">Username</th>
                <th style="color: black;">Password</th>
                <th style="color: black;">Role</th>
                <th style="color: black;">Action</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $data = "SELECT * FROM users";
            $result = mysqli_query($connections, $data);

            while ($row = mysqli_fetch_assoc($result)) {
                $nama_role = "SELECT role FROM role WHERE id = " . $row['id_role'];
                $result_role = mysqli_query($connections, $nama_role);
                $role = mysqli_fetch_assoc($result_role);
                ?>
                <tr>
                    <td><?= $row['nama']; ?></td>
                    <td><?= $row['username']; ?></td>
                    <td><?= $row['password']; ?></td>
                    <td><?= $role['role']; ?></td>
                    <td>
                        <div class="btn-group">
                            <button type="button" class="btn-edit" onclick="editUser(<?= $row['id'] ?>)">Edit</button>
                            <button type="button" class="btn-delete" onclick="hapusUser(<?= $row['id'] ?>)">Hapus</button>
                        </div>
                    </td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</div>

<script>
    function editUser(id) {
        window.location.href = 'editUser.php?id=' + id;
    }

    function hapusUser(id) {
        if (confirm('Apakah Anda Yakin?')) {
            window.location.href = 'admin.php?hapus=' + id;
        }
    }
</script>