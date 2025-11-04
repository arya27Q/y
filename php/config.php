<?php
$host = "localhost";
$user = "root";
$pass = "";
$db = "web_hotel";

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
  die("Koneksi gagal: " . $conn->connect_error);
} else {
echo "Koneksi berhasil!";
}
 else {
 echo "Koneksi berhasil!";
 }
?>
