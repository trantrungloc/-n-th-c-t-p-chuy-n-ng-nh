<?php
// Xử lý thêm sản phẩm vào giỏ hàng (AJAX)
require_once __DIR__ . '/admin/session.php';
require_once __DIR__ . '/admin/class/brand_class.php';

Session::init();
header('Content-Type: application/json');

$db = new Database();

// Lấy dữ liệu từ POST
$product_id = isset($_POST['product_id']) ? (int)$_POST['product_id'] : 0;
$quantity = isset($_POST['quantity']) ? (int)$_POST['quantity'] : 1;
$size = isset($_POST['size']) ? trim($_POST['size']) : '';

if ($product_id <= 0) {
    echo json_encode(['success' => false, 'message' => 'Sản phẩm không hợp lệ']);
    exit;
}

if (empty($size)) {
    echo json_encode(['success' => false, 'message' => 'Vui lòng chọn size']);
    exit;
}

// Lấy thông tin sản phẩm
$query = "SELECT * FROM tbl_product WHERE product_id = $product_id";
$result = $db->select($query);

if (!$result || $result->num_rows == 0) {
    echo json_encode(['success' => false, 'message' => 'Sản phẩm không tồn tại']);
    exit;
}

$product = $result->fetch_assoc();

// Khởi tạo giỏ hàng nếu chưa có
if (!Session::get('cart')) {
    Session::set('cart', array());
}

$cart = Session::get('cart');

// Tạo key unique cho sản phẩm (product_id + size)
$cartKey = $product_id . '_' . $size;

// Kiểm tra sản phẩm đã có trong giỏ chưa
if (isset($cart[$cartKey])) {
    // Cập nhật số lượng
    $cart[$cartKey]['quantity'] += $quantity;
} else {
    // Thêm mới
    $price = (!empty($product['product_price_new']) && $product['product_price_new'] > 0) 
             ? $product['product_price_new'] 
             : $product['product_price'];
    
    $cart[$cartKey] = array(
        'product_id' => $product_id,
        'name' => $product['product_name'],
        'image' => $product['product_img'],
        'price' => $price,
        'original_price' => $product['product_price'],
        'size' => $size,
        'quantity' => $quantity
    );
}

Session::set('cart', $cart);

// Tính tổng số lượng trong giỏ
$totalItems = 0;
foreach ($cart as $item) {
    $totalItems += $item['quantity'];
}

echo json_encode([
    'success' => true, 
    'message' => 'Đã thêm vào giỏ hàng',
    'cartCount' => $totalItems
]);
