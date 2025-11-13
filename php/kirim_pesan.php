<?php
include 'config.php'; // Hubungkan ke database Anda
header('Content-Type: application/json'); // Kita akan membalas dengan JSON

// Ambil data JSON yang dikirim oleh JavaScript
$data = json_decode(file_get_contents('php://input'), true);

// Ambil data satu per satu
$nama = $data['nama'] ?? '';
$email = $data['email'] ?? '';
$pesan = $data['pesan'] ?? '';

// Validasi sederhana (pastikan tidak kosong)
if (empty($nama) || empty($email) || empty($pesan)) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Semua field harus diisi.'
    ]);
    exit;
}

// Data aman untuk dimasukkan ke database
try {
    $sql = "INSERT INTO pesan_kontak (nama_lengkap, email, pesan) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $nama, $email, $pesan);
    
    if ($stmt->execute()) {
        // Jika berhasil
        echo json_encode([
            'status' => 'success',
            'message' => 'Pesan Anda telah terkirim!'
        ]);
    } else {
        // Jika gagal
        throw new Exception('Gagal menyimpan ke database.');
    }
    
    $stmt->close();
    $conn->close();

} catch (Exception $e) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Terjadi kesalahan: ' . $e->getMessage()
    ]);
}
?>