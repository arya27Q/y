<?php
// 1. Hubungkan ke database
include 'config.php';

$query = mysqli_query($conn, "SELECT * FROM halaman_about LIMIT 1");
$data = mysqli_fetch_assoc($query);


if (!$data) {
    $data = [
        'judul' => 'ABOUT US',
        'deskripsi_1' => 'Default description...',
        'deskripsi_2' => 'Default description...',
        'deskripsi_3' => 'Default description...',
        'gambar_background' => 'default.jpg',
        'gambar_hiasan' => 'default.png'
    ];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Luxury Hotel - <?php echo $data['judul']; ?></title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <link rel="stylesheet" href="../css/about.css">

<style>

.background {
  height: 70vh;
  display: flex;
  flex-direction: column;
  justify-content: center;
  align-items: center;
  color: white;
  text-align: center;
  padding: 20px;
  /* PHP echo untuk nama file gambar background */
  background-image: linear-gradient(rgba(0,0,0,0.6), rgba(0,0,0,0.6)),
    url('../img/<?php echo $data['gambar_background']; ?>');
  background-blend-mode: darken;
  background-size: cover;      
  background-position: center;  
  background-repeat: no-repeat; 
}
</style>

</head>
<body id="top">
  <header>
    <img src="../img/logo.png" alt="Luxury Hotel">
    <nav>
      <a href="home.php">Home</a>
      <a href="reservasi_hotel.php">Room</a>
      <a href="meeting_reservasi.php">Meeting</a>
      <a href="facilities.php">Facilities</a>
      <a href="about.php">About us</a>
    </nav>
    <div class="user-menu">
      <a class="a" href="#" id="userIcon">
        <i class="fa-solid fa-user"></i>
        <i class="fa-solid fa-caret-down"></i>
      </a>
      <div class="dropdown" id="dropdownMenu">
        <?php include 'status_menu.php'; ?>
      </div>
    </div> </header>

  <section class="background" id="home">
    <h1> <?php echo $data['judul']; ?> </h1>
  </section>

  <section class="about">
    <h2> <?php echo $data['judul']; ?> </h2>
    
    <p><?php echo $data['deskripsi_1']; ?></p>
      
    <img src="../img/<?php echo $data['gambar_hiasan']; ?>" alt="Decoration">
    
    <p><?php echo $data['deskripsi_2']; ?></p>
      
    <img src="../img/<?php echo $data['gambar_hiasan']; ?>" alt="Decoration">
      
    <p><?php echo $data['deskripsi_3']; ?></p>
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
</body>
</html>