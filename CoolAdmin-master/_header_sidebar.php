<?php
// 1. Deteksi Nama File Halaman Saat Ini
// Fungsi basename($_SERVER['PHP_SELF']) akan mengambil nama file (misal: index.php, tabel_daftar_kamar.php)
$current_page = basename($_SERVER['PHP_SELF']);
?>

<header class="header-mobile d-block d-lg-none">
    <div class="header-mobile__bar">
        <div class="container-fluid">
            <div class="header-mobile-inner">
                <a class="logo" href="index.php">
                    <h2 class="b1" style="color:#002877;">Luxury Hotel</h2>
                </a>
                <button class="hamburger hamburger--slider" type="button">
                    <span class="hamburger-box">
                        <span class="hamburger-inner"></span>
                    </span>
                </button>
            </div>
        </div>
    </div>
    <nav class="navbar-mobile">
        <div class="container-fluid">
            <ul class="navbar-mobile__list list-unstyled">
                <li class="<?php echo ($current_page == 'index.php') ? 'active' : ''; ?>">
                    <a href="index.php"><i class="fas fa-tachometer-alt"></i>Dashboard</a>
                </li>
                <li class="<?php echo ($current_page == 'tabel_daftar_kamar.php') ? 'active' : ''; ?>">
                    <a href="tabel_daftar_kamar.php"><i class="fas fa-bed"></i>Master Kamar</a>
                </li>
                <li class="<?php echo ($current_page == 'table.php') ? 'active' : ''; ?>">
                    <a href="table.php"><i class="fas fa-calendar-alt"></i>Reservasi Kamar</a>
                </li>
                <li class="<?php echo ($current_page == 'tabel_meeting.php') ? 'active' : ''; ?>">
                    <a href="tabel_meeting.php"><i class="fas fa-desktop"></i>Reservasi Meeting</a>
                </li>
                <li class="<?php echo ($current_page == 'tabel_pembayaran.php') ? 'active' : ''; ?>">
                    <a href="tabel_pembayaran.php"><i class="fas fa-credit-card"></i>Pembayaran</a>
                </li>
                <li class="<?php echo ($current_page == 'tabel_pesan.php') ? 'active' : ''; ?>">
                    <a href="tabel_pesan.php"><i class="fas fa-envelope"></i>Pesan Masuk</a>
                </li>
                
                <li class="has-sub <?php echo (in_array($current_page, ['login.php', 'register.php', 'forget-pass.php'])) ? 'active' : ''; ?>">
                    <a class="js-arrow" href="#"><i class="fas fa-copy"></i>Pages</a>
                    <ul class="navbar-mobile-sub__list list-unstyled js-sub-list">
                        <li><a href="login.php">Login</a></li>
                        <li><a href="register.php">Register</a></li>
                        <li><a href="forget-pass.php">Forget Password</a></li>
                    </ul>
                </li>
            </ul>
        </div>
    </nav>
</header>
<aside class="menu-sidebar d-none d-lg-block">
    <div class="logo">
        <a href="index.php">
            <h2 class="b1" style="color:#002877;">Luxury Hotel</h2>
        </a>
    </div>
    <div class="menu-sidebar__content js-scrollbar1">
        <nav class="navbar-sidebar">
            <ul class="list-unstyled navbar__list">
                
                <li class="<?php echo ($current_page == 'index.php') ? 'active' : ''; ?>">
                    <a href="index.php"><i class="fas fa-tachometer-alt"></i>Dashboard</a>
                </li>

                <li class="<?php echo ($current_page == 'tabel_daftar_kamar.php') ? 'active' : ''; ?>">
                    <a href="tabel_daftar_kamar.php"><i class="fas fa-bed"></i>Master Kamar</a>
                </li>

                <li class="<?php echo ($current_page == 'table.php') ? 'active' : ''; ?>">
                    <a href="table.php"><i class="fas fa-calendar-alt"></i>Reservasi Kamar</a>
                </li>

                <li class="<?php echo ($current_page == 'tabel_meeting.php') ? 'active' : ''; ?>">
                    <a href="tabel_meeting.php"><i class="fas fa-desktop"></i>Reservasi Meeting</a>
                </li>

                <li class="<?php echo ($current_page == 'tabel_pembayaran.php') ? 'active' : ''; ?>">
                    <a href="tabel_pembayaran.php"><i class="fas fa-credit-card"></i>Pembayaran</a>
                </li>
                
                <li class="<?php echo ($current_page == 'tabel_pesan.php') ? 'active' : ''; ?>">
                    <a href="tabel_pesan.php"><i class="fas fa-envelope"></i>Pesan Masuk</a>
                </li>

                <li class="has-sub <?php echo (in_array($current_page, ['login.php', 'register.php', 'forget-pass.php'])) ? 'active' : ''; ?>">
                    <a class="js-arrow" href="#">
                        <i class="fas fa-copy"></i>Pages</a>
                    <ul class="list-unstyled navbar__sub-list js-sub-list">
                        <li><a href="login.php">Login</a></li>
                        <li><a href="register.php">Register</a></li>
                        <li><a href="forget-pass.php">Forget Password</a></li>
                    </ul>
                </li>

            </ul>
        </nav>
    </div>
</aside>