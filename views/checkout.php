<?php
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
  WHERE u.id = 1";
  $products = mysqli_query($connect, $sql);
?>


<link rel="stylesheet" href="./assets/css/user/checkout.css">

<div class="container py-5 checkout">
  <form>
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
          </div>
          <div class="mb-3">
            <label class="mb-1">Quận Huyện <span class="text-danger">*</span></label>
            <select name="districts" class="form-control" id="districts" require onchange="getWards(this.value)">
              <option value="0" selected hidden disable>Chọn Quận/ Huyện</option>
            </select>
          </div>
          <div class="mb-3">
            <label class="mb-1">Phường Xã <span class="text-danger">*</span></label>
            <select name="wards" class="form-control" id="wards" require>
              <option value="0" selected hidden disable>Chọn Phường/ Xã</option>
            </select>
          </div>
          <div class="mb-3">
            <label class="mb-1">Địa chỉ chi tiết <span class="text-danger">*</span></label>
            <input type="text" name="address" class="form-control" require>
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
              $sum=0;
              if ($result = mysqli_num_rows($products) > 0){
                while ($row=mysqli_fetch_array($products)){
            ?>
            <a href="<?php echo '?page=san-pham&id=' . $row['product_id'] ?>" class="checkout-item-product">
              <img src="<?php echo $row['image'] ?>" alt="<?php echo $row['name'] ?>">
              <div class="checkout-item-info">
                <p class="checkout-item-title mb-0"><?php echo $row['name'] ?></p>
                <div class="checkout-item-quantity mb-0">
                  SL: <span class="fw-bold"><?php echo $row['quantity'] ?></span>
                </div>
              </div>
              <div class="fw-bold">
                <div class="checkout-price-final"><?= number_format($row['price_final'], 0, ',', '.') . 'đ' ?></div>
                <div class="checkout-price-original"><?= number_format($row['price_original'], 0, ',', '.') . 'đ' ?></div>
              </div>
            </a>
            <?php
                  $sum+= ($row['price_final'] * $row['quantity']);
                }
              }
            ?>
          </div>
          <div class="d-flex align-items-center justify-content-between mb-3">
            <span class="text-uppercase">Tổng cộng</span>
            <div class="fw-bold text-danger fs-5">
              <?php echo number_format($sum, 0, ',', '.') . 'đ' ?>
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
  fetch(urlCities)
    .then(response => {
        if (!response.ok) {
            throw new Error('Network response was not ok ' + response.statusText);
        }
        return response.json();
    })
    .then(data => {
      console.log(data);
        // Lấy phần tử select của quận/huyện
        const citySelect = document.getElementById('cities');
        
        // Thêm các option mới từ API
        data?.data.forEach(city => {
            const option = document.createElement('option');
            option.value = city.id;
            option.textContent = city.name;
            citySelect.appendChild(option);
        });
    })
    .catch(error => {
        console.error('There has been a problem with your fetch operation:', error);
    });

    function getDistricts(cityId) {
      if (cityId) {
        const urlDistricts = `https://esgoo.net/api-tinhthanh/2/${cityId}.htm`;
        fetch(urlDistricts)
          .then(response => {
              if (!response.ok) {
                  throw new Error('Network response was not ok ' + response.statusText);
              }
              return response.json();
          })
          .then(data => {
              const districtSelect = document.getElementById('districts');
              const wardSelect = document.getElementById('wards');
              districtSelect.innerHTML = '<option value="0" selected hidden disable>Chọn Quận/ Huyện</option>';
              wardSelect.innerHTML = '<option value="0" selected hidden disable>Chọn Phường/ Xã</option>';
              data?.data.forEach(dis => {
                  const option = document.createElement('option');
                  option.value = dis.id;
                  option.textContent = dis.name;
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
                  wardSelect.appendChild(option);
              });
          })
          .catch(error => {
              console.error('There has been a problem with your fetch operation:', error);
          });
      }
    }
</script>