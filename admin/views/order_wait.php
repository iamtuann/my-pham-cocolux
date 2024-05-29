<?php
  $sql="SELECT o.id as id, total_price, full_name, phone, address, o.status as status, payment, note, o.create_date as order_date,
    oi.id as oi_id, oi.quantity as quantity, p.id as product_id, p.name as product_name
    FROM `order` o
    INNER JOIN `order_item` oi ON o.id = oi.order_id
    INNER JOIN `product` p ON oi.product_id = p.id
    where shipping_status = 0";
  $result = mysqli_query($connect, $sql);
  
  $orders = [];
  if ($result) {
    while ($row = $result->fetch_assoc()) {
        $orderId = $row['id'];
        if (!isset($orders[$orderId])) {
            $orders[$orderId] = [
                'id' => $row['id'],
                'total_price' => $row['total_price'],
                'phone' => $row['phone'],
                'full_name' => $row['full_name'],
                'address' => $row['address'],
                'status' => $row['status'],
                'order_date' => $row['order_date'],
                'payment' => $row['payment'],
                'note' => $row['note'],
                'order_items' => []
            ];
        }
        $orders[$orderId]['order_items'][] = [
            'order_item_id' => $row['oi_id'],
            'product_id' => $row['product_id'],
            'product_name' => $row['product_name'],
            'quantity' => $row['quantity']
        ];
    }
  }

  if (isset($_GET['accept_id'])) {
    $acceptId=$_GET['accept_id'];
    $sql = "UPDATE `order` SET `shipping_status`='1' WHERE id='$acceptId'";
    $result = mysqli_query($connect, $sql);
    if($result) {
      header('Location: ?page=order-waiting');
    } else {
      header('Location: ?page=dashboard');
    }
  } elseif (isset($_GET['reject_id'])) {
    $rejectId=$_GET['reject_id'];
    $sql = "UPDATE `order` SET `shipping_status`='-1' WHERE id='$rejectId'";
    $result = mysqli_query($connect, $sql);
    if($result) {
      header('Location: ?page=order-waiting');
    } else {
      header('Location: ?page=dashboard');
    }
  }
?>
<style>
  .ad-order-wrap {
    padding: 12px 16px;
    border: 1px solid #333;
    margin-bottom: 16px;
    border-radius: 12px;
  }
</style>
<div class="container pt-4">
  <h4 class="my-2 text-center">
    Đơn hàng chờ phê duyệt
  </h4>
  <div class="">
    <?php
      foreach ($orders as $order) {
    ?>
      <div class="ad-order-wrap">
        <div>
          <span>Ngày đặt: </span>
          <span><?php echo $order['order_date'] ?></span> <br>
          <span>Họ tên: </span>
          <span class="me-4"><?php echo $order['full_name'] ?></span>
          <span>SĐT: </span>
          <span><?php echo $order['phone'] ?></span><br>
          <span>Địa chỉ: </span>
          <span><?php echo $order['address'] . ', ' . $order['note'] ?></span><br>
        </div>
        <hr class="my-2">
        <div class="">
          <?php foreach ($order['order_items'] as $item) { ?>
            <div class="d-flex justify-content-between">
              <span><?php echo $item['product_name'] ?></span>
              <span>Số lượng: <?php echo $item['quantity'] ?></span>
            </div>
          <?php } ?>
        </div>
        <hr class="my-2">
        <div class="d-flex align-item-center justify-content-between">
          <div class="">
            Tổng tiền: 
            <?php echo $order['total_price'] ?>
          </div>
          <div class="d-flex column-gap-3">
            <a href="<?php echo '?page=order-waiting&reject_id='.$order['id'] ?>">
              <button class="btn btn-danger">Hủy</button>
            </a>
            <a href="<?php echo '?page=order-waiting&accept_id='.$order['id'] ?>">
              <button class="btn btn-success">Xác nhận</button>
            </a>
          </div>
        </div>
      </div>
    <?php
      }
    ?>
  </div>
</div>