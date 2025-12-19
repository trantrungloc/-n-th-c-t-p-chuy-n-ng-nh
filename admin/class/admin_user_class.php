<?php
require_once __DIR__ . '/../database.php';

class admin_user
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

    public function login($email, $password)
    {
        $email = strtolower(trim($email));
        $email = $this->escape($email);
        $password = trim($password);

        if ($email === '' || $password === '') {
            return array(false, 'Vui lòng điền đầy đủ thông tin');
        }

        $query = "SELECT * FROM tbl_admin WHERE LOWER(TRIM(admin_email)) = '$email' LIMIT 1";
        $result = $this->db->select($query);
        
        if (!$result) {
            return array(false, 'Email không tồn tại');
        }

        $admin = $result->fetch_assoc();
        if (!$admin) {
            return array(false, 'Không tìm thấy tài khoản');
        }

        $hash = $admin['admin_password_hash'];
        
        // Kiểm tra bcrypt hash
        if (password_verify($password, $hash)) {
            return array(true, $admin);
        }
        
        // Kiểm tra plaintext (tạm thời)
        if ($hash === $password) {
            return array(true, $admin);
        }
        
        // Kiểm tra MD5 (nếu dùng)
        if ($hash === md5($password)) {
            return array(true, $admin);
        }

        return array(false, 'Mật khẩu không đúng');
    }
}
