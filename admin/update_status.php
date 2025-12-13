<?php
// 1. Impor konfigurasi database
include_once '../php/config.php';

// Cek apakah permintaan dikirim melalui metode POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Ambil dan bersihkan data dari form
    $id_reservasi = trim($_POST['id_reservasi']);
    $new_status = trim($_POST['new_status']);

    // Pastikan ID Reservasi dan Status Baru terisi dan Status Baru adalah nilai yang valid
    $valid_statuses = ['Booked', 'Checked-In', 'Checked-Out', 'Canceled'];
    if (!empty($id_reservasi) && !empty($new_status) && in_array($new_status, $valid_statuses)) {
        
        // 2. Siapkan Query UPDATE
        $sql = "UPDATE reservasi_kamar SET status_reservasi = ? WHERE id_reservasi = ?";

        if ($stmt = mysqli_prepare($conn, $sql)) {
            // Bind parameter ke statement
            mysqli_stmt_bind_param($stmt, "si", $param_status, $param_id);
            
            // Atur parameter
            $param_status = $new_status;
            $param_id = $id_reservasi;

            // 3. Jalankan Statement
            if (mysqli_stmt_execute($stmt)) {
                // Sukses: Redirect kembali ke halaman index.php
                // INI ADALAH BAGIAN YANG DIUBAH!
                header("location: table.php?update=success"); 
                exit();
            } else {
                // Gagal eksekusi
                die("Oops! Terjadi kesalahan saat memperbarui database.");
            }

            // Tutup statement
            mysqli_stmt_close($stmt);
        }
    } else {
        // Data yang dikirimkan tidak valid
        header("location: index.php?error=invalid_data");
        exit();
    }
} else {
    // Jika diakses tanpa POST, redirect ke halaman index
    header("location: index.php");
    exit();
}

// Tutup koneksi
mysqli_close($conn);
?>