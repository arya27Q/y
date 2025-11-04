<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Luxury Hotel</title>
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
  background-image: linear-gradient(rgba(0,0,0,0.6), rgba(0,0,0,0.6)),
    url('../img/j1.avif');
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
  <div class="dropdown" id="dropdownMenu"></div>

  </header>

  <section class="background" id="home">
    <h1> ABOUT US  </h1>
  </section>

  <section class="about">
    <h2> ABOUT US </h2>
    <p>Welcome to Luxury Hotel, a symbol of elegance and comfort in the heart of the city. 
      Since our establishment in 2015, we have been committed to providing an unforgettable 
      stay with personalized service andworld-class facilities</p>
      <img src="../img/melungker.png" alt="Luxury Hotel">
    <p>Each room and suite is designed with modern elegance and equipped with the latest 
      technology to ensure your comfort and privacy.</p>
      <img src="../img/melungker.png" alt="Luxury Hotel">
      <p>Luxury Hotel also offers a gourmet restaurant, luxurious spa, infinity pool, 
          and 24-hour concierge service to cater to every guest’s need.We believe every 
          moment at our hotel is a special experience. Book your room now and indulge in true luxury.</p>
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


  
