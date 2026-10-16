<?php
session_start();

$host = getenv('DB_HOST') ?: "localhost";
$user = getenv('DB_USER') ?: "root";
$pass = getenv('DB_PASS') !== false ? getenv('DB_PASS') : "";
$db   = getenv('DB_NAME') ?: "tabunggadget";
$port = getenv('DB_PORT') ? (int)getenv('DB_PORT') : 3307;

$conn = @mysqli_connect($host, $user, $pass, $db, $port);

if(!$conn){
    die("❌ Koneksi database gagal: " . mysqli_connect_error());
}
?>