<?php
// Load session and database
require_once __DIR__ . '/admin/session.php';
require_once __DIR__ . '/admin/class/brand_class.php';

Session::init();
$userLoggedIn = Session::get('user_login');
$userName = Session::get('user_name');

$db = new Database();
$brandClass = new brand();

// Lấy danh mục
$categories = array();
$categoryRows = $brandClass->show_cartegory();
if ($categoryRows) {
    while ($cat = $categoryRows->fetch_assoc()) {
        $categories[] = $cat;
    }
}

// Lấy brand theo category
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

// Lọc theo danh mục hoặc brand nếu có
$filter_cartegory = isset($_GET['cartegory_id']) ? (int)$_GET['cartegory_id'] : 0;
$filter_brand = isset($_GET['brand_id']) ? (int)$_GET['brand_id'] : 0;
$sort = isset($_GET['sort']) ? $_GET['sort'] : '';

// Build query
$where = array();
if ($filter_cartegory > 0) {
    $where[] = "tbl_product.cartegory_id = $filter_cartegory";
}
if ($filter_brand > 0) {
    $where[] = "tbl_product.brand_id = $filter_brand";
}

$whereClause = count($where) > 0 ? "WHERE " . implode(" AND ", $where) : "";

$orderBy = "ORDER BY tbl_product.product_id DESC";
if ($sort == 'price_asc') {
    $orderBy = "ORDER BY tbl_product.product_price ASC";
} elseif ($sort == 'price_desc') {
    $orderBy = "ORDER BY tbl_product.product_price DESC";
}

$productQuery = "SELECT tbl_product.*, tbl_cartegory.cartegory_name, tbl_brand.brand_name 
                 FROM tbl_product 
                 LEFT JOIN tbl_cartegory ON tbl_product.cartegory_id = tbl_cartegory.cartegory_id
                 LEFT JOIN tbl_brand ON tbl_product.brand_id = tbl_brand.brand_id
                 $whereClause
                 $orderBy";
$products = $db->select($productQuery);
$productList = array();
if ($products) {
    while ($prod = $products->fetch_assoc()) {
        $productList[] = $prod;
    }
}

// Lấy tên danh mục đang lọc
$currentCategoryName = "Tất cả sản phẩm";
if ($filter_cartegory > 0) {
    foreach ($categories as $cat) {
        if ($cat['cartegory_id'] == $filter_cartegory) {
            $currentCategoryName = $cat['cartegory_name'];
            break;
        }
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
    <link rel="stylesheet" href="/css/products.css">
    <title>Kho hàng - ZARA SHOP</title>
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
            <li><a href="/products.php" class="fas fa-shopping-bag active" title="Kho hàng"></a></li>
            <li><a href="/cart.php" class="fas fa-shopping-cart" title="Giỏ hàng"></a></li>
        </ul>
    </header>

    <!-- Breadcrumb & Products -->
    <section class="products-page">
        <div class="container">
            <!-- Breadcrumb -->
            <div class="breadcrumb">
                <a href="/index.php">Trang chủ</a>
                <span>›</span>
                <span><?php echo htmlspecialchars($currentCategoryName); ?></span>
            </div>

            <div class="products-wrapper">
                <!-- Sidebar -->
                <aside class="products-sidebar">
                    <ul class="category-list">
                        <li class="<?php echo ($filter_cartegory == 0 && $filter_brand == 0) ? 'active' : ''; ?>">
                            <a href="/products.php">Tất cả sản phẩm</a>
                        </li>
                        <?php foreach ($categories as $cat): ?>
                        <li class="category-item">
                            <a href="/products.php?cartegory_id=<?php echo (int)$cat['cartegory_id']; ?>" class="category-title <?php echo $filter_cartegory == $cat['cartegory_id'] ? 'active' : ''; ?>"><?php echo htmlspecialchars($cat['cartegory_name']); ?></a>
                            <?php $catName = trim($cat['cartegory_name']); ?>
                            <?php if (!empty($brandByCategory[$catName])): ?>
                            <ul class="brand-list">
                                <?php foreach ($brandByCategory[$catName] as $brand): ?>
                                <li class="<?php echo $filter_brand == $brand['brand_id'] ? 'active' : ''; ?>">
                                    <a href="/products.php?brand_id=<?php echo (int)$brand['brand_id']; ?>">
                                        <?php echo htmlspecialchars($brand['brand_name']); ?>
                                    </a>
                                </li>
                                <?php endforeach; ?>
                            </ul>
                            <?php endif; ?>
                        </li>
                        <?php endforeach; ?>
                    </ul>
                </aside>

                <!-- Main Content -->
                <main class="products-main">
                    <!-- Top Bar -->
                    <div class="products-topbar">
                        <h2><?php echo htmlspecialchars($currentCategoryName); ?></h2>
                        <div class="products-actions">
                            <span class="product-count"><?php echo count($productList); ?> sản phẩm</span>
                            <select class="sort-select" onchange="window.location.href=this.value">
                                <option value="/products.php?<?php echo http_build_query(array_merge($_GET, ['sort' => ''])); ?>">Sắp xếp</option>
                                <option value="/products.php?<?php echo http_build_query(array_merge($_GET, ['sort' => 'price_asc'])); ?>" <?php echo $sort == 'price_asc' ? 'selected' : ''; ?>>Giá thấp đến cao</option>
                                <option value="/products.php?<?php echo http_build_query(array_merge($_GET, ['sort' => 'price_desc'])); ?>" <?php echo $sort == 'price_desc' ? 'selected' : ''; ?>>Giá cao đến thấp</option>
                            </select>
                        </div>
                    </div>

                    <!-- Products Grid -->
                    <?php if (empty($productList)): ?>
                    <div class="products-empty">
                        <i class="fas fa-box-open"></i>
                        <p>Chưa có sản phẩm nào</p>
                    </div>
                    <?php else: ?>
                    <div class="products-grid">
                        <?php foreach ($productList as $product): ?>
                        <div class="product-card">
                            <a href="/product.php?id=<?php echo (int)$product['product_id']; ?>" class="product-card-img">
                                <img src="/admin/uploads/<?php echo htmlspecialchars($product['product_img']); ?>" alt="<?php echo htmlspecialchars($product['product_name']); ?>" onerror="this.src='/images/slider1.jpg'">
                            </a>
                            <div class="product-card-info">
                                <h4><a href="/product.php?id=<?php echo (int)$product['product_id']; ?>"><?php echo htmlspecialchars($product['product_name']); ?></a></h4>
                                <p class="product-card-brand"><?php echo htmlspecialchars($product['brand_name'] ?? ''); ?></p>
                                <div class="product-card-price">
                                    <?php if (!empty($product['product_price_new']) && $product['product_price_new'] > 0): ?>
                                    <span class="price-old"><?php echo number_format($product['product_price'], 0, ',', '.'); ?>đ</span>
                                    <span class="price-new"><?php echo number_format($product['product_price_new'], 0, ',', '.'); ?>đ</span>
                                    <?php else: ?>
                                    <span class="price-new"><?php echo number_format($product['product_price'], 0, ',', '.'); ?>đ</span>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                    <?php endif; ?>
                </main>
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
<script src="/js/rollindexheader.js"></script>
</html>
