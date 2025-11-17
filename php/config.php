<?php
$host = "localhost"; 
$user = "root"; 
$pass = "password"; 
$db = "web_hotel"; 


$conn = mysqli_connect($host, $user, $pass, $db, 3306);


if (!$conn) {
    
    die("Koneksi gagal: " . mysqli_connect_error());
} 

?>