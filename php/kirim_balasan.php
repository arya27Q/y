<?php
session_start();
include 'config.php';

// Load PHPMailer (Sesuaikan path vendor Anda)
// Karena struktur Anda: CoolAdmin-master/vendor, dan file ini di php/, maka pathnya:
require '../vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if (isset($_POST['kirim_balasan'])) {
    $email_penerima = $_POST['email_penerima'];
    $nama_penerima = $_POST['nama_penerima'];
    $subject = $_POST['subject'];
    $pesan_balasan = $_POST['pesan_balasan'];
    $id_pesan = $_POST['id_pesan'];

    $mail = new PHPMailer(true);

    try {
        // ===============================================
        // KONFIGURASI SMTP (WAJIB DIISI YANG BENAR)
        // ===============================================
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com'; // Host Gmail
        $mail->SMTPAuth   = true;
        $mail->Username   = 'owencita32@gmail.com'; // GANTI DENGAN EMAIL ADMIN
        $mail->Password   = 'uxec fctn ulex tneq';   // GANTI DENGAN APP PASSWORD (BUKAN PASSWORD LOGIN BIASA)
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;

        // Pengirim & Penerima
        $mail->setFrom('email_admin_anda@gmail.com', 'Admin Luxury Hotel');
        $mail->addAddress($email_penerima, $nama_penerima);

        // Konten Email
        $mail->isHTML(true);
        $mail->Subject = $subject;
        
        // Template Email Sederhana
        $bodyContent = "
        <h3>Halo, $nama_penerima</h3>
        <p>Terima kasih telah menghubungi Luxury Hotel. Berikut adalah balasan kami atas pesan Anda:</p>
        <div style='background-color: #f8f9fa; padding: 15px; border-left: 4px solid #002877; margin: 20px 0;'>
            ".nl2br(htmlspecialchars($pesan_balasan))."
        </div>
        <p>Salam Hangat,<br><strong>Admin Luxury Hotel</strong></p>
        ";
        
        $mail->Body = $bodyContent;

        $mail->send();

        // (Opsional) Update status pesan di database jadi 'Sudah Dibalas' jika ada kolom status
        // $conn->query("UPDATE pesan SET status='Dibalas' WHERE id_pesan='$id_pesan'");

        // Redirect sukses
        header("Location: ../CoolAdmin-master/tabel_pesan.php?status=sent");
        exit();

    } catch (Exception $e) {
        echo "Pesan gagal dikirim. Mailer Error: {$mail->ErrorInfo}";
    }
}
?>