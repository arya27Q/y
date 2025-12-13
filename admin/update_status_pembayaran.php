<?php
include_once '../php/config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // Ambil data dari form
    $payment_id = trim($_POST['payment_id']);
    $new_status = trim($_POST['new_status']);

    // Validasi status
    // Validasi status
    $valid_statuses = ['Pending', 'Paid', 'Lunas', 'Failed', 'Refunded'];
    
    if (!empty($payment_id) && !empty($new_status) && in_array($new_status, $valid_statuses)) {
        
        // Query UPDATE ke tabel 'pembayaran'
        $sql = "UPDATE pembayaran SET status_pembayaran = ? WHERE payment_id = ?";

        if ($stmt = mysqli_prepare($conn, $sql)) {
            mysqli_stmt_bind_param($stmt, "si", $param_status, $param_id);
            
            $param_status = $new_status;
            $param_id = $payment_id;

            if (mysqli_stmt_execute($stmt)) {
                // Sukses: Redirect kembali ke halaman daftar pembayaran
                header("location: tabel_pembayaran.php?update=success"); 
                exit();
            } else {
                die("Oops! Terjadi kesalahan saat memperbarui database.");
            }

            mysqli_stmt_close($stmt);
        }
    } else {
        // Data tidak valid
        header("location: tabel_pembayaran.php?error=invalid_data");
        exit();
    }
} else {
    // Jika diakses tanpa POST
    header("location: tabel_pembayaran.php");
    exit();
}

mysqli_close($conn);
?>