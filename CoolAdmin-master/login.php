<?php
// File ini berisi logika pemrosesan login
include '../php/login_process.php'; 
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Luxury Hotel Login Page">
    <title>Login - Luxury Hotel Admin</title>

    <link href="css/font-face.css" rel="stylesheet" media="all">
    <link href="vendor/fontawesome-7.0.1/css/all.min.css" rel="stylesheet" media="all">
    <link href="vendor/mdi-font/css/material-design-iconic-font.min.css" rel="stylesheet" media="all">
    <link href="vendor/bootstrap-5.3.8.min.css" rel="stylesheet" media="all">
    <link href="vendor/perfect-scrollbar/perfect-scrollbar-1.5.6.css" rel="stylesheet" media="all">
    <link href="css/theme.css" rel="stylesheet" media="all">

    <style>
        body {
            font-family: 'Poppins', sans-serif;
            font-size: 14px;
        }
        
        /* Background Hotel Mewah */
        .page-content--bge5 {
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

        /* Kartu Login Compact */
        .login-wrap {
            width: 100%;
            max-width: 450px;
            margin: 0 auto;
        }

        .login-content {
            background: #ffffff;
            border-radius: 15px;
            box-shadow: 0 15px 35px rgba(0,0,0,0.3);
            padding: 30px 35px;
            border-top: 4px solid #002877; /* Warna tema hotel */
        }

        /* Logo & Header */
        .login-logo img {
            max-height: 60px;
            margin-bottom: 10px;
        }
        
        .login-logo h3 {
            color: #002877;
            font-weight: 700;
            font-size: 22px;
            margin-bottom: 5px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .text-muted {
            font-size: 13px;
            margin-bottom: 20px !important;
        }

        /* Form Elements */
        .form-group {
            margin-bottom: 15px !important;
        }

        .form-group label {
            font-weight: 600;
            color: #555;
            margin-bottom: 5px;
            font-size: 13px;
        }

        .input-group-text {
            background: #f0f2f5;
            border: 1px solid #e1e1e1;
            border-right: none;
            color: #002877;
            height: 40px;
            font-size: 14px;
        }

        .au-input {
            border: 1px solid #e1e1e1;
            border-left: none;
            border-radius: 0 5px 5px 0;
            height: 40px;
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

        /* Checkbox & Links */
        .login-checkbox {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px !important;
            font-size: 13px;
        }

        .login-checkbox label {
            display: flex;
            align-items: center;
            color: #555;
            margin: 0;
            cursor: pointer;
        }

        .login-checkbox input[type="checkbox"] {
            margin-right: 8px;
            accent-color: #002877;
            width: 14px;
            height: 14px;
        }

        .login-checkbox a {
            color: #002877;
            text-decoration: none;
            font-weight: 600;
        }

        .login-checkbox a:hover {
            text-decoration: underline;
        }

        /* Tombol Login */
        .au-btn--green {
            background: linear-gradient(45deg, #002877, #0044cc);
            border: none;
            border-radius: 30px;
            padding: 10px;
            font-size: 14px;
            font-weight: 600;
            letter-spacing: 1px;
            text-transform: uppercase;
            box-shadow: 0 4px 10px rgba(0, 40, 119, 0.3);
            transition: all 0.3s ease;
            width: 100%;
        }

        .au-btn--green:hover {
            background: linear-gradient(45deg, #001f5c, #003399);
            transform: translateY(-2px);
            box-shadow: 0 6px 15px rgba(0, 40, 119, 0.4);
        }

        /* Footer Link */
        .register-link {
            margin-top: 15px;
            font-size: 13px;
        }
        .register-link p { margin-bottom: 0; color: #666; }
        .register-link a { color: #002877; font-weight: 700; text-decoration: none; }
        .register-link a:hover { text-decoration: underline; }

        /* Alert Styling */
        .alert-message {
            padding: 10px 15px;
            margin-bottom: 20px;
            border-radius: 6px;
            font-size: 13px;
            display: flex;
            align-items: center;
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
                            <a href="#">
                                <img src="../img/logo.png" alt="CoolAdmin">
                            </a>
                            <h3>Welcome Back</h3>
                            <p class="text-muted">Sign in to your dashboard</p>
                        </div>
                        
                        <div class="login-form">
                            <?php if (isset($error_message) && !empty($error_message)): ?>
                                <div class="alert-message alert-danger">
                                    <i class="zmdi zmdi-alert-triangle mr-2"></i> <?php echo $error_message; ?>
                                </div>
                            <?php endif; ?>
                            
                            <form action="" method="post"> 
                                <div class="form-group">
                                    <label>Email Address</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="zmdi zmdi-email"></i></span>
                                        <input class="au-input form-control" type="email" name="email" placeholder="Enter your email" required>
                                    </div>
                                </div>
                                
                                <div class="form-group">
                                    <label>Password</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="zmdi zmdi-lock"></i></span>
                                        <input class="au-input form-control" type="password" name="password" id="passwordInput" placeholder="Enter your password" required>
                                        <button class="btn btn-outline-secondary" type="button" id="togglePassword" style="border-left: none; border-color: #e1e1e1; height: 40px;">
                                            <i class="zmdi zmdi-eye"></i>
                                        </button>
                                    </div>
                                </div>

                                <div class="login-checkbox">
                                    <label>
                                        <input type="checkbox" name="remember">Remember Me
                                    </label>
                                    <label>
                                        <a href="forget-pass.php">Forgot Password?</a>
                                    </label>
                                </div>

                                <button class="au-btn au-btn--block au-btn--green m-b-20" type="submit" name="login_submit">
                                    SIGN IN
                                </button>
                            </form>

                            <div class="register-link text-center">
                                <p>
                                    Don't have an account?
                                    <a href="register.php">Sign Up Here</a>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="js/vanilla-utils.js"></script>
    <script src="vendor/bootstrap-5.3.8.bundle.min.js"></script>
    <script src="js/main-vanilla.js"></script>
    
    <script>
        const togglePassword = document.querySelector('#togglePassword');
        const password = document.querySelector('#passwordInput');
        const icon = togglePassword.querySelector('i');

        if(togglePassword) {
            togglePassword.addEventListener('click', function (e) {
                const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
                password.setAttribute('type', type);
                
                if (type === 'text') {
                    icon.classList.remove('zmdi-eye');
                    icon.classList.add('zmdi-eye-off');
                } else {
                    icon.classList.remove('zmdi-eye-off');
                    icon.classList.add('zmdi-eye');
                }
            });
        }
    </script>

</body>

</html>