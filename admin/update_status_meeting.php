<?php
// 1. Impor konfigurasi database
include_once '../php/config.php';

// Cek apakah permintaan dikirim melalui metode POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // Ambil ID khusus meeting
    $id_reservasi = trim($_POST['id_reservasi_meeting']); 
    $new_status = trim($_POST['new_status']);

    // Validasi status (sesuai ENUM di database Anda)
    $valid_statuses = ['Booked', 'Checked-In', 'Checked-Out', 'Canceled'];
    
    if (!empty($id_reservasi) && !empty($new_status) && in_array($new_status, $valid_statuses)) {
        
        // Query diubah ke tabel 'reservasi_meeting'
        $sql = "UPDATE reservasi_meeting SET status_reservasi = ? WHERE id_reservasi_meeting = ?";

        if ($stmt = mysqli_prepare($conn, $sql)) {
            // Bind parameter
            mysqli_stmt_bind_param($stmt, "si", $param_status, $param_id);
            
            // Atur parameter
            $param_status = $new_status;
            $param_id = $id_reservasi;

            // Jalankan Statement
            if (mysqli_stmt_execute($stmt)) {
               
                header("location: tabel_meeting.php?update=success"); 
                exit();
            } else {
                die("Oops! Terjadi kesalahan saat memperbarui database meeting.");
            }

            // Tutup statement
            mysqli_stmt_close($stmt);
        }
    } else {
        // Data tidak valid
        header("location: tabel_meeting.php?error=invalid_data");
        exit();
    }
} else {
    // Jika diakses tanpa POST, redirect ke halaman tabel
    header("location: tabel_meeting.php");
    exit();
}

// Tutup koneksi
mysqli_close($conn);
?>