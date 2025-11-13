<?php
include 'config.php'; // Pastikan path ke koneksi database Anda benar
header('Content-Type: application/json');

$data = json_decode(file_get_contents('php://input'), true);

$kode_promo_input = $data['promo_code'] ?? '';
$original_total = $data['original_total'] ?? 0;

if (empty($kode_promo_input)) {
    echo json_encode(['status' => 'error', 'message' => 'Silakan masukkan kode promo.']);
    exit;
}

if ($original_total <= 0) {
    echo json_encode(['status' => 'error', 'message' => 'Keranjang masih kosong.']);
    exit;
}

// Cek ke tabel promo
$sql_promo = "SELECT * FROM promo 
              WHERE kode_promo = ? 
              AND status = 'aktif' 
              AND tanggal_mulai <= CURDATE() 
              AND tanggal_selesai >= CURDATE()";

$stmt_promo = $conn->prepare($sql_promo);
$stmt_promo->bind_param("s", $kode_promo_input);
$stmt_promo->execute();
$result_promo = $stmt_promo->get_result();

if ($result_promo->num_rows > 0) {
    $promo_data = $result_promo->fetch_assoc();
    
    // Cek apakah promo berlaku untuk 'kamar' atau 'semua'
    if ($promo_data['berlaku_untuk'] == 'kamar' || $promo_data['berlaku_untuk'] == 'semua') {
        
        $new_total = $original_total;
        $discount_amount = 0;

        if ($promo_data['tipe_diskon'] == 'persen') {
            $discount_amount = $original_total * ($promo_data['nilai_diskon'] / 100);
            $new_total = $original_total - $discount_amount;

        } elseif ($promo_data['tipe_diskon'] == 'nominal') {
            // Diskon nominal mengurangi total keranjang
            $discount_amount = $promo_data['nilai_diskon'];
            $new_total = $original_total - $discount_amount;
        }

        if ($new_total < 0) {
            $new_total = 0; // Jangan sampai harga minus
        }
        
        echo json_encode([
            'status' => 'success',
            'message' => 'Promo "' . $promo_data['nama_promo'] . '" berhasil diterapkan!',
            'newTotal' => $new_total,
            'discountAmount' => $discount_amount
        ]);

    } else {
        // Promo ditemukan, tapi bukan untuk kamar
        echo json_encode(['status' => 'error', 'message' => 'Kode promo ini tidak berlaku untuk reservasi kamar.']);
    }

} else {
    // Promo tidak ditemukan atau tidak aktif
    echo json_encode(['status' => 'error', 'message' => 'Kode promo tidak valid atau sudah kedaluwarsa.']);
}

$stmt_promo->close();
$conn->close();
?>