<?php
$host     = "sql111.infinityfree.com";
$username = "if0_41426103";
$password = "adamsfajar1996"; // Wajib diisi!
$database = "if0_41426103_guild";

$conn = mysqli_connect($host, $username, $password, $database);

if (!$conn) {
    die("Koneksi Database Gagal: " . mysqli_connect_error());
}
?>