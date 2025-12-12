<?php
session_start();
require_once 'config.php'; // Pastikan koneksi DB tersedia

if (!isset($_SESSION['is_logged_in']) || $_SESSION['is_logged_in'] !== true) {
    header("Location: login.php");
    exit();
}

$id_tamu = $_SESSION['id_tamu'];
$tamu_data = null;
$error_message = '';
$success_message = '';

$sql_fetch = "SELECT nama_lengkap, email, no_telepon, nik, alamat, foto_profil FROM Tamu WHERE id_tamu = ?";
$stmt_fetch = mysqli_prepare($conn, $sql_fetch);

if ($stmt_fetch) {
    mysqli_stmt_bind_param($stmt_fetch, "i", $id_tamu);
    mysqli_stmt_execute($stmt_fetch);
    $result_fetch = mysqli_stmt_get_result($stmt_fetch);
    $tamu_data = mysqli_fetch_assoc($result_fetch);
    mysqli_stmt_close($stmt_fetch);
}

if (!$tamu_data) {
    header("Location: logout.php");
    exit();
}

// --- PROSES HAPUS FOTO ---
if (isset($_POST['hapus_foto'])) {
    if (!empty($tamu_data['foto_profil']) && file_exists(__DIR__ . '/' . $tamu_data['foto_profil'])) {
        unlink(__DIR__ . '/' . $tamu_data['foto_profil']);
    }

    $sql_delete_foto = "UPDATE Tamu SET foto_profil = NULL WHERE id_tamu = ?";
    $stmt_delete = mysqli_prepare($conn, $sql_delete_foto);
    mysqli_stmt_bind_param($stmt_delete, "i", $id_tamu);

    if (mysqli_stmt_execute($stmt_delete)) {
        $success_message = "Foto profil berhasil dihapus.";
        $tamu_data['foto_profil'] = null;
    } else {
        $error_message = "Gagal menghapus foto dari database.";
    }
    mysqli_stmt_close($stmt_delete);
}

// --- PROSES SIMPAN PERUBAHAN ---
if ($_SERVER["REQUEST_METHOD"] == "POST" && !isset($_POST['hapus_foto'])) {
    // --- A. Proses Foto Profil ---
    if (isset($_FILES['foto_profil']) && $_FILES['foto_profil']['error'] == UPLOAD_ERR_OK) {
        $file = $_FILES['foto_profil'];
        $upload_dir = __DIR__ . '/profil_uploads/';

        if (!file_exists($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }

        $file_ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        $new_file_name = $id_tamu . '_' . time() . '.' . $file_ext;
        $target_file = $upload_dir . $new_file_name;
        $public_path = 'profil_uploads/' . $new_file_name;

        if (move_uploaded_file($file['tmp_name'], $target_file)) {
            $sql_update_pp = "UPDATE Tamu SET foto_profil = ? WHERE id_tamu = ?";
            $stmt_pp = mysqli_prepare($conn, $sql_update_pp);
            mysqli_stmt_bind_param($stmt_pp, "si", $public_path, $id_tamu);

            if (mysqli_stmt_execute($stmt_pp)) {
                $success_message .= "Foto profil berhasil diperbarui. ";
                $tamu_data['foto_profil'] = $public_path;
            } else {
                $error_message .= "Gagal menyimpan path foto ke database. " . mysqli_error($conn);
                unlink($target_file);
            }
            mysqli_stmt_close($stmt_pp);
        } else {
            $error_message .= "Gagal mengunggah foto. Pastikan folder profil_uploads memiliki izin tulis.";
        }
    }

    // --- B. Proses Data Profil Lain ---
    $new_nama = htmlspecialchars(trim($_POST['nama_lengkap']));
    $new_telepon = htmlspecialchars(trim($_POST['no_telepon']));
    $new_alamat = htmlspecialchars(trim($_POST['alamat']));
    $new_nik = htmlspecialchars(trim($_POST['nik']));

    $sql_update_data = "UPDATE Tamu SET nama_lengkap=?, no_telepon=?, alamat=?, nik=? WHERE id_tamu=?";
    $stmt_data = mysqli_prepare($conn, $sql_update_data);
    mysqli_stmt_bind_param($stmt_data, "ssssi", $new_nama, $new_telepon, $new_alamat, $new_nik, $id_tamu);

    if (mysqli_stmt_execute($stmt_data)) {
        header("Location: profil.php?status=success");
        exit();
    } else {
        $error_message .= "Gagal memperbarui data profil.";
    }
    mysqli_stmt_close($stmt_data);
}

// --- PERSIAPAN DATA UNTUK FORM ---
$has_profile_photo = (isset($tamu_data['foto_profil']) && $tamu_data['foto_profil']);
$foto_profil_url = $has_profile_photo ? htmlspecialchars($tamu_data['foto_profil']) : '';
?>
<!DOCTYPE html>
<html lang="id">
<head>
 <meta charset="UTF-8">
 <meta name="viewport" content="width=device-width, initial-scale=1.0">
 <title>Edit Profil - Luxury Hotel</title>
 <link rel="stylesheet" href="../css/editprofil.css">
 <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
 
</head>
<body>

<header>
    <img src="../img/logo.png" alt="Luxury Hotel">
    
    <button class="menu-toggle" id="menuToggle">
        <i class="fa-solid fa-bars"></i>
    </button>
    <nav id="mainNav">
      <a href="home.php">Home</a>
      <a href="room.php">Room</a>
      <a href="meeting.php">Meeting</a>
      <a href="facilities.php">Facilities</a>
      <a href="about.php">About us</a>
    </nav>
    
    <div class="user-menu">
      <a class="a" href="#" id="userIcon" style="color:#0026ff!important;">
        <i class="fa-solid fa-user"></i>
        <i class="fa-solid fa-caret-down"></i>
      </a>
    </div>
    <div class="dropdown" id="dropdownMenu">
      <?php include 'status_menu.php'; ?>
    </div>
</header>

<section class="profile-banner">
 <h1>EDIT PROFILE</h1>
</section> 

<section class="bg">
<section class="profile-section"> 
<h2 class="h2">Edit My Profile</h2>

<?php if ($success_message): ?>
    <div class="alert success"><?php echo $success_message; ?></div>
<?php endif; ?>
<?php if ($error_message): ?>
    <div class="alert error"><?php echo $error_message; ?></div>
<?php endif; ?>

<div class="profile-card"> 
<form action="editprofil.php" method="POST" enctype="multipart/form-data">

 <div class="profile-picture-container">
    <?php if ($has_profile_photo): ?>
        <img id="profile-pic-preview" src="<?php echo $foto_profil_url; ?>" alt="Foto Profil Pengguna">
    <?php else: ?>
        <i class="fa-solid fa-user profile-icon-placeholder" id="profile-icon-placeholder"></i>
    <?php endif; ?>
 </div>

 <div class="upload-actions">
    <label for="foto_profil" class="upload-label">
        <i class="fa fa-camera"></i> Ubah Foto Profil
    </label>
    <input type="file" name="foto_profil" id="foto_profil" accept="image/*" style="display: none;">

    <?php if ($has_profile_photo): ?>
        <button type="submit" name="hapus_foto" class="delete-btn">
            <i class="fa fa-trash"></i> Hapus Foto
        </button>
    <?php endif; ?>
 </div>

 <h3>My Profile Account</h3>
 <div class="form-row">
 <input type="text" name="nama_lengkap" value="<?php echo htmlspecialchars($tamu_data['nama_lengkap']); ?>" placeholder="Nama Lengkap" required>
 <input type="text" name="no_telepon" value="<?php echo htmlspecialchars($tamu_data['no_telepon']); ?>" placeholder="Nomor Telepon">
 <input type="text" name="alamat" value="<?php echo htmlspecialchars($tamu_data['alamat']); ?>" placeholder="Alamat Lengkap">
 </div>

 <div class="form-row">
 <input type="email" value="<?php echo htmlspecialchars($tamu_data['email']); ?>" placeholder="Email" readonly>
 <input type="text" name="nik" value="<?php echo htmlspecialchars($tamu_data['nik']); ?>" placeholder="NIK (Nomor Induk Kependudukan)">
 <div style="flex: 1;"></div> 
 </div>
    
 <div class="form-row button-row" style="margin-top: 30px;">
    <button type="submit" class="save-btn">Simpan Perubahan</button>
    <button type="button" class="cancel-btn" onclick="window.location.href='profil.php'">Batal</button>
    <button type="button" onclick="window.location.href='ubahpassword.php'" class="change-password-btn">Ubah Password</button>
 </div>

</form>
</div>
</section>
</section>

<footer>
    <div class="footer-container">
    <div class="footer-left">
    <p>&copy; Luxury Hotel 2025</p>
      <p>Surabaya, Indonesia</p>
      <p>Your Comfort, Our Priority</p>
     </div>
    <div class="footer-center">
    <a href="#top" class="btn back-top">
     <i class="fa-solid fa-arrow-up"></i> Back to Top
    </a>
 </div>
 <div class="footer-right">
 <p><i class="fa-brands fa-instagram"></i> @luxuryhotel</p>
 <p><i class="fa-solid fa-phone"></i> 6289566895155</p>
 <p><i class="fa-solid fa-envelope"></i> luxuryhotelsby@gmail.com</p>
 </div>
 </div>
</footer>

<script src="../js/user-section.js"></script>
<script src="../js/mobile_menu.js"></script>
<script>
document.getElementById('foto_profil').addEventListener('change', function(event) {
    const [file] = event.target.files;
    let previewImg = document.getElementById('profile-pic-preview');
    const container = document.querySelector('.profile-picture-container');
    const placeholderIcon = document.getElementById('profile-icon-placeholder');

    if (file) {
        if (!previewImg) {
            if (placeholderIcon) placeholderIcon.remove();
            previewImg = document.createElement('img');
            previewImg.id = 'profile-pic-preview';
            previewImg.alt = 'Foto Profil Pengguna';
            container.appendChild(previewImg);
        }
        previewImg.src = URL.createObjectURL(file);
    }
});
</script>
</body>
</html>
