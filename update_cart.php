<?php
// Xử lý cập nhật giỏ hàng (AJAX)
require_once __DIR__ . '/admin/session.php';

Session::init();
header('Content-Type: application/json');

$action = isset($_POST['action']) ? $_POST['action'] : '';
$cartKey = isset($_POST['cart_key']) ? $_POST['cart_key'] : '';

$cart = Session::get('cart');
if (!$cart) $cart = array();

switch ($action) {
    case 'update':
        $quantity = isset($_POST['quantity']) ? (int)$_POST['quantity'] : 1;
        if ($quantity < 1) $quantity = 1;
        if ($quantity > 99) $quantity = 99;
        
        if (isset($cart[$cartKey])) {
            $cart[$cartKey]['quantity'] = $quantity;
            Session::set('cart', $cart);
            echo json_encode(['success' => true, 'message' => 'Đã cập nhật']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Sản phẩm không tồn tại']);
        }
        break;
        
    case 'remove':
        if (isset($cart[$cartKey])) {
            unset($cart[$cartKey]);
            Session::set('cart', $cart);
            echo json_encode(['success' => true, 'message' => 'Đã xóa sản phẩm']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Sản phẩm không tồn tại']);
        }
        break;
        
    case 'clear':
        Session::set('cart', array());
        echo json_encode(['success' => true, 'message' => 'Đã xóa giỏ hàng']);
        break;
        
    default:
        echo json_encode(['success' => false, 'message' => 'Hành động không hợp lệ']);
}
