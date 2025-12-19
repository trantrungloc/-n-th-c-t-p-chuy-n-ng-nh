<?php
require_once __DIR__ . '/../database.php';

class user
{
    private $db;

    public function __construct()
    {
        $this->db = new Database();
    }

    private function escape($value)
    {
        return mysqli_real_escape_string($this->db->link, trim($value));
    }

    public function register($name, $email, $password)
    {
        $name = $this->escape($name);
        $email = $this->escape($email);
        $password = trim($password);

        if ($name === '' || $email === '' || $password === '') {
            return array(false, 'Please fill in all fields');
        }

        $checkQuery = "SELECT user_id FROM tbl_user WHERE email = '$email' LIMIT 1";
        $existing = $this->db->select($checkQuery);
        if ($existing) {
            return array(false, 'Email already registered');
        }

        $passwordHash = password_hash($password, PASSWORD_BCRYPT);
        $query = "INSERT INTO tbl_user (name, email, password_hash) VALUES ('$name', '$email', '$passwordHash')";
        $result = $this->db->insert($query);

        if ($result) {
            return array(true, 'Registered');
        }

        return array(false, 'Could not register user');
    }

    public function login($email, $password)
    {
        $email = $this->escape($email);
        $password = trim($password);

        if ($email === '' || $password === '') {
            return array(false, 'Please fill in all fields');
        }

        $query = "SELECT * FROM tbl_user WHERE email = '$email' LIMIT 1";
        $result = $this->db->select($query);
        if (!$result) {
            return array(false, 'Email or password is incorrect');
        }

        $user = $result->fetch_assoc();
        if (!$user || !password_verify($password, $user['password_hash'])) {
            return array(false, 'Email or password is incorrect');
        }

        return array(true, $user);
    }
}
