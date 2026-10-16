<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$host = getenv('DB_HOST') ?: "127.0.0.1";
$user = getenv('DB_USER') ?: "root";
$pass = getenv('DB_PASS') !== false ? getenv('DB_PASS') : "";
$db   = getenv('DB_NAME') ?: "tabunggadget";
$port = getenv('DB_PORT') ? (int)getenv('DB_PORT') : 3307;

// Matikan fatal exception agar bisa ditangkap dengan rapi
mysqli_report(MYSQLI_REPORT_OFF);

try {
    $conn = @mysqli_connect($host, $user, $pass, $db, $port);
} catch (Throwable $e) {
    $conn = false;
}

if (!$conn) {
    $error_msg = mysqli_connect_error() ?: 'Tidak dapat terhubung ke MySQL server';
    ?>
    <!DOCTYPE html>
    <html lang="id">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Database Belum Terhubung | TabungGadget</title>
        <link rel="stylesheet" href="../assets/style.css">
    </head>
    <body style="display:flex; justify-content:center; align-items:center; min-height:100vh; padding:20px; background:#0d0d0d; color:#f0ece4; font-family:sans-serif;">
        <div class="card" style="max-width:540px; width:100%; text-align:left; padding:32px; border:1px solid rgba(232,162,58,0.3); border-radius:12px; background:#141414;">
            <h2 style="color:var(--amber, #e8a23a); margin-bottom:12px;">⚠️ Database Belum Terhubung</h2>
            <p style="color:#aaa; line-height:1.6; margin-bottom:16px;">
                Vercel adalah serverless hosting (tidak ada MySQL lokal di dalamnya). Untuk menggunakan fitur login, registrasi, dan tabungan di Vercel, hubungkan database MySQL cloud (misal <b>TiDB Cloud, Aiven, atau Clever Cloud</b> gratis).
            </p>
            <div style="background:#1c1c1c; padding:14px; border-radius:8px; font-family:monospace; font-size:0.85rem; color:#e0e0e0; margin-bottom:20px;">
                <b>Status:</b> <?= htmlspecialchars($error_msg); ?><br><br>
                <b>Tambahkan Environment Variables di Vercel:</b><br>
                • <code>DB_HOST</code><br>
                • <code>DB_USER</code><br>
                • <code>DB_PASS</code><br>
                • <code>DB_NAME</code><br>
                • <code>DB_PORT</code>
            </div>
            <a href="index.php" style="display:inline-block; background:var(--amber, #e8a23a); color:#000; padding:10px 20px; border-radius:8px; text-decoration:none; font-weight:600;">Kembali ke Beranda</a>
        </div>
    </body>
    </html>
    <?php
    exit();
}
?>