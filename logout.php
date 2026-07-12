<?php
// 1. Memulai sesi agar sistem tahu sesi mana yang akan dihapus
session_start();

// 2. Menghapus semua data sesi (seperti user_id dan username)
session_unset();

// 3. Menghancurkan sesi secara permanen di server
session_destroy();

// 4. Mengarahkan pengguna kembali ke halaman Beranda (index.php) atau Login
// Anda bisa mengganti 'index.php' dengan 'login.php' jika ingin langsung ke halaman masuk
header("Location: index.php");
exit();
?>