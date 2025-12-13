<?php
include_once '../php/config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['id_pesan']) && isset($_POST['new_status'])) {
        
        $id_pesan = mysqli_real_escape_string($conn, $_POST['id_pesan']);
        $new_status = mysqli_real_escape_string($conn, $_POST['new_status']);

        // Update status di database
        $sql = "UPDATE pesan_kontak SET status = '$new_status' WHERE id_pesan = '$id_pesan'";

        if (mysqli_query($conn, $sql)) {
            // Jika berhasil, redirect kembali ke tabel pesan dengan notifikasi sukses
            header("Location: tabel_pesan.php?update=success");
            exit();
        } else {
            // Jika gagal
            echo "Error: " . mysqli_error($conn);
        }
    } else {
        echo "Data tidak lengkap.";
    }
} else {
    // Jika bukan metode POST, redirect
    header("Location: tabel_pesan.php");
    exit();
}

mysqli_close($conn);
?>