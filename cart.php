<?php
// Trang giỏ hàng
require_once __DIR__ . '/admin/session.php';
require_once __DIR__ . '/admin/class/brand_class.php';

Session::init();
$userLoggedIn = Session::get('user_login');
$userName = Session::get('user_name');

$brandClass = new brand();

// Lấy giỏ hàng
$cart = Session::get('cart');
if (!$cart) $cart = array();

// Tính tổng
$totalItems = 0;
$totalPrice = 0;
foreach ($cart as $item) {
    $totalItems += $item['quantity'];
    $totalPrice += $item['price'] * $item['quantity'];
}

// Lấy danh mục cho header menu
$categories = array();
$categoryRows = $brandClass->show_cartegory();
if ($categoryRows) {
    while ($cat = $categoryRows->fetch_assoc()) {
        $categories[] = $cat;
    }
}

$brandRows = $brandClass->show_brand();
$brandByCategory = array();
if ($brandRows) {
    while ($row = $brandRows->fetch_assoc()) {
        $catName = trim($row['cartegory_name']);
        if (!isset($brandByCategory[$catName])) {
            $brandByCategory[$catName] = array();
        }
        $brandByCategory[$catName][] = $row;
    }
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <script src="https://kit.fontawesome.com/1147679ae7.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="/css/style.css">
    <link rel="stylesheet" href="/css/cart-page.css">
    <title>Giỏ hàng - ZARA SHOP</title>
</head>
<body>
    <header>
        <div class="logo">
            <a href="/index.php"><img src="/images/logo.png" alt="ZARA"></a>
        </div>
        <ul class="menu">
            <?php foreach ($categories as $cat): ?>
                <?php $catName = trim($cat['cartegory_name']); ?>
                <li><a href="/products.php?cartegory_id=<?php echo (int)$cat['cartegory_id']; ?>"><?php echo htmlspecialchars($catName); ?></a>
                    <ul class="sub-menu">
                        <?php if (!empty($brandByCategory[$catName])): ?>
                            <?php foreach ($brandByCategory[$catName] as $brand): ?>
                                <li><a href="/products.php?brand_id=<?php echo (int)$brand['brand_id']; ?>"><?php echo htmlspecialchars($brand['brand_name']); ?></a></li>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <li><span>Chưa có thương hiệu</span></li>
                        <?php endif; ?>
                    </ul>
                </li>
            <?php endforeach; ?>
            <li><a href="#">Bộ sưu tập</a></li>
            <li><a href="#">Thông tin</a></li>
        </ul>
        <ul class="others">
            <li><input type="text" placeholder="Tìm kiếm..."><i class="fas fa-search"></i></li>
            <?php if ($userLoggedIn): ?>
                <li class="user-info">
                    <span>Xin chào, <?php echo htmlspecialchars($userName); ?></span>
                    <a href="/logout.php" class="btn-logout">Đăng xuất</a>
                </li>
            <?php else: ?>
                <li><a href="/login.php" class="fas fa-user"></a></li>
                <li><a href="/login.php" class="btn-login">Đăng nhập</a></li>
            <?php endif; ?>
            <li><a href="/products.php" class="fas fa-shopping-bag" title="Kho hàng"></a></li>
            <li><a href="/cart.php" class="fas fa-shopping-cart active" title="Giỏ hàng"></a></li>
        </ul>
    </header>

    <!-- Cart Page -->
    <section class="cart-page">
        <div class="container">
            <h1 class="cart-title">
                <i class="fas fa-shopping-cart"></i>
                Giỏ hàng của bạn
            </h1>

            <?php if (empty($cart)): ?>
            <!-- Empty Cart -->
            <div class="cart-empty">
                <i class="fas fa-shopping-cart"></i>
                <h2>Giỏ hàng trống</h2>
                <p>Bạn chưa có sản phẩm nào trong giỏ hàng</p>
                <a href="/products.php" class="btn-shopping">Tiếp tục mua sắm</a>
            </div>
            <?php else: ?>
            <!-- Cart Content -->
            <div class="cart-content">
                <!-- Cart Items -->
                <div class="cart-items">
                    <!-- Header -->
                    <div class="cart-header">
                        <label class="cart-checkbox">
                            <input type="checkbox" id="selectAll" checked>
                            <span class="checkmark"></span>
                        </label>
                        <span class="header-product">Sản phẩm</span>
                        <span class="header-price">Đơn giá</span>
                        <span class="header-quantity">Số lượng</span>
                        <span class="header-total">Thành tiền</span>
                        <span class="header-action">Thao tác</span>
                    </div>

                    <!-- Items -->
                    <?php foreach ($cart as $cartKey => $item): ?>
                    <div class="cart-item" data-key="<?php echo htmlspecialchars($cartKey); ?>">
                        <label class="cart-checkbox">
                            <input type="checkbox" class="item-checkbox" checked>
                            <span class="checkmark"></span>
                        </label>
                        <div class="item-product">
                            <a href="/product.php?id=<?php echo (int)$item['product_id']; ?>" class="item-image">
                                <img src="/admin/uploads/<?php echo htmlspecialchars($item['image']); ?>" alt="" onerror="this.src='/images/slider1.jpg'">
                            </a>
                            <div class="item-info">
                                <a href="/product.php?id=<?php echo (int)$item['product_id']; ?>" class="item-name"><?php echo htmlspecialchars($item['name']); ?></a>
                                <span class="item-size">Size: <?php echo htmlspecialchars($item['size']); ?></span>
                            </div>
                        </div>
                        <div class="item-price">
                            <?php if ($item['price'] < $item['original_price']): ?>
                            <span class="price-original"><?php echo number_format($item['original_price'], 0, ',', '.'); ?>đ</span>
                            <?php endif; ?>
                            <span class="price-current"><?php echo number_format($item['price'], 0, ',', '.'); ?>đ</span>
                        </div>
                        <div class="item-quantity">
                            <button class="qty-btn minus" onclick="updateQuantity('<?php echo $cartKey; ?>', -1)">-</button>
                            <input type="number" class="qty-input" value="<?php echo (int)$item['quantity']; ?>" min="1" max="99" data-key="<?php echo htmlspecialchars($cartKey); ?>" onchange="setQuantity('<?php echo $cartKey; ?>', this.value)">
                            <button class="qty-btn plus" onclick="updateQuantity('<?php echo $cartKey; ?>', 1)">+</button>
                        </div>
                        <div class="item-total">
                            <span class="total-price"><?php echo number_format($item['price'] * $item['quantity'], 0, ',', '.'); ?>đ</span>
                        </div>
                        <div class="item-action">
                            <button class="btn-remove" onclick="removeItem('<?php echo $cartKey; ?>')">
                                <i class="fas fa-trash-alt"></i>
                                Xóa
                            </button>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>

                <!-- Cart Summary -->
                <div class="cart-summary">
                    <div class="summary-box">
                        <h3>Tóm tắt đơn hàng</h3>
                        <div class="summary-row">
                            <span>Tạm tính (<span id="itemCount"><?php echo $totalItems; ?></span> sản phẩm)</span>
                            <span id="subtotal"><?php echo number_format($totalPrice, 0, ',', '.'); ?>đ</span>
                        </div>
                        <div class="summary-divider"></div>
                        <div class="summary-row total">
                            <span>Tổng cộng</span>
                            <span id="totalPrice"><?php echo number_format($totalPrice, 0, ',', '.'); ?>đ</span>
                        </div>
                        <button class="btn-checkout" onclick="checkout()">
                            <i class="fas fa-credit-card"></i>
                            Tiến hành thanh toán
                        </button>
                        <a href="/products.php" class="btn-continue">
                            <i class="fas fa-arrow-left"></i>
                            Tiếp tục mua sắm
                        </a>
                    </div>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </section>

    <!-- Footer -->
    <footer class="site-footer">
        <div class="footer-content">
            <div class="footer-links">
                <a href="#">Liên hệ</a>
                <a href="#">Tuyển dụng</a>
                <a href="#">Giới thiệu</a>
                <a href="#">Chính sách</a>
            </div>
            <div class="footer-social">
                <a href="#" class="fab fa-facebook-f"></a>
                <a href="#" class="fab fa-instagram"></a>
                <a href="#" class="fab fa-twitter"></a>
                <a href="#" class="fab fa-youtube"></a>
            </div>
            <p class="copyright">© 2025 ZARA Shop. All rights reserved.</p>
        </div>
    </footer>

    <script>
        // Select all checkbox
        document.getElementById('selectAll')?.addEventListener('change', function() {
            document.querySelectorAll('.item-checkbox').forEach(cb => {
                cb.checked = this.checked;
            });
            calculateTotal();
        });

        // Individual checkbox
        document.querySelectorAll('.item-checkbox').forEach(cb => {
            cb.addEventListener('change', function() {
                const allChecked = document.querySelectorAll('.item-checkbox').length === 
                                   document.querySelectorAll('.item-checkbox:checked').length;
                document.getElementById('selectAll').checked = allChecked;
                calculateTotal();
            });
        });

        // Update quantity
        function updateQuantity(cartKey, delta) {
            const input = document.querySelector(`.qty-input[data-key="${cartKey}"]`);
            let newValue = parseInt(input.value) + delta;
            if (newValue < 1) newValue = 1;
            if (newValue > 99) newValue = 99;
            input.value = newValue;
            setQuantity(cartKey, newValue);
        }

        // Set quantity
        function setQuantity(cartKey, quantity) {
            quantity = parseInt(quantity);
            if (quantity < 1) quantity = 1;
            if (quantity > 99) quantity = 99;

            fetch('/update_cart.php', {
                method: 'POST',
                headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                body: `action=update&cart_key=${encodeURIComponent(cartKey)}&quantity=${quantity}`
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    // Update item total
                    const item = document.querySelector(`.cart-item[data-key="${cartKey}"]`);
                    const price = parseFloat(item.querySelector('.price-current').textContent.replace(/[^\d]/g, ''));
                    const total = price * quantity;
                    item.querySelector('.total-price').textContent = formatPrice(total) + 'đ';
                    calculateTotal();
                }
            });
        }

        // Remove item
        function removeItem(cartKey) {
            if (!confirm('Bạn có chắc muốn xóa sản phẩm này?')) return;

            fetch('/update_cart.php', {
                method: 'POST',
                headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                body: `action=remove&cart_key=${encodeURIComponent(cartKey)}`
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    const item = document.querySelector(`.cart-item[data-key="${cartKey}"]`);
                    item.remove();
                    calculateTotal();
                    
                    // Check if cart is empty
                    if (document.querySelectorAll('.cart-item').length === 0) {
                        location.reload();
                    }
                }
            });
        }

        // Calculate total
        function calculateTotal() {
            let total = 0;
            let count = 0;

            document.querySelectorAll('.cart-item').forEach(item => {
                const checkbox = item.querySelector('.item-checkbox');
                if (checkbox.checked) {
                    const priceText = item.querySelector('.total-price').textContent;
                    const price = parseFloat(priceText.replace(/[^\d]/g, ''));
                    const qty = parseInt(item.querySelector('.qty-input').value);
                    total += price;
                    count += qty;
                }
            });

            document.getElementById('itemCount').textContent = count;
            document.getElementById('subtotal').textContent = formatPrice(total) + 'đ';
            document.getElementById('totalPrice').textContent = formatPrice(total) + 'đ';
        }

        // Format price
        function formatPrice(num) {
            return num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
        }

        // Checkout
        function checkout() {
            const checkedItems = document.querySelectorAll('.item-checkbox:checked');
            if (checkedItems.length === 0) {
                alert('Vui lòng chọn sản phẩm để thanh toán');
                return;
            }
            alert('Chức năng thanh toán đang được phát triển!');
            // window.location.href = '/checkout.php';
        }
    </script>
    <script src="/js/rollindexheader.js"></script>
</body>
</html>
