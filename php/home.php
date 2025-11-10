<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Luxury Hotel</title>

  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
 <link rel="stylesheet" href="../css/home.css">


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
  <a class="a" href="#" id="userIcon"style="color:#0026ff!important;">
    <i class="fa-solid fa-user"></i>
    <i class="fa-solid fa-caret-down"></i>
  </a>
  </div>
  <div class="dropdown" id="dropdownMenu">
    <?php 
            include 'status_menu.php'; 
        ?>
  </div>
  </header>

  <section class="hero" id="home">
    <h1>Hello and welcome to  <span class="blue">Luxury Hotel</span> ,
        It’s time to enjoy and relax <span class="blue">to go vacation</span></h1>
    <p>every guest is treated like <b>family</b> a cozy <b>stay surrounded </b> by peace</p>
    <div class="hero-buttons">
      <a href="spesial_offer.php" class="btn"><i class="fa-solid fa-gift"></i> Special Offers</a>
      <a href="#reservation" class="btn"><i class="fa-solid fa-calendar-check"></i> Book Now</a>
    </div>
  </section>

  <section id="about">
    <h2>Why do you choose our Luxury Hotel?</h2>
    <p class="p1">We are committed to delivering the best experience for you guys from the comfort of our Standard Rooms, the elegance of our Deluxe Rooms, to the luxury of our Suites</p>
   
    <div class="features">
      <div><i class="fa-solid fa-location-dot"></i><h3>Best Location</h3><p>Enjoy comfort and safety in a strategic location, just steps away from the luxury beach</p></div>
      <div><i class="fa-solid fa-tag"></i><h3>Best Price</h3><p>Comfortable rooms, fair prices, and promos every two months</p></div>
      <div><i class="fa-solid fa-handshake"></i><h3>Friendly Service</h3><p></p>Experience comfort with our friendly staff available anytime 24/7, day or night</div>
      <div><i class="fa-solid fa-shield-halved"></i><h3>Safety Place</h3><p>A safe place to relax with comfort and style, close to the luxury beach</p></div>
    </div>
    <div class="info-box">
  <a href="#" class="btn" id="learnMoreBtn">
    <i class="fa-solid fa-circle-info"></i> Learn More
  </a>

  <div class="floating-text" id="floatingText">
    <h3>Welcome to Luxury Hotel</h3>
    <p>
      Step into a world where comfort meets sophistication. Established in 2015, 
      <b>Luxury Hotel</b> redefines the meaning of elegance in modern hospitality. 
      Nestled in the heart of the city, our hotel offers breathtaking panoramic views 
      of the skyline, luxurious interiors, and personalized services that ensure 
      every guest feels truly special.
    </p>
    <p>
      Each of our rooms and suites is meticulously designed to balance beauty and functionality — 
      featuring high-end furnishings, premium bedding, and smart technology for your convenience. 
      Whether you're here for business, leisure, or a romantic getaway, our environment guarantees 
      both tranquility and inspiration.
    </p>
    <p>
      Guests can indulge in our rooftop infinity pool, rejuvenate at the signature spa, 
      or enjoy a fine dining experience at our award-winning restaurant. 
      For business travelers, our modern meeting rooms and event spaces 
      are equipped with state-of-the-art facilities to make every gathering a success.
    </p>
    <p>
      At Luxury Hotel, every detail matters — from the aroma of our freshly brewed coffee 
      each morning to the soft lighting that welcomes you back after a long day. 
      Come and experience the harmony of luxury, comfort, and warmth that makes 
      every stay unforgettable.
    </p>
    <button id="closeInfo">Close</button>
  </div>
</div>

  </section>

<section id="offers" class="promo">
  <div class="promo-card">
    <div class="promo-container">
      <div class="promo-text">
        <h2 class="h2">Come on Get Your Promo</h2>
        <ul>
          <li><i class="fa-solid fa-check"></i> Discounts up to 30% every month</li>
          <li><i class="fa-solid fa-check"></i> Free breakfast for every room and guest</li>
          <li><i class="fa-solid fa-check"></i> Early check-in & late check-out with no extra charge</li>
          <li><i class="fa-solid fa-check"></i> Free access to gym, pool, and more — only for our guests</li>
        </ul>
      </div>
      <div class="promo-image">
        <img src="../img/promo.png" alt="Luxury Hotel Promo">
      </div>
    </div>
  </div>
</section>

  <section id="gallery">
    <h2>This is our Room Hotel</h2>
    <div class="gallery">
      <div><img src="../img/suite.png" alt="Room Hotel"><p>Suite Room</p></div>
      <div><img src="../img/room.png" alt="Beach Hotel"><p>deluxe room</p></div>
      <div><img src="../img/standartroom.png" alt="Lobby Hotel"><p>standart room</p></div>
    </div>
  </section>

  <section id="reservation">
    <h2>Wanna Reservation? Let’s Go</h2>
    <div class="features">
      <div><i class="fa-solid fa-right-to-bracket"></i><h3>Sign In</h3><p>Please sign in to continue your booking.</p></div>
      <div><i class="fa-solid fa-bed"></i><h3>Choose Room</h3><p>You can choose any type of room.</p></div>
      <div><i class="fa-solid fa-credit-card"></i><h3>Payment</h3><p>You can pay with a card or e-wallet</p></div>
      <div><i class="fa-solid fa-envelope"></i><h3>Confirm Email</h3><p>Check your email for confirmationt</p></div>
    </div>
    <div>
      <a href="reservasi_hotel.php" class="btn"><i class="fa-solid fa-hotel"></i> Start Booking</a>
      <button id="contactBtn" class="btn"><i class="fa-solid fa-phone"></i> Contact Us</button>'
    </div>
      </section>
    <div id="contactOverlay"></div>
  <div id="contactForm">
  <h3>Contact Us</h3>
  <form id="contactFormInner">
    <label for="name">Nama Lengkap:</label>
    <input type="text" id="name" placeholder="input your name : " required>

    <label for="email">Email:</label>
    <input type="email" id="email" placeholder="input your email : " required>

    <label for="message">Pesan:</label>
    <textarea id="message" rows="4" placeholder="input your comment or text to us..." required></textarea>

    <div class="contact-buttons">
      <button type="submit" class="submit-btn">Kirim</button>
      <button type="button" id="closeContact" class="close-btn">Tutup</button>
    </div>
  </form>
</div>


  <section id="reviews">
  <h2>We have a lot of rating from our customers</h2>
  <div class="reviews">
    <div class="card">
      <div class="user-info">
        <img src="../img/daniel.avif" alt="Daniel">
        <h3>Daniel ⭐⭐⭐⭐⭐</h3>
      </div>
      <p>I love this hotel, the beach view is perfect.</p>
    </div>
    <div class="card">
      <div class="user-info">
        <img src="../img/banie.avif" alt="Banie">
        <h3>Banie ⭐⭐⭐⭐⭐</h3>
      </div>
      <p>My family love more and wanna come again.</p>
    </div>
    <div class="card">
      <div class="user-info">
        <img src="../img/nicolas.avif" alt="Nicolas">
        <h3>Nicolas ⭐⭐⭐⭐⭐</h3>
      </div>
      <p>I recommend for you guys to come here so perfect</p>
    </div>
    <div class="card">
      <div class="user-info">
        <img src="../img/jimmy.avif" alt="jimmy">
        <h3>jimmy ⭐⭐⭐⭐⭐</h3>
      </div>
      <p>I WANT TO COME AGAIN AND I LOVE SO MUCH THIS VIEW</p>
    </div>
    <div class="card">
      <div class="user-info">
        <img src="../img/james.avif" alt="norman">
        <h3>norman ⭐⭐⭐⭐⭐</h3>
      </div>
      <p>THIS IS COOL AND I LIKE THANKS</p>
    </div>
    <div class="card">
      <div class="user-info">
        <img src="../img/james.avif" alt="james">
        <h3>james ⭐⭐⭐⭐⭐</h3>
      </div>
      <p>I LOVE THIS GYM AND THE VIEW IS SO COOL</p>
    </div>
    <div class="card">
      <div class="user-info">
        <img src="../img/lily.avif" alt="lily">
        <h3>lily ⭐⭐⭐⭐⭐</h3>
      </div>
      <p>MY FAMILY LOVE MORE AND WANNA COME AGAIN</p>
    </div>
    <div class="card">
      <div class="user-info">
        <img src="../img/bd.avif" alt="budi">
        <h3>dandy ⭐⭐⭐⭐⭐</h3>
      </div>
      <p>MY FAMILY REALLY ENJOY FOR THIS HOTEL THANKSS</p>
    </div>
  </div>
  
</section>

  <section id="gallery">
    <h2>This is our Gallery Hotel</h2>
    <div class="gallery">
      <div><img src="../img/swimming.png" alt="Room Hotel"><p>swimming pool</p></div>
      <div><img src="../img/beach.png" alt="Beach Hotel"><p>Beach Hotel</p></div>
      <div><img src="../img/lobby.png" alt="Lobby Hotel"><p>Lobby Hotel</p></div>
    </div>
    <a href="view_galerry.php" class="btn-galery"><i class="fa-solid fa-images"></i> View All Gallery</a>
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
  <script src="../js/home_learnmore.js"></script>
  <script src="../js/user-section.js"></script>
<script src="../js/home_contactUs.js"></script>

</body>
</html>
