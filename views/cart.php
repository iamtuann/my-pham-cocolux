<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gio hang</title>
    <style>
    .content{
    display: flex;
    justify-content: center;
    font-family: "BeVietnamPro", sans-serif;
    font-style: normal;
    font-weight: 400;
    position: relative;
    -webkit-font-smoothing: antialiased;
    font-size: 1rem;
    color: #000;
    overflow-x: hidden;
    background: #f3f3f3;
    }    
        img.cart-product-img {
    height: 100px;
      }
      
    .cart-table {
    padding :20px;
    text-align: center;
    width: 1140px;
    border-radius: 5px;
    align:center;
    box-shadow: 0 3px 6px rgba(0, 0, 0, 0.15);
    font-size: 14px;
    }
    .cart-table td{
        padding:15px;
    }
    
    .tong-tien-hang{
        text-align: right;
        padding-right:10px;
    }
    input.button-dat-hang {
    width: 200px;
    height: 40px;
    display: block;
    background: #c73030;
    color: #fff;
    padding: 10px 15px;
    border-radius: 5px;
    font-size:16px;
}
.button-dat-hang:hover{
    background:red;
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
.fw-bold{
    font-weight: 700 ;
}
.cart-product-name{
    font-weight: 700 ;
}
.tong-tien-hang{
    font-weight: 700 ;
}

    </style>
</head>
<body>

<div class="content">
<table class = "cart-table">
    <tr><td class ="fw-bold">Giỏ Hàng</td></tr>
    <tr class="cart-header">
       <td></td>
        <td class ="fw-bold">Sản phẩm</td>
        <td class ="fw-bold">Giá sản phẩm</td>
        <td class ="fw-bold">Số lượng</td>
        <td class ="fw-bold">Thành tiền</td>
        <td class ="fw-bold">Thao tác</td>
    </tr>
    <?php
    $severname="localhost";
    $username="root";
    $password="";
    $database="my_pham_cocolux";

    $port="3307";
    $connect= new mysqli($severname,$username,$password,$database, $port);
    if ($connect->connect_error) {
      die("Kết nối thất bại: " . $connect->connect_error);
    }
    $sql_cart = "SELECT * FROM product INNER JOIN cart_item ON product.id = cart_item.product_id INNER JOIN product_image ON product.id = product_image.product_id INNER JOIN user ON user.id=cart_item.user_id WHERE cart_item.user_id=1 ";
    $query_cart = mysqli_query($connect, $sql_cart);
    $allprice =0;
    $num = mysqli_num_rows($query_cart);
    ?>
    <?php
			while ($row = mysqli_fetch_array($query_cart)) {
                $allprice += $row['quantity']*$row['price_final'];
			?>
				<tr class="cart-product">
					<td><img class="cart-product-img" src=<?= $row['path_url'] ?>></td>
                    <td><div class="cart-product-name"><?= $row['name'] ?></div></td>
					<td><div class="cart-price_final"><?= number_format($row['price_final'], 0, ',', '.') . ' Đ'  ?></div></td>
                    <td><input type="number" name="new_quantity" value="<?= $row['quantity'] ?>" min="1" required></td>
                    <td><div class="cart-price"><?= number_format($row['quantity']*$row['price_final'], 0, ',', '.') . ' Đ'   ?></div></td>
                    <td><a href='xoa_cart.php?id=<?php echo $row['product_id']; ?>' class="button-xoa">Xóa</a> </td>
                </tr>
			<?php
			}
			?>
     <tr> <td colspan="5" class="tong-tien-hang"> <div class ="allprice">Tổng tiền hàng(<?= $num ?> Sản phẩm ) : <?= $allprice ?> Đ</div></td>
     <td><input class="button-dat-hang" type="submit" value="Tiến hành đặt hàng"></td>
    </tr>
</table>
</div>
</body>
</html>