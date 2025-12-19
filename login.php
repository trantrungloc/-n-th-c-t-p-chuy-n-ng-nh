<?php
require_once __DIR__ . '/admin/session.php';
Session::init();
require_once __DIR__ . '/admin/class/user_class.php';

$userClass = new user();
$message = '';

if (isset($_GET['registered'])) {
    $message = 'Registration successful. Please login.';
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = isset($_POST['email']) ? $_POST['email'] : '';
    $password = isset($_POST['password']) ? $_POST['password'] : '';

    list($ok, $data) = $userClass->login($email, $password);
    if ($ok) {
        Session::set('user_login', true);
        Session::set('user_id', $data['user_id']);
        Session::set('user_name', $data['name']);
        Session::set('user_email', $data['email']);
        header('Location: html/index.html');
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
    <title>Login</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f5f6fa; margin: 0; display: flex; justify-content: center; align-items: center; height: 100vh; }
        .auth-box { background: #fff; padding: 24px; border-radius: 8px; box-shadow: 0 2px 12px rgba(0,0,0,0.08); width: 360px; }
        h2 { margin: 0 0 16px; font-size: 20px; }
        form { display: flex; flex-direction: column; gap: 12px; }
        label { font-size: 14px; color: #444; }
        input { padding: 10px; border: 1px solid #ccc; border-radius: 4px; font-size: 14px; }
        button { padding: 12px; background: #222; color: #fff; border: none; border-radius: 4px; cursor: pointer; font-size: 14px; }
        button:hover { background: #111; }
        .note { margin-top: 10px; font-size: 13px; color: #555; }
        .alert { background: #e8f7ec; color: #1e8449; padding: 10px; border-radius: 4px; font-size: 13px; margin-bottom: 8px; }
        .alert.error { background: #ffecec; color: #c0392b; }
    </style>
</head>
<body>
    <div class="auth-box">
        <h2>Login</h2>
        <?php if ($message !== ''): ?>
            <div class="alert<?php echo isset($_GET['registered']) ? '' : ' error'; ?>"><?php echo htmlspecialchars($message); ?></div>
        <?php endif; ?>
        <form method="POST" action="login.php" autocomplete="off">
            <div>
                <label for="email">Email</label>
                <input id="email" name="email" type="email" required>
            </div>
            <div>
                <label for="password">Password</label>
                <input id="password" name="password" type="password" required>
            </div>
            <button type="submit">Login</button>
        </form>
        <div class="note">No account? <a href="register.php">Register</a></div>
    </div>
</body>
</html>
