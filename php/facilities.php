<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
   <link rel="stylesheet" href="../css/facilites.css">

<style>
  .background-container {
 
  height: 60vh;
  display: flex;
  flex-direction: column;
  justify-content: center;
  align-items: center;
  color: white;
  text-align: center;
  padding: 20px;
  background-image: linear-gradient(rgba(0,0,0,0.6), rgba(0,0,0,0.6)),
  url('../img/facilites.png');
  background-blend-mode: darken;
  animation: fadeIn 1.2s ease forwards;
}
</style>

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
    <h1> FACILITIES  </h1>
  </section>
  <section class="paragrapht1">
    <h2> FACILITIES HOTEL </h2>
    <p class="p1">our hotel offers a complete range of facilities designed to make every guest feel at home. Start your day with an energizing session 
        at the fitness studio or enjoy a refreshing workout in the spacious gym area, both equipped with modern machines. For those who prefer 
        relaxation, the indoor temperature-controlled swimming pool provides the perfect spot to unwind in any season. When it comes to dining, 
        our all-you-can-eat restaurant serves a wide variety of delicious meals in a warm and inviting atmosphere. Guests can also stay connected 
        with complimentary public Wi-Fi available in common areas. To ensure maximum comfort, every room is equipped with a reliable water heater, 
        making your stay as convenient as it is enjoyable.</p>
  </section>
   <section id="gallery">
    <h2>This is our facilities hotel</h2>
    <div class="gallery">
      <div><img src="../img/gym.png" alt="gym Hotel"><p>gym hotel</p></div>
      <div><img src="../img/swimming.png" alt="swimming pool Hotel"><p>swimming pool</p></div>
      <div><img src="../img/wifi.png" alt="wifi hotel"><p>wifi hotel</p></div>
      <div><img src="../img/spa.png" alt="spa hotel"><p>spa hotel</p></div>

      <div><img src="../img/laundry.png" alt="laundry hotel"><p>laundry hotel</p></div>
      <div><img src="../img/beach.png" alt="swimming pool Hotel"><p>beach hotel </p></div>
      <div><img src="../img/restaurant.png" alt="wifi hotel"><p> restaurant hotel</p></div>
      <div><img src="../img/swimmingpool2.png" alt="swimming pool Hotel"><p>swimming pool outdoor</p></div>

      <div><img src="../img/breakfast.png" alt="gym Hotel"><p>breakfast bread</p></div>
      <div><img src="../img/breakfastFamily.png" alt="wifi hotel"><p>breakfast family</p></div>
      <div><img src="../img/carpark.png" alt="spa hotel"><p>car park</p></div>
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