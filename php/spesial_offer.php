<?php

$kode_dari_url = ""; 

if ( isset($_GET['promo_code']) ) {
  
    $kode_dari_url = htmlspecialchars($_GET['promo_code']);
}
?>
<!DOCTYPE html>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Special Offers - Luxury Hotel</title>

  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <link rel="stylesheet" href="../css/spesial_offer.css">
</head>

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
    <?php 
            include 'status_menu.php'; 
        ?>
  </div>
  </header>

  <section class="special-hero">
    <h1><i class="fa-solid fa-gift"></i> Special Offers</h1>
    <p>Enjoy exclusive promotions only at <b>Luxury Hotel</b></p>
  </section>

   <section class="offers-filter">
    <button class="filter-btn active" data-filter="all">All</button>
    <button class="filter-btn" data-filter="easy">Easy for You</button>
    <button class="filter-btn" data-filter="elite">Special for Elite Member</button>
  </section>

<div class="offers">
  <div class="offer-card easy">
    <img src="../img/sale.avif" alt="Daily Deals">
    <h2>✨ Daily Deals</h2>
    <p>Save up to <b>30%</b> on weekday bookings.</p>
   <a href="reservasi_hotel.php?promo_code=DAILYDEALS30" class="btn">Book Now</a>
  </div>

  <div class="offer-card easy">
    <img src="../img/breakfastFamily.png" alt="Breakfast Package">
    <h2>🥞 Breakfast free</h2>
    <p>Free breakfast for <b>2 guests</b> every morning.</p>
   <a href="reservasi_hotel.php?promo_code=FREEBREAKFAST" class="btn">Book Now</a>
  </div>

  <div class="offer-card elite">
    <img src="../img/elit.avif" alt="Member Specials">
    <h2>👑 vip member</h2>
    <p>Exclusive <b>member-only perks</b> & discounts.</p>
   <a href="reservasi_hotel.php?promo_code=VIPMEMBER" class="btn">Book Now</a>
  </div>
</div>



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
 <script src="../js/spesial_offer.js"></script>
 <script src="../js/user-section.js"></script>
</body>
</html>
