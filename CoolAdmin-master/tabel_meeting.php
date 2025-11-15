<?php
// Impor file koneksi database
include_once '../php/config.php';

// Cek notifikasi update
if (isset($_GET['update']) && $_GET['update'] == 'success') {
    echo '<div class="alert alert-success" role="alert" style="margin-bottom:0;">Status reservasi meeting berhasil diperbarui!</div>';
}

// KODE SQL ANDA SUDAH BENAR
$sql = "SELECT
            rm.id_reservasi_meeting,
            rm.tanggal_pemesanan, 
            rm.waktu_mulai,
            rm.waktu_selesai,
            rm.jumlah_peserta,
            rm.tipe_ruang_dipesan, 
            rm.total_biaya,
            rm.status_reservasi,
            t.nama_lengkap,
            t.email
        FROM
            reservasi_meeting rm
        JOIN
            tamu t ON rm.id_tamu = t.id_tamu
        ORDER BY
            rm.id_reservasi_meeting DESC";

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
    <title>Tabel Meeting</title> 
    <link href="css/font-face.css" rel="stylesheet" media="all">
    <link href="vendor/fontawesome-7.0.1/css/all.min.css" rel="stylesheet" media="all">
    <link href="vendor/mdi-font/css/material-design-iconic-font.min.css" rel="stylesheet" media="all">
    <link href="vendor/bootstrap-5.3.8.min.css" rel="stylesheet" media="all">
    <link href="vendor/perfect-scrollbar/perfect-scrollbar-1.5.6.css" rel="stylesheet" media="all">
    <link href="css/theme.css" rel="stylesheet" media="all">
</head>

<body>
    <div class="page-wrapper">
        
        <header class="header-mobile d-block d-lg-none">
            <div class="header-mobile__bar">
                <div class="container-fluid">
                    <div class="header-mobile-inner">
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
                </div>
            </nav>
        </header>
        <aside class="menu-sidebar d-none d-lg-block">
            <div class="logo">
                <a href="index.php">
                    <img src="../CoolAdmin-master/images/logo.png" alt="Luxury Hotel" style="width: 80px; height: 80px; margin-left: 4rem;" /> </a>
            </div>
            <div class="menu-sidebar__content js-scrollbar1">
                <nav class="navbar-sidebar">
                    <ul class="list-unstyled navbar__list">

                        <li>
                            <a href="index.php">
                                <i class="fas fa-tachometer-alt"></i>Dashboard</a>
                        </li>
                        <li>
                            <a href="tabel_daftar_kamar.php">
                                <i class="fas fa-bed"></i>Daftar Kamar</a>
                        </li>
                        <li>
                            <a href="table.php"> 
                                <i class="fas fa-bed"></i>Reservasi Kamar</a>
                        </li>
                        <li class="active"> <a href="tabel_meeting.php">
                                <i class="fas fa-desktop"></i>Reservasi Meeting</a>
                        </li>
                         <li>
                        <a href="tabel_pembayaran.php"><i class="fas fa-credit-card"></i>Pembayaran</a>
                         </li>
                        <li class="has-sub">
                            <a class="js-arrow" href="#">
                                <i class="fas fa-copy"></i>Pages</a>
                            <ul class="list-unstyled navbar__sub-list js-sub-list">
                                <li><a href="login.html">Login</a></li>
                                <li><a href="register.html">Register</a></li>
                                <li><a href="forget-pass.html">Forget Password</a></li>
                            </ul>
                        </li>

                    </ul>
                </nav>
            </div>
        </aside>
        <div class="page-container">
            
            <header class="header-desktop">
                <div class="section__content section__content--p30">
                    <div class="container-fluid">
                        <div class="header-wrap">
                            <form class="form-header" action="" method="POST">
                                <input class="au-input au-input--xl" type="text" name="search" placeholder="Search for datas &amp; reports..." />
                                <button class="au-btn--submit" type="submit">
                                    <i class="zmdi zmdi-search"></i>
                                </button>
                            </form>
                            <div class="header-button">
                                <div class="noti-wrap">
                                    </div>
                                <div class="account-wrap">
                                    <div class="account-item clearfix js-item-menu">
                                        <div class="image">
                                            <img src="images/icon/avatar-01.jpg" alt="John Doe" />
                                        </div>
                                        <div class="content">
                                            <a class="js-acc-btn" href="#">john doe</a>
                                        </div>
                                        <div class="account-dropdown js-dropdown">
                                            <div class="info clearfix">
                                                <div class="image">
                                                    <a href="#">
                                                        <img src="images/icon/avatar-01.jpg" alt="John Doe" />
                                                    </a>
                                                </div>
                                                <div class="content">
                                                    <h5 class="name">
                                                        <a href="#">john doe</a>
                                                    </h5>
                                                    <span class="email">johndoe@example.com</span>
                                                </div>
                                            </div>
                                            <div class="account-dropdown__body">
                                                <div class="account-dropdown__item">
                                                    <a href="#"><i class="zmdi zmdi-account"></i>Account</a>
                                                </div>
                                            </div>
                                            <div class="account-dropdown__footer">
                                                <a href="#"><i class="zmdi zmdi-power"></i>Logout</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </header>
            <div class="main-content">
                <div class="section__content section__content--p30">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-lg-12">
                                <h2 class="title-1 m-b-25">Daftar Tamu Booking Meeting</h2>
                                
                                <div class="table-responsive table--no-card m-b-40">
                                    <table class="table table-borderless table-striped table-earning">
                                        <thead>
                                            <tr>
                                                <th>ID</th>
                                                <th>Nama Tamu</th>
                                                <th>Email</th>
                                                <th>Tipe Ruang</th>
                                                <th>Tanggal Pesan</th>
                                                <th>Waktu</th>
                                                <th>Status Saat Ini</th>
                                                <th>Update Status</th>
                                                <th class="text-center">Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            if ($result && mysqli_num_rows($result) > 0) {
                                                // Status sesuai ENUM di DB Anda
                                                $statuses = ['Booked', 'Checked-In', 'Checked-Out', 'Canceled'];

                                                while ($row = mysqli_fetch_assoc($result)) {
                                                    $waktu = htmlspecialchars($row['waktu_mulai']) . " - " . htmlspecialchars($row['waktu_selesai']);
                                                    
                                                    echo "<tr>";
                                                    echo "<td>" . htmlspecialchars($row['id_reservasi_meeting']) . "</td>";
                                                    echo "<td>" . htmlspecialchars($row['nama_lengkap']) . "</td>";
                                                    echo "<td>" . htmlspecialchars($row['email']) . "</td>";
                                                    echo "<td>" . htmlspecialchars($row['tipe_ruang_dipesan']) . "</td>";
                                                    echo "<td>" . htmlspecialchars($row['tanggal_pemesanan']) . "</td>";
                                                    echo "<td>" . $waktu . "</td>";
                                                    echo "<td><strong>" . htmlspecialchars($row['status_reservasi']) . "</strong></td>";

                                                    // Form untuk Update Status
                                                    echo "<form method='POST' action='update_status_meeting.php'>";
                                                    echo "<input type='hidden' name='id_reservasi_meeting' value='" . $row['id_reservasi_meeting'] . "'>";
                                                    
                                                    echo "<td>";
                                                    echo "<select name='new_status' class='form-control form-control-sm' '>";
                                                    foreach ($statuses as $status) {
                                                        $selected = ($status == $row['status_reservasi']) ? 'selected' : '';
                                                        echo "<option value='{$status}' {$selected}>{$status}</option>";
                                                    }
                                                    echo "</select>";
                                                    echo "</td>";

                                                    echo "<td class='text-center'>";
                                                    echo "<button type='submit' class='btn btn-primary btn-sm' style='padding: 5px 10px;'>";
                                                    echo "<i class='fa fa-edit'></i> Update";
                                                    echo "</button>";
                                                    echo "</td>";
                                                    echo "</form>"; 

                                                    echo "</tr>";
                                                }
                                            } else {
                                                // Colspan 9 (sesuai jumlah <th>)
                                                echo "<tr><td colspan='9' class='text-center'>Tidak ada data reservasi meeting ditemukan.</td></tr>";
                                            }
                                            mysqli_close($conn);
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
    <script src="js/main-vanilla.js"></script>
    </body>
</html>