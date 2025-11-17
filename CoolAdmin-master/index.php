<?php
include '../php/session_check.php';
include_once '../php/config.php';

// Jalur default (Relatif dari index.php di CoolAdmin-master/)
$default_img_path = "images/icon/avatar-default.jpg"; 

$current_user_email = "N/A";
$current_user_img_src = $default_img_path; // Ini yang akan digunakan di tag <img>

if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    
    $stmt = $conn->prepare("SELECT email, profile_img FROM users WHERE id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $user_db_data = $result->fetch_assoc();
        $current_user_email = $user_db_data['email'];
        
        if (!empty($user_db_data['profile_img'])) {
            // Jika ada profile_img dari DB (misal: 'uploads/profiles/xyz.jpg'), 
            // kita tambahkan '../' untuk keluar dari CoolAdmin-master/
            $current_user_img_src = '../' . $user_db_data['profile_img'];
        } else {
            // Jika kolom di DB kosong, gunakan default path internal
            $current_user_img_src = $default_img_path;
        }
    }
    $stmt->close();
}

$sql_terisi = "SELECT COUNT(*) as total_terisi FROM reservasi_kamar WHERE status_reservasi = 'Checked-In'";
$hasil_terisi = mysqli_query($conn, $sql_terisi);
$data_terisi = mysqli_fetch_assoc($hasil_terisi);
$total_terisi = $data_terisi['total_terisi'];

$sql_reservasi_hari_ini = "SELECT COUNT(*) as total_reservasi_hari_ini FROM reservasi_kamar WHERE DATE(tanggal_reservasi) = CURDATE()";
$hasil_reservasi_hari_ini = mysqli_query($conn, $sql_reservasi_hari_ini);
$data_reservasi_hari_ini = mysqli_fetch_assoc($hasil_reservasi_hari_ini);
$total_reservasi_hari_ini = $data_reservasi_hari_ini['total_reservasi_hari_ini'];


$sql_meeting_hari_ini = "SELECT COUNT(*) as total_meeting_hari_ini FROM reservasi_meeting WHERE DATE(tanggal_pemesanan) = CURDATE()";
$hasil_meeting_hari_ini = mysqli_query($conn, $sql_meeting_hari_ini);
$data_meeting_hari_ini = mysqli_fetch_assoc($hasil_meeting_hari_ini);
$total_meeting_hari_ini = $data_meeting_hari_ini['total_meeting_hari_ini'] ?? 0;

$sql_pendapatan = "SELECT SUM(total_amount) as total_pendapatan_bulan_ini FROM pembayaran 
                   WHERE status_pembayaran IN ('Paid', 'Lunas') 
                   AND MONTH(tanggal_pembayaran) = MONTH(CURDATE())
                   AND YEAR(tanggal_pembayaran) = YEAR(CURDATE())";
$hasil_pendapatan = mysqli_query($conn, $sql_pendapatan);
$data_pendapatan = mysqli_fetch_assoc($hasil_pendapatan);
$total_pendapatan_bulan_ini = $data_pendapatan['total_pendapatan_bulan_ini'] ?? 0;


$pendapatan_labels = [];
$pendapatan_data_map = [];
for ($i = 6; $i >= 0; $i--) {
    $tanggal = date('Y-m-d', strtotime("-$i days"));
    $pendapatan_labels[] = date('d M', strtotime($tanggal));
    $pendapatan_data_map[$tanggal] = 0; 
}

$sql_pendapatan_harian = "SELECT 
                            DATE_FORMAT(tanggal_pembayaran, '%Y-%m-%d') as tanggal, 
                            SUM(total_amount) as harian
                          FROM pembayaran
                          WHERE 
                            status_pembayaran IN ('Paid', 'Lunas') AND
                            tanggal_pembayaran >= CURDATE() - INTERVAL 6 DAY
                          GROUP BY tanggal
                          ORDER BY tanggal ASC";
$hasil_pendapatan_harian = mysqli_query($conn, $sql_pendapatan_harian);

if ($hasil_pendapatan_harian) {
    while ($row = mysqli_fetch_assoc($hasil_pendapatan_harian)) {
        if (isset($pendapatan_data_map[$row['tanggal']])) {
            $pendapatan_data_map[$row['tanggal']] = $row['harian'];
        }
    }
}
$pendapatan_data = array_values($pendapatan_data_map);



$sql_status_kamar = "SELECT status_kamar, COUNT(*) as jumlah FROM kamar GROUP BY status_kamar";
$hasil_status_kamar = mysqli_query($conn, $sql_status_kamar);

$status_labels = [];
$status_data = [];
if ($hasil_status_kamar) {
    while ($row = mysqli_fetch_assoc($hasil_status_kamar)) {
        $status_labels[] = $row['status_kamar'];
        $status_data[] = $row['jumlah'];
    }
}


$sql_popularitas_meeting = "SELECT tipe_ruang_dipesan, COUNT(*) as jumlah 
                            FROM reservasi_meeting 
                            GROUP BY tipe_ruang_dipesan 
                            ORDER BY jumlah DESC";
$hasil_popularitas_meeting = mysqli_query($conn, $sql_popularitas_meeting);

$meeting_status_labels = []; 
$meeting_status_data = [];   
if ($hasil_popularitas_meeting) {
    while ($row = mysqli_fetch_assoc($hasil_popularitas_meeting)) {
        $meeting_status_labels[] = $row['tipe_ruang_dipesan'];
        $meeting_status_data[] = $row['jumlah'];
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Dashboard</title>
    <link href="css/font-face.css" rel="stylesheet" media="all">
    <link href="vendor/fontawesome-7.0.1/css/all.min.css" rel="stylesheet" media="all">
    <link href="vendor/mdi-font/css/material-design-iconic-font.min.css" rel="stylesheet" media="all">
    <link href="vendor/bootstrap-5.3.8.min.css" rel="stylesheet" media="all">
    <link href="vendor/perfect-scrollbar/perfect-scrollbar-1.5.6.css" rel="stylesheet" media="all">
    <link href="css/theme.css" rel="stylesheet" media="all">
    <link href="css/custom-dashboard.css" rel="stylesheet" media="all">
</head>

<body>
<div class="page-wrapper">
    <header class="header-mobile d-block d-lg-none">
        <div class="header-mobile__bar">
            <div class="container-fluid">
                <div class="header-mobile-inner">
                    <a class="logo" href="index.php">
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
                    <li class="active">
                        <a href="index.php"><i class="fas fa-tachometer-alt"></i>Dashboard</a>
                    </li>
                    <li>
                        <a href="tabel_daftar_kamar.php"><i class="fas fa-bed"></i>Master Kamar</a>
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
                    <li class="has-sub">
                        <a class="js-arrow" href="#"><i class="fas fa-copy"></i>Pages</a>
                        <ul class="list-unstyled navbar__sub-list js-sub-list">
                            <li><a href="login.php">Login</a></li>
                            <li><a href="register.php">Register</a></li>
                            <li><a href="forget-pass.php">Forget Password</a></li>
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
                        <div class="col-md-12">
                            <div class="overview-wrap">
                                <h2 class="title-1">Overview</h2>
                                <button class="au-btn au-btn-icon au-btn--blue">
                                    <i class="zmdi zmdi-plus"></i>add item</button>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row m-t-25">
                        <div class="col-sm-6 col-lg-3">
                            <div class="overview-item overview-item--c1" style="background-color: #1a00deff;">
                                <div class="overview__inner">
                                    <div class="overview-box clearfix">
                                        <div class="icon">
                                            <i class="fas fa-bed"></i> </div>
                                        <div class="text">
                                            <h2><?php echo $total_terisi; ?></h2> <span>Kamar Terisi (Malam Ini)</span> </div>
                                    </div>
                                    <div class="overview-chart">
                                        <canvas id="widgetChart1"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6 col-lg-3">
                            <div class="overview-item overview-item--c2">
                                <div class="overview__inner">
                                    <div class="overview-box clearfix">
                                        <div class="icon">
                                            <i class="fas fa-bed"></i> </div>
                                        <div class="text">
                                            <h2><?php echo $total_reservasi_hari_ini; ?></h2> <span>Reservasi Baru (Hari Ini)</span> </div>
                                    </div>
                                    <div class="overview-chart">
                                        <canvas id="widgetChart2"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6 col-lg-3">
                            <div class="overview-item overview-item--c3"> <div class="overview__inner">
                                    <div class="overview-box clearfix">
                                        <div class="icon">
                                            <i class="fas fa-desktop"></i>
                                        </div>
                                        <div class="text">
                                            <h2><?php echo $total_meeting_hari_ini; ?></h2> 
                                            <span>Ruang Meeting (Hari ini) </span> 
                                        </div>
                                    </div>
                                    <div class="overview-chart">
                                        <canvas id="widgetChart3"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6 col-lg-3">
                            <div class="overview-item overview-item--c4">
                                <div class="overview__inner">
                                    <div class="overview-box clearfix">
                                        <div class="icon">
                                            <i class="fas fa-dollar-sign"></i> </div>
                                        <div class="text">
                                            <h2>Rp <?php echo number_format($total_pendapatan_bulan_ini, 0, ',', '.'); ?></h2>
                                            <span>Pendapatan (Bulan Ini)</span> </div>
                                    </div>
                                    <div class="overview-chart">
                                        <canvas id="widgetChart4"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row m-t-30">
                        
                        <div class="col-lg-7">
                            <div class="au-card recent-report">
                                <div class="au-card-inner">
                                    <h3 class="title-2">Grafik Pendapatan (7 Hari Terakhir)</h3>
                                    <div class="chart-container m-t-20">
                                        <canvas id="pendapatanChart"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-5">
                            
                            <div class="au-card">
                                <div class="au-card-inner">
                                    <h3 class="title-2">Status Kamar</h3>
                                    <div class="chart-container-pie m-t-20">
                                        <canvas id="statusKamarChart"></canvas>
                                    </div>
                                </div>
                            </div>

                            <div class="au-card m-t-30">
                                <div class="au-card-inner">
                                    <h3 class="title-2">Popularitas Ruang Meeting</h3> <div class="chart-container-pie m-t-20">
                                        <canvas id="statusMeetingChart"></canvas>
                                    </div>
                                </div>
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

    <script>
    const pendapatanLabels = <?php echo json_encode($pendapatan_labels); ?>;
    const pendapatanData = <?php echo json_encode($pendapatan_data); ?>;
    
    const statusLabels = <?php echo json_encode($status_labels); ?>;
    const statusData = <?php echo json_encode($status_data); ?>;

   
    const meetingStatusLabels = <?php echo json_encode($meeting_status_labels); ?>;
    const meetingStatusData = <?php echo json_encode($meeting_status_data); ?>;

    const ctxPendapatan = document.getElementById('pendapatanChart');
    if (ctxPendapatan) {
        new Chart(ctxPendapatan, {
        type: 'bar',
        data: {
            labels: pendapatanLabels,
            datasets: [{
            label: 'Pendapatan (Rp)',
            data: pendapatanData,
            backgroundColor: 'rgba(0, 40, 119, 0.7)',
            borderColor: 'rgba(0, 40, 119, 1)',
            borderRadius: 5,
            borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: { y: { beginAtZero: true } }
        }
        });
    }
    
    // 2. Grafik Pie (Status Kamar)
    const ctxStatus = document.getElementById('statusKamarChart');
    if (ctxStatus) {
        new Chart(ctxStatus, {
        type: 'doughnut',
        data: {
            labels: statusLabels,
            datasets: [{
            label: 'Jumlah Kamar',
            data: statusData,
            backgroundColor: [
                '#28a745', // Hijau (Available)
                '#dc3545', // Merah (Occupied)
                '#ffc107', // Kuning (Cleaning)
                '#17a2b8', // Biru (Booked)
                '#6c757d'  // Abu-abu
            ],
            hoverOffset: 4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
        }
        });
    }

    // 3. Grafik Pie (Popularitas Ruang Meeting)
    const ctxStatusMeeting = document.getElementById('statusMeetingChart');
    if (ctxStatusMeeting) {
        new Chart(ctxStatusMeeting, {
        type: 'doughnut',
        data: {
            labels: meetingStatusLabels, // Variabel BARU (berisi 'Bima Ballroom', dll)
            datasets: [{
            label: 'Jumlah Reservasi',
            data: meetingStatusData, // Variabel BARU (berisi jumlah booking)
            backgroundColor: [
                '#002877', // Biru Tua
                '#4682B4', // Biru Baja
                '#87CEEB', // Biru Langit
                '#ADD8E6', // Biru Muda
                '#6c757d'  // Abu-abu
            ],
            hoverOffset: 4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
        }
        });
    }
    </script>
    <script src="js/main-vanilla.js"></script>
    </body>
</html>
<?php
// TUTUP KONEKSI DI PALING AKHIR
mysqli_close($conn);
?>