<?php
  $sql="SELECT shipping_status, COUNT(*) AS count FROM `order` GROUP BY shipping_status ORDER BY shipping_status ASC";
  $result = mysqli_query($connect, $sql);
  $order_count = [];
  if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
      $order_count[] = $row;
    }
  }
?>
<style>
  .admin-order-status {
    padding: 16px;
    border-radius: 12px;
    display: block;
    text-decoration: none;
    color: #fff;
    height: 100%;
  }
  .order-waiting {
    background-color: rgb(255,193,7);
  }
  .order-delivering {
    background-color: rgb(13,110,253);
  }
  .order-success {
    background-color: rgb(25,135,84);
  }
  .order-reject {
    background-color: #f44646;
  }
  .order-status-title {
    font-size: 24px;
    font-weight: 600;
  }
</style>
<div class="container">
  <div class="row mt-4 gy-3">
    <div class="col-12 col-md-6">
      <a href="?page=order-waiting" class="admin-order-status order-waiting">
        <div class="order-status-title py-2">
          Chờ phê duyệt
        </div>
        <div class="order-number">
          <?php echo isset($order_count[1]) ? $order_count[1]['count'] : '0' ?> đơn hàng
        </div>
      </a>
    </div>
    <div class="col-12 col-md-6">
      <a href="?page=order-delivering" class="admin-order-status order-delivering">
        <div class="order-status-title py-2">
          Đang giao hàng
        </div>
        <div class="order-number">
          <?php echo isset($order_count[2]) ? $order_count[2]['count'] : '0' ?> đơn hàng
        </div>
      </a>
    </div>
    <div class="col-12 col-md-6">
      <a href="?page=order-success" class="admin-order-status order-success">
        <div class="order-status-title py-2">
          Đã giao hàng
        </div>
        <div class="order-number">
          <?php echo isset($order_count[3]) ? $order_count[3]['count'] : '0' ?> đơn hàng
        </div>
      </a>
    </div>
    <div class="col-12 col-md-6">
      <a href="?page=order-reject" class="admin-order-status order-reject">
        <div class="order-status-title py-2">
          Đã hủy
        </div>
        <div class="order-number">
          <?php echo isset($order_count[0]) ? $order_count[0]['count'] : '0' ?> đơn hàng
        </div>
      </a>
    </div>
  </div>
</div>