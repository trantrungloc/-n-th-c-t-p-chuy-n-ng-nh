<?php
require_once __DIR__ . '/session.php';
Session::init();

unset($_SESSION['admin_login'], $_SESSION['admin_id'], $_SESSION['admin_name'], $_SESSION['admin_email']);

header('Location: login.php');
exit;
