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


if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $product_id = $_GET['id'];

   
    $stmt = $connect->prepare("DELETE FROM cart_item WHERE product_id = '$product_id'");
    if ($stmt) {
        
        $stmt->bind_param("i", $product_id);

       
        if ($stmt->execute()) {
        
            header('Location: cart.php');
            exit;
        } else {
         
            echo "Lỗi thực thi: " . $stmt->error;
        }

    
        $stmt->close();
    } else {
       
        echo "Lỗi chuẩn bị câu lệnh: " . $connect->error;
    }
} else {
    echo "product_id không hợp lệ.";
}


$connect->close();
?>
