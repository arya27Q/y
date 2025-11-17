<?php
session_start();

include_once 'config.php';

$error_message = ''; 

if (isset($_POST['login_submit'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    if (empty($email) || empty($password)) {
        $error_message = "Email dan Password wajib diisi.";
    } else {
        $stmt = $conn->prepare("SELECT id, username, password FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            $user = $result->fetch_assoc();
            
            if (password_verify($password, $user['password'])) {
                
                
                $_SESSION['loggedin'] = true;
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                
                header("Location: index.php"); 
                exit();

            } else {
                $error_message = "Password salah.";
            }
        } else {
            $error_message = "Email tidak terdaftar.";
        }

        $stmt->close();
    }
    
    $conn->close();
}
?>