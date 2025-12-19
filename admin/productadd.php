<?php
include "header.php";
include "slider.php";
include "class/product_class.php";
?>
<?php
$product = new product;
if($_SERVER['REQUEST_METHOD']=== 'POST'){
   
    $insert_product = $product ->insert_product();
}
?>
<div class="admin-content-right">
<div class="admin-content-right-product_add">
    <h1>Thêm Sản Phẩm</h1>
    <form action="" method="POST" enctype="multipart/form-data">
        <label for="">Nhập tên sản phẩm <span style="color: red;">*</span></label>
        <input name="product_name" required type="text">
        <label for="cartegory_select">Chọn danh mục <span style="color: red;">*</span></label>
        <select name="cartegory_id" id="cartegory_select">
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
            <label for="brand_select">Chọn loại sản phẩm <span style="color: red;">*</span></label>
        <select name="brand_id" id="brand_select">
            <option value="#">--Chọn--</option>
            <?php
$show_brand = $product -> show_brand();
if($show_brand) {while($result = $show_brand ->fetch_assoc()){

?>
<option data-cartegory-id="<?php echo $result['cartegory_id'] ?>" value="<?php echo $result['brand_id'] ?>"><?php echo $result['brand_name'] ?></option>
<?php
}}
?>
            
        </select>
        <label for="">Giá sản phẩm <span style="color: red;">*</span></label>
        <input name="product_price" required type="text">
        <label for="">Giá khuyến mãi <span style="color: red;">*</span></label>
        <input name="product_price_new" required type="text">
        <label for="">Mô tả sản phẩm <span style="color: red;">*</span></label>
        <textarea name="product_desc" required id="" cols="30" rows="10" ></textarea>
        <label for="">Ảnh sản phẩm <span style="color: red;">*</span></label>
        <input name="product_img" required type="file">
        <label for="">Ảnh Mô tả <span style="color: red;">*</span></label>
        <input name="product_img_desc[]" multiple required type="file">
        <button type="submit">Thêm</button>
    </form>
    <script>
    (function() {
        const cartegorySelect = document.getElementById('cartegory_select');
        const brandSelect = document.getElementById('brand_select');
        const defaultBrandOption = brandSelect.querySelector('option[value="#"]');
        const brandOptions = Array.from(brandSelect.querySelectorAll('option[data-cartegory-id]'));

        function renderBrandOptions(cartegoryId) {
            brandSelect.innerHTML = '';
            brandSelect.appendChild(defaultBrandOption.cloneNode(true));
            brandOptions.forEach(function(option) {
                if (!cartegoryId || cartegoryId === '#' || option.dataset.cartegoryId === cartegoryId) {
                    brandSelect.appendChild(option.cloneNode(true));
                }
            });
        }

        cartegorySelect.addEventListener('change', function(event) {
            const selectedCartegory = event.target.value;
            renderBrandOptions(selectedCartegory);
            brandSelect.value = '#';
        });

        brandSelect.addEventListener('change', function() {
            const selectedOption = brandSelect.options[brandSelect.selectedIndex];
            const brandCartegoryId = selectedOption ? selectedOption.dataset.cartegoryId : null;
            if (brandCartegoryId && cartegorySelect.value !== brandCartegoryId) {
                cartegorySelect.value = brandCartegoryId;
                const selectedBrandId = brandSelect.value;
                renderBrandOptions(brandCartegoryId);
                brandSelect.value = selectedBrandId;
            }
        });
    })();
    </script>
</div>
</div>