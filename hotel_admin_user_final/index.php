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
    .sidebar .nav-link { color: #fff; border-radius:5px; margin-bottom:5px; transition:0.3s; }
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
        <li class="nav-item"><a class="nav-link" href="guests.php"><i class="bi bi-people-fill me-2"></i>Tamu</a></li>

        <!-- Menu Kamar -->
        <li class="nav-item">
            <a class="nav-link collapsed" data-bs-toggle="collapse" href="#collapseKamar" role="button" aria-expanded="false" aria-controls="collapseKamar">
                <i class="bi bi-door-closed-fill me-2"></i>Kamar
            </a>
            <div class="collapse ms-3" id="collapseKamar">
                <ul class="nav flex-column">
                    <li><a class="nav-link text-white" href="rooms.php">Kelola Kamar</a></li>
                    <li><a class="nav-link text-white" href="room_types.php">Jenis Kamar</a></li>
                </ul>
            </div>
        </li>

        <!-- Menu Meeting -->
        <li class="nav-item">
            <a class="nav-link collapsed" data-bs-toggle="collapse" href="#collapseMeeting" role="button" aria-expanded="false" aria-controls="collapseMeeting">
                <i class="bi bi-building me-2"></i>Meeting
            </a>
            <div class="collapse ms-3" id="collapseMeeting">
                <ul class="nav flex-column">
                    <li><a class="nav-link text-white" href="meeting_rooms.php">Ruangan Meeting</a></li>
                    <li><a class="nav-link text-white" href="meeting_reservations.php">Reservasi Meeting</a></li>
                </ul>
            </div>
        </li>

        <li class="nav-item"><a class="nav-link" href="payments.php"><i class="bi bi-cash-stack me-2"></i>Pembayaran</a></li>
    </ul>
  </div>

  <!-- Konten Utama -->
  <div class="content bg-light">
    <div class="container-fluid">
      <h2 class="mb-3">Selamat Datang, <?= htmlspecialchars($user['full_name'] ?? $user['username']) ?>!</h2>
      <p class="text-muted">Gunakan menu di sebelah kiri untuk mengelola semua data hotel.</p>

      <!-- Card Ringkasan Modul -->
      <div class="row">
        <div class="col-md-3">
          <div class="card mb-3 shadow-sm">
            <div class="card-body">
              <h5 class="card-title"><i class="bi bi-people-fill me-2"></i>Tamu</h5>
              <p class="card-text">Kelola semua tamu hotel.</p>
            </div>
          </div>
        </div>
        <div class="col-md-3">
          <div class="card mb-3 shadow-sm">
            <div class="card-body">
              <h5 class="card-title"><i class="bi bi-door-closed-fill me-2"></i>Kamar</h5>
              <p class="card-text">Kelola jenis & ketersediaan kamar.</p>
            </div>
          </div>
        </div>
        <div class="col-md-3">
          <div class="card mb-3 shadow-sm">
            <div class="card-body">
              <h5 class="card-title"><i class="bi bi-building me-2"></i>Meeting</h5>
              <p class="card-text">Kelola ruangan & reservasi meeting dengan jam mulai & akhir.</p>
            </div>
          </div>
        </div>
        <div class="col-md-3">
          <div class="card mb-3 shadow-sm">
            <div class="card-body">
              <h5 class="card-title"><i class="bi bi-cash-stack me-2"></i>Pembayaran</h5>
              <p class="card-text">Kelola pembayaran tamu hotel.</p>
            </div>
          </div>
        </div>
      </div>

      <!-- Contoh Form Reservasi Meeting -->
      <div class="card mt-4 shadow-sm">
        <div class="card-body">
          <h5 class="card-title">Form Reservasi Meeting</h5>
          <form action="save_meeting_reservation.php" method="post">
            <div class="mb-3">
              <label for="meeting_room" class="form-label">Pilih Ruangan</label>
              <select class="form-select" id="meeting_room" name="meeting_room" required>
                <option value="">-- Pilih Ruangan --</option>
                <option value="1">Ruangan A</option>
                <option value="2">Ruangan B</option>
              </select>
            </div>
            <div class="mb-3">
              <label for="date" class="form-label">Tanggal</label>
              <input type="date" class="form-control" id="date" name="date" required>
            </div>
            <div class="mb-3">
              <label for="start_time" class="form-label">Jam Mulai</label>
              <input type="time" class="form-control" id="start_time" name="start_time" required>
            </div>
            <div class="mb-3">
              <label for="end_time" class="form-label">Jam Akhir</label>
              <input type="time" class="form-control" id="end_time" name="end_time" required>
            </div>
            <button type="submit" class="btn btn-primary">Simpan Reservasi</button>
          </form>
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
