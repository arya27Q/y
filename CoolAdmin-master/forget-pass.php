<?php
// File forget-pass.php


// File forget-pass.php

// Sertakan konfigurasi database
include_once '../php/config.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

// KOREKSI PATH: Naik satu level (dari CoolAdmin-master) dan masuk ke vendor
require '../vendor/autoload.php'; 

$email = "";
$message = "";
$msg_type = ""; 


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // Validasi Email
    if (empty(trim($_POST["email"]))) {
        $message = "Silakan masukkan alamat email Anda.";
        $msg_type = "danger";
    } else {
        $email = trim($_POST["email"]);
    }

    if (empty($message)) {
        
        $sql = "SELECT id, username FROM users WHERE email = ?";
        
        if ($stmt = mysqli_prepare($conn, $sql)) {
            mysqli_stmt_bind_param($stmt, "s", $param_email);
            $param_email = $email;

            if (mysqli_stmt_execute($stmt)) {
                
                mysqli_stmt_bind_result($stmt, $user_id, $username);
                mysqli_stmt_store_result($stmt);
                mysqli_stmt_fetch($stmt); 

                if (mysqli_stmt_num_rows($stmt) == 1) {
                    
                    // ==========================================
                    // START: LOGIKA TOKEN & PENGIRIMAN EMAIL
                    // ==========================================

                    // 1. BUAT TOKEN AMAN (WAJIB)
                    $token = bin2hex(random_bytes(32)); 
                    $expires = date("Y-m-d H:i:s", time() + 3600); // 1 jam

                    $reset_link = "http://localhost/web-hotel/admin/reset-password.php?token=" . $token; 

                    // 3. KIRIM EMAIL MENGGUNAKAN PHPMailer
                    $mail = new PHPMailer(true);

                    try {
                        // KREDENSIAL SMTP AMBIL DARI TES ANDA YANG BERHASIL
                        $mail->isSMTP(); 
                        $mail->Host  = 'smtp.gmail.com'; 
                        $mail->SMTPAuth  = true;  
                        $mail->Username  = 'ayrandrapratama@gmail.com'; 
                        $mail->Password  = 'sjjt ccdb uwnh tzae';
                        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; 
                        $mail->Port  = 587;

                        $mail->setFrom('ayrandrapratama@gmail.com', 'Luxury Hotel Admin'); 
                        $mail->addAddress($email, $username); // Email Tujuan (dinamis dari input user)

                        $mail->isHTML(true); 
                        $mail->Subject = 'Permintaan Reset Password Admin Hotel';
                        $mail->Body    = "Halo $username,<br><br>"
                                       . "Kami menerima permintaan reset password. Silakan klik link berikut:<br><br>"
                                       . "<a href='$reset_link'>Reset Password Saya</a><br><br>"
                                       . "Link ini akan kedaluwarsa dalam 1 jam.<br>"
                                       . "Terima kasih.";

                        $mail->send();
                        
                        $message = "Link untuk reset password telah dikirim ke <b>$email</b>";
                        $msg_type = "success";

                    } catch (Exception $e) {
                        // Jika pengiriman gagal (misalnya, masalah koneksi)
                        error_log("Gagal mengirim email: " . $mail->ErrorInfo);
                        $message = "Email gagal dikirim. Kesalahan: {$mail->ErrorInfo}";
                        $msg_type = "danger";
                    }

                
                    
                } else {
                    $message = "Email tersebut tidak terdaftar dalam sistem kami.";
                    $msg_type = "danger";
                }
            } else {
                $message = "Terjadi kesalahan sistem. Silakan coba lagi nanti.";
                $msg_type = "danger";
            }
            mysqli_stmt_close($stmt);
        }
    }
    // Tutup koneksi
    mysqli_close($conn);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Luxury Hotel Password Recovery">
    <title>Forgot Password - Luxury Hotel</title>

    <link href="css/font-face.css" rel="stylesheet" media="all">
    <link href="vendor/fontawesome-7.0.1/css/all.min.css" rel="stylesheet" media="all">
    <link href="vendor/mdi-font/css/material-design-iconic-font.min.css" rel="stylesheet" media="all">
    <link href="vendor/bootstrap-5.3.8.min.css" rel="stylesheet" media="all">
    <link href="css/theme.css" rel="stylesheet" media="all">

</head>


<body>
    <div class="page-wrapper">
        <div class="page-content--bge5">
            <div class="container">
                <div class="login-wrap">
                    <div class="login-content">
                        <div class="login-logo text-center">
                            <a href="login.php">
                                <img src="../CoolAdmin-master/images/logo.png" alt="CoolAdmin">
                            </a>
                            <h3>Password Recovery</h3>
                            <p class="text-muted">Enter your email to reset your password</p>
                        </div>
                        
                        <div class="login-form">
                            
                            <?php if (!empty($message)): ?>
                                <div class="alert-message alert-<?php echo $msg_type; ?>">
                                    <?php if($msg_type == 'success'): ?>
                                        <i class="zmdi zmdi-check-circle mr-2" style="font-size: 18px; margin-right: 10px;"></i>
                                    <?php else: ?>
                                        <i class="zmdi zmdi-alert-triangle mr-2" style="font-size: 18px; margin-right: 10px;"></i>
                                    <?php endif; ?>
                                    <?php echo $message; ?>
                                </div>
                            <?php endif; ?>

                            <form action="" method="post">
                                <div class="form-group">
                                    <label>Email Address</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="zmdi zmdi-email"></i></span>
                                        <input class="au-input form-control" type="email" name="email" placeholder="e.g., admin@hotel.com" required>
                                    </div>
                                </div>
                                
                                <button class="au-btn au-btn--block au-btn--green m-b-20" type="submit">
                                    SEND RESET LINK
                                </button>
                            </form>
                            
                            <a href="login.php" class="btn-back">
                                <i class="zmdi zmdi-arrow-left"></i> Back to Login
                            </a>
                            
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="js/vanilla-utils.js"></script>
    <script src="vendor/bootstrap-5.3.8.bundle.min.js"></script>
    <script src="vendor/perfect-scrollbar/perfect-scrollbar-1.5.6.min.js"></script>
    <script src="js/main-vanilla.js"></script>

</body>

</html>