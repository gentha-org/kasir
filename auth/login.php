<?php
session_start();
include '../db/database.php';

if (isset($_SESSION['id_role'])) {
    if ($_SESSION['id_role'] == 1){
        header('location: ../admin.php');
        exit();
    } elseif ($_SESSION['id_role'] == 2)  {
        header('location: ../kasir.php');
        exit();
    }
}

if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $query = "SELECT * FROM users WHERE username='$username' AND password='$password' LIMIT 1";
    $result = mysqli_query($connections, $query);
    $users = mysqli_fetch_assoc($result);

    if ($username == '' || $password == '') {
        echo"<script>alert('Silahkan Masukan Username dan Password!')</script>";
    } elseif ($users) {
        $_SESSION['username'] = $users['username'];
        $_SESSION['id_role'] = $users['id_role'];
        $_SESSION['id_user'] = $users['id'];

        if ($users['id_role'] == 1) {
            header('location: ../admin.php');
            exit();
        } elseif ($users['id_role'] == 2) {
            header('location: ../kasir.php');
            exit();
        }
    } else {
        echo"<script>alert('Password atau Username Salah!')</script>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Kasir</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .container {
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .card {
            background: white;
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
            width: 350px;
        }
        .card h4 {
            text-align: center;
            margin-bottom: 30px;
            color: #333;
            font-size: 24px;
        }
        .form-grup {
            margin-bottom: 20px;
        }
        .form-grup input {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 14px;
            outline: none;
            transition: border 0.3s;
        }
        .form-grup input:focus {
            border-color: #667eea;
        }
        button {
            width: 100%;
            padding: 12px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            cursor: pointer;
            transition: transform 0.2s;
        }
        button:hover {
            transform: scale(1.02);
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="card">
            <h4>Login Kasir</h4>
            <form action="" method="post">
                <div class="form-grup">
                    <input type="text" name="username" placeholder="Username">
                </div>
                <div class="form-grup">
                    <input type="password" name="password" placeholder="Password">
                </div>
                <button type="submit" name="login">Login</button>
            </form>
        </div>
    </div>
</body>
</html>