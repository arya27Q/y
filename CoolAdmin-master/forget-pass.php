<?php
include_once '../php/config.php';
// use PHPMailer\PHPMailer\PHPMailer;
// use PHPMailer\PHPMailer\Exception;

// // Anda perlu menginstal PHPMailer: composer require phpmailer/phpmailer
// require 'vendor/autoload.php'; // Sesuaikan path jika perlu

$email = "";
$email_err = "";
$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (empty(trim($_POST["email"]))) {
        $email_err = "Silakan masukkan email.";
    } else {
        $email = trim($_POST["email"]);
    }

    if (empty($email_err)) {
        // Cek apakah email ada
        $sql = "SELECT id_admin FROM admin WHERE email = ?";
        if ($stmt = mysqli_prepare($conn, $sql)) {
            mysqli_stmt_bind_param($stmt, "s", $param_email);
            $param_email = $email;

            if (mysqli_stmt_execute($stmt)) {
                mysqli_stmt_store_result($stmt);

                if (mysqli_stmt_num_rows($stmt) == 1) {
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
                } else {
                    // Security: Jangan beri tahu jika email tidak ada
                    $message = "Jika akun dengan email tersebut ada, link reset password telah dikirim.";
                }
            }
            mysqli_stmt_close($stmt);
        }
    }
    mysqli_close($conn);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Required meta tags-->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="au theme template">
    <meta name="author" content="Hau Nguyen">
    <meta name="keywords" content="au theme template">

    <!-- Title Page-->
    <title>Forget Password</title>

    <!-- Fontfaces CSS-->
    <link href="css/font-face.css" rel="stylesheet" media="all">
    <link href="vendor/fontawesome-7.0.1/css/all.min.css" rel="stylesheet" media="all">
    <link href="vendor/mdi-font/css/material-design-iconic-font.min.css" rel="stylesheet" media="all">

    <!-- Bootstrap CSS-->
    <link href="vendor/bootstrap-5.3.8.min.css" rel="stylesheet" media="all">

    <!-- Vendor CSS-->
    <link href="css/aos.css" rel="stylesheet" media="all">
    <link href="vendor/css-hamburgers/hamburgers.min.css" rel="stylesheet" media="all">
    <link href="css/swiper-bundle-11.2.10.min.css" rel="stylesheet" media="all">
    <link href="vendor/perfect-scrollbar/perfect-scrollbar-1.5.6.css" rel="stylesheet" media="all">

    <!-- Main CSS-->
    <link href="css/theme.css" rel="stylesheet" media="all">

</head>

<body>
    <div class="page-wrapper">
        <div class="page-content--bge5">
            <div class="container">
                <div class="login-wrap">
                    <div class="login-content">
                        <div class="login-logo">
                            <a href="login.php">
                                <img src="images/icon/logo.png" alt="CoolAdmin">
                            </a>
                        </div>
                        <div class="login-form">
                            <form action="" method="post">
                                <div class="form-group">
                                    <label>Email Address</label>
                                    <input class="au-input au-input--full" type="email" name="email" placeholder="Email">
                                </div>
                                <button class="au-btn au-btn--block au-btn--green m-b-20" type="submit">submit</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <!-- Bootstrap 5 JS-->
    <!-- Jquery JS-->
    <script src="js/vanilla-utils.js"></script>
    <!-- Bootstrap JS-->
    <script src="vendor/bootstrap-5.3.8.bundle.min.js"></script>
    <!-- Vendor JS       -->
    <script src="vendor/perfect-scrollbar/perfect-scrollbar-1.5.6.min.js"></script>
    <script src="vendor/chartjs/chart.umd.js-4.5.0.min.js"></script>

    <!-- Main JS-->
    <script src="js/bootstrap5-init.js"></script>
    <script src="js/main-vanilla.js"></script>
    <script src="js/swiper-bundle-11.2.10.min.js"></script>
    <script src="js/aos.js"></script>
    <script src="js/modern-plugins.js"></script>

</body>

</html>
<!-- end document-->