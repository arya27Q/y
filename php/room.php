<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Luxury Hotel</title>

  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <link rel="stylesheet" href="../css/room.css">
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
    <?php 
            include 'status_menu.php'; 
        ?>
  </div>
  </header>

  <section class="background" id="home">
    <h1>ROOMS</h1>
  </section>

  <section class="paragrapht1">
    <h2>ROOMS HOTEL</h2>
    <p class="p1">Our hotel offers a complete range of facilities designed to make every guest 
      feel at home. Start your day with an energizing session at the fitness studio or enjoy 
      a refreshing workout in the spacious gym area, both equipped with modern machines. 
      For those who prefer relaxation, the indoor temperature-controlled swimming pool 
      provides the perfect spot to unwind in any season. When it comes to dining, our all-you
      -can-eat restaurant serves a wide variety of delicious meals in a warm and inviting 
      atmosphere. Guests can also stay connected with complimentary public Wi-Fi available 
      in common areas. To ensure maximum comfort, every room is equipped with a reliable water
      heater, making your stay as convenient as it is enjoyable.
    </p>
  </section>

  
  <section class="hotel-rooms">
    <h2>OUR  ROOMS HOTEL</h2>
    <div class="room-box">
      <img src="../img/standartroom.png" alt="Standard Room">
      <div class="room-info">
        <div class="icons">
          <span><i class="fa-solid fa-wifi"></i> Free wifi</span>
          <span><i class="fa-solid fa-wind"></i> Air Conditioning</span>
          <span><i class="fa-solid fa-bed"></i> Extra bed</span>
          <span><i class="fa-solid fa-tv"></i> Smart TV</span>
          <span><i class="fa-solid fa-shower"></i> WaterHeater</span>
          <span><i class="fa-solid fa-utensils"></i> Extra Breakfast</span>
        </div>
        <p>Enjoy a pleasant rest in our Standard Room — simple, elegant, and designed for your comfort.</p>
        <div class="card-room">
        <div class="h3">
        <h3>Standart Room</h3>
        </div>
        </div>
      </div>
    </div>

    <div class="room-box">
      <img src="../img/room.png" alt="Suite Room">
      <div class="room-info">
        <div class="icons">
          <span><i class="fa-solid fa-wifi"></i> Free wifi</span>
          <span><i class="fa-solid fa-wind"></i> Air Conditioning</span>
          <span><i class="fa-solid fa-bed"></i> Extra bed</span>
          <span><i class="fa-solid fa-tv"></i> Smart TV</span>
          <span><i class="fa-solid fa-shower"></i> WaterHeater</span>
          <span><i class="fa-solid fa-utensils"></i> Extra Breakfast</span>
        </div>
        <p>The Deluxe Room offers exclusive comfort with a touch of luxury, perfect for guests seeking a premium stay.</p>
        <div class="card-room">
        <div class="h3">
        <h3>Deluxe Room</h3>
        </div>
        </div>
      </div>
    </div>

    <div class="room-box">
      <img src="../img/suite.png" alt="Deluxe Room">
      <div class="room-info">
        <div class="icons">
          <span><i class="fa-solid fa-wifi"></i> Free wifi</span>
          <span><i class="fa-solid fa-wind"></i> Air Conditioning</span>
          <span><i class="fa-solid fa-bed"></i> Extra bed</span>
          <span><i class="fa-solid fa-tv"></i> Smart TV</span>
          <span><i class="fa-solid fa-shower"></i> WaterHeater</span>
          <span><i class="fa-solid fa-utensils"></i> Extra Breakfast</span>
        </div>
        <p>Our Suite Room offers extra comfort and style, with modern amenities for a more relaxing stay.</p>
        <div class="card-room2">
        <div class="h4">
        <h3>Suite Room</h3>
        </div>
        </div>
      </div>
    </div>
  </section>

  <section class="book-now">
    <a href="reservasi_hotel.html" class="btn-book">Book Now</a>
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
