<?php

// Debug
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Include konfigurasi database
include_once 'config.php';

// PHPMailer
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require '../vendor/autoload.php';

$message = "";
$email   = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    // Validasi email
    if (empty(trim($_POST['email']))) {
        $message = "Silakan masukkan alamat email Anda.";
    } else {
        $email = trim($_POST['email']);
    }

    if (empty($message)) {

        // Cek email di tabel tamu
        $sql = "SELECT id_tamu, nama_lengkap FROM Tamu WHERE email = ?";

        if ($stmt = mysqli_prepare($conn, $sql)) {

            mysqli_stmt_bind_param($stmt, "s", $email);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_store_result($stmt);

            if (mysqli_stmt_num_rows($stmt) == 1) {

                mysqli_stmt_bind_result($stmt, $user_id, $username);
                mysqli_stmt_fetch($stmt);

                // ============ BUAT TOKEN ================
                $token   = bin2hex(random_bytes(32));
                $expires = date("Y-m-d H:i:s", time() + 3600);

                // Simpan token ke database
                $sql2 = "REPLACE INTO password_resets_tamu (email, token, expires_at) VALUES (?, ?, ?)";
                $stmt2 = mysqli_prepare($conn, $sql2);
                mysqli_stmt_bind_param($stmt2, "sss", $email, $token, $expires);
                mysqli_stmt_execute($stmt2);
                mysqli_stmt_close($stmt2);

                // ========== KIRIM EMAIL ===============
                $reset_link = "http://localhost/web-hotel/php/reset_pw_user.php?token=$token";

                $mail = new PHPMailer(true);

                try {
                    $mail->isSMTP();
                    $mail->Host       = 'smtp.gmail.com';
                    $mail->SMTPAuth   = true;
                    $mail->Username   = 'ayrandrapratama@gmail.com';
                    $mail->Password   = 'sjjt ccdb uwnh tzae';
                    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                    $mail->Port       = 587;

                    $mail->setFrom('ayrandrapratama@gmail.com', 'Luxury Hotel Admin');
                    $mail->addAddress($email, $username);

                    $mail->isHTML(true);
                    $mail->Subject = 'Reset Password Akun Tamu Hotel';
                    $mail->Body    =
                        "Halo $username,<br><br>
                        Kami menerima permintaan reset password Anda. Klik link berikut:<br><br>
                        <a href='$reset_link'>Reset Password Saya</a><br><br>
                        Link ini berlaku selama 1 jam.<br><br>
                        Terima kasih.";

                    $mail->send();

                    $message = "Link reset password telah dikirim ke $email.";
                    $status  = "success";

                } catch (Exception $e) {
                    $message = "Gagal mengirim email. Silakan coba lagi.";
                    $status  = "error";
                }

            } else {
                $message = "Email tersebut tidak terdaftar dalam sistem kami.";
                $status  = "error";
            }

            mysqli_stmt_close($stmt);
        }
    }

    mysqli_close($conn);

    header("Location: forgot_pw.php?status=$status&msg=" . urlencode($message));
    exit;
}
?>
