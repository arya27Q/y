<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

include 'config.php'; 
session_start();
date_default_timezone_set('Asia/Jakarta');

require '../vendor/autoload.php'; 

$id_tamu = $_SESSION['id_tamu'] ?? null;


if (!$id_tamu) {
    if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') {
        header("Content-Type: application/json");
        echo json_encode(["success" => false, "message" => "Sesi Anda telah berakhir. Silakan login kembali."]);
    } else {
        echo "<script>alert('Anda belum login!'); window.location.href='login.php';</script>";
    }
    exit;
}


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['method'])) {
    header("Content-Type: application/json"); 
    
    $metode_pembayaran_dipilih = $_POST['method'];

    $email_tamu = '';
    $nama_tamu = '';
    $detail_tagihan_email = '';
    $total_bayar = 0;

    $conn->autocommit(FALSE);
    
    try {
        
        $sql_get_tamu = "SELECT email, nama_lengkap FROM tamu WHERE id_tamu = ?";
        $stmt_tamu = $conn->prepare($sql_get_tamu);
        $stmt_tamu->bind_param("i", $id_tamu);
        $stmt_tamu->execute();
        $tamu_result = $stmt_tamu->get_result();
        
        if ($tamu_result->num_rows == 0) {
             throw new Exception("Data tamu (ID: $id_tamu) tidak ditemukan di database.");
        }
        $tamu = $tamu_result->fetch_assoc();
        
        $email_tamu = $tamu['email'];
        $nama_tamu = $tamu['nama_lengkap'];

        $sql_get_tagihan = "SELECT p.payment_id, p.total_amount, r.tipe_kamar_dipesan 
                            FROM pembayaran p
                            LEFT JOIN reservasi_kamar r ON p.id_reservasi_ref = r.id_reservasi
                            WHERE p.id_tamu = ? 
                              AND p.status_pembayaran = 'Pending' 
                              AND p.jenis_reservasi = 'kamar' FOR UPDATE";
        
        $stmt_tagihan = $conn->prepare($sql_get_tagihan);
        $stmt_tagihan->bind_param("i", $id_tamu);
        $stmt_tagihan->execute();
        $result_tagihan = $stmt_tagihan->get_result();
        
        if ($result_tagihan->num_rows == 0) {
            throw new Exception("Tidak ada tagihan kamar 'Pending' yang ditemukan.");
        }

        $payment_ids_to_update = [];
        while ($row = $result_tagihan->fetch_assoc()) {
            $payment_ids_to_update[] = $row['payment_id']; 
            $total_bayar += (float)$row['total_amount'];
            $tipe_kamar = $row['tipe_kamar_dipesan'] ?? 'Reservasi Lainnya'; 
            $detail_tagihan_email .= "- " . htmlspecialchars($tipe_kamar) . " (Rp " . number_format($row['total_amount'], 0, ',', '.') . ")<br>";
        }

        $id_list = implode(',', $payment_ids_to_update); 
        $sql_update = "UPDATE pembayaran SET 
                        status_pembayaran = 'Lunas',
                        metode_pembayaran = ?,
                        tanggal_pembayaran = NOW(),
                        payment_ref_code = 'SIMULASI-LUNAS-ROOM'
                       WHERE payment_id IN ($id_list) 
                         AND id_tamu = ? 
                         AND jenis_reservasi = 'kamar'"; 
                       
        $stmt_update = $conn->prepare($sql_update);
        $stmt_update->bind_param("si", $metode_pembayaran_dipilih, $id_tamu);
        
        if (!$stmt_update->execute()) {
            throw new Exception("Gagal update status pembayaran di database.");
        }
        
        $conn->commit();

        $mail = new PHPMailer(true);
        try {
          
            $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com';
            $mail->SMTPAuth   = true;
            $mail->Username   = 'ayrandrapratama@gmail.com'; 
            $mail->Password   = 'sjjt ccdb uwnh tzae';       
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port       = 587;

            $mail->setFrom('ayrandrapratama@gmail.com', 'Luxury Hotel Admin');
            $mail->addAddress($email_tamu, $nama_tamu); 

            $mail->isHTML(true);
            $mail->Subject = 'Konfirmasi Pembayaran Kamar Berhasil - Luxury Hotel';
            $mail->Body    = "<h1>Pembayaran Kamar Berhasil!</h1>
                            <p>Halo $nama_tamu,</p>
                            <p>Terima kasih, kami telah mengonfirmasi pembayaran reservasi kamar Anda sebesar <b>Rp " . number_format($total_bayar, 0, ',', '.') . "</b> 
                            dengan metode <b>$metode_pembayaran_dipilih</b>.</p>
                            <p><b>Detail Tagihan:</b></p>
                            <p>$detail_tagihan_email</p>
                            <p>Hormat kami,<br>Luxury Hotel</p>";

            $mail->send();

        } catch (Exception $e) {
            error_log("Email Gagal (tapi lunas): " . $mail->ErrorInfo);
        }

        echo json_encode(["success" => true]);

    } catch (Exception $e) {
        $conn->rollback();
        echo json_encode(["success" => false, "message" => $e->getMessage()]);
    }
    
    $conn->autocommit(TRUE);
    exit; 
}


$sql = "SELECT r.tipe_kamar_dipesan, r.tanggal_check_in, r.tanggal_check_out, p.total_amount, p.status_pembayaran
        FROM pembayaran p
        JOIN reservasi_kamar r ON p.id_reservasi_ref = r.id_reservasi
        WHERE p.id_tamu = ? 
          AND p.status_pembayaran = 'Pending'
          AND p.jenis_reservasi = 'kamar' 
        ORDER BY r.tanggal_reservasi ASC";

$stmt = $conn->prepare($sql);
if($stmt === false) {
    die("Error saat prepare query (Bagian 2): " . $conn->error); 
}
$stmt->bind_param("i", $id_tamu);
$stmt->execute();
$result = $stmt->get_result();
$stmt->close();

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
            I give my <strong>Consent to personal data processing...</strong>
          </label>
        </div>

        <div class="method-card">
          <div class="left-content">
             <p><strong>E-Payment</strong><br>Visa, Master Card, E-wallet</p>
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
      <h3>My Booking (Pending Payment)</h3>
      <ul id="booking-list">
        <?php if ($result->num_rows > 0): ?>
          <?php while ($row = $result->fetch_assoc()): ?>
            <li>
              <?= htmlspecialchars($row['tipe_kamar_dipesan']) ?>
              (<?= htmlspecialchars($row['tanggal_check_in']) ?> → <?= htmlspecialchars($row['tanggal_check_out']) ?>)
              - Rp.<?= number_format($row['total_amount'], 0, ',', '.') ?>
              <span style="color:orange;">(<?= htmlspecialchars($row['status_pembayaran']) ?>)</span>
            </li>
            <?php $total_semua += (float)$row['total_amount']; ?>
          <?php endwhile; ?>
        <?php else: ?>
          <li>Tidak ada tagihan yang belum dibayar.</li> 
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
   
    confirmBtn.disabled = true;
    confirmBtn.textContent = "Processing...";
    
      fetch("<?php echo basename($_SERVER['PHP_SELF']); ?>", { 
      method: "POST",
      headers: { "Content-Type": "application/x-www-form-urlencoded" },
      body: "method=" + encodeURIComponent(icon.dataset.method)
    })
   .then(res => {
     if (!res.ok) { 
        throw new Error("Server merespon error: " + res.status);
    }
    return res.json();
    })
    .then(data => {
    if (data.success) {
    alert(`Pembayaran via ${icon.dataset.method} berhasil! Email konfirmasi dikirim.`);
    location.reload(); 
    } else {
      alert("Gagal: " + (data.message || 'Terjadi kesalahan.'));
       
      confirmBtn.disabled = false;
      confirmBtn.textContent = "Confirm";
   }
   })
   .catch(err => {
    console.error('Fetch Error:', err);
    alert("Error: " + err.message);
       
      confirmBtn.disabled = false;
      confirmBtn.textContent = "Confirm";
  });
  };

  document.getElementById("closeForm").onclick = function(){
    floatingForm.style.display="none";
    overlay.style.display="none";
  };
};


document.getElementById('userIcon').addEventListener('click', function(event) {
    event.preventDefault();
    document.getElementById('dropdownMenu').classList.toggle('show');
});

</script>
</body>
</html>