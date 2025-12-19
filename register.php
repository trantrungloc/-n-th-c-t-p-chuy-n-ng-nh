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
    <title>Register</title>
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
        .alert { background: #ffecec; color: #c0392b; padding: 10px; border-radius: 4px; font-size: 13px; }
    </style>
</head>
<body>
    <div class="auth-box">
        <h2>Create account</h2>
        <?php if ($message !== ''): ?>
            <div class="alert"><?php echo htmlspecialchars($message); ?></div>
        <?php endif; ?>
        <form method="POST" action="register.php" autocomplete="off">
            <div>
                <label for="name">Name</label>
                <input id="name" name="name" type="text" required>
            </div>
            <div>
                <label for="email">Email</label>
                <input id="email" name="email" type="email" required>
            </div>
            <div>
                <label for="password">Password</label>
                <input id="password" name="password" type="password" required>
            </div>
            <div>
                <label for="password_confirm">Confirm password</label>
                <input id="password_confirm" name="password_confirm" type="password" required>
            </div>
            <button type="submit">Register</button>
        </form>
        <div class="note">Already have an account? <a href="login.php">Login</a></div>
    </div>
</body>
</html>
