<?php
    if (isset($_GET['plus'])) {
        $product_id = $_GET['plus'];
        $user_id = $_SESSION['user_id'];
        $sql_update = "UPDATE cart_item SET quantity = quantity + 1 WHERE user_id = $user_id AND product_id = $product_id";
        mysqli_query($connect, $sql_update);
        header("Location: /my-pham-cocolux/?page=gio-hang");
        exit();
    }

    if (isset($_GET['minus'])) {
        $product_id = $_GET['minus'];
        $user_id = $_SESSION['user_id'];
        $sql_check_quantity = "SELECT quantity FROM cart_item WHERE user_id = $user_id AND product_id = $product_id";
        $result = mysqli_query($connect, $sql_check_quantity);
        $row = mysqli_fetch_assoc($result);

        if ($row['quantity'] > 1) {
            $sql_update = "UPDATE cart_item SET quantity = quantity - 1 WHERE user_id = $user_id AND product_id = $product_id";
            mysqli_query($connect, $sql_update);
        } else {
            echo "<script>alert('Số lượng sản phẩm phải lớn hơn hoặc bằng 1.'); window.location.href='/my-pham-cocolux/?page=gio-hang';</script>";
        }

        header("Location: /my-pham-cocolux/?page=gio-hang");
        exit();
    }
?>