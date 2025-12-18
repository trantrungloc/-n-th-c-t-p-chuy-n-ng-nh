<?php
// Nhúng file kết nối database
include "database.php"; 
?>

<?php
class cartegory {
    private $db;

    // Hàm khởi tạo: Khởi tạo kết nối database
    public function __construct() {
        // Giả sử class Database đã được định nghĩa trong database.php
        $this->db = new Database(); 
    }

    // Hàm thêm danh mục
    public function insert_cartegory($cartegory_name) {
        // Chuẩn bị câu lệnh SQL
        $query = "INSERT INTO tbl_cartegory (cartegory_name) VALUES ('$cartegory_name')";
        
        // Thực thi câu lệnh INSERT (giả sử $this->db->insert() là phương thức thực thi SQL)
        $result = $this->db->insert($query);
        
        // Trả về kết quả
        return $result;
    }
    public function show_cartegory(){
    $query = "SELECT * FROM tbl_cartegory ORDER BY cartegory_id DESC";
    $result = $this ->db->select($query);
    return $result;
}
}
?>