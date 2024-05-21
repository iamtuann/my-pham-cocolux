<?php
  
?>


<link rel="stylesheet" href="./assets/css/user/checkout.css">

<div class="container py-5 checkout">
  <form>
    <div class="row">
      <div class="col-12 col-md-8">
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
      </div>
      <div class="col-12 col-md-4">
        b
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