<?php
include "header.php";
include "slider.php";
include "class/brand_class.php";
?>
<?php
$brand = new brand;
if (!isset($_GET['brand_id']) || $_GET['brand_id'] == NULL) {
	echo "<script>window.location = 'brandlist.php'</script>";
} else {
	$brand_id = $_GET['brand_id'];
}

$get_brand = $brand->get_brand($brand_id);
if ($get_brand) {
	$result = $get_brand->fetch_assoc();
}
?>

<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	$cartegory_id = $_POST['cartegory_id'];
	$brand_name = $_POST['brand_name'];
	$update_brand = $brand->update_brand($cartegory_id, $brand_name, $brand_id);
}
?>
<style>
	select {
		height: 30px;
		width: 200px;
	}
</style>
<div class="admin-content-right">
	<div class="admin-content-right-cartegory_add">
		<h1>Sửa Loại Sản Phẩm</h1> <br>
		<form action="" method="POST">
			<select name="cartegory_id" id="">
				<option value="#">--Chọn Danh mục</option>
				<?php
				$show_cartegory = $brand->show_cartegory();
				if ($show_cartegory) {
					while ($resultA = $show_cartegory->fetch_assoc()) {
				?>
				<option <?php if ($result['cartegory_id'] == $resultA['cartegory_id']) echo "selected"; ?> value="<?php echo $resultA['cartegory_id']; ?>"><?php echo $resultA['cartegory_name']; ?></option>
				<?php
					}
				}
				?>
			</select> <br>
			<input required name="brand_name" type="text" placeholder="Nhập tên loại sản phẩm"
			value="<?php echo $result['brand_name']; ?>">
			<button type="submit">Sửa</button>
		</form>
	</div>
</div>
