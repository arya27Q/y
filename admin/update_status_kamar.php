<?php
include_once '../php/config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // Ambil data dari form
    $id_kamar = trim($_POST['id_kamar']);
    $new_status = trim($_POST['new_status']);

    // Validasi status sesuai ENUM di tabel 'kamar'
    $valid_statuses = ['Available', 'Booked', 'Occupied', 'Cleaning'];
    
    if (!empty($id_kamar) && !empty($new_status) && in_array($new_status, $valid_statuses)) {
        
        // Query UPDATE ke tabel 'kamar'
        $sql = "UPDATE kamar SET status_kamar = ? WHERE id_kamar = ?";

        if ($stmt = mysqli_prepare($conn, $sql)) {
            mysqli_stmt_bind_param($stmt, "si", $param_status, $param_id);
            
            $param_status = $new_status;
            $param_id = $id_kamar;

            if (mysqli_stmt_execute($stmt)) {
                // Sukses: Redirect kembali ke halaman daftar kamar
                header("location: tabel_daftar_kamar.php?update=success"); 
                exit();
            } else {
                die("Oops! Terjadi kesalahan saat memperbarui database.");
            }

            mysqli_stmt_close($stmt);
        }
    } else {
        // Data tidak valid
        header("location: tabel_daftar_kamar.php?error=invalid_data");
        exit();
    }
} else {
    // Jika diakses tanpa POST
    header("location: tabel_daftar_kamar.php");
    exit();
}

mysqli_close($conn);
?>