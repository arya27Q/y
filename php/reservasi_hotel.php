<?php
include 'config.php';
session_start();
header("Content-Type: text/html; charset=UTF-8");
date_default_timezone_set('Asia/Jakarta');


if ($_SERVER['REQUEST_METHOD'] === 'POST' && strpos($_SERVER['CONTENT_TYPE'], 'application/json') !== false) {
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
    
    $reservasi_berhasil_semua = true; // Melacak status semua item
    $conn->autocommit(FALSE); // Mulai mode transaksi

    foreach ($data['list'] as $item) {
        $tipe_kamar = $item['name'] ?? '';
        $harga_per_malam_dari_js = $item['price'] ?? 0;
        
        $checkin_str = $item['checkin'] ?? date('Y-m-d');
        $checkout_str = $item['checkout'] ?? date('Y-m-d', strtotime('+1 day'));
        $jumlah_tamu = $item['jumlah_tamu'] ?? 1;

        if (empty($tipe_kamar) || $harga_per_malam_dari_js <= 0) {
            $reservasi_berhasil_semua = false; // Ada item tidak valid
            continue; // Lanjut ke item berikutnya
        }

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

        $total_biaya = $harga_per_malam_dari_js * $jumlah_malam;

        
        // Asumsi kolom status Anda bernama 'status_kamar' dan nilai defaultnya 'Available'
        $sql_get_kamar = "SELECT id_kamar FROM kamar 
                          WHERE tipe_kamar = ? AND status_kamar = 'Available' 
                          ORDER BY id_kamar LIMIT 1 FOR UPDATE"; 
                          // 'FOR UPDATE' mengunci baris ini

        $stmt_get_kamar = $conn->prepare($sql_get_kamar);
        
        if ($stmt_get_kamar === false) {
            error_log("Prepare gagal (SELECT): " . $conn->error);
            $reservasi_berhasil_semua = false;
            continue; 
        }
        
        $stmt_get_kamar->bind_param("s", $tipe_kamar);
        $stmt_get_kamar->execute();
        $result = $stmt_get_kamar->get_result(); 
        
        
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $id_kamar = $row['id_kamar']; // Ini adalah ID kamar spesifik yang tersedia

            // 1. Mulai coba INSERT dan UPDATE
         // 1. Mulai coba TIGA query
            try {
                // Query 1: Masukkan ke reservasi
                $sql_insert = "INSERT INTO reservasi_kamar 
                                (id_tamu, id_kamar, tanggal_reservasi, tanggal_check_in, tanggal_check_out, jumlah_tamu, tipe_kamar_dipesan, total_biaya, status_reservasi)
                                VALUES (?, ?, ?, ?, ?, ?, ?, ?, 'Booked')";
                
                $stmt_insert = $conn->prepare($sql_insert);
                // Pastikan bind_param ini benar
                $stmt_insert->bind_param("iisssisd", $id_tamu, $id_kamar, $tanggal_reservasi, $checkin_str, $checkout_str, $jumlah_tamu, $tipe_kamar, $total_biaya);
                
                if (!$stmt_insert->execute()) {
                    throw new Exception("Gagal insert reservasi: " . $stmt_insert->error);
                }

                // Ambil ID reservasi yang baru saja dibuat
                $new_id_reservasi = $stmt_insert->insert_id;

                // Query 2: Update status kamar
                $sql_update_kamar = "UPDATE kamar SET status_kamar = 'Booked' WHERE id_kamar = ?";
                $stmt_update = $conn->prepare($sql_update_kamar);
                $stmt_update->bind_param("i", $id_kamar);

                if (!$stmt_update->execute()) {
                    throw new Exception("Gagal update status kamar: " . $stmt_update->error);
                }

                // Query 3: Buat catatan tagihan di tabel pembayaran
                $sql_insert_payment = "INSERT INTO pembayaran 
                                        (jenis_reservasi, id_reservasi_ref, id_tamu, total_amount, status_pembayaran, tanggal_pembayaran)
                                        VALUES ('kamar', ?, ?, ?, 'Pending', NOW())";
                                        
                $stmt_payment = $conn->prepare($sql_insert_payment);
                // bind_param: i (id_reservasi_ref), i (id_tamu), d (total_biaya)
                $stmt_payment->bind_param("iid", $new_id_reservasi, $id_tamu, $total_biaya);
                
                if (!$stmt_payment->execute()) {
                     throw new Exception("Gagal membuat catatan pembayaran: " . $stmt_payment->error);
                }

            } catch (Exception $e) {
                // Jika salah satu dari TIGA query gagal, batalkan semua
                error_log("Kesalahan Transaksi: " . $e->getMessage());
                $reservasi_berhasil_semua = false;
            }

        } else {
            // Tidak ada kamar yang tersedia untuk tipe ini
            error_log("Tidak ada kamar TERSEDIA untuk tipe: " . $tipe_kamar);
            $reservasi_berhasil_semua = false;
        }
        
    } // Akhir dari foreach

    
    // Setelah loop selesai, tentukan apakah akan commit atau rollback
    if ($reservasi_berhasil_semua) {
        $conn->commit(); // Simpan semua perubahan jika semua berhasil
        echo json_encode(["status" => "success", "message" => "Reservasi berhasil disimpan."]);
    } else {
        $conn->rollback(); // Batalkan semua perubahan jika ada satu saja yang gagal
        echo json_encode(["status" => "error", "message" => "Gagal menyimpan reservasi. Kemungkinan kamar untuk tipe yang dipilih sudah penuh."]);
    }
    
    $conn->autocommit(TRUE); // Kembalikan ke mode autocommit
    exit; 
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
