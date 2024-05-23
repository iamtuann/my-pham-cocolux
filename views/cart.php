
<?php
    $sql_cart = "SELECT p.id AS product_id, p.name, p.price_original, p.price_final, pi.path_url as path_url, ci.quantity
    FROM cart_item ci
    JOIN user u ON ci.user_id = u.id
    JOIN product p ON ci.product_id = p.id
    LEFT JOIN (
      SELECT product_id, MIN(id) as min_id
      FROM product_image
      GROUP BY product_id
    ) pim ON p.id = pim.product_id
    LEFT JOIN product_image pi ON pim.min_id = pi.id
    WHERE u.id = 1";
    $query_cart = mysqli_query($connect, $sql_cart);
    $allprice =0;
    $num = mysqli_num_rows($query_cart);
?>
<style> 
    img.cart-product-img {
        height: 100px;
    }
      
    .cart-table {
        border-radius: 5px;
        box-shadow: 0 3px 6px rgba(0, 0, 0, 0.15);
        font-size: 14px;
    }
    .cart-table td{
        padding:15px;
        vertical-align: middle;
        text-align: center;
    }
    .cart-table td:nth-child(2) {
        vertical-align: baseline;
        text-align: left;
    }
    .cart-table .quantity-input {
        margin: auto;
        padding: 5px;
        width: 60px;
        font-size: 14px;
        outline: 0;
        border: 0;
        background: #fff;
        border-radius: 5px;
        box-shadow: 0 3px 6px rgba(0, 0, 0, 0.15) !important;
    }
    .cart-table th {
        white-space: nowrap;
        text-align: center;
        vertical-align: middle;
        padding: 20px;
        background: #e5e4e4;
    }
    .confirm-checkout {
        display: block;
        background: #c73030;
        color: #fff;
        padding: 10px 15px;
        border-radius: 5px;
        text-decoration: none;
        height: 100%;
    }
    .button-xoa{
        border: none;
        background:none;   
        text-decoration: none;
        color: black;
        padding-left: 10px;
        padding-right: 10px;
    }
    .cart-product{
        padding-bottom:20px;
        background:white;
    }
    .cart-product:hover{
        background: #f3f3f3;
    }
    .cart-product:hover button{
        background: #f3f3f3;
    }
    .cart-header {
        background: #e5e4e4;
        border-radius: 5px 5px 0 0;
        box-shadow: 0 3px 6px rgba(0, 0, 0, 0.15);
    }
</style>

<div class="container">
    <div class="mt-4 mb-5">
        <div class ="fw-bold mb-b">Giỏ Hàng</div>
        <table class = "cart-table mb-4">
            <tr class="cart-header">
                <th></th>
                <th class ="fw-bold">Sản phẩm</th>
                <th class ="fw-bold">Giá sản phẩm</th>
                <th class ="fw-bold">Số lượng</th>
                <th class ="fw-bold">Thành tiền</th>
                <th class ="fw-bold">Thao tác</th>
            </tr>
            <?php
                while ($row = mysqli_fetch_array($query_cart)) {
                    $allprice += $row['quantity']*$row['price_final'];
                ?>
                    <tr class="cart-product">
                        <td><img class="cart-product-img" src=<?= $row['path_url'] ?>></td>
                        <td style="width: 100%;"><div class="fw-bold"><?= $row['name'] ?></div></td>
                        <td><div><?= number_format($row['price_final'], 0, ',', '.') . ' Đ'  ?></div></td>
                        <td><input class="quantity-input" type="number" name="new_quantity" value="<?= $row['quantity'] ?>" min="1" required></td>
                        <td><div><?= number_format($row['quantity']*$row['price_final'], 0, ',', '.') . ' Đ'   ?></div></td>
                        <td><a href='xoa_cart.php?id=<?php echo $row['product_id']; ?>' class="button-xoa">Xóa</a> </td>
                    </tr>
                <?php
                }
                ?>
        </table>
        <div class="d-flex justify-content-end"> 
            <div class ="fw-bold pe-4 text-end">
                <p class="mb-0">Tổng tiền hàng (<?= $num ?> Sản phẩm)</p>
                <p class="fs-5 text-danger"><?= number_format($allprice , 0, ',', '.') ?> Đ</p>
            </div>
            <a href="?page=checkout" class="confirm-checkout">Tiến hành đặt hàng</a>
        </div>
    </div>
</div>