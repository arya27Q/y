<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Luxury Hotel</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <link rel="stylesheet" href="../css/payment_reservation_room.css">
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
  <a href="#" id="userIcon">
    <i class="fa-solid fa-user"></i>
    <i class="fa-solid fa-caret-down"></i>
  </a>
   <div class="dropdown" id="dropdownMenu">
    <?php 
            include 'status_menu.php'; 
        ?>
  </div>
</div>
</header>


  <section class="background">
    <h1> PAYMENT  </h1>
  </section>
<section class="bg">
 <section class="method">
    <h3>PAYMENT METHOD</h3>
  </section>

<section class="payment-section">
  <div class="payment-container">
    <div class="payment-box">
      <div class="consent">
        <label>
          <input type="checkbox">
          I give my <strong>Consent to personal data processing</strong> and confirm that I have read the 
          <a href="#">Cancellation policy</a>, the <a href="#">Online booking</a> rules and the 
          <a href="#">Privacy policy</a>.
        </label>
      </div>

      <div class="method-card">
        <div class="left-content">
          <p><strong>E-Payment</strong><br>Visa, Master Card, E-wallet</p>
          <div class="icons">
            <i class="fa-solid fa-qrcode"></i>
            <i class="fa-brands fa-cc-visa"></i>
            <i class="fa-brands fa-cc-mastercard"></i>
            <i class="fa-solid fa-wallet"></i>
          </div>
        </div>

        <div class="right-content">
          <button class="pay-btn">Pay Now</button>
        </div>
      </div>

     
     <div id="paymentMenu" class="payment-menu">
        <p>Pilih metode pembayaran:</p>
        <button data-method="E-Wallet" onclick="showPayment(this)">E-Wallet</button>
        <button data-method="Visa" onclick="showPayment(this)">Visa</button>
        <button data-method="MasterCard" onclick="showPayment(this)">MasterCard</button>
        <button data-method="QRIS" onclick="showPayment(this)">QRIS</button>
      </div>
    </div>
  </div>

  <div class="payment-box">
    <h3>My Booking</h3>
    <ul id="booking-list"></ul>
    <p>Total : <span id="total-price">0</span></p>
  </div>
</section>
</section>


<div id="overlay"></div>
<div id="floatingForm">
  <h4 id="floatingTitle"></h4>
  <div id="formContent"></div>
  <div id="buttonGroup">
  <button id="confirmBtn">Confirm</button>
  <button id="closeForm">Close</button>
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

  <script src="../js/payment_reservation_room.js"></script>
  <script src="../js/reservation_room.js"></script>
  <script src="../js/user-section.js"></script>
</body>
</html>
