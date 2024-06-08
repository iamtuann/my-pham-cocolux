<link rel="stylesheet" href="assets/css/user/list_product.css">

<?php
	$sql_show_hot = "SELECT p.*, pi.path_url, b.name AS brand_name
					FROM product p
					JOIN brand b ON p.brand_id = b.id
					LEFT JOIN (
						SELECT product_id, MIN(id) as min_id
						FROM product_image
						GROUP BY product_id
					) pim ON p.id = pim.product_id
					LEFT JOIN product_image pi ON pim.min_id = pi.id
					ORDER BY p.create_date DESC
					LIMIT 8";
    $query_show_hot = mysqli_query($connect, $sql_show_hot);
?>

<div class="container">
    <div class="wp-products">
        <div class="d-flex justify-content-between">
            <h3 class="title">SẢN PHẨM HOT</h3>
            <a class="view-all" href="?page=all-product">XEM TẤT CẢ</a>
        </div>
        <div class="list-products">
            <?php
			while ($row = mysqli_fetch_array($query_show_hot)) {
			?>
            <div class="wp-product-item">
                <a style="text-decoration: none;" href="?page=san-pham&id=<?= $row['id'] ?>">
                    <img class="product-img" src="uploads/<?= $row['path_url'] ?>">
                    <div class="item-content">
                        <div class="price">
                            <div class="price_final"><?= number_format($row['price_final'], 0, ',', '.') . ' VNĐ'  ?>
                            </div>
                            <?php if ($row['price_original'] != $row['price_final']) : ?>
                            <div class="price_original">
                                <?= number_format($row['price_original'], 0, ',', '.') . ' VNĐ'  ?></div>
                            <?php endif; ?>
                        </div>
                        <div class="brand"><?= $row['brand_name'] ?></div>
                        <div class="name"><?= $row['name'] ?></div>
                    </div>
                </a>
            </div>
            <?php
			}
			?>
        </div>
    </div>
</div>