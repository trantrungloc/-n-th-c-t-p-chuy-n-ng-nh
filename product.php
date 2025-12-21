<?php
// Load session and database
require_once __DIR__ . '/admin/session.php';
require_once __DIR__ . '/admin/class/brand_class.php';

Session::init();
$userLoggedIn = Session::get('user_login');
$userName = Session::get('user_name');

$brandClass = new brand();
$db = new Database();

// Lấy product_id từ URL
$product_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Lấy thông tin sản phẩm
$product = null;
if ($product_id > 0) {
    $productQuery = "SELECT tbl_product.*, tbl_cartegory.cartegory_name, tbl_cartegory.cartegory_id, tbl_brand.brand_name, tbl_brand.brand_id
                     FROM tbl_product 
                     LEFT JOIN tbl_cartegory ON tbl_product.cartegory_id = tbl_cartegory.cartegory_id
                     LEFT JOIN tbl_brand ON tbl_product.brand_id = tbl_brand.brand_id
                     WHERE tbl_product.product_id = $product_id";
    $result = $db->select($productQuery);
    if ($result && $result->num_rows > 0) {
        $product = $result->fetch_assoc();
    }
}

// Nếu không tìm thấy sản phẩm, redirect về trang products
if (!$product) {
    header('Location: /products.php');
    exit;
}

// Lấy sản phẩm liên quan (cùng brand hoặc category)
$relatedProducts = array();
$relatedQuery = "SELECT tbl_product.*, tbl_brand.brand_name 
                 FROM tbl_product 
                 LEFT JOIN tbl_brand ON tbl_product.brand_id = tbl_brand.brand_id
                 WHERE tbl_product.product_id != $product_id 
                 AND (tbl_product.brand_id = " . (int)$product['brand_id'] . " 
                      OR tbl_product.cartegory_id = " . (int)$product['cartegory_id'] . ")
                 ORDER BY RAND()
                 LIMIT 5";
$relatedResult = $db->select($relatedQuery);
if ($relatedResult) {
    while ($row = $relatedResult->fetch_assoc()) {
        $relatedProducts[] = $row;
    }
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
    <link rel="stylesheet" href="/css/product-detail.css">
    <title><?php echo htmlspecialchars($product['product_name']); ?> - ZARA SHOP</title>
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
            <li><a href="/cart.php" class="fas fa-shopping-cart" title="Giỏ hàng"></a></li>
        </ul>
    </header>

    <!-- Product Detail Section -->
    <section class="product-detail">
        <div class="container">
            <!-- Breadcrumb -->
            <div class="breadcrumb">
                <a href="/index.php">Trang chủ</a>
                <span>›</span>
                <a href="/products.php?cartegory_id=<?php echo (int)$product['cartegory_id']; ?>"><?php echo htmlspecialchars($product['cartegory_name']); ?></a>
                <span>›</span>
                <a href="/products.php?brand_id=<?php echo (int)$product['brand_id']; ?>"><?php echo htmlspecialchars($product['brand_name']); ?></a>
                <span>›</span>
                <span class="current"><?php echo htmlspecialchars($product['product_name']); ?></span>
            </div>

            <div class="product-content">
                <!-- Product Images -->
                <div class="product-images">
                    <div class="product-main-image">
                        <img src="/admin/uploads/<?php echo htmlspecialchars($product['product_img']); ?>" alt="<?php echo htmlspecialchars($product['product_name']); ?>" id="mainImage" onerror="this.src='/images/slider1.jpg'">
                    </div>
                    <div class="product-thumbnails">
                        <img src="/admin/uploads/<?php echo htmlspecialchars($product['product_img']); ?>" alt="" class="thumb active" onclick="changeImage(this)" onerror="this.src='/images/slider1.jpg'">
                        <img src="/images/slider1.jpg" alt="" class="thumb" onclick="changeImage(this)">
                        <img src="/images/slider2.jpg" alt="" class="thumb" onclick="changeImage(this)">
                        <img src="/images/slider3.jpg" alt="" class="thumb" onclick="changeImage(this)">
                    </div>
                </div>

                <!-- Product Info -->
                <div class="product-info">
                    <h1 class="product-name"><?php echo htmlspecialchars($product['product_name']); ?></h1>
                    <p class="product-code">MSP: <?php echo htmlspecialchars($product['product_id']); ?></p>

                    <div class="product-price">
                        <?php if (!empty($product['product_price_new']) && $product['product_price_new'] > 0): ?>
                        <span class="price-old"><?php echo number_format($product['product_price'], 0, ',', '.'); ?><sup>đ</sup></span>
                        <span class="price-current"><?php echo number_format($product['product_price_new'], 0, ',', '.'); ?><sup>đ</sup></span>
                        <?php else: ?>
                        <span class="price-current"><?php echo number_format($product['product_price'], 0, ',', '.'); ?><sup>đ</sup></span>
                        <?php endif; ?>
                    </div>

                    <div class="product-color">
                        <p><strong>Màu sắc:</strong> <?php echo htmlspecialchars($product['brand_name']); ?></p>
                        <div class="color-options">
                            <img src="/admin/uploads/<?php echo htmlspecialchars($product['product_img']); ?>" alt="" class="color-thumb" onerror="this.src='/images/slider1.jpg'">
                        </div>
                    </div>

                    <div class="product-size">
                        <p><strong>Size:</strong></p>
                        <div class="size-options">
                            <span class="size-btn" onclick="selectSize(this)">S</span>
                            <span class="size-btn" onclick="selectSize(this)">M</span>
                            <span class="size-btn" onclick="selectSize(this)">L</span>
                            <span class="size-btn" onclick="selectSize(this)">XL</span>
                            <span class="size-btn" onclick="selectSize(this)">XXL</span>
                        </div>
                    </div>

                    <div class="product-quantity">
                        <p><strong>Số lượng:</strong></p>
                        <div class="quantity-selector">
                            <button class="qty-btn" onclick="changeQty(-1)">-</button>
                            <input type="number" id="quantity" value="1" min="1" max="99">
                            <button class="qty-btn" onclick="changeQty(1)">+</button>
                        </div>
                    </div>

                    <p class="size-warning" id="sizeWarning">Vui lòng chọn size</p>

                    <div class="product-buttons">
                        <button class="btn-buy" onclick="buyNow()">
                            <i class="fas fa-credit-card"></i>
                            MUA NGAY
                        </button>
                        <button class="btn-add-cart" onclick="addToCart()">
                            <i class="fas fa-shopping-cart"></i>
                            THÊM VÀO GIỎ
                        </button>
                    </div>

                    <!-- Product Details Tabs -->
                    <div class="product-tabs">
                        <div class="tabs-header">
                            <button class="tab-btn active" onclick="openTab(event, 'details')">Chi tiết</button>
                            <button class="tab-btn" onclick="openTab(event, 'care')">Bảo quản</button>
                            <button class="tab-btn" onclick="openTab(event, 'size-guide')">Tham khảo size</button>
                        </div>
                        <div class="tabs-content">
                            <div id="details" class="tab-pane active">
                                <p><?php echo nl2br(htmlspecialchars($product['product_desc'] ?? 'Quần Âu có túi phía trước và túi may phía sau. Đai quần có đĩa. Cài phía trước bằng khóa kéo và khuy. Form dáng tạo phong cách trẻ trung, sang trọng và lịch lãm.')); ?></p>
                            </div>
                            <div id="care" class="tab-pane">
                                <p><strong>Chi tiết bảo quản sản phẩm:</strong></p>
                                <ul>
                                    <li>Vải dệt kim: sau khi giặt sản phẩm phải được phơi ngang tránh bai dãn.</li>
                                    <li>Vải voan, lụa, chiffon nên giặt bằng tay.</li>
                                    <li>Vải thô, tuytsy, kaki không có phối hay trang trí đá cườm thì có thể giặt máy.</li>
                                    <li>Các sản phẩm cần được giặt ngay không ngâm tránh bị loang màu.</li>
                                    <li>Nên phơi sản phẩm tại chỗ thoáng mát, tránh ánh nắng trực tiếp.</li>
                                </ul>
                            </div>
                            <div id="size-guide" class="tab-pane">
                                <table class="size-table">
                                    <tr>
                                        <th>Size</th>
                                        <th>Chiều cao (cm)</th>
                                        <th>Cân nặng (kg)</th>
                                    </tr>
                                    <tr>
                                        <td>S</td>
                                        <td>155 - 160</td>
                                        <td>45 - 50</td>
                                    </tr>
                                    <tr>
                                        <td>M</td>
                                        <td>160 - 165</td>
                                        <td>50 - 55</td>
                                    </tr>
                                    <tr>
                                        <td>L</td>
                                        <td>165 - 170</td>
                                        <td>55 - 62</td>
                                    </tr>
                                    <tr>
                                        <td>XL</td>
                                        <td>170 - 175</td>
                                        <td>62 - 70</td>
                                    </tr>
                                    <tr>
                                        <td>XXL</td>
                                        <td>175 - 180</td>
                                        <td>70 - 80</td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Related Products -->
    <?php if (!empty($relatedProducts)): ?>
    <section class="related-products">
        <div class="container">
            <h2 class="section-title">SẢN PHẨM LIÊN QUAN</h2>
            <div class="related-grid">
                <?php foreach ($relatedProducts as $related): ?>
                <a href="/product.php?id=<?php echo (int)$related['product_id']; ?>" class="related-item">
                    <img src="/admin/uploads/<?php echo htmlspecialchars($related['product_img']); ?>" alt="<?php echo htmlspecialchars($related['product_name']); ?>" onerror="this.src='/images/slider1.jpg'">
                    <h3><?php echo htmlspecialchars($related['product_name']); ?></h3>
                    <p class="related-price">
                        <?php if (!empty($related['product_price_new']) && $related['product_price_new'] > 0): ?>
                        <?php echo number_format($related['product_price_new'], 0, ',', '.'); ?><sup>đ</sup>
                        <?php else: ?>
                        <?php echo number_format($related['product_price'], 0, ',', '.'); ?><sup>đ</sup>
                        <?php endif; ?>
                    </p>
                </a>
                <?php endforeach; ?>
            </div>
        </div>
    </section>
    <?php endif; ?>

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
        // Change main image
        function changeImage(thumb) {
            document.getElementById('mainImage').src = thumb.src;
            document.querySelectorAll('.thumb').forEach(t => t.classList.remove('active'));
            thumb.classList.add('active');
        }

        // Select size
        let selectedSize = null;
        function selectSize(btn) {
            document.querySelectorAll('.size-btn').forEach(b => b.classList.remove('active'));
            btn.classList.add('active');
            selectedSize = btn.textContent;
            document.getElementById('sizeWarning').style.display = 'none';
        }

        // Change quantity
        function changeQty(delta) {
            const input = document.getElementById('quantity');
            let val = parseInt(input.value) + delta;
            if (val < 1) val = 1;
            if (val > 99) val = 99;
            input.value = val;
        }

        // Add to cart
        function addToCart() {
            if (!selectedSize) {
                document.getElementById('sizeWarning').style.display = 'block';
                return;
            }
            
            const productId = <?php echo (int)$product_id; ?>;
            const quantity = document.getElementById('quantity').value;
            
            fetch('/add_to_cart.php', {
                method: 'POST',
                headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                body: `product_id=${productId}&quantity=${quantity}&size=${selectedSize}`
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    alert('Đã thêm sản phẩm vào giỏ hàng!');
                    // Update cart count in header if needed
                } else {
                    alert(data.message);
                }
            })
            .catch(err => {
                alert('Có lỗi xảy ra, vui lòng thử lại');
            });
        }

        // Buy now
        function buyNow() {
            if (!selectedSize) {
                document.getElementById('sizeWarning').style.display = 'block';
                return;
            }
            
            const productId = <?php echo (int)$product_id; ?>;
            const quantity = document.getElementById('quantity').value;
            
            fetch('/add_to_cart.php', {
                method: 'POST',
                headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                body: `product_id=${productId}&quantity=${quantity}&size=${selectedSize}`
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    window.location.href = '/cart.php';
                } else {
                    alert(data.message);
                }
            })
            .catch(err => {
                alert('Có lỗi xảy ra, vui lòng thử lại');
            });
        }

        // Tabs
        function openTab(evt, tabName) {
            document.querySelectorAll('.tab-pane').forEach(p => p.classList.remove('active'));
            document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
            document.getElementById(tabName).classList.add('active');
            evt.currentTarget.classList.add('active');
        }
    </script>
    <script src="/js/rollindexheader.js"></script>
</body>
</html>
