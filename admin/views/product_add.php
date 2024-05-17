<?php
  session_start();

  if (!isset($_SESSION['list_image'])) {
    $_SESSION['list_image'] = [];
  }
  //get brands
  $queryBrand = "Select id, name from brand";
  $brands = mysqli_query($connect, $queryBrand);

  $name = "";
  $price_original = "";
  $price_final = "";
  $quantity = "";
  $brand_id = "";
  $country = "";
  $description = "";
  $ingredient = "";
  $uses = "";
  $instruction = "";
  $review = "";

  //add image
  if(isset($_POST['addImage'])) {
    $image_url= isset($_POST['image_url']) ? $_POST['image_url'] : '';
    if (!empty($image_url)) {
      $_SESSION['list_image'][] = $image_url;
    }
    header('Location: ?page=add-product');
  }

  if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['addBtn'])) {
    $name=$_POST['name'];
    $price_original=$_POST['price_original'];
    $price_final=$_POST['price_final'];
    $quantity=$_POST['quantity'];
    $brand_id=$_POST['brand_id'];
    $country=$_POST['country'];
    $description=$_POST['description'];
    $ingredient=$_POST['ingredient'];
    $uses=$_POST['uses'];
    $instruction=$_POST['instruction'];
    $review=$_POST['review'];

    $sql_sua="UPDATE brand SET name='".$name."',country='".$country."',description='".$description."',image='".$image."' WHERE id='$_POST[id]'";
    mysqli_query($connect,$sql_sua);
    header('Location: ?page=brand');
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
  <form action="" method="POST">
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
        <input class="input-field" type="price" name="country" value="<?php echo $price_original; ?>">
      </div>
      <div class="col-12 col-md-6">
        <label for="" class="mb-2">Giá bán:</label><br>
        <input class="input-field" type="number" name="description" value="<?php echo $price_final; ?>">
      </div>
    </div>
    <div class="row">
      <div class="col-12 col-md-6">
        <label for="" class="mb-2">Số lượng:</label><br>
        <input class="input-field" type="text" name="description" value="<?php echo $quantity; ?>">
      </div>
      <div class="col-12 col-md-6">
        <label for="" class="mb-2">Quốc gia:</label><br>
        <input class="input-field" type="text" name="description" value="<?php echo $country; ?>">
      </div>
    </div>
    <label for="" class="mb-2">Mô tả:</label><br>
    <input class="input-field" type="text" name="description" value="<?php echo $description; ?>">

    <label for="" class="mb-2">Thành phần:</label><br>
    <input class="input-field" type="text" name="description" value="<?php echo $ingredient; ?>">

    <label for="" class="mb-2">Công dụng:</label><br>
    <input class="input-field" type="text" name="description" value="<?php echo $uses; ?>">

    <label for="" class="mb-2">Cách dùng:</label><br>
    <input class="input-field" type="text" name="description" value="<?php echo $instruction; ?>">

    <label for="" class="mb-2">Review:</label><br>
    <input class="input-field" type="text" name="description" value="<?php echo $review; ?>">

    <label for="" class="mb-2">Hình ảnh:</label><br>
    <form method="POST">
      <div class="d-flex align-items-center">
        <input oninput="updatePathImage(this.value)" class="input-field flex-grow-1 mb-0" type="text" name="image_url">
        <button class="btn btn-primary h-100 ms-3" name="addImage">
          Thêm
        </button>
      </div>
    </form>

    <div class="my-2 text-center">Xem trước ảnh</div>
    <div class="list-image">
      <div class="preview-img-wrap mx-3">
        <img id="preview-image">
      </div>
      <?php
        if (count($_SESSION['list_image'])) {
          foreach ($_SESSION['list_image'] as $image) {
      ?>
        <div class="preview-img-wrap mx-3">
          <img src="<?php echo $image; ?>">
        </div>
      <?php
          }
        }
      ?>
    </div>

    <input hidden name="id" value="<?php echo $brand['id']; ?>">
    <div class="actions d-flex justify-content-center my-3">
      <button class="add-new-btn" name="addBtn">
        Tạo mới
      </button>
    </div>
  </form>
</div>

<script>
  function updatePathImage(value) {
    document.getElementById('preview-image').src = value;
  }
</script>