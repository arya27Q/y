<?php

session_start();

require_once 'config.php'; 

$error_message = '';
$nama_lengkap = ''; $no_telepon = ''; $email = ''; 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    $nama_lengkap = trim($_POST['username']); 
    $no_telepon = trim($_POST['number']);
    $email = trim(strtolower($_POST['email']));
    $password = $_POST['password'];

    if (empty($nama_lengkap) || empty($email) || empty($password)) {
        $error_message = "Semua kolom wajib diisi.";
    } else {
        $password_hash = password_hash($password, PASSWORD_DEFAULT);
        
        $sql = "INSERT INTO Tamu (nama_lengkap, no_telepon, email, password_hash) 
                 VALUES (?, ?, ?, ?)";
        
        $stmt = mysqli_prepare($conn, $sql); 
        
        if ($stmt) {
           
            mysqli_stmt_bind_param($stmt, "ssss", $nama_lengkap, $no_telepon, $email, $password_hash);
            
            try {
                
                if (mysqli_stmt_execute($stmt)) {
                    $_SESSION['registration_success'] = "Akun berhasil dibuat! Silakan login.";
                    header("Location: login.php");
                    exit();
                } 

            } catch (mysqli_sql_exception $e) {
               
                
                $error_code = $e->getCode(); 
                
                if ($error_code === 1062) {
                    $error_message = "Akun sudah terdaftar. Email " . htmlspecialchars($email) . " sudah digunakan.";
                } else {
                    $error_message = "Terjadi kesalahan SQL: " . $e->getMessage();
                }

            } catch (Exception $e) {
                
                $error_message = "Kesalahan sistem: " . $e->getMessage();
            }
            
            mysqli_stmt_close($stmt);

        } else {
             $error_message = "Gagal mempersiapkan query: " . mysqli_error($conn);
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Luxury Hotel - Create Account</title>
  <link rel="stylesheet" href="../css/create_account.css">
  <link rel="preconnect" href="https://fonts.googleapis.com"> 
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;500;700&display=swap" rel="stylesheet">
</head>
<body>
<main class="register-container">
<section class="register-box">
  <header>
  <img src="../img/logo.png" alt="Luxury Hotel Logo" class="logo">
</header>

    <?php if ($error_message): ?>
        <div style="color: white; background-color: #e74c3c; padding: 10px; border-radius: 5px; margin-bottom: 15px;">
            <?php echo $error_message; ?>
        </div>
    <?php endif; ?>



     <form method="POST"> 

 <section class="form-group">
    <input type="text" placeholder="username " name="username" required value="<?php echo htmlspecialchars($nama_lengkap ?? ''); ?>">
</section>
 <section class="form-group">
 <input type="tel" placeholder="number " name="number" value="<?php echo htmlspecialchars($no_telepon ?? ''); ?>" required>
 </section>
<section class="form-group">
   <input type="email" placeholder="email" name="email" value="<?php echo htmlspecialchars($email ?? ''); ?>" required>
</section>
 <section class="form-group">
 <input type="password" placeholder="password" name="password" required>
</section>
<section class="options">
 <a href="login.php">Already have account?</a>
 </section>
 <button type="submit" class="login-btn">CREATE ACCOUNT</button>
 </form>
 </section>
</main>
</body>
</html>