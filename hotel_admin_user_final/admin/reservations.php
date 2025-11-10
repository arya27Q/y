<?php
session_start();
require '../koneksi.php';
if (!isset($_SESSION['user']) || ($_SESSION['user']['role'] ?? '') !== 'admin') {
    header('Location: ../index.php'); exit;
}
?>
<!doctype html>
<html><head><meta charset="utf-8"><title>Kelola Reservasi</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head><body>
<div class="container mt-4">
  <h3>Kelola Reservasi</h3>
  <p class="text-muted">Halaman placeholder — sistem reservasi asli tidak disentuh.</p>
  <p><a href="dashboard.php">Kembali ke Dashboard</a></p>
</div>
</body></html>
