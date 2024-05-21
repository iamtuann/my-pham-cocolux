<?php
  session_start();

  if (!isset($_SESSION['list_image'])) {
    $_SESSION['list_image'] = [];
  }
  //get brands
  $queryBrand = "Select id, name from brand";
  $brands = mysqli_query($connect, $queryBrand);
  $queryCategory = "Select id, name from category";
  $categories = mysqli_query($connect, $queryCategory);

  $name = "";
  $price_original = "";
  $price_final = "";
  $quantity = "";
  $brand_id = "";
  $country = "";
  $category_id="";
  $description = "";
  $ingredient = "";
  $uses = "";
  $instruction = "";
  $review = "";

  if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['addBtn'])) {
    $name=$_POST['name'];
    $price_original=$_POST['price_original'];
    $price_final=$_POST['price_final'];
    $quantity=$_POST['quantity'];
    $brand_id=$_POST['brand_id'];
    $category_id=$_POST['category_id'];
    $country=$_POST['country'];
    $description=$_POST['description'];
    $ingredient=$_POST['ingredient'];
    $uses=$_POST['uses'];
    $instruction=$_POST['instruction'];
    $review=$_POST['review'];
    $imageUrls = json_decode($_POST['image_urls'], true);


    // $sql_sua="UPDATE brand SET name='".$name."',country='".$country."',description='".$description."',image='".$image."' WHERE id='$_POST[id]'";
    $stmt = $connect->prepare("INSERT INTO product (name, price_original, price_final, quantity, brand_id, country, description, ingredient, uses, instruction, review, status, create_date, update_date) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 1, NOW(), NOW())");;
    echo $connect->error;
    $stmt->bind_param("sddiissssss", $name, $price_original, $price_final, $quantity, $brand_id, $country, $description, $ingredient, $uses, $instruction, $review);
    $stmt->execute();
    $productId = $stmt->insert_id;
    $stmt->close();

    $stmt = $connect->prepare("INSERT INTO product_category (product_id, category_id) VALUES (?, ?)");
    $stmt->bind_param("ii", $productId, $category_id);
    $stmt->execute();
    $stmt->close();

    $stmt = $connect->prepare("INSERT INTO product_image (product_id, path_url) VALUES (?, ?)");
    foreach ($imageUrls as $imageUrl) {
      $stmt->bind_param("is", $productId, $imageUrl);
      $stmt->execute();
    }
    $stmt->close();
    header('Location: ?page=product');
  }
?>
<style>
  .list-image {
    /* display: flex; */
    width: 100%;
    overflow-y: auto;
    white-space: nowrap;
  }
  .preview-img-wrap {
    display: inline-block;
    padding: 8px;
    height: 250px;
    width: 250px;
    margin: 0 auto;
    border: 1px solid #333;
    border-radius: 8px;
  }
  .preview-img-wrap img {
    width: 100%;
    height: 100%;
    object-fit: cover;
  }
</style>
<div class="container pt-4">
  <form id="product_form" action="" method="POST">
    <h4 class="mb-3 text-center">Thêm sản phẩm</h4>
    <div class="row">
      <div class="col-12 col-md-6">
        <label for="" class="mb-2">Tên sản phẩm:</label><br>
        <input class="input-field" type="text" name="name" value="<?php echo $name; ?>">
      </div>
      <div class="col-12 col-md-6">
        <label for="" class="mb-2">Thương hiệu:</label><br>
        <select class="select-field" name="brand_id">
        <?php
            while ($row=mysqli_fetch_array($brands)){
        ?>
          <option value="<?php echo $row['id']; ?>"><?php echo $row['name']; ?></option>
        <?php
          }
        ?>
        </select>
      </div>
    </div>
    <div class="row">
      <div class="col-12 col-md-6">
        <label for="" class="mb-2">Giá ban đầu:</label><br>
        <input class="input-field" type="price" name="price_original" value="<?php echo $price_original; ?>">
      </div>
      <div class="col-12 col-md-6">
        <label for="" class="mb-2">Giá bán:</label><br>
        <input class="input-field" type="number" name="price_final" value="<?php echo $price_final; ?>">
      </div>
    </div>
    <div class="row">
      <div class="col-12 col-md-4">
        <label for="" class="mb-2">Loại sản phẩm:</label><br>
        <select class="select-field" name="category_id">
        <?php
            while ($row=mysqli_fetch_array($categories)){
        ?>
          <option value="<?php echo $row['id']; ?>"><?php echo $row['name']; ?></option>
        <?php
          }
        ?>
        </select>
      </div>
      <div class="col-12 col-md-4">
        <label for="" class="mb-2">Quốc gia:</label><br>
        <input class="input-field" type="text" name="country" value="<?php echo $country; ?>">
      </div>
      <div class="col-12 col-md-4">
        <label for="" class="mb-2">Số lượng:</label><br>
        <input class="input-field" type="text" name="quantity" value="<?php echo $quantity; ?>">
      </div>
    </div>
    <label for="" class="mb-2">Mô tả:</label><br>
    <input class="input-field" type="text" name="description" value="<?php echo $description; ?>">

    <label for="" class="mb-2">Thành phần:</label><br>
    <input class="input-field" type="text" name="ingredient" value="<?php echo $ingredient; ?>">

    <label for="" class="mb-2">Công dụng:</label><br>
    <input class="input-field" type="text" name="uses" value="<?php echo $uses; ?>">

    <label for="" class="mb-2">Cách dùng:</label><br>
    <input class="input-field" type="text" name="instruction" value="<?php echo $instruction; ?>">

    <label for="" class="mb-2">Review:</label><br>
    <input class="input-field" type="text" name="review" value="<?php echo $review; ?>">

    <label for="" class="mb-2">Hình ảnh:</label><br>
      <div class="d-flex align-items-center">
        <input oninput="updatePathImage(this.value)" class="input-field flex-grow-1 mb-0" type="text" id="image_url">
        <button type="button" class="btn btn-primary h-100 ms-3" onclick="addImage()">
          Thêm
        </button>
      </div>

    <div class="my-2 text-center">Xem trước ảnh</div>
    <div class="list-image">
    </div>
    <input type="hidden" name="image_urls" id="image_urls">
    <div class="actions d-flex justify-content-center my-3">
      <button class="add-new-btn" name="addBtn" type="submit">
        Tạo mới
      </button>
    </div>
  </form>
</div>

<script>
  function updatePathImage(value) {
    // document.getElementById('preview-image').src = value;
  }
  let imageUrls = [];
  const hiddenInput = document.getElementById('image_urls');
  function addImage() {
    const imageUrlInput = document.getElementById('image_url');
    const imageUrl = imageUrlInput.value.trim();

    if (imageUrl) {
        imageUrls.push(imageUrl);
        hiddenInput.value = JSON.stringify(imageUrls);
        displayImages();
        imageUrlInput.value = '';
    }
  }
  function displayImages() {
    const imageList = document.querySelector('.list-image');
    imageList.innerHTML = '';

    imageUrls.forEach((url, index) => {
      let img = document.createElement('img');
      img.src = url;
      let wrap = document.createElement('div');
      wrap.classList.add('preview-img-wrap', 'mx-3');
      wrap.appendChild(img);
      imageList.appendChild(wrap);
    });
  }
</script>