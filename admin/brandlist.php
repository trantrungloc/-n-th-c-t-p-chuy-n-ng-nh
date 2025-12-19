<?php
// Nhúng các file header và slider (Từ image_bb4a9b.png và image_bb4204.png)
include "header.php";
include "slider.php";
include "class/brand_class.php";
?>
<?php
$brand = new brand;
$db = new Database();

// Lấy danh sách danh mục để hiển thị bộ lọc
$categories = $db->select("SELECT * FROM tbl_cartegory ORDER BY cartegory_id ASC");

// Lọc theo danh mục nếu có
$filter_cartegory = isset($_GET['cartegory_id']) ? (int)$_GET['cartegory_id'] : 0;

if ($filter_cartegory > 0) {
    $query = "SELECT tbl_brand.*, tbl_cartegory.cartegory_name
              FROM tbl_brand 
              INNER JOIN tbl_cartegory ON tbl_brand.cartegory_id = tbl_cartegory.cartegory_id
              WHERE tbl_brand.cartegory_id = $filter_cartegory
              ORDER BY tbl_brand.brand_id ASC";
    $show_brand = $db->select($query);
} else {
    $show_brand = $brand->show_brand();
}
?>
<div class="admin-content-right">
<div class="admin-content-right-cartegory_list">
    <h1>Danh sách loại sản phẩm</h1>
    <div style="margin-bottom: 15px;">
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
            <th>Stt</th>
            <th>ID</th>
            <th>Tên danh mục</th>
            <th>Danh mục</th>
            <th>Tùy biến</th>
        </tr>
        <?php
        if($show_brand){$i=0;
            while($result = $show_brand->fetch_assoc()) {$i++;
        ?>
        <tr>
            <td><?php echo $i ?></td>
            <td><?php echo $result['brand_id'] ?></td>
            <td><?php echo $result['cartegory_name'] ?></td>
            <td><?php echo $result['brand_name'] ?></td>
            <td><a href="brandedit.php?brand_id=<?php echo $result['brand_id'] ?>">Sửa</a>|<a href="branddelete.php?brand_id=<?php echo $result['brand_id'] ?>">Xóa</a></td>
        </tr>
        <?php
            }
        }
        ?>
    </table>
</div>
</div>
    </section>