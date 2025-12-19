<?php
// Nhúng file kết nối database
include "database.php";
?>

<?php
class brand
{
    private $db;

    // Hàm khởi tạo: Khởi tạo kết nối database
    public function __construct()
    {
        // Giả sử class Database đã được định nghĩa trong database.php
        $this->db = new Database();
    }

    // Hàm thêm danh mục
    public function insert_brand($cartegory_id, $brand_name)
    {

        $query = "INSERT INTO tbl_brand (cartegory_id, brand_name) VALUES ('$cartegory_id', '$brand_name')";


        $result = $this->db->insert($query);
   

        return $result;
    }
    public function show_cartegory()
    {

        $query = "SELECT * FROM tbl_cartegory ORDER BY cartegory_id ASC";
        $result = $this->db->select($query);
        return $result;
    }
    public function get_cartegory($cartegory_id)
    {

        $query = "SELECT * FROM tbl_cartegory WHERE cartegory_id = '$cartegory_id'";
        $result = $this->db->select($query);
        return $result;
    }
    public function update_cartegory($cartegory_name, $cartegory_id)
    {
        $query = "UPDATE tbl_cartegory SET cartegory_name = '$cartegory_name' WHERE cartegory_id = '$cartegory_id' ";
        $result = $this->db->update($query);
        header('Location:cartegorylist.php');
        return $result;
    }
    public function delete_cartegory($cartegory_id)
    {
        $query = "DELETE FROM tbl_cartegory WHERE cartegory_id = '$cartegory_id' ";
        $result = $this->db->delete($query);
        header('Location:cartegorylist.php');
        return $result;
    }
}
?>