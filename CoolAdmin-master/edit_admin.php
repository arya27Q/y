<?php
session_start();
include '../php/config.php';

// 1. Cek Akses (Hanya Super Admin)
if (!isset($_SESSION['user_id']) || (isset($_SESSION['role']) && $_SESSION['role'] !== 'super_admin')) {
    header("Location: data_admin.php");
    exit();
}

$id_target = isset($_GET['id']) ? intval($_GET['id']) : 0;
$data_admin = null;

// 2. Ambil data admin yang mau diedit
if ($id_target > 0) {
    $stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
    $stmt->bind_param("i", $id_target);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $data_admin = $result->fetch_assoc();
    }
    $stmt->close();
}

// Jika data tidak ketemu
if (!$data_admin) {
    echo "<script>alert('User tidak ditemukan!'); window.location='data_admin.php';</script>";
    exit();
}

// 3. Proses Update saat tombol Simpan ditekan
if (isset($_POST['simpan'])) {
    $username_baru = $_POST['username'];
    $email_baru    = $_POST['email'];
    $role_baru     = $_POST['role'];

    // Siapkan Query
    $stmt_update = $conn->prepare("UPDATE users SET username = ?, email = ?, role = ? WHERE id = ?");
    $stmt_update->bind_param("sssi", $username_baru, $email_baru, $role_baru, $id_target);

    // --- BAGIAN PERBAIKAN (TRY-CATCH) ---
    try {
        if ($stmt_update->execute()) {
            echo "<script>alert('Data berhasil diupdate!'); window.location='data_admin.php?msg=updated';</script>";
        } else {
            echo "<script>alert('Tidak ada perubahan data atau gagal update.');</script>";
        }
    } catch (mysqli_sql_exception $e) {
        // Cek jika errornya karena duplikat (Kode 1062)
        if ($e->getCode() == 1062) {
            echo "<script>alert('GAGAL: Username atau Email tersebut sudah dipakai user lain!');</script>";
        } else {
            // Error lain
            echo "<script>alert('Terjadi error database: " . addslashes($e->getMessage()) . "');</script>";
        }
    }
    // -------------------------------------
    
    $stmt_update->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Admin</title>
    <link href="vendor/bootstrap-5.3.8.min.css" rel="stylesheet" media="all">
    <link href="css/theme.css" rel="stylesheet" media="all">
    <style>
       body { background-color: #f5f5f5; }
        .card-edit {
            max-width: 600px;
            margin: 150px auto; 
            border: none;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        }
        .card-header { background-color: #002877; color: white; font-weight: bold; }
    </style>
</head>
<body>

<div class="container">
    <div class="card card-edit">
        <div class="card-header">
            Edit Data Admin
        </div>
        <div class="card-body">
            <form action="" method="post">
                
                <div class="mb-3">
                    <label class="form-label">Username</label>
                    <input type="text" name="username" class="form-control" value="<?php echo htmlspecialchars($data_admin['username']); ?>" required>
                    <small class="text-muted">Username harus unik.</small>
                </div>

                <div class="mb-3">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control" value="<?php echo htmlspecialchars($data_admin['email']); ?>" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Role / Jabatan</label>
                    <select name="role" class="form-control">
                        <option value="admin" <?php echo ($data_admin['role'] == 'admin') ? 'selected' : ''; ?>>Admin Biasa</option>
                        <option value="super_admin" <?php echo ($data_admin['role'] == 'super_admin') ? 'selected' : ''; ?>>Super Admin</option>
                    </select>
                </div>

                <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                    <a href="data_admin.php" class="btn btn-secondary">Batal</a>
                    <button type="submit" name="simpan" class="btn btn-warning text-white">Simpan Perubahan</button>
                </div>

            </form>
        </div>
    </div>
</div>

</body>
</html>