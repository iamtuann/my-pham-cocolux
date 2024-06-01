
<?php
    if (isset($_SESSION['user_id'])) {
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
    WHERE u.id = ". $_SESSION['user_id'];
    $query_cart = mysqli_query($connect, $sql_cart);
    $allprice = 0;
    $num = mysqli_num_rows($query_cart);
    } else {
        echo "<script>alert('Vui lòng đăng nhập để xem giỏ hàng.'); window.location.href='login.php';</script>";
        exit();
    }
?>
<style> 
    img.cart-product-img {
        height: 100px;
    }
      
    .cart-table {
        border-radius: 5px;
        box-shadow: 0 3px 6px rgba(0, 0, 0, 0.15);
        font-size: 14px;
        width: 100%;
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
    .cart-table .quantity-cart-input {
        margin: auto;
        padding: 5px;
        width: 35px;
        font-size: 14px;
        outline: 0;
        border: 0;
        background: #fff;
        border-radius: 5px;
        box-shadow: 0 3px 6px rgba(0, 0, 0, 0.15) !important;
        text-align: center;
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
    .empty-cart {
        padding: 50px;
        display: flex;
        flex-direction: column;
        gap: 20px;
        align-items: center;
    }
    .empty-cart-img{
        width: 500px;
        height: 300px;
        color: #f3f3f3;
    }
    .empty-cart-text {
        font-size: 22px;
        font-weight: 500;
        color: grey;
    }
    .back-to-home-page {
        text-decoration: none;
        color: black;
    }
    .empty-cart-button{
        padding: 12px 20px;
        border-radius: 15px;
        background-color: grey;
        color: white;
        font-size: 16px;
        font-weight: 500;
    }
    .quantity{
        display: flex; 
        gap: 10px;
        align-items: center;
    }
    .quantity-icon{
        color: black;
    }
</style>
<?php
if ($num > 0){
?>
<div class="container">
    <div class="mt-4 mb-5">
        <div class ="fw-bold py-3">Giỏ Hàng</div>
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
                    <td><div><?= number_format($row['price_final'], 0, ',', '.') . ' VNĐ'  ?></div></td>
                    <td>
                        <div class="quantity">
                            <a class="quantity-icon" href='?page=update-quantity&minus=<?php echo $row['product_id']; ?>'><i class="fa-solid fa-minus"></i></a>
                               <input class="quantity-cart-input" type="text" name="quantity" value="<?php echo $row['quantity'] ?>">
                            <a class="quantity-icon" href='?page=update-quantity&plus=<?php echo $row['product_id']; ?>'><i class="fa-solid fa-plus"></i></a>
                        </div>
                    </td>
                    <td><div><?= number_format($row['quantity']*$row['price_final'], 0, ',', '.') . ' VNĐ'   ?></div></td>
                    <td><a href='?page=delete-product&id=<?php echo $row['product_id']; ?>' class="button-xoa" onclick="return confirm('Bạn có chắc chắn muốn xóa?');">Xóa</a> </td>
                </tr>
            <?php
                }
            ?>
        </table>
        <?php
            if ($num > 0) {
        ?>
            <div class="d-flex justify-content-end">
                <div class ="fw-bold pe-4 text-end">
                    <p class="mb-0">Tổng tiền hàng (<?= $num ?> Sản phẩm)</p>
                    <p class="fs-5 text-danger"><?= number_format($allprice , 0, ',', '.') ?> VNĐ</p>
                </div>
                <a href="?page=checkout" class="confirm-checkout">Tiến hành đặt hàng</a>
            </div>
        <?php
            }
        ?>
    </div>
</div>
<?php
    } else {
?>
<div class="container" style="background-color: white;">
    <div class="empty-cart">
        <img class="empty-cart-img" src="https://assets.materialup.com/uploads/16e7d0ed-140b-4f86-9b7e-d9d1c04edb2b/preview.png" alt="Empty cart">
        <div class="empty-cart-text">Giỏ hàng của bạn đang trống</div>
        <a class="back-to-home-page" href="index.php">
            <div class="empty-cart-button">Tiếp tục mua hàng</div>
        </a>
    </div>
</div>
<?php
    }
?>