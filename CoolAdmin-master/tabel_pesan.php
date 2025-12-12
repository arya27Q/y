<?php
include '../php/session_check.php';
include '../php/config.php';


// Set charset ke UTF-8
mysqli_set_charset($conn, "utf8");

// Query untuk mengambil data pesan kontak
$sql = "SELECT * FROM pesan_kontak ORDER BY tanggal_kirim DESC";
$result = mysqli_query($conn, $sql);

if (!$result) {
    echo "<div class='alert alert-danger m-3'>Error Query: " . mysqli_error($conn) . "</div>";
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Pesan Masuk - Admin Panel</title>

    <link href="css/font-face.css" rel="stylesheet" media="all">
    <link href="vendor/fontawesome-7.0.1/css/all.min.css" rel="stylesheet" media="all">
    <link href="vendor/mdi-font/css/material-design-iconic-font.min.css" rel="stylesheet" media="all">
    <link href="vendor/bootstrap-5.3.8.min.css" rel="stylesheet" media="all">
    <link href="vendor/perfect-scrollbar/perfect-scrollbar-1.5.6.css" rel="stylesheet" media="all">
    <link href="css/theme.css" rel="stylesheet" media="all">
    <link href="css/custom-dashboard.css" rel="stylesheet" media="all">
    <link rel="stylesheet" href="mobile-admin.css">

    <style>
        /* Custom Styles for Table Actions */
        .table-data-feature {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 5px;
        }

        .table-data-feature form {
            margin-bottom: 0 !important;
        }

        /* Vertical Alignment for Table Cells */
        .table tbody td {
            vertical-align: middle !important;
        }

        /* Badge Styles */
        .badge {
            font-size: 12px;
            padding: 5px 10px;
            border-radius: 4px;
        }

        .badge-danger { background-color: #dc3545; color: white; }
        .badge-warning { background-color: #ffc107; color: black; }
        .badge-success { background-color: #28a745; color: white; }
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
                        
                        <?php if (isset($_GET['update']) && $_GET['update'] == 'success'): ?>
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                Status pesan berhasil diperbarui!
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        <?php elseif (isset($_GET['status']) && $_GET['status'] == 'sent'): ?>
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <i class="fa fa-check-circle"></i> Balasan email berhasil dikirim!
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        <?php endif; ?>

                        <div class="row">
                            <div class="col-lg-12">
                                <h2 class="title-1 m-b-25">Daftar Pesan Masuk</h2>
                                <div class="table-responsive table--no-card m-b-40">
                                    <table class="table table-borderless table-striped table-earning">
                                        <thead>
                                            <tr>
                                                <th>ID</th>
                                                <th>Nama Pengirim</th>
                                                <th>Email</th>
                                                <th width="30%">Isi Pesan</th>
                                                <th>Tanggal</th>
                                                <th>Status</th>
                                                <th class="text-center">Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            if ($result && mysqli_num_rows($result) > 0) :
                                                $statuses = ['Baru', 'Sudah Dibaca', 'Sudah Dibalas']; 

                                                while ($row = mysqli_fetch_assoc($result)) :
                                                    // Determine badge color
                                                    $status_val = $row['status'] ?? 'Baru';
                                                    $status_color = 'badge-secondary';
                                                    
                                                    if ($status_val == 'Baru') $status_color = 'badge-danger';
                                                    elseif ($status_val == 'Sudah Dibaca') $status_color = 'badge-warning';
                                                    elseif ($status_val == 'Sudah Dibalas') $status_color = 'badge-success';
                                            ?>
                                            <tr>
                                                <td><?= htmlspecialchars($row['id_pesan'] ?? '') ?></td>
                                                <td><?= htmlspecialchars($row['nama_lengkap'] ?? '-') ?></td>
                                                <td><?= htmlspecialchars($row['email'] ?? '-') ?></td>
                                                
                                                <td style="white-space: normal; text-align: justify;">
                                                    <?= nl2br(htmlspecialchars($row['pesan'] ?? '')) ?>
                                                </td>
                                                
                                                <td>
                                                    <?= !empty($row['tanggal_kirim']) ? date('d M Y H:i', strtotime($row['tanggal_kirim'])) : '-' ?>
                                                </td>
                                                
                                                <td>
                                                    <span class="badge <?= $status_color ?>">
                                                        <?= htmlspecialchars($status_val) ?>
                                                    </span>
                                                </td>
                                                
                                                <td class="text-center">
                                                    <div class="table-data-feature">
                                                        <form method="POST" action="update_status_pesan.php" style="margin:0;">
                                                            <input type="hidden" name="id_pesan" value="<?= $row['id_pesan'] ?? '' ?>">
                                                            
                                                            <div class="input-group input-group-sm">
                                                                <select name="new_status" class="form-control form-control-sm" style="width: 130px;">
                                                                    <?php foreach ($statuses as $status) : ?>
                                                                        <option value="<?= $status ?>" <?= ($status == $status_val) ? 'selected' : '' ?>>
                                                                            <?= $status ?>
                                                                        </option>
                                                                    <?php endforeach; ?>
                                                                </select>
                                                                
                                                                <button type="submit" class="btn btn-primary btn-sm" 
                                                                        style="border-radius: 3px 3px; height: 31px; line-height: 1; display: flex; align-items: center; justify-content: center; margin-top: 3px;">
                                                                    ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎‎  Update ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ ‎ 
                                                                </button>
                                                            </div>
                                                        </form>

                                                        <button type="button" class="btn btn-success btn-sm" title="Balas Pesan"
                                                                onclick="openReplyModal('<?= $row['id_pesan'] ?? '' ?>', '<?= htmlspecialchars($row['email'] ?? '', ENT_QUOTES) ?>', '<?= htmlspecialchars($row['nama_lengkap'] ?? '', ENT_QUOTES) ?>')"
                                                                style="height: 60px; display: flex; align-items: center;">
                                                            <i class="zmdi zmdi-mail-reply" style="margin: 5px;"></i> Balas
                                                        </button>
                                                    </div>
                                                </td>
                                            </tr>
                                            <?php endwhile; ?>
                                            <?php else : ?>
                                            <tr>
                                                <td colspan="7" class="text-center">Tidak ada pesan masuk.</td>
                                            </tr>
                                            <?php endif; mysqli_close($conn); ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-12">
                                <div class="copyright">
                                    <p>Copyright © 2025 Luxury Hotel. All rights reserved.</p>
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

    <div class="modal fade" id="replyModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title text-white" id="replyModalLabel"><i class="fa fa-envelope"></i> Balas Pesan Tamu</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="../php/kirim_balasan.php" method="POST">
                    <div class="modal-body">
                        <input type="hidden" name="id_pesan" id="modal_id_pesan">
                        
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Kepada:</label>
                                <input type="text" class="form-control bg-light" name="email_penerima" id="modal_email" readonly>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Nama Tamu:</label>
                                <input type="text" class="form-control bg-light" name="nama_penerima" id="modal_nama" readonly>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Subjek Balasan:</label>
                            <input type="text" class="form-control" name="subject" value="Re: Pertanyaan Anda di Luxury Hotel" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Isi Pesan:</label>
                            <textarea class="form-control" name="pesan_balasan" rows="6" placeholder="Tulis balasan Anda di sini..." required></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" name="kirim_balasan" class="btn btn-primary">
                            <i class="fa fa-paper-plane"></i> Kirim Email
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
    function openReplyModal(id, email, nama) {
        // Set values in modal inputs
        document.getElementById('modal_id_pesan').value = id;
        document.getElementById('modal_email').value = email;
        document.getElementById('modal_nama').value = nama;
        
        // Show Modal
        var myModal = new bootstrap.Modal(document.getElementById('replyModal'));
        myModal.show();
    }
    </script>

</body>
</html>