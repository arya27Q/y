<?php
// 1. Hubungkan ke database
include 'config.php';

// 2. Ambil Info Halaman (Judul & Deskripsi Atas)
$query_info = mysqli_query($conn, "SELECT * FROM room_info LIMIT 1");
$info = mysqli_fetch_assoc($query_info);

// 3. Ambil Daftar Kamar
$query_rooms = mysqli_query($conn, "SELECT * FROM room_list");
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Luxury Hotel - Rooms</title>

  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <link rel="stylesheet" href="../css/room.css">
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

  <section class="background" id="home">
    <h1><?php echo $info['judul_hero']; ?></h1>
  </section>

  <section class="paragrapht1">
    <h2><?php echo $info['judul_intro']; ?></h2>
    <p class="p1">
        <?php echo $info['deskripsi']; ?>
    </p>
  </section>
  
  <section class="hotel-rooms">
    <h2>OUR ROOMS HOTEL</h2>

    <?php 
    // --- MULAI LOOPING KAMAR ---
    while($room = mysqli_fetch_assoc($query_rooms)) { 
    ?>

    <div class="room-box">
      <img src="../img/<?php echo $room['gambar']; ?>" alt="<?php echo $room['nama_kamar']; ?>">
      
      <div class="room-info">
        <div class="icons">
          <span><i class="fa-solid fa-wifi"></i> Free wifi</span>
          <span><i class="fa-solid fa-wind"></i> Air Conditioning</span>
          <span><i class="fa-solid fa-bed"></i> Extra bed</span>
          <span><i class="fa-solid fa-tv"></i> Smart TV</span>
          <span><i class="fa-solid fa-shower"></i> WaterHeater</span>
          <span><i class="fa-solid fa-utensils"></i> Extra Breakfast</span>
        </div>
        
        <p><?php echo $room['deskripsi']; ?></p>
        
        <div class="<?php echo $room['css_card_class']; ?>">
            <div class="<?php echo $room['heading_tag']; ?>">
                <h3><?php echo $room['nama_kamar']; ?></h3>
            </div>
        </div>

      </div>
    </div>

    <?php 
    } // --- SELESAI LOOPING ---
    ?>
    
  </section>

  <section class="book-now">
    <a href="reservasi_hotel.php" class="btn-book">Book Now</a>
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