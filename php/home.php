<?php
include 'config.php'; // Koneksi Database

// 1. Ambil Data Konten Utama (Hero, About, Promo)
$query_content = mysqli_query($conn, "SELECT * FROM home_content LIMIT 1");
$content = mysqli_fetch_assoc($query_content);

// 2. Ambil Data Fitur (Looping)
$query_features = mysqli_query($conn, "SELECT * FROM home_features");

// 3. Ambil Data Reviews (Looping)
$query_reviews = mysqli_query($conn, "SELECT * FROM home_reviews");
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?php echo $content['hero_highlight']; ?></title> 

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
      <a class="a" href="#" id="userIcon" style="color:#0026ff!important;">
        <i class="fa-solid fa-user"></i>
        <i class="fa-solid fa-caret-down"></i>
      </a>
    </div>
    <div class="dropdown" id="dropdownMenu">
      <?php include 'status_menu.php'; ?>
    </div>
  </header>

  <section class="hero" id="home">
    <h1>
        <?php echo $content['hero_title_1']; ?> 
        <span class="blue"><?php echo $content['hero_highlight']; ?></span> ,
        It’s time to enjoy and relax <span class="blue">to go vacation</span>
    </h1>
    <p><?php echo $content['hero_subtitle']; ?></p>
    
    <div class="hero-buttons">
      <a href="spesial_offer.php" class="btn"><i class="fa-solid fa-gift"></i> Special Offers</a>
      <a href="#reservation" class="btn"><i class="fa-solid fa-calendar-check"></i> Book Now</a>
    </div>
  </section>

  <section id="about">
    <h2><?php echo $content['about_title']; ?></h2>
    <p class="p1"><?php echo $content['about_intro']; ?></p>
   
    <div class="features">
      <?php while($fitur = mysqli_fetch_assoc($query_features)) { ?>
        <div>
            <i class="<?php echo $fitur['icon']; ?>"></i>
            <h3><?php echo $fitur['title']; ?></h3>
            <p><?php echo $fitur['description']; ?></p>
        </div>
      <?php } ?>
    </div>

    <div class="info-box">
      <a href="#" class="btn" id="learnMoreBtn">
        <i class="fa-solid fa-circle-info"></i> Learn More
      </a>

      <div class="floating-text" id="floatingText">
        <h3><?php echo $content['modal_title']; ?></h3>
        <p><?php echo $content['modal_p1']; ?></p>
        <p><?php echo $content['modal_p2']; ?></p>
        <p><?php echo $content['modal_p3']; ?></p>
        <p><?php echo $content['modal_p4']; ?></p>
        <button id="closeInfo">Close</button>
      </div>
    </div>
  </section>

  <section id="offers" class="promo">
    <div class="promo-card">
      <div class="promo-container">
        <div class="promo-text">
          <h2 class="h2"><?php echo $content['promo_title']; ?></h2>
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
      <button id="contactBtn" class="btn"><i class="fa-solid fa-phone"></i> Contact Us</button>
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

      <div id="contactStatus" style="margin-top: 10px; font-weight: bold;"></div>

      <div class="contact-buttons">
        <button type="submit" class="submit-btn">Kirim</button>
        <button type="button" id="closeContact" class="close-btn">Tutup</button>
      </div>
    </form>
  </div>

  <section id="reviews">
    <h2>We have a lot of rating from our customers</h2>
    <div class="reviews">
      <?php while($review = mysqli_fetch_assoc($query_reviews)) { ?>
        
        <div class="card">
          <div class="user-info">
            <img src="../img/<?php echo $review['image']; ?>" alt="<?php echo $review['name']; ?>">
            <h3><?php echo $review['name']; ?> <?php echo $review['rating']; ?></h3>
          </div>
          <p><?php echo $review['comment']; ?></p>
        </div>

      <?php } ?>
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