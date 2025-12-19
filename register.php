<?php
require_once __DIR__ . '/admin/session.php';
Session::init();
require_once __DIR__ . '/admin/class/user_class.php';

$userClass = new user();
$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = isset($_POST['name']) ? $_POST['name'] : '';
    $email = isset($_POST['email']) ? $_POST['email'] : '';
    $password = isset($_POST['password']) ? $_POST['password'] : '';
    $confirm = isset($_POST['password_confirm']) ? $_POST['password_confirm'] : '';

    if ($password !== $confirm) {
        $message = 'Password confirmation does not match';
    } else {
        list($ok, $data) = $userClass->register($name, $email, $password);
        if ($ok) {
            header('Location: login.php?registered=1');
            exit;
        }
        $message = $data;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng ký</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600&display=swap" rel="stylesheet">
    <style>
        :root {
            --bg: linear-gradient(135deg, #10141c, #1f2533);
            --card: rgba(255, 255, 255, 0.1);
            --border: rgba(255, 255, 255, 0.2);
            --text: #eef1f7;
            --accent: #f7c948;
            --muted: #c6ccda;
            --error: #ff5f6d;
        }
        * { box-sizing: border-box; }
        body {
            margin: 0;
            min-height: 100vh;
            font-family: 'Montserrat', sans-serif;
            background: var(--bg);
            color: var(--text);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 24px;
        }
        .shell {
            width: min(960px, 100%);
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
            gap: 16px;
            background: rgba(255,255,255,0.05);
            border: 1px solid var(--border);
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 20px 60px rgba(0,0,0,0.35);
        }
        .hero {
            background: url('/images/slider2.jpg') center/cover no-repeat;
            position: relative;
            min-height: 320px;
        }
        .hero::after {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(180deg, rgba(16,20,28,0.6), rgba(16,20,28,0.9));
        }
        .hero .overlay {
            position: absolute;
            inset: 0;
            display: flex;
            flex-direction: column;
            justify-content: flex-end;
            padding: 24px;
            gap: 8px;
            z-index: 1;
        }
        .hero h1 { margin: 0; font-size: 26px; }
        .hero p { margin: 0; color: var(--muted); }

        .auth-box {
            padding: 32px 28px 28px;
            backdrop-filter: blur(10px);
        }
        .auth-box h2 { margin: 0 0 12px; font-size: 22px; }
        .sub { margin: 0 0 20px; color: var(--muted); font-size: 14px; }
        form { display: flex; flex-direction: column; gap: 14px; }
        label { font-size: 13px; color: var(--muted); }
        input {
            width: 100%;
            padding: 12px;
            border-radius: 10px;
            border: 1px solid var(--border);
            background: rgba(255,255,255,0.06);
            color: var(--text);
            font-size: 14px;
        }
        input:focus { outline: 2px solid var(--accent); border-color: transparent; }
        button {
            margin-top: 4px;
            padding: 13px;
            border: none;
            border-radius: 12px;
            background: linear-gradient(135deg, #f7c948, #f59e0b);
            color: #1b1f2a;
            font-weight: 600;
            cursor: pointer;
            transition: transform 0.1s ease, box-shadow 0.2s ease;
        }
        button:hover { transform: translateY(-1px); box-shadow: 0 10px 25px rgba(0,0,0,0.25); }
        .note { margin-top: 16px; font-size: 13px; color: var(--muted); }
        .note a { color: var(--accent); text-decoration: none; font-weight: 600; }
        .note a:hover { text-decoration: underline; }
        .alert {
            border-radius: 10px;
            padding: 12px;
            font-size: 13px;
            margin-bottom: 8px;
            border: 1px solid rgba(255,255,255,0.2);
            background: rgba(255,95,109,0.16);
            color: #ffd6dc;
        }
    </style>
</head>
<body>
    <div class="shell">
        <div class="hero">
            <div class="overlay">
                <h1>Tạo tài khoản</h1>
                <p>Nhận ưu đãi và quản lý đơn hàng dễ dàng.</p>
            </div>
        </div>
        <div class="auth-box">
            <h2>Đăng ký</h2>
            <p class="sub">Điền thông tin để bắt đầu mua sắm.</p>
            <?php if ($message !== ''): ?>
                <div class="alert"><?php echo htmlspecialchars($message); ?></div>
            <?php endif; ?>
            <form method="POST" action="register.php" autocomplete="off">
                <div>
                    <label for="name">Họ và tên</label>
                    <input id="name" name="name" type="text" required>
                </div>
                <div>
                    <label for="email">Email</label>
                    <input id="email" name="email" type="email" required>
                </div>
                <div>
                    <label for="password">Mật khẩu</label>
                    <input id="password" name="password" type="password" required>
                </div>
                <div>
                    <label for="password_confirm">Nhập lại mật khẩu</label>
                    <input id="password_confirm" name="password_confirm" type="password" required>
                </div>
                <button type="submit">Tạo tài khoản</button>
            </form>
            <div class="note">Đã có tài khoản? <a href="login.php">Đăng nhập</a></div>
        </div>
    </div>
</body>
</html>
