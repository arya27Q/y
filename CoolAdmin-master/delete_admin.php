<?php
session_start();
include '../php/config.php'; // Pastikan path config.php benar

// 1. Cek Login & Role (Keamanan)
// Hanya user yang login DAN role-nya 'super_admin' yang boleh menghapus
if (!isset($_SESSION['user_id']) || (isset($_SESSION['role']) && $_SESSION['role'] !== 'super_admin')) {
    echo "<script>alert('Anda tidak memiliki akses!'); window.location='data_admin.php';</script>";
    exit();
}

// 2. Proses Hapus
if (isset($_GET['id'])) {
    $id_target = intval($_GET['id']); // Ambil ID dari URL
    $my_id = $_SESSION['user_id'];    // ID user yang sedang login

    // Cek: Jangan sampai menghapus diri sendiri
    if ($id_target == $my_id) {
        echo "<script>alert('Tidak bisa menghapus akun sendiri saat sedang login!'); window.location='data_admin.php';</script>";
        exit();
    }

    // Jalankan Query Hapus
    $stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
    $stmt->bind_param("i", $id_target);

    if ($stmt->execute()) {
        // Jika sukses, balik ke data_admin dengan pesan sukses
        header("Location: data_admin.php?msg=deleted");
    } else {
        echo "Gagal menghapus data: " . $conn->error;
    }
    $stmt->close();
} else {
    // Jika tidak ada ID di URL, kembalikan ke tabel
    header("Location: data_admin.php");
}

$conn->close();
?>