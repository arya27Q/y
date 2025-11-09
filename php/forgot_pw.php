<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="../css/forgot_pw.css">
</head>
<body>
    <main class="login-container">
    <section class="login-box">
      <header>
        <img src="../img/logo.png" alt="Luxury Hotel Logo" class="logo">
      </header>

      <form action="forgot_pw_process.php" method="POST">
        <section class="form-group">
          <input type="email" placeholder="Enter your email" name="email" required>
        </section>
        <button type="submit" class="login-btn">Send Reset password</button>
      </form>

      <footer>
        <p class="create">
          <a href="login.php">Back to Login</a>
        </p>
      </footer>
    </section>
  </main>
</body>
</html>
