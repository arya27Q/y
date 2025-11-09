<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
   <link rel="stylesheet" href="../css/gallery.css">
</head>
<body id="top">
  <header>
    <img src="/img/logo.png" alt="Luxury Hotel">
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
    <?php 
            include 'status_menu.php'; 
        ?>
  </div>
  </header>
  <section class="background-container" id="home">
    <h1> GALLERY </h1>
  </section>
  <section class="paragrapht1">
    <h2> GALLERY HOTEL </h2>
    <p class="p1">Experience Unmatched Comfort and Luxury Our hotel provides a full range 
      of world-class facilities to make your stay unforgettable. Begin your day with an energizing
      workout in our modern fitness studio or spacious gym, equipped with top-tier machines. Relax
      and rejuvenate in our indoor, temperature-controlled swimming pool, perfect for any season. 
      Savor delightful flavors at our all-you-can-eat restaurant, offering a diverse menu in a cozy 
      atmosphere. Stay connected with complimentary high-speed Wi-Fi in all public areas. For your 
      comfort, every room is equipped with a reliable water heater, ensuring a warm and pleasant stay every time.</p>
  </section>
   <section id="gallery">
    <h2>This is our Gallery Hotel</h2>
    <div class="gallery">
      <div><img src="../img/galery1.avif" alt="gallery1"></div>
      <div><img src="../img/gallery2.avif" alt="gallery2"></div>
      <div><img src="../img/gallery3.avif" alt="gallery3"></div>
      <div><img src="../img/gallery4.avif" alt="gallery4"></div>

      <div><img src="../img/gallery5.avif" alt="gallery5"></div>
      <div><img src="../img/gallery6.avif" alt="gallery6"></div>
      <div><img src="../img/gallery7.avif" alt="gallery7"></div>
      <div><img src="../img/gallery8.avif" alt="gallery8"></div>

      <div><img src="../img/gallery9.avif" alt="gallery9"></div>
      <div><img src="../img/swimming.png" alt="swimming"></div>
      <div><img src="../img/swimmingpool2.png" alt="swimmingpool2"></div>
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
</body>
</html>