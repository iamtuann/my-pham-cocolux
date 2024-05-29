<?php
  $shippingStatus = "";
  if (isset($_GET['status'])) {
    switch ($_GET['status']) {
      case 'waiting':
        $shippingStatus=0;
        break;
      case 'delivering':
        $shippingStatus=1;
        break;
      case 'success':
        $shippingStatus=2;
        break;
      case 'reject':
        $shippingStatus=-1;
        break;
      default:
        header('Location:?page=dashboard');
        break;
    }
  }
  $sql="SELECT o.id as id, total_price, full_name, phone, address, o.status as status, payment, note, o.create_date as order_date,
    oi.id as oi_id, oi.quantity as quantity, p.id as product_id, p.name as product_name
    FROM `order` o
    INNER JOIN `order_item` oi ON o.id = oi.order_id
    INNER JOIN `product` p ON oi.product_id = p.id
    where shipping_status = '$shippingStatus'";
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

  if (isset($_GET['accept_id']) && $shippingStatus==0) {
    $acceptId=$_GET['accept_id'];
    $sql = "UPDATE `order` SET `shipping_status`='1' WHERE id='$acceptId'";
    $result = mysqli_query($connect, $sql);
    if($result) {
      header('Location: ?page=orders&status=waiting');
    } else {
      header('Location: ?page=dashboard');
    }
  } elseif (isset($_GET['reject_id']) && $shippingStatus==0) {
    $rejectId=$_GET['reject_id'];
    $sql = "UPDATE `order` SET `shipping_status`='-1' WHERE id='$rejectId'";
    $result = mysqli_query($connect, $sql);
    if($result) {
      header('Location: ?page=orders&status=waiting');
    } else {
      header('Location: ?page=dashboard');
    }
  }
?>

<div class="container pt-4">
  <h4 class="my-2 text-center">
    <?php
      switch ($shippingStatus) {
        case '0':
          echo "Đơn hàng chờ phê duyệt";
          break;
        case '1':
          echo "Đơn hàng đang vận chuyển";
          break;
        case '2':
          echo "Đơn hàng đã giao thành công";
          break;
        case '-1':
          echo "Đơn hàng đã hủy bỏ";
          break;
        default:
          break;
      }
    ?>
  </h4>
  <div class="">
    <?php
      if (count($orders) <= 0) {
        echo "<p class='text-center'>Không có đơn hàng nào</p>";
      } else {
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
            <div>Tổng tiền: <?php echo $order['total_price'] ?></div>
            <?php
              if ($order['payment']==0) {
                echo "<div>Thanh toán khi nhận hàng</div>";
              } elseif ($order['payment']==1) {
                echo "<div>Thanh toán chuyển khoản</div>";
              }
            ?>
          </div>
          <?php
            if ($shippingStatus==0) {
          ?>
            <div class="d-flex column-gap-3">
              <a href="<?php echo '?page=orders&status=waiting&reject_id='.$order['id'] ?>">
                <button class="btn btn-danger">Hủy</button>
              </a>
              <a href="<?php echo '?page=orders&status=waiting&accept_id='.$order['id'] ?>">
                <button class="btn btn-success">Xác nhận</button>
              </a>
            </div>
          <?php
            }
          ?>
        </div>
      </div>
    <?php
        }
      }
    ?>
  </div>
</div>