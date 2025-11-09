<?php

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$is_logged_in = isset($_SESSION['id_tamu']);

if ($is_logged_in) {
    
    $nama = htmlspecialchars($_SESSION['nama_lengkap'] ?? 'Tamu');
    
    echo '
        <a href="profil.php">Profil</a>
        <a href="logout.php" id="logoutBtn">Logout</a>
    ';
} else {

    echo '
        <a href="login.php">Login</a>
        <a href="create_account.php">Create Account</a>
    ';
}
?>