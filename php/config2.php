<?php
$host = "localhost"; // Nama host server database
$user = "root"; // Username database
$pass = ""; // Password database
$db = "web_hotel"; // Nama database yang akan digunakan
// Membuat koneksi
$conn = mysqli_connect($host, $user, $pass, $db);
// Mengecek koneksi
if (!$conn) {
die("Koneksi gagal: " . mysqli_connect_error());
} else {
echo "Koneksi berhasil!";
}
?>