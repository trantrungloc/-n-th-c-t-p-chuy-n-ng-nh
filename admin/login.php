<?php
require_once __DIR__ . '/session.php';
Session::init();
require_once __DIR__ . '/class/admin_user_class.php';

$adminAuth = new admin_user();
$message = '';

// Nếu đã đăng nhập admin thì vào luôn dashboard
if (Session::get('admin_login')) {
    header('Location: brandlist.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = isset($_POST['email']) ? trim($_POST['email']) : '';
    $password = isset($_POST['password']) ? trim($_POST['password']) : '';

    list($ok, $data) = $adminAuth->login($email, $password);
    if ($ok) {
        Session::set('admin_login', true);
        Session::set('admin_id', $data['admin_id']);
        Session::set('admin_name', $data['admin_name']);
        Session::set('admin_email', $data['admin_email']);
        header('Location: brandlist.php');
        exit;
    }
    $message = $data;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600&display=swap" rel="stylesheet">
    <style>
        :root {
            --bg: linear-gradient(135deg, #0f172a, #1f2937);
            --card: rgba(255,255,255,0.06);
            --border: rgba(255,255,255,0.15);
            --text: #eef2ff;
            --muted: #cbd5f5;
            --accent: #fbbf24;
        }
        * { box-sizing: border-box; }
        body { margin:0; min-height:100vh; display:flex; align-items:center; justify-content:center; background:var(--bg); font-family:'Montserrat',sans-serif; color:var(--text); padding:20px; }
        .card { width: min(420px, 100%); background: var(--card); border:1px solid var(--border); border-radius:16px; padding:28px 24px; backdrop-filter: blur(10px); box-shadow:0 15px 50px rgba(0,0,0,0.35); }
        h1 { margin:0 0 10px; font-size:22px; }
        p.sub { margin:0 0 18px; color:var(--muted); font-size:14px; }
        form { display:flex; flex-direction:column; gap:12px; }
        label { font-size:13px; color:var(--muted); }
        input { width:100%; padding:12px; border-radius:10px; border:1px solid var(--border); background:rgba(255,255,255,0.08); color:var(--text); font-size:14px; }
        input:focus { outline:2px solid var(--accent); border-color:transparent; }
        button { margin-top:6px; padding:12px; border:none; border-radius:12px; background:linear-gradient(135deg, #fbbf24, #f59e0b); color:#1f1300; font-weight:600; cursor:pointer; box-shadow:0 10px 25px rgba(0,0,0,0.25); }
        .alert { margin-bottom:6px; padding:10px; border-radius:10px; background:rgba(255,107,129,0.15); color:#ffd6dc; border:1px solid rgba(255,255,255,0.15); font-size:13px; }
    </style>
</head>
<body>
    <div class="card">
        <h1>Admin Login</h1>
        <p class="sub">Đăng nhập để vào trang quản trị.</p>
        <?php if ($message !== ''): ?>
            <div class="alert"><?php echo htmlspecialchars($message); ?></div>
        <?php endif; ?>
        <form method="POST" action="login.php" autocomplete="off">
            <div>
                <label for="email">Email</label>
                <input id="email" name="email" type="email" required>
            </div>
            <div>
                <label for="password">Mật khẩu</label>
                <input id="password" name="password" type="password" required>
            </div>
            <button type="submit">Đăng nhập</button>
        </form>
    </div>
</body>
</html>
