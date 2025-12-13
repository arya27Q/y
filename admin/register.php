<?php
include 'create_account.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Luxury Hotel Register">
    <title>Register - Luxury Hotel Admin</title>

    <link href="css/font-face.css" rel="stylesheet" media="all">
    <link href="vendor/fontawesome-7.0.1/css/all.min.css" rel="stylesheet" media="all">
    <link href="vendor/mdi-font/css/material-design-iconic-font.min.css" rel="stylesheet" media="all">
    <link href="vendor/bootstrap-5.3.8.min.css" rel="stylesheet" media="all">
    <link href="css/theme.css" rel="stylesheet" media="all">

    <style>
        body {
            font-family: 'Poppins', sans-serif;
            font-size: 14px; /* Perkecil font dasar sedikit */
        }
        
        /* Background Hotel Mewah */
        .page-content--bge5 {
            background: url('https://images.unsplash.com/photo-1542314831-068cd1dbfeeb?ixlib=rb-1.2.1&auto=format&fit=crop&w=1950&q=80') no-repeat center center fixed; 
            background-size: cover;
            position: relative;
            z-index: 1;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 15px; /* Tambah padding di body agar tidak mentok HP */
        }

        .page-content--bge5::before {
            content: "";
            position: absolute;
            top: 0; left: 0; right: 0; bottom: 0;
            background: rgba(0, 0, 0, 0.6);
            z-index: -1;
        }

        /* Styling Kartu Login - LEBIH COMPACT */
        .login-wrap {
            width: 100%;
            max-width: 450px; /* Lebar sedikit dikurangi */
            margin: 0 auto;
        }

        .login-content {
            background: #ffffff;
            border-radius: 15px;
            box-shadow: 0 15px 35px rgba(0,0,0,0.3);
            padding: 25px 30px; /* Padding dikurangi drastis (dari 40px) */
            border-top: 4px solid #002877;
        }

        /* Header Logo & Judul - LEBIH RAPAT */
        .login-logo img {
            max-height: 50px; /* Perkecil logo */
            margin-bottom: 5px;
        }
        
        .login-logo h3 {
            color: #002877;
            font-weight: 700;
            font-size: 20px; /* Font judul diperkecil */
            margin-top: 5px;
            margin-bottom: 2px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .text-muted {
            font-size: 12px;
            margin-bottom: 15px !important; /* Paksa jarak bawah lebih kecil */
        }

        /* Form Elements - LEBIH PENDEK */
        .form-group {
            margin-bottom: 12px !important; /* Jarak antar input dirapatkan */
        }

        .form-group label {
            font-weight: 600;
            color: #555;
            margin-bottom: 4px; /* Jarak label ke input dirapatkan */
            font-size: 13px;
        }

        .input-group-text {
            background: #f0f2f5;
            border: 1px solid #e1e1e1;
            border-right: none;
            color: #002877;
            height: 38px; /* Tinggi input dikurangi */
            font-size: 13px;
        }

        .au-input {
            border: 1px solid #e1e1e1;
            border-left: none;
            border-radius: 0 5px 5px 0;
            height: 38px; /* Tinggi input dikurangi (dari 45px) */
            padding-left: 10px;
            font-size: 13px;
            transition: all 0.3s ease;
        }

        .au-input:focus {
            border-color: #002877;
            box-shadow: none;
        }

        .input-group:focus-within .input-group-text {
            border-color: #002877;
        }

        /* Checkbox - RAPAT */
        .login-checkbox {
            display: flex;
            align-items: center;
            margin-bottom: 15px !important;
            margin-top: 5px;
        }

        .login-checkbox label {
            color: #555555 !important;
            display: flex;
            align-items: center;
            font-size: 13px;
            cursor: pointer;
            margin: 0;
        }

        .login-checkbox input[type="checkbox"] {
            margin-right: 8px;
            width: 14px;
            height: 14px;
            cursor: pointer;
            accent-color: #002877;
        }

        /* Tombol Register */
        .au-btn--green {
            background: linear-gradient(45deg, #002877, #0044cc);
            border: none;
            border-radius: 30px;
            padding: 10px; /* Padding tombol dikurangi */
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
            margin-top: 15px !important;
            font-size: 13px;
        }

        .register-link p { color: #666; margin-bottom: 0; }
        .register-link a { color: #002877; font-weight: 700; text-decoration: none; }
        .register-link a:hover { text-decoration: underline; }

        /* Alert Compact */
        .alert-message {
            padding: 10px 15px;
            margin-bottom: 15px;
            border-radius: 6px;
            font-size: 13px;
            display: flex;
            align-items: center;
        }
        .alert-success { background-color: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
        .alert-danger { background-color: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }
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
                            <h3>Create Account</h3>
                            <p class="text-muted mb-4">Join Luxury Hotel Management</p>
                        </div>
                        <div class="login-form">

                            <?php if (isset($success_message)): ?>
                                <div class="alert-message alert-success">
                                    <i class="zmdi zmdi-check-circle mr-2"></i> <?php echo $success_message; ?>
                                </div>
                            <?php endif; ?>

                            <?php if (isset($error_message)): ?>
                                <div class="alert-message alert-danger">
                                    <i class="zmdi zmdi-alert-triangle mr-2"></i> <?php echo $error_message; ?>
                                </div>
                            <?php endif; ?>
                            
                            <form action="" method="post">
                                <div class="form-group mb-3">
                                    <label>Username</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="zmdi zmdi-account"></i></span>
                                        <input class="au-input form-control" type="text" name="username" placeholder="Enter your username" required>
                                    </div>
                                </div>

                                <div class="form-group mb-3">
                                    <label>Email Address</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="zmdi zmdi-email"></i></span>
                                        <input class="au-input form-control" type="email" name="email" placeholder="Enter your email" required>
                                    </div>
                                </div>

                                <div class="form-group mb-3">
                                    <label>Password</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="zmdi zmdi-lock"></i></span>
                                        <input class="au-input form-control" type="password" name="password" id="passwordInput" placeholder="Create a password" required>
                                        <button class="btn btn-outline-secondary" type="button" id="togglePassword" style="border-left: none; border-color: #e1e1e1;">
                                            <i class="zmdi zmdi-eye"></i>
                                        </button>
                                    </div>
                                </div>

                                <div class="login-checkbox mb-4">
                                    <label>
                                        <input type="checkbox" name="aggree" required> I agree to the terms and policy
                                    </label>
                                </div>

                                <button class="au-btn au-btn--block au-btn--green m-b-20" type="submit" name="register_submit">
                                    REGISTER NOW
                                </button>
                                
                            </form>
                            <div class="register-link text-center mt-3">
                                <p>
                                    Already have an account?
                                    <a href="login.php">Sign In Here</a>
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

        togglePassword.addEventListener('click', function (e) {
            // toggle the type attribute
            const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
            password.setAttribute('type', type);
            
            // toggle the icon
            if (type === 'text') {
                icon.classList.remove('zmdi-eye');
                icon.classList.add('zmdi-eye-off');
            } else {
                icon.classList.remove('zmdi-eye-off');
                icon.classList.add('zmdi-eye');
            }
        });
    </script>

</body>
</html>