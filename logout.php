<?php
require_once __DIR__ . '/admin/session.php';
Session::init();

unset($_SESSION['user_login'], $_SESSION['user_id'], $_SESSION['user_name'], $_SESSION['user_email']);

header('Location: login.php');
exit;
