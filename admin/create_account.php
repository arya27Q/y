<?php
include '../php/config.php';

if (isset($_POST['register_submit'])) {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $agree = isset($_POST['aggree']); 

    if (empty($username) || empty($email) || empty($password)) {
        $error_message = "Semua kolom wajib diisi!";
    } elseif (!$agree) {
        $error_message = "Anda harus menyetujui syarat dan ketentuan.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error_message = "Format email tidak valid.";
    } else {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        $stmt_check = $conn->prepare("SELECT id FROM users WHERE username = ? OR email = ?");
        $stmt_check->bind_param("ss", $username, $email);
        $stmt_check->execute();
        $stmt_check->store_result();

        if ($stmt_check->num_rows > 0) {
            $error_message = "Username atau Email sudah terdaftar.";
        } else {
            $stmt_insert = $conn->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
            $stmt_insert->bind_param("sss", $username, $email, $hashed_password);

            if ($stmt_insert->execute()) {
                $success_message = "Pendaftaran berhasil! Silakan masuk.";
                
                echo "<script>
                        alert('Pendaftaran Berhasil! Silakan Login.');
                        window.location.href='../CoolAdmin-master/login.php';
                      </script>";
                exit();
            } else {
                $error_message = "Terjadi kesalahan saat pendaftaran: " . $conn->error;
            }
            $stmt_insert->close();
        }
        $stmt_check->close();
    }
}
?>