<?php
include "header.php";
include "slider.php";
include "class/product_class.php";
?>
<?php
$product = new product;
$db = new Database();

// Lấy danh sách danh mục để hiển thị bộ lọc
$categories = $db->select("SELECT * FROM tbl_cartegory ORDER BY cartegory_id ASC");

// Lọc theo danh mục nếu có
$filter_cartegory = isset($_GET['cartegory_id']) ? (int)$_GET['cartegory_id'] : 0;

if ($filter_cartegory > 0) {
    $query = "SELECT tbl_product.*, tbl_cartegory.cartegory_name, tbl_brand.brand_name 
              FROM tbl_product 
              LEFT JOIN tbl_cartegory ON tbl_product.cartegory_id = tbl_cartegory.cartegory_id
              LEFT JOIN tbl_brand ON tbl_product.brand_id = tbl_brand.brand_id
              WHERE tbl_product.cartegory_id = $filter_cartegory
              ORDER BY tbl_product.product_id ASC";
} else {
    $query = "SELECT tbl_product.*, tbl_cartegory.cartegory_name, tbl_brand.brand_name 
              FROM tbl_product 
              LEFT JOIN tbl_cartegory ON tbl_product.cartegory_id = tbl_cartegory.cartegory_id
              LEFT JOIN tbl_brand ON tbl_product.brand_id = tbl_brand.brand_id
              ORDER BY tbl_product.product_id ASC";
}
$show_product = $db->select($query);
?>
<div class="admin-content-right">
<div class="admin-content-right-cartegory_list">
    <h1>Danh sách sản phẩm</h1><br>
    <div style="margin-bottom: 15px; display: flex; gap: 15px; align-items: center;">
        <a href="productadd.php" style="padding: 8px 16px; background: #28a745; color: #fff; text-decoration: none; border-radius: 4px;">+ Thêm sản phẩm</a>
        <form method="GET" style="display: inline-flex; gap: 10px; align-items: center;">
            <label>Lọc theo danh mục:</label>
            <select name="cartegory_id" onchange="this.form.submit()" style="padding: 6px 12px; border-radius: 4px; border: 1px solid #ccc;">
                <option value="0">-- Tất cả --</option>
                <?php if($categories){ while($cat = $categories->fetch_assoc()){ ?>
                    <option value="<?php echo $cat['cartegory_id']; ?>" <?php echo ($filter_cartegory == $cat['cartegory_id']) ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars(trim($cat['cartegory_name'])); ?>
                    </option>
                <?php }} ?>
            </select>
        </form>
    </div>
    <table>
        <tr>
            <th>STT</th>
            <th>ID</th>
            <th>Ảnh</th>
            <th>Tên sản phẩm</th>
            <th>Danh mục</th>
            <th>Loại</th>
            <th>Giá gốc</th>
            <th>Giá KM</th>
            <th>Tùy biến</th>
        </tr>
        <?php
        if($show_product){ $i = 0;
            while($result = $show_product->fetch_assoc()) { $i++;
        ?>
        <tr>
            <td><?php echo $i; ?></td>
            <td><?php echo $result['product_id']; ?></td>
            <td><img src="uploads/<?php echo $result['product_img']; ?>" alt="" style="width: 60px; height: 60px; object-fit: cover;"></td>
            <td><?php echo htmlspecialchars($result['product_name']); ?></td>
            <td><?php echo htmlspecialchars($result['cartegory_name']); ?></td>
            <td><?php echo htmlspecialchars($result['brand_name']); ?></td>
            <td><?php echo number_format($result['product_price'], 0, ',', '.'); ?>đ</td>
            <td><?php echo number_format($result['product_price_new'], 0, ',', '.'); ?>đ</td>
            <td>
                <a href="productedit.php?product_id=<?php echo $result['product_id']; ?>">Sửa</a> | 
                <a href="productdelete.php?product_id=<?php echo $result['product_id']; ?>" onclick="return confirm('Bạn có chắc muốn xóa sản phẩm này?');">Xóa</a>
            </td>
        </tr>
        <?php
            }
        } else {
        ?>
        <tr>
            <td colspan="9" style="text-align: center;">Chưa có sản phẩm nào</td>
        </tr>
        <?php } ?>
    </table>
</div>
</div>
</section>
