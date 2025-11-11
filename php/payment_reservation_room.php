<?php
include 'config.php';
session_start();
date_default_timezone_set('Asia/Jakarta');

$id_tamu = $_SESSION['id_tamu'] ?? null;

if (!$id_tamu) {
  echo "<script>alert('Anda belum login!'); window.location.href='login.php';</script>";
  exit;
}

// === HANDLE PEMBAYARAN ===
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['method'])) {
    $method = $_POST['method'];

    // Pertama, cari timestamp pesanan terbaru yang 'Booked'
    $sql_latest_time = "SELECT MAX(tanggal_reservasi) AS latest_time 
                        FROM reservasi_kamar 
                        WHERE id_tamu = ? AND status_reservasi = 'Booked'";
    $stmt_time = $conn->prepare($sql_latest_time);
    $stmt_time->bind_param("i", $id_tamu);
    $stmt_time->execute();
    $latest_time = $stmt_time->get_result()->fetch_assoc()['latest_time'];
    $stmt_time->close();

    if ($latest_time) {
        // UPDATE semua kamar yang memiliki timestamp terbaru tersebut
        $update = $conn->prepare("UPDATE reservasi_kamar SET status_reservasi = 'Paid', metode_pembayaran = ?, tanggal_pembayaran = NOW() 
                                  WHERE id_tamu = ? AND status_reservasi = 'Booked' AND tanggal_reservasi = ?");
        $update->bind_param("sis", $method, $id_tamu, $latest_time);
        $update->execute();

        if ($update->affected_rows > 0) {
            echo json_encode(["success" => true]);
        } else {
            echo json_encode(["success" => false, "message" => "Tidak ada pesanan aktif yang belum dibayar saat ini."]);
        }
        $update->close();
    } else {
        echo json_encode(["success" => false, "message" => "Tidak ada pesanan aktif atau sudah dibayar."]);
    }
    exit;
}

// === AMBIL DATA RESERVASI ===
$sql_latest_time = "SELECT MAX(tanggal_reservasi) AS latest_time 
                    FROM reservasi_kamar 
                    WHERE id_tamu = ? AND status_reservasi = 'Booked'";
$stmt_time = $conn->prepare($sql_latest_time);
$stmt_time->bind_param("i", $id_tamu);
$stmt_time->execute();
$result_time = $stmt_time->get_result();
$latest_time = $result_time->fetch_assoc()['latest_time'];
$stmt_time->close();

// Jika tidak ada pesanan 'Booked' sama sekali, set result kosong
if (!$latest_time) {
    $result = new mysqli_result(new mysqli()); // Membuat hasil kosong
} else {
    // 2. Ambil SEMUA reservasi yang memiliki tanggal_reservasi yang sama dengan yang terbaru
    $sql = "SELECT * FROM reservasi_kamar 
            WHERE id_tamu = ? 
            AND status_reservasi = 'Booked' 
            AND tanggal_reservasi = ? 
            ORDER BY tanggal_reservasi DESC";

    $stmt = $conn->prepare($sql);
    // Bind parameter: integer (id_tamu) dan string (tanggal_reservasi)
    $stmt->bind_param("is", $id_tamu, $latest_time); 
    $stmt->execute();
    $result = $stmt->get_result();
    $stmt->close();
}

$total_semua = 0;
?>

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
      <?php include 'status_menu.php'; ?>
    </div>
  </div>
</header>

  <section class="background">
    <h1> PAYMENT </h1>
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
            <input type="checkbox" id="consentCheck">
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

        <div id="paymentMenu" class="payment-menu" style="display:none;">
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
      <ul id="booking-list">
        <?php if ($result->num_rows > 0): ?>
          <?php while ($row = $result->fetch_assoc()): ?>
            <li>
              <?= htmlspecialchars($row['tipe_kamar_dipesan']) ?>
              (<?= htmlspecialchars($row['tanggal_check_in']) ?> → <?= htmlspecialchars($row['tanggal_check_out']) ?>)
              - Rp.<?= number_format($row['total_biaya'], 0, ',', '.') ?>
              <?php if ($row['status_reservasi'] === 'Paid'): ?>
                <span style="color:green;">(Paid)</span>
              <?php endif; ?>
            </li>
            <?php $total_semua += (float)$row['total_biaya']; ?>
          <?php endwhile; ?>
        <?php else: ?>
          <li>Tidak ada data pemesanan ditemukan.</li>
        <?php endif; ?>
      </ul>

      <p>Total : <span id="total-price">Rp.<?= number_format($total_semua, 0, ',', '.') ?></span></p>
    </div>

<?php $conn->close(); ?>
  </section>
</section>

<div id="overlay"></div>
<div id="floatingForm">
  <h4 id="floatingTitle"></h4>
  <div id="formContent"></div>
  <div id="buttonGroup">
    <button id="confirmBtn" disabled>Confirm</button>
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

<script>
const payBtn = document.querySelector(".pay-btn");
const paymentMenu = document.getElementById("paymentMenu");

payBtn.addEventListener("click", () => {
  const consent = document.getElementById("consentCheck");
  if (!consent.checked) {
    alert("Silakan centang persetujuan sebelum melanjutkan pembayaran.");
    return;
  }
  paymentMenu.style.display = "block";
});

window.showPayment = function(icon){
  const overlay = document.getElementById("overlay");
  const floatingForm = document.getElementById("floatingForm");
  const title = document.getElementById("floatingTitle");
  const formContent = document.getElementById("formContent");
  const confirmBtn = document.getElementById("confirmBtn");

  overlay.style.display = "block";
  floatingForm.style.display = "block";
  confirmBtn.disabled = true;

  switch(icon.dataset.method){
    case "E-Wallet":
      title.textContent="E-Wallet Payment";
      formContent.innerHTML = `<label>Nomor E-Wallet:</label>
        <input type="text" id="inputField" placeholder="Masukkan nomor e-wallet">`;
      break;
    case "Visa":
      title.textContent="Visa Payment";
      formContent.innerHTML = `
        <label>Nomor Kartu:</label><input type="text" id="inputField" placeholder="Nomor kartu">
        <label>CVV:</label><input type="text" id="cvvField" placeholder="CVV">`;
      break;
    case "MasterCard":
      title.textContent="MasterCard Payment";
      formContent.innerHTML = `
        <label>Nomor Kartu:</label><input type="text" id="inputField" placeholder="Nomor kartu">
        <label>CVV:</label><input type="text" id="cvvField" placeholder="CVV">`;
      break;
    case "QRIS":
      title.textContent="QRIS Payment";
      formContent.innerHTML = `
        <p style="text-align:center;">Scan QR ini untuk bayar:</p>
        <img src="../img/qris.webp" alt="QRIS" width="200" style="display:block;margin:auto;border-radius:10px;">`;
      confirmBtn.disabled = false;
      break;
  }

  const inputField = document.getElementById("inputField");
  const cvvField = document.getElementById("cvvField");
  if(inputField) inputField.addEventListener("input", checkInput);
  if(cvvField) cvvField.addEventListener("input", checkInput);

  function checkInput(){
    if(inputField && cvvField){
      confirmBtn.disabled = !(inputField.value.trim() && cvvField.value.trim());
    } else if(inputField){
      confirmBtn.disabled = !inputField.value.trim();
    }
  }

  confirmBtn.onclick = function(){
    fetch("payment_reservation_room.php", {
      method: "POST",
      headers: { "Content-Type": "application/x-www-form-urlencoded" },
      body: "method=" + encodeURIComponent(icon.dataset.method)
    })
    .then(res => res.json())
    .then(data => {
      if (data.success) {
        alert(`Pembayaran via ${icon.dataset.method} berhasil!`);
        location.reload();
      } else {
        alert("Gagal: " + data.message);
      }
    });
  };

  document.getElementById("closeForm").onclick = function(){
    floatingForm.style.display="none";
    overlay.style.display="none";
  };
};
</script>
</body>
</html>
