<?php
require_once __DIR__ . '/session.php';
Session::init();
if (!Session::get('admin_login')) {
    header('Location: login.php');
    exit;
}
$adminName = Session::get('admin_name');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản trị</title>
    <link rel="stylesheet" href="style.css"> 
    <style>
        .admin-topbar { display:flex; justify-content:space-between; align-items:center; padding:12px 16px; background:#111; color:#f5f5f5; }
        .admin-topbar a { color:#f5f5f5; text-decoration:none; font-weight:600; }
    </style>
</head>
<body>
    <header>
        <div class="admin-topbar">
            <div>Dashboard</div>
            <div>
                <?php if ($adminName): ?>Xin chào, <?php echo htmlspecialchars($adminName); ?> | <?php endif; ?>
                <a href="logout.php">Đăng xuất</a>
            </div>
        </div>
    </header>