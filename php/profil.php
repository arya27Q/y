<?php
session_start();
require_once 'config.php'; 

if (!isset($_SESSION['is_logged_in']) || $_SESSION['is_logged_in'] !== true) {
    header("Location: login.php");
    exit();
}

$id_tamu = $_SESSION['id_tamu'];
$tamu_data = null;


$sql = "SELECT nama_lengkap, email, no_telepon, nik, alamat, password_hash, foto_profil FROM Tamu WHERE id_tamu = ?";
$stmt = mysqli_prepare($conn, $sql);

if ($stmt) {
    mysqli_stmt_bind_param($stmt, "i", $id_tamu);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $tamu_data = mysqli_fetch_assoc($result);
    mysqli_stmt_close($stmt);
}

if (!$tamu_data) {
    header("Location: logout.php");
    exit();
}

$username = htmlspecialchars($tamu_data['nama_lengkap']); 
$email = htmlspecialchars($tamu_data['email']);
$no_telepon = htmlspecialchars($tamu_data['no_telepon'] ?: 'Belum diisi'); 
$alamat = htmlspecialchars($tamu_data['alamat'] ?: 'Belum diisi');
$nik = htmlspecialchars($tamu_data['nik'] ?: 'Belum diisi');


$display_password = '********'; 


$has_profile_photo = (isset($tamu_data['foto_profil']) && $tamu_data['foto_profil']);
$foto_profil_url = $has_profile_photo
    ? htmlspecialchars($tamu_data['foto_profil']) 
    : ''; 
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Profil Pengguna - Luxury Hotel</title>
  <link rel="stylesheet" href="../css/profil.css">
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
    <h1> MY PROFILE</h1>
  </section>
  
  <section class="bg">
  <section class="profile-section">
    <h2 class="h2">MY Profile</h2>
   <div class="profile-card"> 
    <div class="profile-picture-container">
        <?php if ($has_profile_photo): ?>
            <!-- Jika ada foto di database, tampilkan sebagai IMG -->
            <img id="profile-pic" src="<?php echo $foto_profil_url; ?>" alt="Foto Profil Pengguna">
        <?php else: ?>
            <!-- Jika tidak ada foto (placeholder), tampilkan ikon Font Awesome -->
            <i class="fa-solid fa-user profile-icon-placeholder"></i>
        <?php endif; ?>
    </div>
      <h3>My Profile Account</h3>
     <form id="editForm">
  <div class="form-row">
    <input type="text" id="user-name" value="<?php echo $username; ?>" placeholder="Username" readonly>

    <input type="password" id="user-password" value="<?php echo $display_password; ?>" placeholder="Password" readonly>
    <input type="text" id="user-address" value="<?php echo $alamat; ?>" placeholder="Address" readonly>
  </div>
  <div class="form-row">
    <input type="email" id="user-email" value="<?php echo $email; ?>" placeholder="Email" readonly>
    <input type="text" id="user-phone" value="<?php echo $no_telepon; ?>" placeholder="62+ xxx xxx xxx" readonly>
    <input type="text" id="user-nik" value="<?php echo $nik; ?>" placeholder="NIK" readonly>
  </div>
 <button type="button" class="save-btn" onclick="window.location.href='editprofil.php'">Edit Profil</button>
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
</body>
</html>
