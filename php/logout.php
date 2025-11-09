<?php
// Wajib: Mulai sesi
session_start();

// Hapus semua variabel sesi
session_unset();

// Hancurkan sesi
session_destroy();

// Redirect pengguna kembali ke halaman utama (home.php)
header("Location: home.php");
exit;
?>