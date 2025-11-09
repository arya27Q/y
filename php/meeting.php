<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Luxury Hotel</title>

  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <link rel="stylesheet" href="../css/meeting.css">
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

  <section class="background-img" id="home">
    <h1> MEETING ROOM</h1>
  </section>

  <section class="paragrapht2">
    <h2>MEETING ROOM</h2>
    <p class="p2">Kuta Paradiso Hotel is adding the excellent choice for meeting and conventions in Bali,
       with facilities to cater up to 450 participants. The Paradiso ballroom is fully functional as a 
       single venue or in various layout combinations for cocktail parties, presentations, and banquets. 
       Kuta Paradiso’s professional banqueting and convention team support all events held and create 
       amazing imaging setups.
    </p>
  </section>

  
  <section class="hotel-rooms">
    <h2>OUR MEETING ROOMS HOTEL</h2>
    <div class="room-box">
      <img src="./img/ball room.png" alt="Grand Ball room">
      <div class="room-info">
        <div class="icons">
          <span><i class="fa-solid fa-wifi"></i> Free wifi</span>
          <span><i class="fa-solid fa-wind"></i> Air Conditioning</span>
          <span><i class="fa-solid fa-utensils"></i> Extra Food</span>
        </div>
        <p>Enjoy our Grand Ballroom simple, elegant, and designed for your comfort.. 420 m2 / max. 400 paxs.</p>
        <div class="card-room">
        <div class="h3">
        <h3>Grand ballRoom</h3>
        </div>
        </div>
      </div>
    </div>

    <div class="room-box">
      <img src="../img/arjuna ballroom.png" alt="Suite Room">
      <div class="room-info">
        <div class="icons">
          <span><i class="fa-solid fa-wifi"></i> Free wifi</span>
          <span><i class="fa-solid fa-wind"></i> Air Conditioning</span>
          <span><i class="fa-solid fa-utensils"></i> Extra Food</span>
        </div>
        <p>The Arjuna BallRoom offers exclusive comfort with a touch of luxury, perfect for guests seeking a premium stay.</p>
        <div class="card-room">
        <div class="h3">
        <h3>Arjuna Ballroom</h3>
        </div>
        </div>
      </div>
    </div>

    <div class="room-box">
      <img src="../img/ball room.png" alt="Nakula ballroom">
      <div class="room-info">
        <div class="icons">
          <span><i class="fa-solid fa-wifi"></i> Free wifi</span>
          <span><i class="fa-solid fa-wind"></i> Air Conditioning</span>
          <span><i class="fa-solid fa-utensils"></i> Extra Food</span>
        </div>
        <p>Enjoy a  in our Nakula BallRoom — simple, elegant, and designed for your comfort.. 420 m2 / max. 400 paxs.</p>
        <div class="card-room">
        <div class="h3">
        <h3>Nakula Ballroom</h3>
        </div>
        </div>
      </div>
    </div>

  <div class="room-box">
      <img src="./img/bima ball room.png" alt="Deluxe Room">
      <div class="room-info">
        <div class="icons">
          <span><i class="fa-solid fa-wifi"></i> Free wifi</span>
          <span><i class="fa-solid fa-wind"></i> Air Conditioning</span>
          <span><i class="fa-solid fa-utensils"></i> Extra Food</span>
        </div>
        <p>Our Bima BallRoom offers extra comfort and style, with modern amenities for a more relaxing stay.</p>
        <div class="card-room">
        <div class="h3">
        <h3>bima Ballroom</h3>
        </div>
        </div>
      </div>
    </div>
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
