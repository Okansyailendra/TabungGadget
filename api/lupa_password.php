<?php
session_start();
require 'koneksi.php';

$pesan_error = "";
$pesan_sukses = "";
$pesan_testing = ""; // Khusus untuk localhost

if (isset($_POST['reset_akun'])) {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);

    // Cek apakah username dan email cocok
    $query = mysqli_query($conn, "SELECT id FROM users WHERE username='$username' AND email='$email'");
    
    if (mysqli_num_rows($query) > 0) {
        $user = mysqli_fetch_assoc($query);
        $id = $user['id'];

        // 1. Buat kata sandi acak (8 karakter)
        $password_baru = substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 8);
        
        // 2. Enkripsi password baru untuk disimpan di database
        $password_hashed = password_hash($password_baru, PASSWORD_DEFAULT);
        
        // 3. Update password di database
        $update = mysqli_query($conn, "UPDATE users SET password='$password_hashed' WHERE id='$id'");
        
        if ($update) {
            // 4. Proses Pengiriman Email
            $to = $email;
            $subject = "Reset Kata Sandi - TabungGadget";
            $message = "Halo $username,\n\nKami menerima permintaan untuk mereset kata sandi akun TabungGadget Anda.\n\nKata sandi baru Anda adalah: $password_baru\n\nSilakan login menggunakan kata sandi baru tersebut dan segera ubah di menu pengaturan demi keamanan.\n\nTerima kasih,\nTim TabungGadget";
            $headers = "From: noreply@tabunggadget.com";

            // Coba kirim email
            $kirim_email = @mail($to, $subject, $message, $headers);

            if ($kirim_email) {
                $pesan_sukses = "Berhasil! Kata sandi baru telah dikirim ke email Anda. Silakan cek Inbox atau folder Spam.";
            } else {
                // Jika gagal (biasanya karena limitasi localhost/XAMPP)
                $pesan_sukses = "Kata sandi berhasil direset di sistem.";
                $pesan_testing = "Gagal mengirim email (konfigurasi SMTP Localhost belum diatur). <br><br><b>UNTUK TESTING LOKAL:</b><br>Kata Sandi Baru Anda adalah: <span style='color:#4da6ff; font-size:1.2rem;'>$password_baru</span>";
            }
        } else {
            $pesan_error = "Terjadi kesalahan pada sistem. Gagal mereset kata sandi.";
        }
    } else {
        $pesan_error = "Verifikasi Gagal! Username atau Email tidak ditemukan/tidak cocok.";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lupa Kata Sandi | TabungGadget</title>
    <link rel="stylesheet" href="../assets/style.css">
    <style>
        /* CSS FIX DROPDOWN/INPUT GELAP */
        input[type="text"], input[type="email"] {
            background-color: #1e293b !important;
            color: #ffffff !important;
            border: 1px solid rgba(255, 255, 255, 0.2) !important;
            padding: 12px;
            border-radius: 8px;
            width: 100%;
            outline: none;
            margin-bottom: 10px;
        }
        ::placeholder { color: rgba(255, 255, 255, 0.4) !important; }

        .auth-container { max-width: 400px; margin: 80px auto; padding: 20px; }
        .alert-error { background: rgba(248, 113, 113, 0.2); color: #fca5a5; padding: 15px; border-radius: 8px; border: 1px solid #f87171; margin-bottom: 15px; text-align: center; }
        .alert-success { background: rgba(74, 222, 128, 0.2); color: #86efac; padding: 15px; border-radius: 8px; border: 1px solid #4ade80; margin-bottom: 15px; text-align: center; }
        .alert-testing { background: rgba(250, 204, 21, 0.1); color: #fef08a; padding: 15px; border-radius: 8px; border: 1px dashed #facc15; margin-bottom: 15px; text-align: center; }
    </style>
</head>
<body>

<div class="container auth-container">
    <section class="card">
        <div style="text-align: center; margin-bottom: 20px;">
            <h2>🔐 Pemulihan Akun</h2>
            <p>Tabung<span>Gadget</span></p>
        </div>

        <?php if ($pesan_error): ?>
            <div class="alert-error"><?php echo $pesan_error; ?></div>
        <?php endif; ?>

        <?php if ($pesan_sukses): ?>
            <div class="alert-success"><?php echo $pesan_sukses; ?></div>
            <?php if ($pesan_testing): ?>
                <div class="alert-testing"><?php echo $pesan_testing; ?></div>
            <?php endif; ?>
            <a href="login.php"><button style="margin-top:10px;">Kembali ke Login</button></a>
        <?php else: ?>
            <p style="text-align:center; opacity:0.8; font-size:0.9rem; margin-bottom:20px;">
                Masukkan Username dan Email. Kami akan mengirimkan kata sandi baru ke email Anda.
            </p>
            <form method="POST">
                <input type="text" name="username" placeholder="Username Anda" required>
                <input type="email" name="email" placeholder="Email Terdaftar" required>
                <button type="submit" name="reset_akun" style="margin-top:10px;">Kirim Kata Sandi Baru</button>
            </form>
            <div style="text-align:center; margin-top:20px;">
                <a href="login.php" style="color:var(--primary); text-decoration:none;">⬅ Batal & Kembali ke Login</a>
            </div>
        <?php endif; ?>

    </section>
</div>

</body>
</html>