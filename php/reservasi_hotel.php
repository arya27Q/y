<?php
include 'config.php';
session_start();
header("Content-Type: text/html; charset=UTF-8");
date_default_timezone_set('Asia/Jakarta');

// kalau request dari JavaScript (fetch JSON)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && str_contains($_SERVER['CONTENT_TYPE'], 'application/json')) {
    header("Content-Type: application/json");

    $id_tamu = $_SESSION['id_tamu'] ?? null;
    if (!$id_tamu) {
        echo json_encode(["status" => "error", "message" => "Anda belum login."]);
        exit;
    }

    $input = file_get_contents("php://input");
    $data = json_decode($input, true);

    // Debug log
    file_put_contents("debug_reservasi.txt", print_r($data, true));

    if (!isset($data["list"]) || !is_array($data["list"]) || !isset($data["total"])) {
        echo json_encode(["status" => "error", "message" => "Data reservasi tidak valid."]);
        exit;
    }

    $tanggal_reservasi = date('Y-m-d H:i:s');

    foreach ($data['list'] as $item) {
        $tipe_kamar = $item['name'] ?? '';
        // HARGA YANG DITERIMA DARI JS HANYA HARGA PER MALAM
        $harga_per_malam_dari_js = $item['price'] ?? 0; 
        
        $checkin_str = $item['checkin'] ?? date('Y-m-d');
        $checkout_str = $item['checkout'] ?? date('Y-m-d', strtotime('+1 day'));
        $jumlah_tamu = $item['jumlah_tamu'] ?? 1;

        if (empty($tipe_kamar) || $harga_per_malam_dari_js <= 0) continue;

        // --- PENTING: PERHITUNGAN DURASI MENGINAP (PHP) ---
        try {
            $checkin = new DateTime($checkin_str);
            $checkout = new DateTime($checkout_str);
            $interval = $checkin->diff($checkout);
            $jumlah_malam = $interval->days;

            if ($jumlah_malam <= 0) {
                $jumlah_malam = 1;
            }
        } catch (Exception $e) {
            $jumlah_malam = 1; 
        }

        // PENTING: Hitung TOTAL BIAYA = Harga Per Malam * Jumlah Malam
        $total_biaya = $harga_per_malam_dari_js * $jumlah_malam; 
        // ------------------------------------------

        // Cek ID Kamar dan Lanjutkan INSERT
        // ... (Kode untuk SELECT id_kamar) ...

        if ($result->num_rows > 0) {
            // ...
            $sql_insert = "INSERT INTO reservasi_kamar 
                (id_tamu, id_kamar, tanggal_reservasi, tanggal_check_in, tanggal_check_out, jumlah_tamu, tipe_kamar_dipesan, total_biaya, status_reservasi)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, 'Booked')";
            $stmt_insert = $conn->prepare($sql_insert);
            // PENTING: Bind variabel $total_biaya (BUKAN $harga_per_malam_dari_js)
            $stmt_insert->bind_param("iisssisd", $id_tamu, $id_kamar, $tanggal_reservasi, $checkin_str, $checkout_str, $jumlah_tamu, $tipe_kamar, $total_biaya);
            $stmt_insert->execute();
        }
    }

    echo json_encode(["status" => "success", "message" => "Reservasi berhasil disimpan."]);
    exit; // stop di sini biar HTML nggak ikut ke kirim
}
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Luxury Hotel</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
    <link rel="stylesheet" href="../css/reservasi_room.css" />
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
        background-image: linear-gradient(rgba(0, 0, 0, 0.356), rgba(0, 0, 0, 0.4)), url('../img/re.png');
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
          <?php include 'status_menu.php'; ?>
        </div>
      </div>
    </header>

    <section class="background">
      <h1>RESERVATION ROOM</h1>
    </section>

    <section class="why">
      <h2 class="stay">
        Book Your Stay With Us Start your unforgettable Jakarta experience—choose your dates and reserve your room today!
      </h2>
      <h2>✨ Why Choose Us?</h2>
      <p>✔ Prime Location ...</p>
      <p>✔ Smart, Stylish Rooms ...</p>
      <p>✔ In-House Entertainment ...</p>
      <p>✔ 24/7 Services ...</p>
      <p>✔ Affordable Luxury ...</p>
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
            <label>Check-in <input type="date" id="checkin" /></label>
            <label>Check-out <input type="date" id="checkout" /></label>
            <label>Order date <input type="date" id="orderdate" /></label>
          </div>

          <div class="booking-list">
            <div class="room-card">
              <img src="../img/standartroom.png" alt="Standard Room" />
              <h3>Standard Room</h3>
              <p>Rp.250.000,00</p>
              <div class="options">
                <button onclick="addBooking('Standard Room', 250000)">Select</button>
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
            <button id="bookNow" class="btn-book">Book Now</button>
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
          <a href="#top" class="btn back-top"><i class="fa-solid fa-arrow-up"></i> Back to Top</a>
        </div>
        <div class="footer-right">
          <p><i class="fa-brands fa-instagram"></i> @luxuryhotel</p>
          <p><i class="fa-solid fa-phone"></i> 6289566895155</p>
          <p><i class="fa-solid fa-envelope"></i> luxuryhotelsby@gmail.com</p>
        </div>
      </div>
    </footer>

    <script src="../js/reservation_room.js"></script>
    <script src="../js/user-section.js"></script>
  </body>
</html>
