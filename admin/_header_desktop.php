<?php

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (!isset($conn)) {
 
    if (file_exists('../php/config.php')) {
        include '../php/config.php';
    } elseif (file_exists('php/config.php')) {
        include 'php/config.php';
    }
}

$header_default_img = 'images/icon/avatar-default.jpg'; 
$header_final_src = $header_default_img; 

$header_username = $_SESSION['username'] ?? 'Admin';
$header_email = 'email@example.com'; 
$header_role = $_SESSION['role'] ?? 'admin'; // Default role
$raw_img_path = '';


if (isset($user_data) && is_array($user_data)) {
    $header_username = $user_data['username'];
    $header_email = $user_data['email'];
    $raw_img_path = $user_data['profile_img'];
    if (isset($user_data['role'])) { $header_role = $user_data['role']; }


} elseif (isset($current_user_img)) {
    $raw_img_path = $current_user_img;
    if (isset($current_user_email)) { $header_email = $current_user_email; }


} elseif (isset($_SESSION['user_id']) && isset($conn)) {
    $uid_temp = $_SESSION['user_id'];
    
   
    $stmt_header = $conn->prepare("SELECT username, email, profile_img, role FROM users WHERE id = ?");
    $stmt_header->bind_param("i", $uid_temp);
    $stmt_header->execute();
    $res_header = $stmt_header->get_result();
    
    if ($res_header->num_rows > 0) {
        $u_header = $res_header->fetch_assoc();
        $header_username = $u_header['username'];
        $header_email = $u_header['email'];
        $header_role = $u_header['role']; // Ambil role dari DB
        $raw_img_path = $u_header['profile_img'];
    }
    $stmt_header->close();
}

// 4. Proses Jalur Gambar (Path Correction)
if (!empty($raw_img_path) && $raw_img_path !== $header_default_img) {
    $clean_path = str_replace('../', '', $raw_img_path);

    // Jika gambar ada di folder uploads, tambahkan ../ agar bisa diakses dari folder CoolAdmin
    if (strpos($clean_path, 'uploads/') !== false) {
        $header_final_src = '../' . $clean_path; 
    } else {
        $header_final_src = $clean_path;
    }
}

// Format Tampilan Role (Huruf Besar Awal & Ganti Underscore)
$display_role = ucwords(str_replace('_', ' ', $header_role));
?>

   <link href="css/theme.css" rel="stylesheet" media="all">
<header class="header-desktop">
    <div class="section__content section__content--p30">
        <div class="container-fluid">
            <div class="header-wrap">
                <form class="form-header" action="" method="POST">
                    <input class="au-input au-input--xl" type="text" name="search" placeholder="Search for datas &amp; reports..." />
                    <button class="au-btn--submit" type="submit">
                        <i class="zmdi zmdi-search"></i>
                    </button>
                </form>
                
                <div class="header-button">
                    <div class="noti-wrap">
                        </div>
                    
                    <div class="account-wrap">
                        <div class="account-item clearfix js-item-menu">
                            
                            <div class="image">
                                <img src="<?php echo $header_final_src; ?>" alt="<?php echo htmlspecialchars($header_username); ?>" />
                            </div>
                            
                            <div class="content">
                                <a class="js-acc-btn" href="#"><?php echo htmlspecialchars($header_username); ?></a>
                            </div>
                            
                            <div class="account-dropdown js-dropdown">
                                <div class="info clearfix">
                                    <div class="image">
                                        <a href="edit_profil_admin.php">
                                            <img src="<?php echo $header_final_src; ?>" alt="<?php echo htmlspecialchars($header_username); ?>" />
                                        </a>
                                    </div>
                                    <div class="content">
                                        <h5 class="name">
                                            <a href="edit_profil_admin.php"><?php echo htmlspecialchars($header_username); ?></a>
                                        </h5>
                                        <span class="email"><?php echo htmlspecialchars($header_email); ?></span>
                                        
                                        <div>
                                            <?php if($header_role == 'super_admin' || $header_role == 'Super Admin'): ?>
                                                <span class="role-badge badge-super"><i class="fas fa-crown"></i> <?php echo $display_role; ?></span>
                                            <?php else: ?>
                                                <span class="role-badge badge-admin"><?php echo $display_role; ?></span>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="account-dropdown__body">
                                    <div class="account-dropdown__item">
                                        <a href="edit_profil_admin.php">
                                            <i class="zmdi zmdi-account"></i>Account
                                        </a>
                                    </div>
                                </div>
                                
                                <div class="account-dropdown__footer">
                                    <a href="logout.php">
                                        <i class="zmdi zmdi-power"></i>Logout
                                    </a>
                                </div>
                            </div>
                            
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>