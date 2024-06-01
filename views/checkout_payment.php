<?php
  $order_id = "";
  if (isset($_GET['order_id'])) {
    $order_id = $_GET['order_id'];
  } else {
    header('Location: ?page=home');
  }

  $sql="SELECT * FROM `order` WHERE id = '$order_id' LIMIT 1";
  $result = mysqli_query($connect, $sql);
  $total_price = 0;
  if (mysqli_num_rows($result) > 0) {
    $order = mysqli_fetch_assoc($result);
    $total_price = $order['total_price'];
  } else {
    header('Location: ?page=home');
  }
?>
<style>
  .qr-img {
    width: 100%;
    height: auto;
    max-width: 400px;
    text-align: center;
    display: block;
    margin: 0 auto;
  }
  .pay-content {
    font-size: 20px;
    color: #000;
    font-weight: 500;
    margin-bottom: 12px;
  }
  .pay-title {
    color: #555;
    line-height: 20px;
  }
</style>

<div class="container py-4 bg-white">
  <h4 class="text-center py-3 mb-3">Vui lòng chuyển khoản đúng với số tiền và nội dung</h4>
  <div class="row">
    <div class="col-12 col-md-6">
      <img class="qr-img" src="assets/images/payment-qr.jpg" alt="">
    </div>
    <div class="col-12 col-md-6">
      <div class="pay-title">Ngân hàng:</div>
      <div class="pay-content">Ngân hàng Kỹ Thương Techcombank</div>
      <div class="pay-title">Chủ tài khoản:</div>
      <div class="pay-content">NGUYEN DUNG TUAN</div>
      <div class="pay-title">Số tài khoản:</div>
      <div class="pay-content">6868060703</div>
      <div class="pay-title">Số tiền: </div>
      <div class="pay-content">
        <?= number_format($total_price, 0, ',', '.') . ' VNĐ'  ?>
      </div>
      <div class="pay-title">Nội dung: </div>
      <div class="pay-content">
        <?php echo "Thanh toan don hang ". $order_id; ?>
      </div>
    </div>
  </div>
  <a href="?page=home" class="py-3 d-block text-center">Trở về trang chủ</a>
</div>