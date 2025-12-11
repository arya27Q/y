<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

include 'config.php'; 
session_start();
date_default_timezone_set('Asia/Jakarta');

require '../vendor/autoload.php'; 

$id_tamu = $_SESSION['id_tamu'] ?? null;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && strpos($_SERVER['CONTENT_TYPE'], 'application/json') !== false) {
    header("Content-Type: application/json"); 

    if (!$id_tamu) {
        echo json_encode(["status" => "error", "message" => "Anda harus login untuk membuat reservasi."]);
        exit;
    }

    $input = file_get_contents("php://input");
    $data = json_decode($input, true);

    if (!isset($data["list"]) || !isset($data["date"]) || !isset($data["startTime"]) || !isset($data["endTime"])) {
        echo json_encode(["status" => "error", "message" => "Data reservasi tidak lengkap."]);
        exit;
    }

    $tanggal_pemesanan = $data['date'];
    $waktu_mulai = $data['startTime'];
    $waktu_selesai = $data['endTime'];
    $booking_list = $data['list'];

    $conn->autocommit(FALSE);

    try {
        
        // LANGKAH A: CEK KETERSEDIAAN (OVERLAP CHECK)
        foreach ($booking_list as $item) {
            $id_ruang = $item['id']; // <-- BENAR (ambil ID)

            $sql_check = "SELECT id_reservasi_meeting FROM reservasi_meeting 
                          WHERE id_ruang = ?  -- <-- BENAR (cek ID)
                          AND tanggal_pemesanan = ?
                          AND status_reservasi != 'Cancelled'
                          AND (waktu_mulai < ? AND waktu_selesai > ?)";
            
            $stmt_check = $conn->prepare($sql_check);
            $stmt_check->bind_param("isss", $id_ruang, $tanggal_pemesanan, $waktu_selesai, $waktu_mulai); // <-- BENAR ("isss")
            $stmt_check->execute();
            $result_check = $stmt_check->get_result();

            if ($result_check->num_rows > 0) {
                $nama_ruang_error = $item['name'];
                throw new Exception("Maaf, '$nama_ruang_error' sudah dibooking pada tanggal dan jam tersebut.");
            }
        }
        
        // LANGKAH B: JIKA SEMUA TERSEDIA, MASUKKAN DATA
        foreach ($booking_list as $item) {
            $id_ruang = $item['id'];
            $nama_ruang = $item['name'];
            $total_biaya_item = $item['subtotal'];
            $jumlah_peserta = 10; 

            // Query INSERT sekarang ada id_ruang
            $sql_insert_meeting = "INSERT INTO reservasi_meeting 
                                    (id_tamu, id_ruang, tanggal_pemesanan, waktu_mulai, waktu_selesai, jumlah_peserta, tipe_ruang_dipesan, total_biaya, status_reservasi)
                                   VALUES (?, ?, ?, ?, ?, ?, ?, ?, 'Booked')"; // <-- BENAR
            
            $stmt_insert = $conn->prepare($sql_insert_meeting);
            $stmt_insert->bind_param("iisssisd", $id_tamu, $id_ruang, $tanggal_pemesanan, $waktu_mulai, $waktu_selesai, $jumlah_peserta, $nama_ruang, $total_biaya_item); // <-- BENAR ("iisssisd")
            
            if (!$stmt_insert->execute()) {
                throw new Exception("Gagal menyimpan data reservasi meeting: " . $stmt_insert->error);
            }

            $new_meeting_id = $stmt_insert->insert_id;

            // Query 3: Buat catatan tagihan (INI SUDAH BENAR)
            $sql_insert_payment = "INSERT INTO pembayaran 
                                    (jenis_reservasi, id_reservasi_ref, id_tamu, total_amount, status_pembayaran, tanggal_pembayaran)
                                   VALUES ('meeting', ?, ?, ?, 'Pending', NOW())";
            
            $stmt_payment = $conn->prepare($sql_insert_payment);
            $stmt_payment->bind_param("iid", $new_meeting_id, $id_tamu, $total_biaya_item);

            if (!$stmt_payment->execute()) {
                throw new Exception("Gagal membuat tagihan pembayaran: " . $stmt_payment->error);
            }
        }

        $conn->commit();
        echo json_encode(["status" => "success", "message" => "Reservasi meeting berhasil disimpan. Anda akan diarahkan ke halaman pembayaran."]);

    } catch (Exception $e) {
        $conn->rollback();
        echo json_encode(["status" => "error", "message" => $e->getMessage()]);
    }
    
    $conn->autocommit(TRUE);
    exit; 
}

$sql_get_rooms = "SELECT id_ruang, nama_ruang, harga_per_jam, deskripsi, kapasitas FROM ruang_meeting";
$result_rooms = $conn->query($sql_get_rooms);

?>
<!DOCTYPE html>
<html lang="en">
  <head>
  	<meta charset="UTF-8" />
  	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
  	<title>Luxury Hotel</title>

  	<link rel="stylesheet"href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
  	<link rel="stylesheet" href="../css/meeting_reservasi.css" />
  	
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
  	<script>
      	// Tambahkan ini untuk cek login di JavaScript
      	const isUserLoggedIn = <?php echo isset($_SESSION['id_tamu']) ? 'true' : 'false'; ?>;
  	</script>
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
  	</header>

  	<section class="background">
  	<h1>RESERVATION MEETING ROOM</h1>
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
	<div class="hotel-info"> <div class="date-form">
    		<label>Meeting Date <input type="date" id="meetingDate" /></label>
    		<label>Start Time <input type="time" id="startTime" step="1800" /></label>
    		<label>End Time <input type="time" id="endTime" step="1800" /></label>
  		</div>

		<div class="booking-list">
      	<?php if ($result_rooms && $result_rooms->num_rows > 0): ?>
        	  	<?php while ($room = $result_rooms->fetch_assoc()): 
          	    	$id_ruang = (int)$room['id_ruang']; 
          	    	$nama_ruang = htmlspecialchars($room['nama_ruang']);
          	    	$harga_per_jam = (float)$room['harga_per_jam'];
          	    	
          	    	$img_name = strtolower(str_replace(' ', '_', $nama_ruang));
          	    	if ($nama_ruang == 'Grand Ballroom') $img_name = 'ball room';
          	    	if ($nama_ruang == 'Arjuna Ballroom') $img_name = 'arjuna ballroom';
          	    	if ($nama_ruang == 'Nakula Ballroom') $img_name = 'nakula ballroom';
          	    	if ($nama_ruang == 'Bima Ballroom') $img_name = 'bima ball room';
          	    	$img_path = '../img/' . $img_name . '.png';
          	?>
          	    	<div class="room-card">
          	    	  	<img src="<?= $img_path ?>" alt="<?= $nama_ruang ?>" />
          	    	  	<h3><?= $nama_ruang ?></h3>
          	    	  	<p>Rp.<?= number_format($harga_per_jam, 0, ',', '.') ?> / jam</p>
          	    	  	<div class="options">
              	    	  	<button onclick="addBooking(<?= $id_ruang ?>, '<?= $nama_ruang ?>', <?= $harga_per_jam ?>)">Select</button>
          	    	  	</div>
          	    	</div>
          	<?php endwhile; ?>
      	<?php else: ?>
        	  	<p>Tidak ada ruang meeting tersedia.</p>
      	<?php endif; ?>
  	</div> <div class="my-booking">
  		<h2>My Booking</h2>
  		<ul id="booking-list-ui"></ul>
        <p>Total : <span id="total-price">Rp. 0</span></p>
  		<button id="bookNowMeeting" class="button2">Book Now</button>
	</div>

	</div> 
</section> 
</section>  
 

<script src="../js/user-section.js"></script> 

<script>
// Variabel global untuk keranjang
let bookingList = [];
let grandTotal = 0;

// PERBAIKAN: Menerima (roomId, roomName, pricePerHour)
function addBooking(roomId, roomName, pricePerHour) {
    const meetingDate = document.getElementById('meetingDate').value;
    const startTime = document.getElementById('startTime').value;
    const endTime = document.getElementById('endTime').value;

    if (!meetingDate || !startTime || !endTime) {
        alert("Silakan isi Tanggal, Jam Mulai, dan Jam Selesai terlebih dahulu!");
        return;
    }

  	const startDate = new Date(`1970-01-01T${startTime}:00`);
  	const endDate = new Date(`1970-01-01T${endTime}:00`);
  	let diffMs = endDate - startDate;
  	if (diffMs <= 0) {
    	alert("Jam Selesai harus setelah Jam Mulai!");
    	return;
  	}
  	const durationHours = diffMs / (1000 * 60 * 60);
  	const subtotal = pricePerHour * durationHours;

  	const bookingItem = {
        id: roomId, // <-- PERBAIKAN: Simpan ID
    	name: roomName,
    	pricePerHour: pricePerHour,
    	duration: durationHours,
    	subtotal: subtotal
  	};
  	bookingList.push(bookingItem);
  	updateBookingUI();
}

// Fungsi untuk update UI keranjang
function updateBookingUI() {
  	const listUI = document.getElementById('booking-list-ui');
  	listUI.innerHTML = ''; 
  	grandTotal = 0;
  	bookingList.forEach((item, index) => {
    	const li = document.createElement('li');
    	li.innerHTML = `
      	  	${item.name} (${item.duration.toFixed(1)} jam) 
      	  	- Rp. ${item.subtotal.toLocaleString('id-ID')}
      	  	<button onclick="removeBooking(${index})" style="color:red; background:none; border:none; cursor:pointer;">X</button>
    	  	`;
    	listUI.appendChild(li);
    	grandTotal += item.subtotal;
  	});
  	document.getElementById('total-price').textContent = `Rp. ${grandTotal.toLocaleString('id-ID')}`;
}

// Fungsi untuk hapus item dari keranjang
function removeBooking(index) {
  	bookingList.splice(index, 1); 
  	updateBookingUI(); 
}

// Event listener untuk tombol "Book Now"
document.getElementById('bookNowMeeting').addEventListener('click', async function() {
  	
  	if (!isUserLoggedIn) {
    	alert('Anda harus login terlebih dahulu untuk melakukan reservasi!');
    	window.location.href = 'login.php';
    	return;
  	}
  	if (bookingList.length === 0) {
    	alert('Keranjang Anda kosong. Silakan pilih ruangan terlebih dahulu.');
    	return;
  	}
  	const meetingDate = document.getElementById('meetingDate').value;
  	const startTime = document.getElementById('startTime').value;
  	const endTime = document.getElementById('endTime').value;
  	if (!meetingDate || !startTime || !endTime) {
    	alert("Data tanggal atau jam tidak lengkap.");
    	return;
  	}
  	const dataToSend = {
    	date: meetingDate,
    	startTime: startTime,
    	endTime: endTime,
    	total: grandTotal,
    	list: bookingList
  	};

  	try {
    	const response = await fetch("meeting_reservasi.php", {
      	  	method: 'POST',
      	  	headers: { 'Content-Type': 'application/json' },
      	  	body: JSON.stringify(dataToSend)
      	});

    	const result = await response.json();

    	if (result.status === 'success') {
      	  	alert(result.message);
      	  	// PERBAIKAN: Arahkan ke halaman pembayaran meeting
      	  	window.location.href = 'payment_meeting.php'; 
      	} else {
      	  	alert("Gagal: " + result.message);
      	}
  	} catch (error) {
    	console.error("Error saat fetch:", error);
    	alert("Terjadi kesalahan koneksi. Coba lagi.");
  	}
});
</script>
  </body>
</html>
