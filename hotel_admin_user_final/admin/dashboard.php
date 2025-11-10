<?php
session_start();
require '../koneksi.php';
if (!isset($_SESSION['user']) || ($_SESSION['user']['role'] ?? '') !== 'admin') {
    header('Location: ../index.php');
    exit;
}
$user = $_SESSION['user'];
?>
<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Admin Dashboard - Web Hotel</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
  <style>
    body { min-height:100vh; }
    .sidebar { min-width:220px; max-width:220px; background-color: #0d6efd; }
    .sidebar .nav-link { color: #fff; border-radius: 5px; margin-bottom:5px; transition:0.3s; }
    .sidebar .nav-link:hover { background-color: rgba(255,255,255,0.2); }
    .brand { font-weight:700; color:#fff; }
    .content { width:100%; padding:20px; }
    @media (max-width: 768px) {
        .sidebar { position: fixed; height: 100%; z-index: 1000; left: -250px; transition: left 0.3s; }
        .sidebar.show { left: 0; }
    }
  </style>
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-dark bg-primary">
  <div class="container-fluid">
    <button class="btn btn-outline-light d-md-none" id="sidebarToggle"><i class="bi bi-list"></i></button>
    <span class="navbar-brand mb-0 h1">Web Hotel - Admin</span>
    <div class="d-flex align-items-center">
      <span class="text-white me-3"><?= htmlspecialchars($user['full_name'] ?? $user['username']) ?></span>
      <a href="../logout.php" class="btn btn-sm btn-danger">Logout</a>
    </div>
  </div>
</nav>

<div class="d-flex">
  <!-- Sidebar -->
  <div class="sidebar p-3">
    <h5 class="brand">Admin Panel</h5>
    <ul class="nav flex-column mt-3">
      <li class="nav-item"><a class="nav-link" href="guests.php"><i class="bi bi-people-fill me-2"></i>Kelola Data Tamu</a></li>
      <li class="nav-item"><a class="nav-link" href="reservations.php"><i class="bi bi-calendar-check-fill me-2"></i>Kelola Reservasi</a></li>
      <li class="nav-item"><a class="nav-link" href="payments.php"><i class="bi bi-cash-stack me-2"></i>Kelola Pembayaran</a></li>
    </ul>
  </div>

  <!-- Konten Utama -->
  <div class="content bg-light">
    <div class="container-fluid">
      <h2 class="mb-3">Selamat Datang, <?= htmlspecialchars($user['full_name'] ?? $user['username']) ?>!</h2>
      <p class="text-muted">Ini adalah panel admin—gunakan menu di sebelah kiri untuk mengelola data.</p>

      <div class="row">
        <div class="col-md-4">
          <div class="card mb-3 shadow-sm">
            <div class="card-body">
              <h5 class="card-title"><i class="bi bi-people-fill me-2"></i>Data Tamu</h5>
              <p class="card-text">Kelola semua informasi tamu yang terdaftar di hotel.</p>
            </div>
          </div>
        </div>
        <div class="col-md-4">
          <div class="card mb-3 shadow-sm">
            <div class="card-body">
              <h5 class="card-title"><i class="bi bi-calendar-check-fill me-2"></i>Reservasi</h5>
              <p class="card-text">Kelola semua reservasi kamar dan jadwal check-in/check-out.</p>
            </div>
          </div>
        </div>
        <div class="col-md-4">
          <div class="card mb-3 shadow-sm">
            <div class="card-body">
              <h5 class="card-title"><i class="bi bi-cash-stack me-2"></i>Pembayaran</h5>
              <p class="card-text">Kelola pembayaran tamu, termasuk status dan history transaksi.</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Bootstrap JS & Toggle Sidebar -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
  const sidebar = document.querySelector('.sidebar');
  const toggleBtn = document.getElementById('sidebarToggle');
  toggleBtn?.addEventListener('click', () => {
    sidebar.classList.toggle('show');
  });
</script>
</body>
</html>
