<?php
$host     = "localhost";
$user     = "root";
$password = "";
$database = "koperasi_sejahtera";

$conn = mysqli_connect($host, $user, $password, $database);

if (!$conn) {
    die("Koneksi database gagal: " . mysqli_connect_error());
}

// optional: hilangkan pesan error saat online
// error_reporting(0);
?>
