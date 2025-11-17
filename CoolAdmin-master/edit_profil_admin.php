<?php
// 1. Mulai Sesi & Koneksi
include '../php/session_check.php'; 
include '../php/config.php'; 

// 2. Logika Update & Ambil Data User ($user_data)
include '../php/update_profile.php'; 

// 3. Logika Penentuan Jalur Gambar (Fix Path Issue)
$default_img_path = 'images/icon/avatar-default.jpg'; // Path default (internal)
$current_img_db = $user_data['profile_img'] ?? '';

// Tentukan src untuk tag <img>
if (!empty($current_img_db) && $current_img_db !== $default_img_path) {
    // Cek jika gambar ada di folder uploads (di root project)
    if (strpos($current_img_db, 'uploads/') !== false) {
        // Tambahkan '../' agar bisa keluar dari folder CoolAdmin-master
        $display_src = '../' . $current_img_db;
    } else {
        // Jika path tidak mengandung uploads/, anggap internal
        $display_src = $current_img_db;
    }
} else {
    // Jika kosong atau default
    $display_src = $default_img_path;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Edit Profil Admin</title>

    <link href="css/font-face.css" rel="stylesheet" media="all">
    <link href="vendor/fontawesome-7.0.1/css/all.min.css" rel="stylesheet" media="all">
    <link href="vendor/mdi-font/css/material-design-iconic-font.min.css" rel="stylesheet" media="all">
    <link href="vendor/bootstrap-5.3.8.min.css" rel="stylesheet" media="all">
    <link href="vendor/perfect-scrollbar/perfect-scrollbar-1.5.6.css" rel="stylesheet" media="all">
    <link href="css/theme.css" rel="stylesheet" media="all">
    <link href="css/custom-dashboard.css" rel="stylesheet" media="all">

    <style>
        .alert-message { padding: 15px; margin-bottom: 25px; border-radius: 5px; color: #fff; font-weight: 500; }
        .alert-success { background-color: #28a745; }
        .alert-danger { background-color: #dc3545; }
        .profile-img-preview {
            width: 150px;       /* Lebar Tetap */
            height: 150px;      /* Tinggi Tetap (Harus sama dengan lebar) */
            object-fit: cover;  /* PENTING: Agar gambar tidak gepeng */
            border-radius: 50%; /* Membuat jadi lingkaran */
            border: 3px solid #e5e5e5;
            display: block;
            margin: 0 auto 15px auto; /* Posisi tengah */
        }
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
                        <div class="col-lg-8 offset-lg-2">
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="title-3"><i class="zmdi zmdi-account-o"></i> Edit Profil Admin</h3>
                                </div>
                                <div class="card-body">
                                    
                                    <?php if (isset($success_message) && !empty($success_message)): ?>
                                        <div class="alert-message alert-success">
                                            <i class="zmdi zmdi-check-circle"></i> <?php echo $success_message; ?>
                                        </div>
                                    <?php endif; ?>

                                    <?php if (isset($error_message) && !empty($error_message)): ?>
                                        <div class="alert-message alert-danger">
                                            <i class="zmdi zmdi-alert-triangle"></i> <?php echo $error_message; ?>
                                        </div>
                                    <?php endif; ?>

                                    <form action="" method="post" enctype="multipart/form-data">

                                        <div class="form-group text-center">
                                            <label class="control-label mb-2">Foto Profil</label>
                                            <div style="display: flex; justify-content: center;">
                                                <img src="<?php echo $display_src; ?>" 
                                                     alt="Preview Profil" 
                                                     id="profileImagePreview" 
                                                     class="profile-img-preview">
                                            </div>
                                            <div class="text-center">
                                                <label for="profileImageInput" class="btn btn-sm btn-outline-secondary" style="cursor: pointer;">
                                                    <i class="zmdi zmdi-camera"></i> Ganti Foto
                                                </label>
                                                <input type="file" 
                                                       class="form-control-file" 
                                                       id="profileImageInput" 
                                                       name="profile_image" 
                                                       accept="image/jpeg,image/png" 
                                                       style="display: none;"> <div class="small text-muted mt-2">Max 2MB (JPG/PNG)</div>
                                            </div>
                                        </div>

                                        <hr>

                                        <div class="form-group">
                                            <label for="username" class="control-label mb-1">Username</label>
                                            <input id="username" name="username" type="text" class="au-input au-input--full" 
                                                   value="<?php echo htmlspecialchars($user_data['username'] ?? ''); ?>" required>
                                        </div>

                                        <div class="form-group">
                                            <label for="email" class="control-label mb-1">Email Address</label>
                                            <input id="email" name="email" type="email" class="au-input au-input--full" 
                                                   value="<?php echo htmlspecialchars($user_data['email'] ?? ''); ?>" required>
                                        </div>

                                        <div class="form-group">
                                            <label for="new_password" class="control-label mb-1">Password Baru</label>
                                            <input id="new_password" name="new_password" type="password" class="au-input au-input--full" 
                                                   placeholder="Kosongkan jika tidak ingin mengubah password">
                                        </div>

                                        <div class="form-group">
                                            <label for="confirm_password" class="control-label mb-1">Konfirmasi Password Baru</label>
                                            <input id="confirm_password" name="confirm_password" type="password" class="au-input au-input--full" 
                                                   placeholder="Ulangi password baru">
                                        </div>

                                        <div class="form-group mt-4">
                                            <button id="payment-button" type="submit" name="update_submit" class="btn btn-lg btn-info btn-block">
                                                <i class="fa fa-save fa-lg"></i>&nbsp;
                                                <span id="payment-button-amount">Simpan Perubahan</span>
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
        const imageInput = document.getElementById('profileImageInput');
        const imagePreview = document.getElementById('profileImagePreview');
    
        if (imageInput) {
            imageInput.addEventListener('change', function(event) {
                const file = event.target.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        imagePreview.src = e.target.result;
                    }
                    reader.readAsDataURL(file);
                }
            });
        }
    </script>
    
    <script src="js/vanilla-utils.js"></script>
    
    <script src="vendor/bootstrap-5.3.8.bundle.min.js"></script>
    
    <script src="vendor/perfect-scrollbar/perfect-scrollbar-1.5.6.min.js"></script>
    
    <script src="js/bootstrap5-init.js"></script>
    
    <script src="js/main-vanilla.js"></script>

</body>
</html>
</body>
</html>
</body>
</html>