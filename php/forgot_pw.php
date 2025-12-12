<?php
$msg_type = "";
$message  = "";

if (!empty($_GET['status']) && !empty($_GET['msg'])) {
    $msg_type = $_GET['status'];
    $message  = urldecode($_GET['msg']);
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password - Luxury Hotel</title>
    <link rel="stylesheet" href="../css/forgot_pw.css">

    <style>
        .alert-message {
            margin-bottom: 20px;
            padding: 15px;
            border-radius: 8px;
            text-align: center;
            font-weight: bold;
        }

        .alert-success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .alert-error {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
    </style>
</head>

<body>

    <main class="login-container">
        <section class="login-box">

            <header>
                <img src="../img/logo.png" class="logo">
                <h2>Password Recovery</h2>
            </header>

            <?php if (!empty($message)): ?>
                <div class="alert-message alert-<?php echo $msg_type; ?>">
                    <?= $message ?>
                </div>
            <?php endif; ?>

            <form action="forgot_pw_process.php" method="POST">
                <section class="form-group">
                    <input type="email" placeholder="Enter your email" name="email" required>
                </section>

                <button type="submit" class="login-btn">Send Reset Password</button>
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
