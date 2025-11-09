<?php

session_start(); 
require_once 'config.php'; 

$error_message = '';
$success_message = '';

if (isset($_SESSION['registration_success'])) {
    $success_message = $_SESSION['registration_success'];
    unset($_SESSION['registration_success']);
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && !isset($_POST['token'])) {
    
    $login_input = trim(strtolower($_POST['login_field']));
    $password = $_POST['password'];

    $sql = "SELECT id_tamu, password_hash, nama_lengkap, email, metode_auth 
            FROM Tamu 
            WHERE (email = ? OR nama_lengkap = ?) AND metode_auth = 'email'";
    
    $stmt = mysqli_prepare($conn, $sql);

    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "ss", $login_input, $login_input);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $tamu = mysqli_fetch_assoc($result);
        mysqli_stmt_close($stmt);
        
        if ($tamu) {
            if (password_verify($password, $tamu['password_hash'])) {
                
                $_SESSION['id_tamu'] = $tamu['id_tamu'];
                $_SESSION['nama_lengkap'] = $tamu['nama_lengkap'];
                $_SESSION['email'] = $tamu['email'];
                $_SESSION['is_logged_in'] = true;
                
                header("Location: home.php"); 
                exit();
            } else {
                $error_message = "Email/Username atau Password salah.";
            }
        } else {
            $error_message = "Email/Username atau Password salah.";
        }
    } else {
        $error_message = "Gagal mempersiapkan query database.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
 <meta charset="UTF-8">
 <meta name="viewport" content="width=device-width, initial-scale=1.0">
 <title>Luxury Hotel - Login</title>
 <link rel="stylesheet" href="../css/login.css">
 <link rel="preconnect" href="https://fonts.googleapis.com">
 <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;500;700&display=swap" rel="stylesheet">
</head>
<body>
  <main class="login-container">
  <section class="login-box">
  <header>
  <img src="../img/logo.png" alt="Luxury Hotel Logo" class="logo">
  </header>

        <?php if ($success_message): ?>
        <div class="message-box" style="color: white; background-color: #2ecc71; padding: 5px; border-radius: 5px; margin-bottom: 15px;">
        <?php echo $success_message; ?>
        </div>
    <?php endif; ?>
    <?php if ($error_message): ?>
    <div class="message-box" style="color: white; background-color: #e74c3c; padding: 5px; border-radius: 5px; margin-bottom: 15px;">
    <?php echo $error_message; ?>
    </div> 
    <?php endif; ?>

     <form method="POST">  <section class="form-group">
     <input type="text" placeholder="username or email" name="login_field" required>
 </section>
 <section class="form-group">
     <input type="password" placeholder="password" name="password" required>
 </section>
 <section class="options">
 <a href="forgot_pw.php">Forgot Password?</a>
 </section>
 <button type="submit" class="login-btn">LOGIN</button>
 </form>

 <footer>
      <p class="create">
 <a href="create_account.php">Create Account</a>
 </p>
</footer>
</section>
 </main>
 </body>
</html>