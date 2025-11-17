<?php
/**
 * _header_desktop.php
 * Header Desktop Cerdas: Otomatis mengambil data user jika belum ada.
 */

// 1. Inisialisasi Variabel Default
$header_default_img = 'images/icon/avatar-default.jpg'; 
$header_final_src = $header_default_img; 

$header_username = $_SESSION['username'] ?? 'Admin';
$header_email = 'email@example.com'; // Placeholder default
$raw_img_path = '';

// 2. LOGIKA PINTAR: Cek Sumber Data
// Apakah data user ($user_data) sudah disediakan oleh halaman induk (seperti edit_profil_admin.php)?
if (isset($user_data) && is_array($user_data)) {
    // Kasus 1: Data dari edit profil
    $header_username = $user_data['username'];
    $header_email = $user_data['email'];
    $raw_img_path = $user_data['profile_img'];

} elseif (isset($current_user_img)) {
    // Kasus 2: Data dari index.php lama (jika masih pakai cara lama)
    $raw_img_path = $current_user_img;
    if (isset($current_user_email)) { $header_email = $current_user_email; }

} elseif (isset($_SESSION['user_id']) && isset($conn)) {
    // Kasus 3 (PENTING): Halaman Tabel/Lainnya yang belum punya data user.
    // Kita ambil langsung dari database DI SINI agar otomatis muncul di semua halaman.
    
    $uid_temp = $_SESSION['user_id'];
    $stmt_header = $conn->prepare("SELECT username, email, profile_img FROM users WHERE id = ?");
    $stmt_header->bind_param("i", $uid_temp);
    $stmt_header->execute();
    $res_header = $stmt_header->get_result();
    
    if ($res_header->num_rows > 0) {
        $u_header = $res_header->fetch_assoc();
        $header_username = $u_header['username'];
        $header_email = $u_header['email'];
        $raw_img_path = $u_header['profile_img'];
    }
    $stmt_header->close();
}

// 3. Proses Jalur Gambar (Path Correction)
if (!empty($raw_img_path) && $raw_img_path !== $header_default_img) {
    // Bersihkan '../' berlebih jika ada
    $clean_path = str_replace('../', '', $raw_img_path);

    // Cek apakah gambar ada di folder uploads (artinya di luar folder CoolAdmin-master)
    if (strpos($clean_path, 'uploads/') !== false) {
        // Tambahkan '../' agar bisa naik satu level ke root folder
        $header_final_src = '../' . $clean_path; 
    } else {
        // Gambar internal template
        $header_final_src = $clean_path;
    }
}
?>

<style>
    /* Foto Kecil di Navbar */
    .account-item > .image {
        border-radius: 50%;
        overflow: hidden; /* Memotong gambar yang keluar dari lingkaran */
        width: 45px;      /* Ukuran fix */
        height: 45px;     /* Ukuran fix */
    }
    
    .account-item > .image > img {
        width: 100%;
        height: 100%;
        object-fit: cover; /* Agar gambar proporsional */
        border-radius: 50%;
    }

    /* Foto Besar di Dropdown */
    .account-dropdown .info .image {
        border-radius: 50%;
        overflow: hidden;
        width: 65px;  /* Ukuran fix dropdown */
        height: 65px; /* Ukuran fix dropdown */
    }

    .account-dropdown .info .image a {
        display: block;
        width: 100%;
        height: 100%;
    }

    .account-dropdown .info .image img {
        width: 100%;
        height: 100%;
        object-fit: cover; /* Agar gambar proporsional */
        border-radius: 50%;
    }
</style>

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
                    <div class="noti-wrap"></div>
                    
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