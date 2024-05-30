<link rel="stylesheet" href="assets/css/user/detail_product.css">

<?php
    $product_id = isset($_GET['id']) ? (int)$_GET['id'] : 1;

    if (isset($_POST['themgiohang'])) {
        if (!isset($_SESSION['user_id'])) {
        echo "<script>alert('Vui lòng đăng nhập trước khi thêm sản phẩm vào giỏ hàng.'); window.location.href='/my-pham-cocolux/login.php';</script>";
        exit();
    }

    $product_id = isset($_POST['id']) ? (int)$_POST['id'] : $product_id;
    $quantity = isset($_POST['quantity']) ? (int)$_POST['quantity'] : 1;

    $user_id = $_SESSION['user_id'];

    $sql_check_cart = "SELECT quantity FROM cart_item WHERE product_id = $product_id AND user_id = $user_id";
    $result = mysqli_query($connect, $sql_check_cart);

    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $new_quantity = $row['quantity'] + $quantity;
        $sql_update_cart = "UPDATE cart_item SET quantity = $new_quantity WHERE product_id = $product_id AND user_id = $user_id";
        mysqli_query($connect, $sql_update_cart);
    } else {
        $sql_insert_cart = "INSERT INTO cart_item (product_id, quantity, user_id) VALUES ($product_id, $quantity, $user_id)";
        mysqli_query($connect, $sql_insert_cart);
    }

    header("Location: ?page=gio-hang");
    exit();
    }

    $sql_detail = "SELECT p.*, pi.path_url, b.name AS brand_name, pc.category_id
					FROM product p
					JOIN brand b ON p.brand_id = b.id
					LEFT JOIN (
						SELECT product_id, MIN(id) as min_id
						FROM product_image
						GROUP BY product_id
					) pi ON p.id = pi.product_id
					LEFT JOIN product_image pi ON pi.min_id = pi.id
                    LEFT JOIN product_category pc ON p.id = pc.product_id
                    WHERE p.id= $product_id";
    $query_detail = mysqli_query($connect, $sql_detail);

    while ($row_detail = mysqli_fetch_array($query_detail)) {
?>

<div class="wp-detail-product">
    <div class="container">
        <div class="detail-product-nav">
            <div class="nav-detail-product-child">
                <a class="home-page-link" href="/my-pham-cocolux">
                    <i class="fa-solid fa-house-chimney"></i>
                    <div>Trang chủ</div>
                </a>
                <div class="slash">/</div>
                <a href="?page=danh-muc&id=<?= $row_detail['category_id'] ?>">Sản phẩm</a>
                <div class="slash">/</div>
                <div><?= $row_detail['name'] ?></div>
            </div>
        </div>
    </div>
    <hr class="detail-product-hr">
    <div class="container">
        <form  action="" method="POST">
            <div class="detail-product">
                <div class="product">
                    <div class="product-img">
                        <img src="<?= $row_detail['path_url'] ?>">
                    </div>
                    <div class="product-infor">
                        <div class="product-brand"><?= $row_detail['brand_name'] ?></div>
                        <div class="product-name"><?= $row_detail['name'] ?></div>
                        <div class="feedback">
                            <div class="stars" style="display: flex; gap: 2px;">
                                <span style="color: #c73030 ;" class="fa fa-star checked"></span>
                                <span style="color: #c73030 ;" class="fa fa-star checked"></span>
                                <span style="color: #c73030;" class="fa fa-star checked"></span>
                                <span style="color: #c73030;" class="fa fa-star checked"></span>
                                <span style="color: grey;" class="fa fa-star checked"></span>
                            </div>
                            <div class="slash-straight">|</div>
                            <a>0 đánh giá</a>
                        </div>
                        <div class="product-price">
                            <div class="price-final"><?= number_format($row_detail['price_final'], 0, ',', '.') . ' VNĐ'  ?></div>
                            <?php if ($row_detail['price_original'] != $row_detail['price_final']) : ?>
                                <div class="price-original"><?= number_format($row_detail['price_original'], 0, ',', '.') . ' VNĐ'  ?></div>
                            <?php endif; ?>
                        </div>
                        <div class="quantity">
                            <div class="quantity-title">Số lượng:</div>
                            <div class="quantity-up-and-down">
                                <a class="quantity-down"><i class="fa fa-minus" style="color: black;"></i></a>
                                <input class="quantity-input" type="text" name="quantity" value="1">
                                <a class="quantity-up"><i class="fa fa-plus" style="color: black;"></i></a>
                            </div>
                            <div class="remaining"><?= $row_detail['quantity'] - $row_detail['sold'] ?> sản phẩm có sẵn</div>
                        </div>
                        <button class="add-to-cart" type="submit" name="themgiohang" value="Giỏ hàng"><i class="fa-solid fa-cart-plus"></i>Giỏ hàng</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
    <div class="container">
        <div class="information">
            <div class="information-big-title">Thông tin sản phẩm</div>
            <div class="information-col">
                <div class="information-row">
                    <div class="information-title">Xuất xứ thương hiệu</div>
                    <div class="information-content"><?= $row_detail['country']?></div>
                </div>
            </div>
            <div class="information-col">
                <div class="information-row">
                    <div class="information-title">Thương hiệu</div>
                    <div class="information-content"><?= $row_detail['brand_name']?></div>
                </div>
            </div>
            <div class="information-col">
                <div class="information-row">
                    <div class="information-title">Loại da thích hợp</div>
                    <div class="information-content"><?= $row_detail['skin_type']?></div>
                </div>
            </div>
            <div class="information-col">
                <div class="information-row custom-border">
                    <div class="information-title">Nguyên liệu</div>
                    <div class="information-content"><?= $row_detail['ingredient']?></div>
                </div>
            </div>
        </div>
    </div>
    <div class="container">
        <div class="description">
            <div class="description-title">Mô tả sản phẩm</div>
            <div class="description-content"><?= $row_detail['description']?></div>
        </div>
    </div>
    <div class="container">
        <div class="uses">
            <div class="uses-title">Công dụng</div>
            <div class="uses-content"><?= $row_detail['uses']?></div>
        </div>
    </div>
    <div class="container">
        <div class="instruction">
            <div class="instruction-title">Cách dùng</div>
            <div class="instruction-content"><?= $row_detail['instruction']?></div>
        </div>
    </div>
    <?php
        $brand_name = $row_detail['brand_name'];
        $sql_same_brand = "SELECT p.*, pi.path_url, b.name AS brand_name
                            FROM product p
                            JOIN brand b ON p.brand_id = b.id
                            LEFT JOIN (
                                SELECT product_id, MIN(id) as min_id
                                FROM product_image
                                GROUP BY product_id
                            ) pim ON p.id = pim.product_id
                            LEFT JOIN product_image pi ON pim.min_id = pi.id
                            WHERE b.name = '$brand_name' AND p.id != $product_id
                            LIMIT 4";
        $query_same_brand = mysqli_query($connect, $sql_same_brand);
    ?>
    <div class="container">
        <div class="the-same-brand">
            <div class="the-same-brand-title">Sản phẩm cùng thương hiệu</div>
            <div class="list-products">
                <?php
                while ($row_same_brand = mysqli_fetch_array($query_same_brand)) {
                ?>
                    <div class="wp-product-item">
                        <a style="text-decoration: none;" href="?page=san-pham&id=<?= $row_same_brand['id'] ?>">
                            <img class="img" src=<?= $row_same_brand['path_url'] ?>>
                            <div class="item-content">
                                <div class="price">
                                    <div class="price_final"><?= number_format($row_same_brand['price_final'], 0, ',', '.') . ' VNĐ'  ?></div>
                                    <?php if ($row_same_brand['price_original'] != $row_same_brand['price_final']) : ?>
                                        <div class="price_original"><?= number_format($row_same_brand['price_original'], 0, ',', '.') . ' VNĐ'  ?></div>
                                    <?php endif; ?>
                                </div>
                                <div class="brand"><?= $row_same_brand['brand_name'] ?></div>
                                <div class="name"><?= $row_same_brand['name'] ?></div>
                            </div>
                        </a>
                    </div>
                <?php
                }
                ?>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function () {
    const quantityInput = document.querySelector(".quantity-input");
    const quantityUp = document.querySelector(".quantity-up");
    const quantityDown = document.querySelector(".quantity-down");
    const maxQuantity = <?= $row_detail['quantity'] - $row_detail['sold'] ?>;

    quantityUp.addEventListener("click", function () {
        let currentQuantity = parseInt(quantityInput.value);
        if (currentQuantity < maxQuantity) {
            quantityInput.value = currentQuantity + 1;
        } else {
            alert("Số lượng sản phẩm còn lại chỉ còn: " + maxQuantity);
        }
    });

    quantityDown.addEventListener("click", function () {
        let currentQuantity = parseInt(quantityInput.value);
        if (currentQuantity > 1) {
            quantityInput.value = currentQuantity - 1;
        } else {
            alert("Số lượng sản phẩm phải lớn hơn hoặc bằng 1");
        }
    });
});
</script>
<?php
}
?>