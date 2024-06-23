<?php
  $order_id = "";
  if (isset($_GET['order_id'])) {
    $order_id = $_GET['order_id'];
  } else {
    header('Location: ?page=home');
  }

  $sql="SELECT * FROM `order` WHERE id = '$order_id' LIMIT 1";
  $order = mysqli_query($connect, $sql);
  if (mysqli_num_rows($order) <= 0) {
    header('Location: ?page=home');
  }
?>
<style>
  .success-wrap {
    padding: 50px;
    margin-top: 40px;
  }
  .success-wrap img {
    max-width: 160px;
    height: 100%;
    margin: 30px 0;
  }
</style>
<div class="container">
  <div class="d-flex align-items-center justify-content-center flex-column success-wrap">
    <img src="assets/images/check.png" alt="">
    <h4 class="mt-4">Thanks you for your order.</h4>
    <p class="mt-2">Chúng tôi sẽ sớm xác nhận đơn hàng và chuyển đến bạn sớm nhất</p>
    <a href="?page=home" class="py-3 d-block text-center">Trở về trang chủ</a>
  </div>
</div>