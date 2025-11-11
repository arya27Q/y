<?php
// Impor kelas PHPMailer ke namespace global
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

// Muat autoloader Composer
// Ini mengarah ke folder 'vendor' yang baru saja Anda buat
require '../vendor/autoload.php'; // <--- INI DIA PERBAIKANNYA

// Buat instance; passing `true` mengaktifkan exceptions
$mail = new PHPMailer(true);

try {
    // Pengaturan Server
    $mail->isSMTP();                                      
    $mail->Host       = 'smtp.gmail.com';                 
    $mail->SMTPAuth   = true;                             
    
    $mail->Username   = 'ayrandrapratama@gmail.com';            
    $mail->Password   = 'sjjt ccdb uwnh tzae';         
    
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;    
    $mail->Port       = 587;                              

    // Penerima
    $mail->setFrom('ayrandrapratama@gmail.com', 'Luxury Hotel Admin'); 
    $mail->addAddress('ayrandrapratama@gmail.com', 'Tes Tamu');  

    // Konten Email
    $mail->isHTML(true);                                  
    $mail->Subject = 'Tes Pengiriman Email dari Hotel';
    $mail->Body    = '<h1>Halo!</h1><p>Ini adalah email tes untuk mengonfirmasi bahwa PHPMailer berfungsi dengan baik.</p><p>Hormat kami,<br>Luxury Hotel</p>';
    $mail->AltBody = 'Ini adalah email tes untuk mengonfirmasi bahwa PHPMailer berfungsi.'; 

    $mail->send();
    echo 'Email berhasil terkirim!';
} catch (Exception $e) {
    echo "Email gagal dikirim. Error: {$mail->ErrorInfo}";
}
?>