<?php
// 1. Session & Config
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

include '../php/session_check.php';
include_once '../php/config.php';

// Cek Login
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// 2. Logic Header User
$default_img_path = "images/icon/avatar-default.jpg"; 
$current_user_email = "N/A";
$current_user_img_src = $default_img_path; 

if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    $stmt = $conn->prepare("SELECT email, profile_img, role FROM users WHERE id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $user_db_data = $result->fetch_assoc();
        $current_user_email = $user_db_data['email'];
        $_SESSION['role'] = $user_db_data['role']; 
        
        if (!empty($user_db_data['profile_img'])) {
            $current_user_img_src = '../' . $user_db_data['profile_img'];
        } else {
            $current_user_img_src = $default_img_path;
        }
    }
    $stmt->close();
}

// 3. Update Status Online
if (isset($_SESSION['username'])) {
    $user_now = $_SESSION['username'];
    $stmt_update = $conn->prepare("UPDATE users SET last_activity = NOW() WHERE username = ?");
    $stmt_update->bind_param("s", $user_now);
    $stmt_update->execute();
}

// 4. Ambil Data Admin untuk Tabel
$sql = "SELECT id, username, email, role, last_activity, profile_img FROM users ORDER BY role DESC, username ASC";
$result_admin = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Data Admin - Luxury Hotel</title>
    
    <link href="css/font-face.css" rel="stylesheet" media="all">
    <link href="vendor/fontawesome-7.0.1/css/all.min.css" rel="stylesheet" media="all">
    <link href="vendor/mdi-font/css/material-design-iconic-font.min.css" rel="stylesheet" media="all">
    <link href="vendor/bootstrap-5.3.8.min.css" rel="stylesheet" media="all">
    <link href="vendor/perfect-scrollbar/perfect-scrollbar-1.5.6.css" rel="stylesheet" media="all">
    <link href="css/theme.css" rel="stylesheet" media="all">
    <link href="css/custom-dashboard.css" rel="stylesheet" media="all">

</head>

<body class="animsition">
<div class="page-wrapper">
    <header class="header-mobile d-block d-lg-none">
        <div class="header-mobile__bar">
            <div class="container-fluid">
                <div class="header-mobile-inner">
                    <a class="logo" href="index.php">
                        <h2 class="b1" style="color:#002877;">Luxury Hotel</h2>
                    </a>
                    <button class="hamburger hamburger--slider" type="button">
                        <span class="hamburger-box"><span class="hamburger-inner"></span></span>
                    </button>
                </div>
            </div>
        </div>
        <nav class="navbar-mobile">
            <div class="container-fluid">
                <ul class="navbar-mobile__list list-unstyled">
                    <li><a href="index.php"><i class="fas fa-tachometer-alt"></i>Dashboard</a></li>
                    <li class="has-sub">
                        <a class="js-arrow" href="#"><i class="fas fa-copy"></i>Pages</a>
                        <ul class="list-unstyled navbar__sub-list js-sub-list">
                            <li><a href="login.php">Login</a></li>
                            <?php if (isset($_SESSION['role']) && $_SESSION['role'] == 'super_admin') : ?>
                            <li class="active"><a href="data_admin.php"><i class="fas fa-users"></i> Data Admin</a></li>
                            <?php endif; ?>
                        </ul>
                    </li>
                </ul>
            </div>
        </nav>
    </header>

    <?php include('_header_sidebar.php'); ?>

    <div class="page-container">
        <?php include('_header_desktop.php'); ?>

        <div class="main-content">
            <div class="section__content section__content--p30">
                <div class="container-fluid">
                    
                    <?php if(isset($_GET['msg']) && $_GET['msg'] == 'deleted'): ?>
                        <div class="alert alert-success">Data berhasil dihapus.</div>
                    <?php elseif(isset($_GET['msg']) && $_GET['msg'] == 'updated'): ?>
                        <div class="alert alert-success">Data berhasil diperbarui.</div>
                    <?php endif; ?>

                    <div class="row">
                        <div class="col-md-12">
                            <h3 class="title-5 m-b-35">Data Administrator</h3>
                            
                            <div class="table-responsive table-responsive-data2">
                                <table class="table table-data-admin">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Username</th>
                                            <th>Email</th>
                                            <th>Role</th>
                                            <th>Status</th>
                                            <th>Last Seen</th>
                                            <th class="text-center">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php 
                                        $no = 1;
                                        if ($result_admin && $result_admin->num_rows > 0) {
                                            while($row = $result_admin->fetch_assoc()) {
                                                $is_online = false;
                                                if ($row['last_activity']) {
                                                    $last_active_time = strtotime($row['last_activity']);
                                                    if (time() - $last_active_time < 300) { 
                                                        $is_online = true;
                                                    }
                                                }
                                                $id_admin = $row['id']; 
                                        ?>
                                        <tr>
                                            <td><?php echo $no++; ?></td>
                                            <td><span class="block-email"><?php echo htmlspecialchars($row['username']); ?></span></td>
                                            <td><span class="role-email"><?php echo htmlspecialchars($row['email']); ?></span></td>
                                            <td>
                                                <?php if($row['role'] == 'super_admin'): ?>
                                                    <span class="role-super"><i class="fas fa-crown"></i> Super Admin</span>
                                                <?php else: ?>
                                                    <span class="role-regular">Admin</span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <?php if ($is_online): ?>
                                                    <span class="status-badge bg-online">Online</span>
                                                <?php else: ?>
                                                    <span class="status-badge bg-offline">Offline</span>
                                                <?php endif; ?>
                                            </td>
                                            <td><?php echo $row['last_activity'] ? date('d-m-Y H:i', strtotime($row['last_activity'])) : '-'; ?></td>
                                            
                                            <td class="text-center">
                                                <div class="table-data-feature justify-content-center">
                                                    
                                                    <a href="edit_admin.php?id=<?php echo $id_admin; ?>" 
                                                       class="item btn-edit" 
                                                       data-toggle="tooltip" 
                                                       data-placement="top" 
                                                       title="Edit">
                                                        <i class="zmdi zmdi-edit"></i>
                                                    </a>
                                                    
                                                    <?php if($_SESSION['user_id'] != $id_admin): ?>
                                                    <a href="delete_admin.php?id=<?php echo $id_admin; ?>" 
                                                       class="item btn-delete" 
                                                       data-toggle="tooltip" 
                                                       data-placement="top" 
                                                       title="Delete" 
                                                       onclick="return confirm('Yakin hapus user <?php echo $row['username']; ?>?');">
                                                        <i class="zmdi zmdi-delete"></i>
                                                    </a>
                                                    <?php endif; ?>

                                                </div>
                                            </td>
                                        </tr>
                                        <?php 
                                            }
                                        } else {
                                            echo "<tr><td colspan='7' class='text-center'>Tidak ada data admin.</td></tr>";
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-12">
                            <div class="copyright">
                                <p>Luxury Hotel 2025</p>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

<script src="js/vanilla-utils.js"></script>
<script src="vendor/jquery-3.2.1.min.js"></script>
<script src="vendor/bootstrap-5.3.8.bundle.min.js"></script>
<script src="vendor/perfect-scrollbar/perfect-scrollbar-1.5.6.min.js"></script>
<script src="js/main-vanilla.js"></script>

</body>
</html>
<?php mysqli_close($conn); ?>