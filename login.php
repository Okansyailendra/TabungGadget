<?php require 'koneksi.php'; 
if(isset($_SESSION['user_id'])) header("Location: tabungan.php"); // Redirect kalau sudah login
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | TabungGadget</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body style="display:flex; justify-content:center; align-items:center; height:100vh;">
    <div class="card" style="width:100%; max-width:400px; text-align:center;">
        <h2>Masuk Tabungan</h2>
        <form method="POST">
            <input type="text" name="username" placeholder="Username" required>
            <input type="password" name="password" placeholder="Password" required>
            
            <div style="text-align: right; margin: 5px 0 15px 0;">
                <a href="lupa_password.php" style="color: var(--primary); font-size: 0.85rem; text-decoration: none;">Lupa kata sandi?</a>
            </div>

            <button type="submit" name="login">Masuk</button>
        </form>
        <p style="margin-top:15px; font-size:0.9rem;">Belum punya akun? <a href="register.php" style="color:var(--primary);">Daftar disini</a></p>
        
        <?php
        if(isset($_POST['login'])){
            $user = htmlspecialchars($_POST['username']);
            $pass = $_POST['password'];

            $q = mysqli_query($conn, "SELECT * FROM users WHERE username='$user'");
            $d = mysqli_fetch_assoc($q);

            if($d && password_verify($pass, $d['password'])){
                $_SESSION['user_id'] = $d['id'];
                $_SESSION['username'] = $d['username'];
                echo "<script>location.href='tabungan.php';</script>";
            } else {
                echo "<p class='alert bad'>Username atau Password salah!</p>";
            }
        }
        ?>
    </div>
</body>
</html>