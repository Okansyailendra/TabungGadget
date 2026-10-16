<?php
require 'koneksi.php';
if (isset($_SESSION['user_id'])) header("Location: tabungan.php");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Masuk ke akun TabungGadget untuk melacak tabunganmu.">
    <title>Masuk | TabungGadget</title>
    <link rel="stylesheet" href="../assets/style.css">
</head>
<body style="display: flex; justify-content: center; align-items: center; min-height: 100dvh; padding: 24px;">

    <div class="card" style="width: 100%; max-width: 380px;">
        <h2>Tabung<span style="color: var(--amber);">Gadget</span></h2>
        <p style="margin: 6px 0 24px; font-size: 0.9rem;">Masuk untuk melacak tabunganmu.</p>

        <form method="POST">
            <label for="username">Username</label>
            <input id="username" type="text" name="username" placeholder="username kamu" required autocomplete="username">

            <label for="password" style="margin-top: 14px;">Kata sandi</label>
            <input id="password" type="password" name="password" placeholder="��������" required autocomplete="current-password">

            <div style="text-align: right; margin: 8px 0 4px;">
                <a href="lupa_password.php" style="color: var(--text-tertiary); font-size: 0.82rem; text-decoration: none;">
                    Lupa kata sandi?
                </a>
            </div>

            <button type="submit" name="login" style="margin-top: 16px;">Masuk</button>
        </form>

        <p style="margin-top: 18px; font-size: 0.875rem; text-align: center;">
            Belum punya akun?
            <a href="register.php" style="color: var(--amber); text-decoration: none; font-weight: 500;">Daftar</a>
        </p>

        <?php
        if (isset($_POST['login'])) {
            $user = htmlspecialchars($_POST['username']);
            $pass = $_POST['password'];
            $q = mysqli_query($conn, "SELECT * FROM users WHERE username='$user'");
            $d = mysqli_fetch_assoc($q);
            if ($d && password_verify($pass, $d['password'])) {
                $_SESSION['user_id']   = $d['id'];
                $_SESSION['username']  = $d['username'];
                echo "<script>location.href='tabungan.php';</script>";
            } else {
                echo "<p class='alert bad' style='margin-top: 16px;'>Username atau kata sandi salah.</p>";
            }
        }
        ?>
    </div>
</body>
</html>
