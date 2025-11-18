<?php
include_once '../php/config.php';

// Variabel untuk pesan notifikasi
$email = "";
$email_err = "";
$message = "";
$msg_type = ""; // 'success' atau 'danger'

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (empty(trim($_POST["email"]))) {
        $email_err = "Silakan masukkan email.";
        $msg_type = "danger";
    } else {
        $email = trim($_POST["email"]);
    }

    if (empty($email_err)) {
        // Cek apakah email ada di tabel 'users' (sesuaikan nama tabel Anda, sebelumnya 'admin')
        // Asumsi tabel user Anda bernama 'users' sesuai konteks sebelumnya
        $sql = "SELECT id FROM users WHERE email = ?"; 
        
        if ($stmt = mysqli_prepare($conn, $sql)) {
            mysqli_stmt_bind_param($stmt, "s", $param_email);
            $param_email = $email;

            if (mysqli_stmt_execute($stmt)) {
                mysqli_stmt_store_result($stmt);

                if (mysqli_stmt_num_rows($stmt) == 1) {
<<<<<<< HEAD
                    // Email ada, buat token
                    $token = bin2hex(random_bytes(50));
                    $expires = date("Y-m-d H:i:s", strtotime('+1 hour'));

                    // Simpan token ke database
                    $sql_update = "UPDATE admin SET reset_token = ?, reset_token_expires = ? WHERE email = ?";
                    if ($stmt_update = mysqli_prepare($conn, $sql_update)) {
                        mysqli_stmt_bind_param($stmt_update, "sss", $token, $expires, $email);
                        mysqli_stmt_execute($stmt_update);
                        mysqli_stmt_close($stmt_update);

                        // Kirim email (Gunakan PHPMailer)
                        $mail = new PHPMailer(true);
                        $reset_link = "http://localhost/folder-proyek-anda/reset-password.php?to
                        
                        
                        
                        
                        
                        
                        
                        
                        
                        
                        
                        
                        
                        
                        
                        
                        
                        
                        
                        
                        
                        
                        
                        
                        
                        
                        
                        
                        
                        
                        
                        
                        
                        
                        
                        
                        
                        
                        
                        
                        
                        
                        
                        
                        
                        
                        
                        
                        
                        
                        
                        
                        
                        
                        
                        
                        
                        
                        
                        
                        
                        
                        
                        
                        
                        
                        
                        
                        
                        
                        
                        
                        
                        
                        
                        
                        
                        
                        
                        
                        
                        
                        
                        
                        
                        
                        
                        
                        
                        
                        
                        
                        
                        
                        
                        
                        
                        
                        
                        
                        
                        
                        
                        
                        
                        
                        
                        
                        
                        
                        
                        
                        
                        
                        
                        
                        
                        
                        
                        
                        
                        
                        
                        
                        
                        
                        
                        
                        
                        
                        
                        
                        
                        
                        
                        
                        
                        
                        
                        
                        
                        
                        
                        
                        
                        
                        
                        
                        
                        
                        
                        
                        
                        
                        
                        
                        
                        
                        
                        
                        
                        
                        
                        
                        
                        
                        
                        
                        
                        
                        
                        
                        
                        
                        
                        
                        
                        
                        
                        
                        
                        
                        
                        
                        
                        
                        
                      
                        
                        ken=" . $token; // SESUAIKAN LINK INI

                        try {
                            // Pengaturan Server (Gunakan SMTP, contoh: Gmail)
                            // $mail->isSMTP();
                            // $mail->Host       = 'smtp.gmail.com';
                            // $mail->SMTPAuth   = true;
                            // $mail->Username   = 'emailanda@gmail.com';
                            // $mail->Password   = 'password-app-gmail-anda';
                            // $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                            // $mail->Port       = 587;

                            // //Penerima
                            // $mail->setFrom('no-reply@hotelanda.com', 'Admin Hotel');
                            // $mail->addAddress($email);

                            // //Konten
                            // $mail->isHTML(true);
                            // $mail->Subject = 'Reset Password Akun Admin Hotel';
                            // $mail->Body    = 'Klik link berikut untuk mereset password Anda: <a href="' . $reset_link . '">' . $reset_link . '</a>';
                            
                            // $mail->send();
                            
                            // --- HAPUS BAGIAN DI BAWAH INI JIKA SUDAH MENGATUR EMAIL ---
                            // Ini hanya simulasi karena email belum di-setup
                            echo "<b>SIMULASI (Hapus ini nanti):</b> Link reset Anda adalah: <a href='$reset_link'>$reset_link</a>";
                            // --- BATAS SIMULASI ---

                            $message = "Jika akun dengan email tersebut ada, link reset password telah dikirim.";

                        } catch (Exception $e) {
                            $message = "Gagal mengirim email. Mailer Error: {$mail->ErrorInfo}";
                        }
                    }






























                    
=======
                    // Email ditemukan! (Simulasi Reset)
                    // Di sistem nyata, di sini Anda generate token & kirim email via PHPMailer
                    
                    // Simulasi Sukses
                    $message = "Link reset password telah dikirim ke email Anda (Simulasi).";
                    $msg_type = "success";
                    
                    // TODO: Implementasi PHPMailer di sini jika sudah siap
                    
>>>>>>> d1fc39ca29ddcb084285019d74476b24bac6292e
                } else {
                    // Email tidak ditemukan, tapi demi keamanan kita beri pesan umum
                    $message = "Jika email terdaftar, link reset telah dikirim.";
                    $msg_type = "success";
                }
            } else {
                $message = "Terjadi kesalahan sistem. Coba lagi nanti.";
                $msg_type = "danger";
            }
            mysqli_stmt_close($stmt);
        }
    } else {
        $message = $email_err;
    }
    mysqli_close($conn);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Luxury Hotel Forgot Password">
    <title>Forgot Password - Luxury Hotel Admin</title>

    <link href="css/font-face.css" rel="stylesheet" media="all">
    <link href="vendor/fontawesome-7.0.1/css/all.min.css" rel="stylesheet" media="all">
    <link href="vendor/mdi-font/css/material-design-iconic-font.min.css" rel="stylesheet" media="all">
    <link href="vendor/bootstrap-5.3.8.min.css" rel="stylesheet" media="all">
    <link href="css/theme.css" rel="stylesheet" media="all">

    <style>
        body {
            font-family: 'Poppins', sans-serif;
            font-size: 14px;
        }
        
        /* Background Hotel Mewah */
        .page-content--bge5 {
            /* Gambar Background yang sama dengan Login/Register agar konsisten */
            background: url('https://images.unsplash.com/photo-1566073771259-6a8506099945?ixlib=rb-1.2.1&auto=format&fit=crop&w=1950&q=80') no-repeat center center fixed; 
            background-size: cover;
            position: relative;
            z-index: 1;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 15px;
        }

        .page-content--bge5::before {
            content: "";
            position: absolute;
            top: 0; left: 0; right: 0; bottom: 0;
            background: rgba(0, 0, 0, 0.5); /* Overlay gelap */
            z-index: -1;
        }

        /* Kartu Form */
        .login-wrap {
            width: 100%;
            max-width: 450px;
            margin: 0 auto;
        }

        .login-content {
            background: #ffffff;
            border-radius: 15px;
            box-shadow: 0 15px 35px rgba(0,0,0,0.3);
            padding: 35px 35px;
            border-top: 4px solid #002877;
        }

        /* Header Logo */
        .login-logo img {
            max-height: 60px;
            margin-bottom: 15px;
        }
        
        .login-logo h3 {
            color: #002877;
            font-weight: 700;
            font-size: 20px;
            margin-bottom: 5px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .text-muted {
            font-size: 13px;
            color: #666 !important;
            margin-bottom: 25px !important;
        }

        /* Form Elements */
        .form-group {
            margin-bottom: 20px !important;
        }

        .form-group label {
            font-weight: 600;
            color: #555;
            margin-bottom: 8px;
            font-size: 13px;
        }

        .input-group-text {
            background: #f0f2f5;
            border: 1px solid #e1e1e1;
            border-right: none;
            color: #002877;
            height: 42px;
            font-size: 14px;
        }

        .au-input {
            border: 1px solid #e1e1e1;
            border-left: none;
            border-radius: 0 5px 5px 0;
            height: 42px;
            padding-left: 10px;
            font-size: 14px;
            transition: all 0.3s ease;
        }

        .au-input:focus {
            border-color: #002877;
            box-shadow: none;
        }
        
        .input-group:focus-within .input-group-text {
            border-color: #002877;
        }

        /* Tombol Submit */
        .au-btn--green {
            background: linear-gradient(45deg, #002877, #0044cc);
            border: none;
            border-radius: 30px;
            padding: 12px;
            font-size: 14px;
            font-weight: 600;
            letter-spacing: 1px;
            text-transform: uppercase;
            box-shadow: 0 4px 10px rgba(0, 40, 119, 0.3);
            transition: all 0.3s ease;
            width: 100%;
            margin-top: 10px;
        }

        .au-btn--green:hover {
            background: linear-gradient(45deg, #001f5c, #003399);
            transform: translateY(-2px);
            box-shadow: 0 6px 15px rgba(0, 40, 119, 0.4);
        }
        
        /* Tombol Batal/Kembali */
        .btn-back {
            display: block;
            text-align: center;
            margin-top: 20px;
            color: #666;
            font-weight: 500;
            text-decoration: none;
            font-size: 13px;
            transition: color 0.3s;
        }
        
        .btn-back:hover {
            color: #002877;
            text-decoration: none;
        }
        
        .btn-back i {
            margin-right: 5px;
        }

        /* Alert Styling */
        .alert-message {
            padding: 12px 15px;
            margin-bottom: 20px;
            border-radius: 6px;
            font-size: 13px;
            display: flex;
            align-items: center;
            line-height: 1.4;
        }
        .alert-success { 
            background-color: #d4edda; 
            color: #155724; 
            border: 1px solid #c3e6cb; 
        }
        .alert-danger { 
            background-color: #f8d7da; 
            color: #721c24; 
            border: 1px solid #f5c6cb; 
        }
    </style>
</head>

<body>
    <div class="page-wrapper">
        <div class="page-content--bge5">
            <div class="container">
                <div class="login-wrap">
                    <div class="login-content">
                        <div class="login-logo text-center">
                            <a href="login.php">
                                <img src="../img/logo.png" alt="CoolAdmin">
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
    <script src="js/main-vanilla.js"></script>

</body>

</html>