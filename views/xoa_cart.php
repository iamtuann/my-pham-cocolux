<?php
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $product_id = $_GET['id'];

    $stmt = $connect->prepare("DELETE FROM cart_item WHERE product_id = ?");
    $stmt->bind_param("i", $product_id);

    if ($stmt->execute()) {
        echo "<script>alert('Xóa sản phẩm khỏi giỏ hàng thành công.'); window.location.href='?page=gio-hang';</script>";
        exit;
    } else {
        echo "<script>alert('Đã xảy ra lỗi khi xóa sản phẩm khỏi giỏ hàng.'); window.location.href='?page=gio-hang';</script>";
        exit;
    }
} else {
    echo "<script>alert('ID sản phẩm không hợp lệ.'); window.location.href='?page=gio-hang';</script>";
    exit;
}
?>