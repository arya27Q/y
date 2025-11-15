<?php
// Impor file koneksi database
include_once '../php/config.php';

// Set charset ke UTF-8
mysqli_set_charset($conn, "utf8");

// Tampilkan notifikasi jika ada parameter 'update=success'
if (isset($_GET['update']) && $_GET['update'] == 'success') {
    echo '<div class="alert alert-success" role="alert" style="margin-bottom:0;">Status pesan berhasil diperbarui!</div>';
}

// Query untuk mengambil data pesan kontak
$sql = "SELECT * FROM pesan_kontak ORDER BY tanggal_kirim DESC";

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
    <title>Pesan Kontak Masuk</title>
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
            </header>

        <aside class="menu-sidebar d-none d-lg-block">
            <div class="logo">
                <a href="index.php">
                    <h2 class="b1" style="color:#002877;">Luxury Hotel </h2>
                </a>
            </div>
            <div class="menu-sidebar__content js-scrollbar1">
                <nav class="navbar-sidebar">
                    <ul class="list-unstyled navbar__list">
                        <li><a href="index.php"><i class="fas fa-tachometer-alt"></i>Dashboard</a></li>
                        <li><a href="tabel_daftar_kamar.php"><i class="fas fa-bed"></i>Daftar Kamar</a></li>
                        <li><a href="table.php"><i class="fas fa-bed"></i>Reservasi Kamar</a></li>
                        <li><a href="tabel_meeting.php"><i class="fas fa-desktop"></i>Reservasi Meeting</a></li>
                        <li><a href="tabel_pembayaran.php"><i class="fas fa-credit-card"></i>Pembayaran</a></li>
                        
                        <li class="active"><a href="tabel_pesan.php"><i class="fas fa-envelope"></i>Pesan Masuk</a></li>
                        
                        <li class="has-sub">
                            <a class="js-arrow" href="#"><i class="fas fa-copy"></i>Pages</a>
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
                                        <img src="" alt="YUDHIS" />
                                    </div>
                                    <div class="content">
                                        <a class="js-acc-btn" href="#"></a>
                                    </div>
                                    <div class="account-dropdown js-dropdown">
                                        <div class="info clearfix">
                                            <div class="image">
                                                <a href="#">
                                                    <img src="" alt="YUDHIS" />
                                                </a>
                                            </div>
                                            <div class="content">
                                                <h5 class="name">
                                                    <a href="#"></a>
                                                </h5>
                                                <span class="email"></span>
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
                                <h2 class="title-1 m-b-25">Daftar Pesan Kontak Masuk</h2>
                                <div class="table-responsive table--no-card m-b-40">
                                    <table class="table table-borderless table-striped table-earning">
                                        <thead>
                                            <tr>
                                                <th>ID</th>
                                                <th>Nama</th>
                                                <th>Email</th>
                                                <th>Pesan</th>
                                                <th>Tanggal Kirim</th>
                                                <th>Status</th>
                                                <th>Update Status</th>
                                                <th classs="text-center">Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            if ($result && mysqli_num_rows($result) > 0) :
                                                $statuses = ['Baru', 'Sudah Dibaca', 'Sudah Dibalas']; // Opsi status

                                                while ($row = mysqli_fetch_assoc($result)) :
                                            ?>
                                                    <tr>
                                                        <td><?= htmlspecialchars($row['id_pesan']) ?></td>
                                                        <td><?= htmlspecialchars($row['nama_lengkap']) ?></td>
                                                        <td><?= htmlspecialchars($row['email']) ?></td>
                                                        
                                                        <td style="min-width: 300px; white-space: normal;"><?= htmlspecialchars($row['pesan']) ?></td>
                                                        
                                                        <td><?= htmlspecialchars($row['tanggal_kirim']) ?></td>
                                                        
                                                        <td>
                                                           <span class="badge 
                                                                <?php 
                                                                if ($row['status'] == 'Baru') echo 'badge-danger'; // Merah untuk 'Baru'
                                                                else if ($row['status'] == 'Sudah Dibaca') echo 'badge-warning'; // Kuning untuk 'Sudah Dibaca'
                                                                else echo 'badge-success'; // Hijau untuk 'Sudah Dibalas'
                                                                ?>
                                                           ">
                                                                <?= htmlspecialchars($row['status']) ?>
                                                           </span>
                                                        </td>

                                                        <form method='POST' action='update_status_pesan.php'> <input type='hidden' name='id_pesan' value='<?= $row['id_pesan'] ?>'>
                                                            
                                                            <td>
                                                                <select name='new_status' class='form-control form-control-sm'>
                                                                    <?php foreach ($statuses as $status) : ?>
                                                                        <?php $selected = ($status == $row['status']) ? 'selected' : ''; ?>
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
                                                    <td colspan='8' class='text-center'>Tidak ada pesan masuk.</td>
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