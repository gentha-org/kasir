<?php
include 'db/database.php';

if (isset($_POST['edit'])) {
    $id = intval($_POST['id']);
    $nama = mysqli_real_escape_string($connections, $_POST['nama']);
    $username = mysqli_real_escape_string($connections, $_POST['username']);
    $password = mysqli_real_escape_string($connections, $_POST['password']);
    $role = intval($_POST['role']);

    $data = "UPDATE users SET nama='$nama', username='$username', password='$password', id_role='$role' WHERE id=$id";
    mysqli_query($connections, $data);
    header('location: admin.php?msg=updated');
    exit();
}

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $query = "SELECT * FROM users WHERE id = $id";
    $result = mysqli_query($connections, $query);
    $user = mysqli_fetch_assoc($result);

    if (!$user) {
        header('location: admin.php');
        exit();
    }
} else {
    header('location: admin.php');
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
        <h3 style="color: black;">Edit User</h3>
        <form action="" method="post">
            <input type="hidden" name="id" value="<?= $user['id'] ?>">
            <div class="form-grup">
                <input type="text" name="nama" placeholder="nama" value="<?= $user['nama'] ?>">
            </div>
            <div class="form-grup">
                <input type="text" name="username" placeholder="username" value="<?= $user['username'] ?>">
            </div>
            <div class="form-grup">
                <input type="password" name="password" placeholder="password" value="<?= $user['password'] ?>">
            </div>
            <div class="form-grup">
                <select name="role">
                    <option value="1">Admin</option>
                    <option value="2">Kasir</option>
                </select>
            </div>
            <button type="submit" name="edit" onclick="return confirm('Apakah Anda Yakin?')">Edit Data</button>
        </form>
    </div>
</div>