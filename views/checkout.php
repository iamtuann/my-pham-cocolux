<?php
  $user_id = $_SESSION['user_id'];
  $sql = "SELECT p.id AS product_id, p.name, p.price_original, p.price_final, pi.path_url as image, ci.quantity
  FROM cart_item ci
  JOIN user u ON ci.user_id = u.id
  JOIN product p ON ci.product_id = p.id
  LEFT JOIN (
    SELECT product_id, MIN(id) as min_id
    FROM product_image
    GROUP BY product_id
  ) pim ON p.id = pim.product_id
  LEFT JOIN product_image pi ON pim.min_id = pi.id
  WHERE u.id = ".$user_id;
  $products = mysqli_query($connect, $sql);
  $product_array = [];
  $total_price_original=0;
  $total_price_final=0;

  if (mysqli_num_rows($products) <= 0) {
    header('Location: ?page=gio-hang');
  } else {
    while ($row = mysqli_fetch_assoc($products)) {
      $total_price_final+= ($row['price_final'] * $row['quantity']);
      $total_price_original+= ($row['price_original'] * $row['quantity']);
      $product_array[] = $row;
    }
  }

  if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['order-btn'])) {
    $full_name=$_POST['name'];
    $phone=$_POST['phone'];
    $address=$_POST['city_txt'] . ', ' . $_POST['district_txt'] . ', ' . $_POST['ward_txt'] . ', ' . $_POST['detail_address'];
    $note=$_POST['note'];
    $payment=$_POST['payment'];
    
    $stmt = $connect->prepare("INSERT INTO `order` (user_id, full_name, phone, total_price_original, total_price_final, address, total_price, payment, note) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
    echo $connect->error;
    $stmt->bind_param("issddsdis", $user_id, $full_name, $phone, $total_price_original, $total_price_final, $address, $total_price_final, $payment, $note);
    $stmt->execute();
    $orderId = $stmt->insert_id;
    $stmt->close();

    $stmtOrderItem = $connect->prepare("INSERT INTO `order_item` (product_id, price_original, price_final, quantity, order_id) VALUES (?, ?, ?, ?, ?)");
    if ($stmtOrderItem === false) {
      die("Chuẩn bị câu lệnh order_item thất bại: " . $connect->error);
    }

    $stmtCart = $connect->prepare("DELETE FROM `cart_item` WHERE `product_id` = ? AND `user_id` = ?");
    if ($stmtCart === false) {
      die("Chuẩn bị câu lệnh order_item thất bại: " . $connect->error);
    }

    foreach ($product_array as $product) :
      $productId = $product['product_id'];
      $stmtOrderItem->bind_param("iddii", $productId, $product['price_original'], $product['price_final'], $product['quantity'], $orderId);
      $stmtOrderItem->execute();

      $stmtCart->bind_param("ii", $productId, $user_id);
      $stmtCart->execute();
    endforeach;
    $stmtOrderItem->close();
    $stmtCart->close();
    if ($payment == 0) {
      header('Location: ?page=checkout-success&order_id='.$orderId);
    } elseif ($payment==1) {
      header('Location: ?page=home');
    }
  }
?>


<link rel="stylesheet" href="./assets/css/user/checkout.css">

<div class="container py-5 checkout">
  <form method="POST" action="">
    <div class="row">
      <div class="col-12 col-md-7">
        <div class="layout-title bg-white fw-bold">
          Thông tin nhận hàng
        </div>
        <div class="form-detail mb-4 p-3 bg-white">
          <div class="mb-3">
            <label class="mb-1">Họ và tên <span class="text-danger">*</span></label>
            <input type="text" name="name" class="form-control" require>
          </div>
          <div class="mb-3">
            <label class="mb-1">Số điện thoại khi nhận hàng <span class="text-danger">*</span></label>
            <input type="text" name="phone" class="form-control" require>
          </div>
          <div class="mb-3">
            <label class="mb-1">Tỉnh Thành <span class="text-danger">*</span></label>
            <select name="city" class="form-control" id="cities" require onchange="getDistricts(this.value)">
              <option value="0" selected hidden disable>Chọn Tỉnh/ Thành phố</option>
            </select>
            <input type="text" name="city_txt" id="city_txt" hidden>
          </div>
          <div class="mb-3">
            <label class="mb-1">Quận Huyện <span class="text-danger">*</span></label>
            <select name="district" class="form-control" id="districts" require onchange="getWards(this.value)">
              <option value="0" selected hidden disable>Chọn Quận/ Huyện</option>
            </select>
            <input type="text" name="district_txt" id="district_txt" hidden>
          </div>
          <div class="mb-3">
            <label class="mb-1">Phường Xã <span class="text-danger">*</span></label>
            <select name="ward" class="form-control" id="wards" require onchange="changeWard()">
              <option value="0" selected hidden disable>Chọn Phường/ Xã</option>
            </select>
            <input type="text" name="ward_txt" id="ward_txt" hidden>
          </div>
          <div class="mb-3">
            <label class="mb-1">Địa chỉ chi tiết <span class="text-danger">*</span></label>
            <input type="text" name="detail_address" class="form-control" require>
          </div>
          <div class="mb-3">
            <label class="mb-1">Ghi chú</label>
            <textarea type="text" name="note" rows="4" placeholder="Nhập ghi chú nếu có" class="form-control"></textarea>
          </div>
        </div>

        <div class="layout-title bg-white">
          <div class="fw-bold">Vận chuyển & Thanh toán</div>
        </div>
        <div class="p-3 bg-white">
          <p class="fw-bold mb-2">
            Hình thức thanh toán
          </p>
          <div class="checkout-form-check mb-5">
            <input type="radio" name="payment" class="form-check-input" value="0" id="payment0" checked>
            <label for="payment0" class="form-check-label">Thanh toán khi nhận hàng (COD)</label>
          </div>
          <div class="checkout-form-check mb-5">
            <input type="radio" name="payment" class="form-check-input" value="1" id="payment1">
            <label for="payment1" class="form-check-label">Chuyển khoản</label>
          </div>
        </div>
      </div>
      <div class="col-12 col-md-5">
        <div class="layout-title bg-white fw-bold">
          Danh sách sản phẩm
        </div>
        <div class="form-detail mb-4 p-3 bg-white">
          <div class="list-product-checkout mb-5">
            <?php
              if ($result = mysqli_num_rows($products) > 0){
                foreach ($product_array as $product) :
            ?>
            <a href="<?php echo '?page=san-pham&id=' . $product['product_id'] ?>" class="checkout-item-product">
              <img src="<?php echo $product['image'] ?>" alt="<?php echo $product['name'] ?>">
              <div class="checkout-item-info">
                <p class="checkout-item-title mb-0"><?php echo $product['name'] ?></p>
                <div class="checkout-item-quantity mb-0">
                  SL: <span class="fw-bold"><?php echo $product['quantity'] ?></span>
                </div>
              </div>
              <div class="fw-bold">
                <div class="checkout-price-final"><?= number_format($product['price_final'], 0, ',', '.') . 'đ' ?></div>
                <div class="checkout-price-original"><?= number_format($product['price_original'], 0, ',', '.') . 'đ' ?></div>
              </div>
            </a>
            <?php
              endforeach;
              }
            ?>
          </div>
          <div class="d-flex align-items-center justify-content-between mb-3">
            <span class="text-uppercase">Tổng cộng</span>
            <div class="fw-bold text-danger fs-5">
              <?php echo number_format($total_price_final, 0, ',', '.') . 'đ' ?>
            </div>
          </div>
          <button type="submit" name="order-btn" class="btn submit-checkout d-flex align-items-center justify-content-center text-white text-uppercase">
            Đặt hàng
          </button>
        </div>
      </div>
    </div>
  </form>
</div>

<script>
  const urlCities = "https://esgoo.net/api-tinhthanh/1/0.htm";
  const cityInput = document.getElementById("city_txt");
  const districtInput = document.getElementById("district_txt");
  const wardInput = document.getElementById("ward_txt");
  const citySelect = document.getElementById('cities');
  const districtSelect = document.getElementById('districts');
  const wardSelect = document.getElementById('wards');
  fetch(urlCities)
    .then(response => {
        if (!response.ok) {
            throw new Error('Network response was not ok ' + response.statusText);
        }
        return response.json();
    })
    .then(data => {
        data?.data.forEach(city => {
            const option = document.createElement('option');
            option.value = city.id;
            option.textContent = city.name;
            option.setAttribute('data-city-name', city.name);
            citySelect.appendChild(option);
        });
    })
    .catch(error => {
        console.error('There has been a problem with your fetch operation:', error);
    });

    function getDistricts(cityId) {
      if (cityId) {
        const urlDistricts = `https://esgoo.net/api-tinhthanh/2/${cityId}.htm`;
        cityInput.value = citySelect.options[citySelect.selectedIndex].getAttribute('data-city-name');
        fetch(urlDistricts)
          .then(response => {
              if (!response.ok) {
                  throw new Error('Network response was not ok ' + response.statusText);
              }
              return response.json();
          })
          .then(data => {
            console.log(data);
              districtSelect.innerHTML = '<option value="0" selected hidden disable>Chọn Quận/ Huyện</option>';
              wardSelect.innerHTML = '<option value="0" selected hidden disable>Chọn Phường/ Xã</option>';
              data?.data.forEach(dis => {
                  const option = document.createElement('option');
                  option.value = dis.id;
                  option.textContent = dis.name;
                  option.setAttribute('data-district-name', dis.name);
                  districtSelect.appendChild(option);
              });
          })
          .catch(error => {
              console.error('There has been a problem with your fetch operation:', error);
          });
      }
    }
    function getWards(districtId) {
      if (districtId) {
        const urlWards = `https://esgoo.net/api-tinhthanh/3/${districtId}.htm`;
        districtInput.value = districtSelect.options[districtSelect.selectedIndex].getAttribute('data-district-name');
        fetch(urlWards)
          .then(response => {
              if (!response.ok) {
                  throw new Error('Network response was not ok ' + response.statusText);
              }
              return response.json();
          })
          .then(data => {
              const wardSelect = document.getElementById('wards');
              wardSelect.innerHTML = '<option value="0" selected hidden disable>Chọn Phường/ Xã</option>';
              data?.data.forEach(ward => {
                  const option = document.createElement('option');
                  option.value = ward.id;
                  option.textContent = ward.name;
                  option.setAttribute('data-ward-name', ward.name);
                  wardSelect.appendChild(option);
              });
          })
          .catch(error => {
              console.error('There has been a problem with your fetch operation:', error);
          });
      }
    }
    function changeWard() {
      wardInput.value = wardSelect.options[wardSelect.selectedIndex].getAttribute('data-ward-name');
    }
</script>