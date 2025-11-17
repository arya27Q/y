<?php
// File ini di-include oleh halaman admin (misal: edit_profil_admin.php)
// Pastikan session_start() dan config.php sudah dipanggil di file induk.

$user_id = $_SESSION['user_id'] ?? 0;
$user_data = [];
$error_message = '';
$success_message = '';

// Konfigurasi Path
$default_img_internal = 'images/icon/avatar-default.jpg'; // Path default
$upload_dir_physical = '../uploads/profiles/'; // Lokasi simpan fisik (dari folder php/)
$upload_dir_db = 'uploads/profiles/'; // Lokasi simpan string di DB

// ====================================================================
// 1. LOGIKA MEMUAT DATA PENGGUNA (FETCH)
// ====================================================================
if ($user_id > 0) {
    // Ambil data termasuk profile_img
    $stmt_fetch = $conn->prepare("SELECT username, email, profile_img FROM users WHERE id = ?");
    $stmt_fetch->bind_param("i", $user_id);
    $stmt_fetch->execute();
    $result_fetch = $stmt_fetch->get_result();

    if ($result_fetch->num_rows === 1) {
        $user_data = $result_fetch->fetch_assoc();
        
        // Jika kolom profile_img kosong/NULL, set ke default
        if (empty($user_data['profile_img'])) {
            $user_data['profile_img'] = $default_img_internal;
        }
    } else {
        // User tidak ditemukan, logout paksa
        header("Location: logout.php");
        exit();
    }
    $stmt_fetch->close();
}

// ====================================================================
// 2. LOGIKA PEMROSESAN UPDATE (SUBMIT)
// ====================================================================
if (isset($_POST['update_submit'])) {
    
    $new_username = trim($_POST['username']);
    $new_email = trim($_POST['email']);
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];
    
    $update_fields = [];
    $update_types = '';
    $update_values = [];
    $new_profile_img_db_path = null; // Menyimpan path baru untuk DB
    
    // --- 2A. VALIDASI PASSWORD ---
    if (!empty($new_password)) {
        if ($new_password !== $confirm_password) {
            $error_message = "Password baru dan konfirmasi password tidak cocok.";
        } else {
            $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
            $update_fields[] = "password = ?";
            $update_types .= 's';
            $update_values[] = $hashed_password;
        }
    }

    // --- 2B. PROSES UPLOAD GAMBAR ---
    if (empty($error_message)) {
        if (isset($_FILES['profile_image']) && $_FILES['profile_image']['error'] === UPLOAD_ERR_OK) {
            $file = $_FILES['profile_image'];
            $file_name = $file['name'];
            $file_tmp = $file['tmp_name'];
            $file_size = $file['size'];
            
            // Ambil ekstensi file
            $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
            $allowed_extensions = ['jpg', 'jpeg', 'png'];
            
            // Validasi
            if (!in_array($file_ext, $allowed_extensions)) {
                $error_message = "Format file tidak didukung. Hanya JPG, JPEG, dan PNG.";
            } elseif ($file_size > 2 * 1024 * 1024) { // 2MB
                $error_message = "Ukuran file terlalu besar (Max 2MB).";
            } else {
                // Generate nama unik
                $file_new_name = uniqid('profile_', true) . '.' . $file_ext;
                
                // Path tujuan fisik (C:\...\web-hotel\uploads\profiles\...)
                $destination = $upload_dir_physical . $file_new_name;
                
                // Path untuk disimpan di Database (uploads/profiles/...)
                $db_path = $upload_dir_db . $file_new_name;

                // Pindahkan file
                if (move_uploaded_file($file_tmp, $destination)) {
                    $new_profile_img_db_path = $db_path;
                    
                    // Hapus gambar lama (jika ada dan bukan default)
                    $old_img = $user_data['profile_img'];
                    if (!empty($old_img) && $old_img !== $default_img_internal) {
                        // Cek file fisik lama (relatif dari php/)
                        $old_file_phys = '../' . $old_img; 
                        if (file_exists($old_file_phys)) {
                            unlink($old_file_phys);
                        }
                    }
                } else {
                    $error_message = "Gagal mengunggah file ke server.";
                }
            }
        }
    }

    // --- 2C. PERSIAPAN QUERY UPDATE ---
    if (empty($error_message)) {
        
        // Cek perubahan Username
        if ($new_username !== $user_data['username']) {
            $update_fields[] = "username = ?";
            $update_types .= 's';
            $update_values[] = $new_username;
        }

        // Cek perubahan Email
        if ($new_email !== $user_data['email']) {
            $update_fields[] = "email = ?";
            $update_types .= 's';
            $update_values[] = $new_email;
        }

        // Cek perubahan Gambar
        if ($new_profile_img_db_path) {
            $update_fields[] = "profile_img = ?";
            $update_types .= 's';
            $update_values[] = $new_profile_img_db_path;
        }

        // Jika ada field yang berubah, lakukan update
        if (!empty($update_fields)) {
            
            // Cek Duplikasi Username/Email
            $stmt_check = $conn->prepare("SELECT id FROM users WHERE (username = ? OR email = ?) AND id != ?");
            $stmt_check->bind_param("ssi", $new_username, $new_email, $user_id);
            $stmt_check->execute();
            $stmt_check->store_result();

            if ($stmt_check->num_rows > 0) {
                $error_message = "Username atau Email sudah digunakan pengguna lain.";
            } else {
                // Konstruksi Query Dinamis
                $sql_update = "UPDATE users SET " . implode(', ', $update_fields) . " WHERE id = ?";
                $update_types .= 'i';
                $update_values[] = $user_id;

                $stmt_update = $conn->prepare($sql_update);
                $stmt_update->bind_param($update_types, ...$update_values);

                if ($stmt_update->execute()) {
                    $success_message = "Profil berhasil diperbarui!";
                    
                    // Update Session & Data Lokal agar tampilan langsung berubah
                    if ($new_username !== $user_data['username']) {
                        $_SESSION['username'] = $new_username;
                    }
                    
                    $user_data['username'] = $new_username;
                    $user_data['email'] = $new_email;
                    
                    if ($new_profile_img_db_path) {
                        $user_data['profile_img'] = $new_profile_img_db_path;
                    }
                } else {
                    $error_message = "Terjadi kesalahan database: " . $conn->error;
                }
                $stmt_update->close();
            }
            $stmt_check->close();
        } else {
            // Tidak ada field yang berubah tapi tidak error (misal user cuma klik simpan tanpa ubah apa2)
             if (empty($success_message)) {
                 $success_message = "Tidak ada data yang diubah.";
             }
        }
    }
}
?>