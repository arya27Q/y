<?php
include '../php/session_check.php';
include_once '../php/config.php';


// Atur koneksi untuk membaca data sebagai UTF-8
mysqli_set_charset($conn, "utf8"); 

// Cek notifikasi update
if (isset($_GET['update']) && $_GET['update'] == 'success') {
    echo '<div class="alert alert-success" role="alert" style="margin-bottom:0;">Status pembayaran berhasil diperbarui!</div>';
}

// Query SQL
$sql = "SELECT
            p.payment_id, p.jenis_reservasi, p.id_reservasi_ref, p.tanggal_pembayaran,
            p.metode_pembayaran, p.total_amount, p.status_pembayaran, t.nama_lengkap
        FROM pembayaran p
        JOIN tamu t ON p.id_tamu = t.id_tamu
        ORDER BY p.payment_id DESC";

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
    <title>Tabel Pembayaran</title>
    <link href="css/font-face.css" rel="stylesheet" media="all">
    <link href="vendor/fontawesome-7.0.1/css/all.min.css" rel="stylesheet" media="all">
    <link href="vendor/mdi-font/css/material-design-iconic-font.min.css" rel="stylesheet" media="all">
    <link href="vendor/bootstrap-5.3.8.min.css" rel="stylesheet" media="all">
    <link href="vendor/perfect-scrollbar/perfect-scrollbar-1.5.6.css" rel="stylesheet" media="all">
    <link href="css/theme.css" rel="stylesheet" media="all">
    
    <style>
        .action-btn { border: none; background: none; cursor: pointer; }
        .form-inline-action { display: flex; align-items: center; gap: 5px; }
    </style>
</head>
<body>
<div class="page-wrapper">
    
    <?php include('_header_sidebar.php'); ?>
    <div class="page-container">
        
        <?php include('_header_desktop.php'); ?>
        <div class="main-content">
            <div class="section__content section__content--p30">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-lg-12">
                            <h2 class="title-1 m-b-25">Daftar Semua Pembayaran</h2>
                            <div class="table-responsive table--no-card m-b-40">
                                <table class="table table-borderless table-striped table-earning">
                                    <thead>
                                        <tr>
                                            <th>ID Bayar</th>
                                            <th>Nama Tamu</th>
                                            <th>Jenis</th>
                                            <th>ID Ref</th>
                                            <th>Tgl Bayar</th>
                                            <th>Metode</th>
                                            <th>Total</th>
                                            <th>Status</th>
                                            <th>Update Status</th>
                                            <th class="text-center">Aksi</th>
                                        </tr>
                                    </thead>
                                    
                                    <tbody>
                                        <?php
                                        if ($result && mysqli_num_rows($result) > 0) {
                                            
                                            $statuses = ['Pending', 'Paid', 'Failed', 'Refunded']; 

                                            while ($row = mysqli_fetch_assoc($result)) {
                                                echo "<tr>";
                                                // PERBAIKAN: Menggunakan ?? '' untuk menangani NULL
                                                echo "<td>" . htmlspecialchars($row['payment_id'] ?? '') . "</td>";
                                                echo "<td>" . htmlspecialchars($row['nama_lengkap'] ?? '') . "</td>";
                                                echo "<td>" . htmlspecialchars($row['jenis_reservasi'] ?? '') . "</td>";
                                                echo "<td>" . htmlspecialchars($row['id_reservasi_ref'] ?? '') . "</td>";
                                                echo "<td>" . htmlspecialchars($row['tanggal_pembayaran'] ?? '') . "</td>";
                                                echo "<td>" . htmlspecialchars($row['metode_pembayaran'] ?? '') . "</td>";
                                                echo "<td>" . number_format($row['total_amount'] ?? 0) . "</td>";
                                                
                                                // Logic Status Badge
                                                $status_pembayaran = htmlspecialchars($row['status_pembayaran'] ?? '');
                                                $badge_class = '';
                                                
                                                if ($status_pembayaran == 'Paid' || $status_pembayaran == 'Lunas') {
                                                    $badge_class = 'badge-checked-in'; 
                                                } else if ($status_pembayaran == 'Pending') {
                                                    $badge_class = 'badge-booked'; 
                                                } else if ($status_pembayaran == 'Failed') {
                                                    $badge_class = 'badge-canceled'; 
                                                } else if ($status_pembayaran == 'Refunded') {
                                                    $badge_class = 'badge-checked-out'; 
                                                } else {
                                                    $badge_class = 'badge-secondary'; 
                                                }
                                                
                                                echo "<td><span class='badge-status {$badge_class}'>{$status_pembayaran}</span></td>";
                                                
                                                // KOLOM UPDATE STATUS
                                                // Saya gabungkan Select dan Tombol Simpan dalam satu Form agar berfungsi
                                                echo "<td>";
                                                echo "<form method='POST' action='update_status_pembayaran.php' class='form-inline-action'>";
                                                echo "<input type='hidden' name='payment_id' value='" . ($row['payment_id'] ?? '') . "'>";
                                                
                                                echo "<select name='new_status' class='form-control form-control-sm' style='width: 110px;'>";
                                                foreach ($statuses as $status) {
                                                    $selected = ($status == $status_pembayaran) ? 'selected' : '';
                                                    echo "<option value='{$status}' {$selected}>{$status}</option>";
                                                }
                                                echo "</select>";
                                                
                                                echo "<button type='submit' class='action-btn' data-toggle='tooltip' title='Simpan Perubahan'>";
                                                echo "<i class='zmdi zmdi-refresh-sync' style='color: orange; font-size: 22px;'></i>"; 
                                                echo "</button>";
                                                echo "</form>";
                                                echo "</td>";

                                                // KOLOM AKSI (Cetak Invoice)
                                                echo "<td class='text-center'>";
                                                echo "<div class='table-data-feature' style='justify-content:center;'>";
                                                
                                                echo "<a href='invoice.php?id=" . ($row['payment_id'] ?? '') . "' target='_blank' class='item' data-toggle='tooltip' title='Cetak Invoice'>";
                                                echo "<i class='zmdi zmdi-print' style='color: #002877; font-size: 20px;'></i>";
                                                echo "</a>";

                                                echo "</div>";
                                                echo "</td>";

                                                echo "</tr>";
                                            }
                                        } else {
                                            echo "<tr><td colspan='10' class='text-center'>Tidak ada data pembayaran ditemukan.</td></tr>";
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