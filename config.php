<?php
$host     = "hostname";
$username = "username";
$password = "password"; // Wajib diisi!
$database = "database";

$conn = mysqli_connect($host, $username, $password, $database);

if (!$conn) {
    die("Koneksi Database Gagal: " . mysqli_connect_error());
}
?>