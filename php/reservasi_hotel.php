
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Luxury Hotel</title>

    <link rel="stylesheet"href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
    <link rel="stylesheet" href="../css/reservasi_room.css" />
     <script src="../js/reservation_room.js"></script>
    <script src="../js/user-section.js"></script>

 <style>
        .background {
  height: 60vh;
  display: flex;
  flex-direction: column;
  justify-content: center;
  align-items: center;
  color: white;
  text-align: center;
  padding: 20px;
  background-image: linear-gradient(rgba(0, 0, 0, 0.356), rgba(0, 0, 0, 0.4)),
  url('../img/re.png');
  background-blend-mode: darken;

}
      </style>
  
  </head>
  <body>
    <header>
      <img src="../img/logo.png" alt="Luxury Hotel" />
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

    <section class="background">

    <h1>RESERVATION ROOM</h1>
    </section>
    <section class="why">
      <h2 class="stay">
        Book Your Stay With Us Start your unforgettable Jakarta
        experience—choose your dates and reserve your room today!
      </h2>
      <h2>✨ Why Choose Us?</h2>
      <p>
        ✔ Prime Location:Situated in MGK Kemayoran, our hotel places you close
        to the city's major business hubs and entertainment spots. Easily access
        public transport and explore Jakarta without hassle.
      </p>
      <p>
        ✔ Smart, Stylish Rooms:Our 31 air-conditioned rooms are thoughtfully
        designed for both comfort and productivity. Enjoy high-speed
        Wi-Fi, smart TVs, workstations, and private bathrooms. Smoking and
        non-smoking options are available, and some rooms offer unique layouts
        without windows.
      </p>
      <p>
        ✔ In-House Entertainment:Unwind at our karaoke lounge, indulge in spa
        treatments, or experience Jakarta's nightlife vibes right inside the
        hotel with our lively bar and lounge.
      </p>
      <p>
        ✔ 24/7 Services:We offer round-the-clock room service, a 24-hour front
        desk, and on-site parking (with additional charge), making sure your
        stay is as seamless as it is stylish.
      </p>
      <p>
        ✔ Affordable Luxury:Get the boutique experience without breaking the
        bank. We combine premium amenities with competitive rates to give you
        the best value in Jakarta.
      </p>
    </section>
    
        <section class="bg2">
        <section class="hotel-title">
        <div class="hotel-details">
          <h2>Luxury Hotel Surabaya ⭐⭐⭐⭐⭐</h2>
        </div>
        </section>

    <section class="reservation">
      <div class="hotel-info">
       <div class="date-form">
  <label>
    Check-in
    <input type="date" id="checkin"  />
  </label>
  <label>
    Check-out
    <input type="date" id="checkout" />
  </label>
  <label>
    Order date
    <input type="date" id="orderdate"  />
  </label>
</div>

      <div class="booking-list">
        <div class="room-card">
          <img src="../img/standartroom.png" alt="Standard Room" />
          <h3>Standard Room</h3>
          <p>Rp.250.000,00</p>
          <div class="options">
            <button onclick="addBooking('Standard Room', 250000)">select </button>
          </div>
        </div>

        <div class="room-card">
          <img src="../img/Suite.png" alt="Suite Room" />
          <h3>Suite Room</h3>
          <p>Rp.750.000,00</p>
          <div class="options">
            <button onclick="addBooking('Suite Room', 750000)">Select</button>
          </div>
        </div>

        <div class="room-card">
          <img src="../img/room.png" alt="Deluxe Room" />
          <h3>Deluxe Room</h3>
          <p>Rp.550.000,00</p>
          <div class="options">
            <button onclick="addBooking('Deluxe Room', 550000)">Select</button>
          </div>
        </div>
      </div>

    <div class="my-booking">
      <h2>My Booking</h2>
  <ul id="my-booking-list"></ul>
  <p>Total : <span id="total-price">0</span></p>
  <a href="payment_reservation_room.php" class="btn-book">Book Now</a>
</div>
      </div>
    </section>
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
  </body>
</html>