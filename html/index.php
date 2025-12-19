<?php
require_once __DIR__ . '/../admin/session.php';
Session::init();
$userLoggedIn = Session::get('user_login');
$userName = Session::get('user_name');
require_once __DIR__ . '/../admin/class/brand_class.php';
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
        <div class="menu">
            <?php foreach ($categories as $cat): ?>
                <?php $catName = trim($cat['cartegory_name']); ?>
                <li><a href="#"><?php echo htmlspecialchars($catName); ?></a>
                    <ul class="sub-menu">
                        <?php if (!empty($brandByCategory[$catName])): ?>
                            <?php foreach ($brandByCategory[$catName] as $brand): ?>
                                <li><a href="/product.php?brand_id=<?php echo (int)$brand['brand_id']; ?>"><?php echo htmlspecialchars($brand['brand_name']); ?></a></li>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <li><span>Chưa có thương hiệu</span></li>
                        <?php endif; ?>
                    </ul>
                </li>
            <?php endforeach; ?>
            <li><a href="#">BỘ SƯU TẬP</a></li>
            <li><a href="#">THÔNG TIN</a></li>
        </div>
        <div class="others">
            <li><input type="text" placeholder="Tìm kiếm sản phẩm"><i href="" class="fas fa-search"></i></li>
            <?php if ($userLoggedIn): ?>
                <li style="display:flex; align-items:center; gap:8px;">
                    <span style="font-weight:600; color:#333;">Xin chào, <?php echo htmlspecialchars($userName); ?></span>
                    <a href="/logout.php" style="padding:6px 12px; border:1px solid #ddd; border-radius:16px; font-size:13px; color:#222; text-decoration:none;">Đăng xuất</a>
                </li>
            <?php else: ?>
                <li style="display:flex; align-items:center; gap:8px;">
                    <a href="/login.php" class="fas fa-user" style="font-size:16px; color:#222;"></a>
                    <a href="/login.php" style="display:inline-flex; align-items:center; padding:6px 14px; border:1px solid #e0e0e0; border-radius:16px; font-size:13px; font-weight:600; color:#222; text-decoration:none; box-shadow:0 2px 6px rgba(0,0,0,0.08); background:#fff;">Đăng nhập</a>
                </li>
            <?php endif; ?>
            <li><a href="" class="fas fa-shopping-bag"></a></li>
        </div>
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

    <!-------------------------------------- app-container------------------------------------------------->
     <section class="app-container"> 
        <p>Tải ứng dụng ZARA</p>
        <div class="app-google">
<img src="/images/appstore.png" alt="">
<img src="/images/ggplay.png" alt="">
        </div>
<p>Nhận bản tin ZARA</p>
<input type="text"  placeholder="Nhập email của bạn...">
     </section>
    
     <!----------------------------------------- footer--------------------------------------------------------->
     <div class="footer-top">
        <li><a href="">Liên hệ</a></li>
        <li><a href="">Tuyển dụng</a></li>
        <li><a href="">Giới thiệu</a></li>
        <li>
            <a href="" class="fab fa-facebook-f"></a>
            <a href="" class="fab fa-twitter"></a>
            <a href="" class="fab fa-youtube"></a>

        </li>
        </div>
        </body>
<script src="/js/sliderindex.js"></script>
<script src="/js/rollindexheader.js"></script>


</html>
