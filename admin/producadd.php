<?php
include "header.php";
include "slider.php";
include "class/product_class.php";
?>
<?php
$product = new product;
?>
<div class="admin-content-right">
<div class="admin-content-right-product_add">
    <h1>Thêm Sản Phẩm</h1>
    <form action="" method="POST" enctype="multipart/form-data">
        <label for="">Nhập tên sản phẩm <span style="color: red;">*</span></label>
        <input required type="text">
        <label for="">Chọn danh mục <span style="color: red;">*</span></label>
        <select name="" id="">
            <option value="#">--Chọn--</option>
            <?php
$show_cartegory = $product -> show_cartegory();
if($show_cartegory) {while($result = $show_cartegory ->fetch_assoc()){

?>
<option value="<?php echo $result['cartegory_id'] ?>"><?php echo $result['cartegory_name'] ?></option>
<?php
}}
?>
        </select>
            <label for="">Chọn loại sản phẩm <span style="color: red;">*</span></label>
        <select name="" id="">
            <label for="">Chọn loại sản phẩm <span style="color: red;">*</span></label>
            <option value="#">--Chọn--</option>
            <?php
$show_brand = $product -> show_brand();
if($show_brand) {while($result = $show_brand ->fetch_assoc()){

?>
<option value="<?php echo $result['brand_id'] ?>"><?php echo $result['brand_name'] ?></option>
<?php
}}
?>
            
        </select>
        <label for="">Giá sản phẩm <span style="color: red;">*</span></label>
        <input required type="text">
        <label for="">Giá khuyến mãi <span style="color: red;">*</span></label>
        <input required type="text">
        <label for="">Mô tả sản phẩm <span style="color: red;">*</span></label>
        <textarea required name="" id="" cols="30" rows="10" ></textarea>
        <label for="">Ảnh sản phẩm <span style="color: red;">*</span></label>
        <input required type="file">
        <label for="">Ảnh Mô tả <span style="color: red;">*</span></label>
        <input multiple required type="file">
        <button type="submit">Thêm</button>
    </form>
</div>
</div>