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

  if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id=$_POST['orderId'];
    $sql_sua="UPDATE `order` SET status='1' WHERE id='$id'";
    mysqli_query($connect,$sql_sua);
    header('Location: ?page=checkout-success&order_id='.$id);
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
      <img class="qr-img" src="assets/images/payment-qr-2.jpg" alt="">
    </div>
    <div class="col-12 col-md-6">
      <div class="pay-title">Ngân hàng:</div>
      <div class="pay-content">Ngân hàng VietinBank</div>
      <div class="pay-title">Chủ tài khoản:</div>
      <div class="pay-content">DAO XUAN TRUONG</div>
      <div class="pay-title">Số tài khoản:</div>
      <div class="pay-content">100873968184</div>
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

  <form method="POST" name="order_succcess" id="order_success">
    <input hidden type="text" id="order_id" name="orderId" value="<?php echo $order_id; ?>">
    <input hidden type="text" id="order_content" name="orderContent" value="<?php echo "Thanh toan don hang ". $order_id; ?>">
  </form>
</div>

<script>
document.addEventListener("DOMContentLoaded", () => {
  const formSuccess = document.getElementById("order_success");
  const orderId = document.getElementById("order_id").value;
  const orderContent = document.getElementById("order_content").value;
  let isSuccess = false;
  setInterval(() => {
    checkPaid(5000, orderContent);
  }, 2000);

  async function checkPaid(price = 5000, content) {
    if (isSuccess) {
      return;
    } else {
      try {
        const response = await fetch("https://script.google.com/macros/s/AKfycbygO0Yrl_bUIW46kME9rV0j83H5jojKD5DU8hWccEAKnENH7BPnhoQZqNZi6urw09lnzw/exec");
        const data = await response.json();
        const lastPaid = data.data[data.data.length - 1];
        const lastPrice = lastPaid["Giá trị"];
        const lastContent = lastPaid["Mô tả"];
        if (lastPrice == price && lastContent.includes(content)) {
          console.log("Thành công");
          isSuccess = true;
          formSuccess.submit();
        } else {
          console.log("Không thành công");
        }
      } catch (error) {
        console.log(error);
      }
    }
  }
})
  
</script>