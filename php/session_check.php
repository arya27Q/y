<?php
// Cek dulu: Kalau belum ada session, baru dinyalakan.
// Kalau sudah ada (dari data_admin.php), jangan start lagi (biar ga error).
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Cek apakah user sudah login
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    // Pastikan path ini benar
    header("Location: ../CoolAdmin-master/login.php");
    exit;
}
?>