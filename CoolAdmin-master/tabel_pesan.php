<?php
include_once '../php/session_check.php';
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

        <?php include('_header_sidebar.php'); ?>

        <div class="page-container">
        <?php include('_header_desktop.php'); ?>

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
                <td><?= htmlspecialchars($row['id_pesan'] ?? '') ?></td>
                <td><?= htmlspecialchars($row['nama_lengkap'] ?? '') ?></td>
                <td><?= htmlspecialchars($row['email'] ?? '') ?></td>
                
                <td style="min-width: 300px; white-space: normal;"><?= htmlspecialchars($row['pesan'] ?? '') ?></td>
                
                <td><?= htmlspecialchars($row['tanggal_kirim'] ?? '') ?></td>
                
                <td>
                    <span class="badge 
                        <?php 
                        if (($row['status'] ?? 'Baru') == 'Baru') echo 'badge-danger';
                        else if ($row['status'] == 'Sudah Dibaca') echo 'badge-warning'; 
                        else echo 'badge-success'; 
                        ?>
                    ">
                        <?= htmlspecialchars($row['status'] ?? 'Baru') ?>
                    </span>
                </td>

                <form method='POST' action='update_status_pesan.php'> 
                    <input type='hidden' name='id_pesan' value='<?= $row['id_pesan'] ?>'>
                    
                    <td>
                        <select name='new_status' class='form-control form-control-sm'>
                            <?php foreach ($statuses as $status) : ?>
                                <?php $selected = ($status == ($row['status'] ?? 'Baru')) ? 'selected' : ''; ?>
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