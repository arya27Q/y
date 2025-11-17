<?php
include '../php/session_check.php';
include '../php/config.php';

// Set charset ke UTF-8
mysqli_set_charset($conn, "utf8");

// Tampilkan notifikasi jika ada parameter 'update=success'
if (isset($_GET['update']) && $_GET['update'] == 'success') {
    echo '<div class="alert alert-success" role="alert" style="margin-bottom:0;">Status reservasi meeting berhasil diperbarui!</div>';
}

// Query untuk mengambil data reservasi meeting digabung dengan data tamu
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

// Cek jika query gagal
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

        <?php include('_header_sidebar.php'); ?>

        <div class="page-container">
            <?php include('_header_desktop.php'); ?>
						
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
                                           
                                            if ($result && mysqli_num_rows($result) > 0) :
                                               
                                                $statuses = ['Booked', 'Checked-In', 'Checked-Out', 'Canceled'];

                                                while ($row = mysqli_fetch_assoc($result)) :
                                                    $waktu = htmlspecialchars($row['waktu_mulai']) . " - " . htmlspecialchars($row['waktu_selesai']);

                                                    $status_reservasi = htmlspecialchars($row['status_reservasi']);
                                                    $badge_class = '';

                                                    switch ($status_reservasi) {
                                                        case 'Booked':
                                                            $badge_class = 'badge-booked';
                                                            break;
                                                        case 'Checked-In':
                                                            $badge_class = 'badge-checked-in';
                                                            break;
                                                        case 'Checked-Out':
                                                            $badge_class = 'badge-checked-out';
                                                            break;
                                                        case 'Canceled':
                                                            $badge_class = 'badge-canceled';
                                                            break;
                                                        default:
                                                            $badge_class = 'badge-secondary';
                                                    }
                                            ?>
                                                    <tr>
                                                        <td><?= htmlspecialchars($row['id_reservasi_meeting']) ?></td>
                                                        <td><?= htmlspecialchars($row['nama_lengkap']) ?></td>
                                                        <td><?= htmlspecialchars($row['email']) ?></td>
                                                        <td><?= htmlspecialchars($row['tipe_ruang_dipesan']) ?></td>
                                                        <td><?= htmlspecialchars($row['tanggal_pemesanan']) ?></td>
                                                        <td><?= $waktu ?></td>
                                                        <td>
                                                            <span class="badge-status <?= $badge_class ?>"><?= $status_reservasi ?></span>
                                                        </td>

                                                        <form method='POST' action='update_status_meeting.php'>
                                                            <input type='hidden' name='id_reservasi_meeting' value='<?= $row['id_reservasi_meeting'] ?>'>
                                                            
                                                            <td>
                                                                <select name='new_status' class='form-control form-control-sm'>
                                                                    <?php foreach ($statuses as $status) : ?>
                                                                        <?php $selected = ($status == $row['status_reservasi']) ? 'selected' : ''; ?>
                                                                        <option value='<?= $status ?>' <?= $selected ?>><?= $status ?></option>
                                                                    <?php endforeach; ?>
                                                                </select>
                                                            </td>

                                                            <td class='text-center'>
                                                                <button type='submit' class='btn btn-primary btn-sm' style='padding: 5px 10px;'>
                                                                    <i class='fa fa-edit'></i> Update
                                                                </button>
                                                            </td>
                                                        </form>
                                                    </tr>
                                                <?php
                                                endwhile;
                                            else :
                                                ?>
                                                <tr>
                                                    <td colspan='9' class='text-center'>Tidak ada data reservasi meeting ditemukan.</td>
                                                </tr>
                                            <?php
                                            endif;
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