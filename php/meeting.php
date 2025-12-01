<?php
// 1. Hubungkan ke database
include 'config.php';

// 2. Ambil Info Halaman (Judul & Deskripsi Atas)
$query_info = mysqli_query($conn, "SELECT * FROM meeting_info LIMIT 1");
$info = mysqli_fetch_assoc($query_info);

// 3. Ambil Daftar Ruangan Meeting
$query_rooms = mysqli_query($conn, "SELECT * FROM meeting_list");
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Luxury Hotel - Meeting</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <link rel="stylesheet" href="../css/meeting.css">
</head>
<body id="top">
 
  <header>
    <img src="../img/logo.png" alt="Luxury Hotel">
    <nav>
      <a href="home.php">Home</a>
      <a href="room.php">Room</a>
      <a href="meeting.php">Meeting</a>
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

  <section class="background-img" id="home">
    <h1> <?php echo $info['judul_hero']; ?> </h1>
  </section>

  <section class="paragrapht2">
    <h2> <?php echo $info['judul_intro']; ?> </h2>
    <p class="p2">
        <?php echo $info['deskripsi_intro']; ?>
    </p>
  </section>

  <section class="hotel-rooms">
    <h2>OUR MEETING ROOMS HOTEL</h2>
    
    <?php 
    // --- MULAI LOOPING RUANGAN ---
    while($room = mysqli_fetch_assoc($query_rooms)) { 
    ?>

    <div class="room-box">
      <img src="../img/<?php echo $room['gambar']; ?>" alt="<?php echo $room['nama_ruangan']; ?>">
      
      <div class="room-info">
        <div class="icons">
          <span><i class="fa-solid fa-wifi"></i> Free wifi</span>
          <span><i class="fa-solid fa-wind"></i> Air Conditioning</span>
          <span><i class="fa-solid fa-utensils"></i> Extra Food</span>
        </div>
        
        <p><?php echo $room['deskripsi']; ?></p>
        
        <div class="card-room">
          <div class="h3">
            <h3><?php echo $room['nama_ruangan']; ?></h3>
          </div>
        </div>
      </div>
    </div>

    <?php 
    } // --- SELESAI LOOPING ---
    ?>

  </section>

  <section class="book-now">
    <a href="meeting_reservasi.php" class="btn-book2">Book Now</a>
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