<?php
session_start();

$host = "localhost";
$user = "root";
$pass = "";
$db   = "tabunggadget";
$port = 3307; // Tambahkan variabel port di sini

// Masukkan variabel $port sebagai parameter ke-5
$conn = mysqli_connect($host, $user, $pass, $db, $port);

if(!$conn){
    die("❌ Koneksi database gagal: " . mysqli_connect_error());
}
?>