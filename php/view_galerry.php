<?php

include 'config.php'; 

$query_info = mysqli_query($conn, "SELECT * FROM gallery_info LIMIT 1");
$info = mysqli_fetch_assoc($query_info);

$query_foto = mysqli_query($conn, "SELECT * FROM gallery_photos");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Luxury Hotel - Gallery</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="../css/gallery.css">
</head>
<body id="top">
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

  <section class="background-container" id="home">
    <h1> <?php echo $info['judul_utama']; ?> </h1>
  </section>

  <section class="paragrapht1">
    <h2> <?php echo $info['judul_section']; ?> </h2>
    <p class="p1">
        <?php echo $info['deskripsi']; ?>
    </p>
  </section>

  <section id="gallery">
    <h2>This is our Gallery Hotel</h2>
    <div class="gallery">
      <?php 
      
      while($foto = mysqli_fetch_assoc($query_foto)) { 
      ?>
          
          <div>
            <img src="../img/<?php echo $foto['nama_file']; ?>" alt="<?php echo $foto['keterangan']; ?>">
          </div>

      <?php 
      } 
      ?>
    </div>
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