<?php
include '../php/session_check.php';
include '../php/config.php';


mysqli_set_charset($conn, "utf8"); 


if (isset($_GET['update']) && $_GET['update'] == 'success') {
    echo '<div class="alert alert-success" role="alert" style="margin-bottom:0;">Status reservasi berhasil diperbarui!</div>';
}

$sql = "SELECT
            rk.id_reservasi, rk.tanggal_check_in, rk.tanggal_check_out, rk.jumlah_tamu,
            rk.tipe_kamar_dipesan, rk.total_biaya, rk.status_reservasi, t.nama_lengkap,
            t.email, t.no_telepon, k.nomor_kamar
        FROM
            reservasi_kamar rk
        JOIN
            tamu t ON rk.id_tamu = t.id_tamu
        LEFT JOIN
            kamar k ON rk.id_kamar = k.id_kamar
        ORDER BY 
            rk.id_reservasi DESC"; 

$result = mysqli_query($conn, $sql);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Tabel Tamu</title>
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
<?php include('_header_sidebar.php'); ?>
<div class="page-container">
            
            <?php include('_header_desktop.php'); ?>

            <div class="main-content">
                <div class="section__content section__content--p30">
                    <div class="container-fluid">
                        
                        <div class="row">
                            <div class="col-lg-12">
                                <h2 class="title-1 m-b-25">Daftar Tamu Booking Kamar</h2>
                                <div class="table-responsive table--no-card m-b-40">
                                    <table class="table table-borderless table-striped table-earning">
                                        <thead>
                                            <tr>
                                                <th>ID Reservasi</th>
                                                <th>Nama Tamu</th>
                                                <th>Email</th>
                                                <th>Tipe Kamar</th>
                                                <th>waktu</th>
                                                <th>Status Saat Ini</th>
                                                <th>Pilih Status Baru</th>
                                                <th class="text-center">Aksi</th>
                                            </tr>
                                        </thead>
                                        
                                        <tbody>
                                            <?php
                                           
                                            if ($result && mysqli_num_rows($result) > 0) {
                                              
                                                $statuses = ['Booked', 'Checked-In', 'Checked-Out', 'Canceled'];

                                                while ($row = mysqli_fetch_assoc($result)) {
                                                    echo "<tr>";
                                                    echo "<td>" . htmlspecialchars($row['id_reservasi']) . "</td>";
                                                    echo "<td>" . htmlspecialchars($row['nama_lengkap']) . "</td>";
                                                    echo "<td>" . htmlspecialchars($row['email']) . "</td>";
                                                    echo "<td>" . htmlspecialchars($row['tipe_kamar_dipesan']) . "</td>";
                                                    echo "<td>" . htmlspecialchars($row['tanggal_check_in']) . "</td>";
                                                    
                                                   
                                                    $status_reservasi = htmlspecialchars($row['status_reservasi']);
                                                    $badge_class = '';
                                                    
                                                    if ($status_reservasi == 'Booked') {
                                                        $badge_class = 'badge-booked'; 
                                                    } else if ($status_reservasi == 'Checked-In') {
                                                        $badge_class = 'badge-checked-in'; 
                                                    } else if ($status_reservasi == 'Checked-Out') {
                                                        $badge_class = 'badge-checked-out'; 
                                                    } else if ($status_reservasi == 'Canceled') {
                                                        $badge_class = 'badge-canceled'; 
                                                    } else {
                                                        $badge_class = 'badge-secondary'; 
                                                    }
                                                    
                                                    echo "<td><span class='badge-status {$badge_class}'>{$status_reservasi}</span></td>";
                                                    echo "<form method='POST' action='update_status.php'>";
                                                    echo "<input type='hidden' name='id_reservasi' value='" . $row['id_reservasi'] . "'>";
                                                    echo "<td>";
                                                    echo "<select name='new_status' class='form-control form-control-sm'>";
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
                                                echo "<tr><td colspan='8' class='text-center'>Tidak ada data reservasi ditemukan.</td></tr>";
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
    <script src="vendor/chartjs/chart.umd.js-4.5.0.min.js"></script>
    <script src="js/bootstrap5-init.js"></script>
    <script src="js/main-vanilla.js"></script>
    <script src="js/swiper-bundle-11.2.10.min.js"></script>
    <script src="js/aos.js"></script>
    <script src="js/modern-plugins.js"></script>
</body>
</html>