<?php
include '../php/session_check.php'; 
include_once '../php/config.php';



mysqli_set_charset($conn, "utf8"); 


if (isset($_GET['update']) && $_GET['update'] == 'success') {
    echo '<div class="alert alert-success" role="alert" style="margin-bottom:0;">Status kamar berhasil diperbarui!</div>';
}


$sql = "SELECT id_kamar, nomor_kamar, tipe_kamar, kapasitas, harga_per_malam, status_kamar FROM kamar ORDER BY id_kamar ASC";
$result = mysqli_query($conn, $sql);

if (!$result) {
    echo "<div class='alert alert-danger'>Error Query: " . mysqli_error($conn) . "</div>";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Daftar Kamar</title>
    <link href="css/font-face.css" rel="stylesheet" media="all">
    <link href="vendor/fontawesome-7.0.1/css/all.min.css" rel="stylesheet" media="all">
    <link href="vendor/mdi-font/css/material-design-iconic-font.min.css" rel="stylesheet" media="all">
    <link href="vendor/bootstrap-5.3.8.min.css" rel="stylesheet" media="all">
    <link href="css/aos.css" rel="stylesheet" media="all">
    <link href="vendor/css-hamburgers/hamburgers.min.css" rel="stylesheet" media="all">
    <link href="css/swiper-bundle-11.2.10.min.css" rel="stylesheet" media="all">
    <link href="vendor/perfect-scrollbar/perfect-scrollbar-1.5.6.css" rel="stylesheet" media="all">
    <link href="css/theme.css" rel="stylesheet" media="all">
</head>
<body>
<div class="page-wrapper">
    
    <header class="header-mobile d-block d-lg-none">
        <div class="header-mobile__bar">
            <div class="container-fluid">
                <div class="header-mobile-inner">
                    <a class="logo" href="index.php">
                        <h2 class="b1" style="color:#002877;">Luxury Hotel </h2>
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
                    <li>
                        <a href="index.php"><i class="fas fa-tachometer-alt"></i>Dashboard</a>
                    </li>
                    <li class="active">
                        <a href="tabel_daftar_kamar.php"><i class="fas fa-bed"></i>Daftar Kamar</a>
                    </li>
                    <li>
                        <a href="table.php"><i class="fas fa-table"></i>Reservasi Kamar</a>
                    </li>
                    <li>
                        <a href="tabel_meeting.php"><i class="fas fa-desktop"></i>Reservasi Meeting</a>
                    </li>
                    <li>
                        <a href="tabel_pembayaran.php"><i class="fas fa-credit-card"></i>Pembayaran</a>
                    </li>
                     <li><a href="tabel_pesan.php"><i class="fas fa-envelope"></i>Pesan Masuk</a></li>
                    <li>
                        <a href="data_admin.php"><i class="fas fa-credit-card"></i>data admin</a>
                    </li>
                    <li class="has-sub">
                        <a class="js-arrow" href="#"><i class="fas fa-copy"></i>Pages</a>
                        <ul class="list-unstyled navbar__sub-list js-sub-list">
                            <li><a href="login.html">Login</a></li>
                            <li><a href="register.html">Register</a></li>
                            <li><a href="forget-pass.html">Forget Password</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </nav>
    </header>
    <?php include('_header_sidebar.php'); ?>
    <div class="page-container">
        <?php include('_header_desktop.php'); ?>
        <div class="main-content">
            <div class="section__content section__content--p30">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-lg-12">
                            <h2 class="title-1 m-b-25">Daftar Master Kamar Hotel</h2>
                            <div class="table-responsive table--no-card m-b-40">
                                <table class="table table-borderless table-striped table-earning">
                                    <thead>
                                        <tr>
                                            <th>ID Kamar</th>
                                            <th>Nomor Kamar</th>
                                            <th>Tipe Kamar</th>
                                            <th>Kapasitas</th>
                                            <th>Harga/Malam</th>
                                            <th>Status Saat Ini</th>
                                            <th>Update Status</th>
                                            <th class="text-center">Aksi</th>
                                        </tr>
                                    </thead>
                                    
                                    <tbody>
                                        <?php
                                        if ($result && mysqli_num_rows($result) > 0) {
                                            
                                          
                                            $statuses = ['Available', 'Booked', 'Occupied', 'Cleaning'];
                                            
                                            while ($row = mysqli_fetch_assoc($result)) {
                                                echo "<tr>";
                                                echo "<td>" . htmlspecialchars($row['id_kamar']) . "</td>";
                                                echo "<td>" . htmlspecialchars($row['nomor_kamar']) . "</td>";
                                                echo "<td>" . htmlspecialchars($row['tipe_kamar']) . "</td>";
                                                echo "<td>" . htmlspecialchars($row['kapasitas']) . "</td>";
                                                echo "<td>" . number_format($row['harga_per_malam']) . "</td>";
                                                
                                              
                                                $status_kamar = htmlspecialchars($row['status_kamar']);
                                                $badge_class = '';
                                                
                                                if ($status_kamar == 'Available') {
                                                    $badge_class = 'badge-available';
                                                } else if ($status_kamar == 'Booked') {
                                                    $badge_class = 'badge-booked';
                                                } else if ($status_kamar == 'Occupied') {
                                                    $badge_class = 'badge-occupied';
                                                } else if ($status_kamar == 'Cleaning') {
                                                    $badge_class = 'badge-cleaning';
                                                }
                                                echo "<td><span class='badge-status {$badge_class}'>{$status_kamar}</span></td>";

                                                echo "<form method='POST' action='update_status_kamar.php'>";
                                                echo "<input type='hidden' name='id_kamar' value='" . $row['id_kamar'] . "'>";
                                                echo "<td>";
                                                echo "<select name='new_status' class='form-control form-control-sm'>";

                                                foreach ($statuses as $status) {
                                                    $selected = ($status == $row['status_kamar']) ? 'selected' : '';
                                                    echo "<option value='{$status}' {$selected}>{$status}</option>";
                                                }
                                                
                                                echo "</select>";
                                                
                                                
                                                echo "</td>";
                                                echo "<td class='text-center'>";
                                                echo "<button type='submit' class='btn btn-success btn-sm' style='padding: 5px 10px;'>";
                                                echo "<i class='fa fa-check'></i> Simpan";
                                                echo "</button>";
                                                echo "</td>";
                                                echo "</form>"; 
                                                echo "</tr>";
                                            }
                                        } else {
                                            echo "<tr><td colspan='8' class='text-center'>Tidak ada data kamar ditemukan.</td></tr>";
                                        }
                                        ?>
                                    </tbody>
                                    </table>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="copyright">
                                <p>Luxury Hotel 2025</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        </div>
    </div>

<script src="js/vanilla-utils.js"></script>
<script src="vendor/bootstrap-5.3.8.bundle.min.js"></script>
<script src="vendor/perfect-scrollbar/perfect-scrollbar-1.5.6.min.js"></script>
<script src="vendor/chartjs/chart.umd.js-4.5.0.min.js"></script>
<script src="js/bootstrap5-init.js"></script>
<script src="js/main-vanilla.js"></script>
<script src="js/swiper-bundle-11.2.10.min.js"></script>
<script src="js/aos.js"></script>
<script src="js/modern-plugins.js"></script>
</body>
</html>
<?php

mysqli_close($conn);
?>