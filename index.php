<?php
// Load session and classes from the local admin folder (not parent dir)
require_once __DIR__ . '/admin/session.php';
Session::init();
$userLoggedIn = Session::get('user_login');
$userName = Session::get('user_name');
require_once __DIR__ . '/admin/class/brand_class.php';

$brandClass = new brand();
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
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <script src="https://kit.fontawesome.com/1147679ae7.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="/css/style.css ">
    <title>ZARA SHOP</title>
</head>

<body>
    <header>
        <div class="logo">
            <img src="/images/logo.png" alt="">
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

    <section id="Slider">
        <div class="aspect-ratio-169">
            <div class="slider-container">
                <div class="slider-inner-frame">
                    <div class="slider-track">
                        <img src="/images/slider1.jpg" alt="" data-slide="1">
                        <img src="/images/slider2.jpg" alt="" data-slide="2">
                        <img src="/images/slider3.jpg" alt="" data-slide="3">
                        <img src="/images/slider4.png" alt="" data-slide="4">
                        <img src="/images/slider5.jpg" alt="" data-slide="5">
                    </div>
                </div>
            </div>
            <button class="arrow prev">&#8249;</button>
            <button class="arrow next">&#8250;</button>
            <div class="dot-container">
                <div class="dot active"></div>
                <div class="dot"></div>
                <div class="dot"></div>
                <div class="dot"></div>
                <div class="dot"></div>
            </div>
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
</body>
<script src="/js/sliderindex.js"></script>
<script src="/js/rollindexheader.js"></script>


</html>
