<?php require 'koneksi.php'; 
if(isset($_SESSION['user_id'])) header("Location: tabungan.php"); // Redirect kalau sudah login
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <title>Daftar Akun | TabungGadget</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body style="display:flex; justify-content:center; align-items:center; height:100vh;">
    <div class="card" style="width:100%; max-width:400px; text-align:center;">
        <h2>Daftar Akun</h2>
        <form method="POST">
            <input type="text" name="username" placeholder="Username Baru" required>
            
            <input type="email" name="email" placeholder="Email (Untuk Pemulihan Akun)" required>
            
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit" name="daftar">Buat Akun</button>
        </form>
        <p style="margin-top:15px; font-size:0.9rem;">Sudah punya akun? <a href="login.php" style="color:var(--primary);">Login disini</a></p>
        
        <?php
        if(isset($_POST['daftar'])){
            $user = htmlspecialchars($_POST['username']);
            $email = htmlspecialchars($_POST['email']); // Tangkap data email
            $pass = password_hash($_POST['password'], PASSWORD_DEFAULT); // Enkripsi password

            // Cek username kembar
            $cek = mysqli_query($conn, "SELECT * FROM users WHERE username='$user'");
            if(mysqli_num_rows($cek) > 0){
                echo "<p class='alert bad'>Username sudah dipakai!</p>";
            } else {
                // TAMBAHAN: Masukkan email ke dalam query INSERT
                $q = mysqli_query($conn, "INSERT INTO users (username, email, password) VALUES ('$user', '$email', '$pass')");
                if($q) {
                    echo "<script>alert('Berhasil daftar! Silakan login.'); location.href='login.php';</script>";
                } else {
                    echo "<p class='alert bad'>Gagal! Pastikan kolom email sudah ada di database.</p>";
                }
            }
        }
        ?>
    </div>
</body>
</html>