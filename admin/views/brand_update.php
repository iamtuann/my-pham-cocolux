<?php
  //kiểm tra id
  $id = "";
  if (isset($_GET['id'])) {
    $id = $_GET['id'];
  } else {
    header('Location: ?page=brand');
  }
  $query = "Select * from brand where id='$id' LIMIT 1";
  $result = mysqli_query($connect, $query);
  if (mysqli_num_rows($result) > 0) {
    $brand = mysqli_fetch_assoc($result);
  } else {
    header('Location: ?page=brand');
  }
  //update
  if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['updateBtn'])) {
    $name=$_POST['name'];
    $country=$_POST['country'];
    $description=$_POST['description'];
    $image=$_POST['image'];

    $sql_sua="UPDATE brand SET name='".$name."',country='".$country."',description='".$description."',image='".$image."' WHERE id='$_POST[id]'";
    mysqli_query($connect,$sql_sua);
    header('Location: ?page=brand');
  }
?>
<style>
  .preview-img-wrap {
    padding: 8px;
    height: 300px;
    width: 300px;
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
    <h4 class="mb-3 text-center">Cập nhật thương hiệu</h4>
    <label for="" class="mb-2">Tên thương hiệu:</label><br>
    <input class="input-field" type="text" name="name" value="<?php echo $brand['name']; ?>">

    <label for="" class="mb-2">Quốc gia:</label><br>
    <input class="input-field" type="text" name="country" value="<?php echo $brand['country']; ?>">

    <label for="" class="mb-2">Mô tả:</label><br>
    <input class="input-field" type="text" name="description" value="<?php echo $brand['description']; ?>">

    <label for="" class="mb-2">Ảnh:</label><br>
    <input oninput="updatePathImage(this.value)" class="input-field" type="text" name="image" value="<?php echo $brand['image']; ?>">
    <div class="my-2 text-center">Xem trước ảnh</div>
    <div class="preview-img-wrap">
      <img src="<?php echo $brand['image']; ?>" id="preview-image">
    </div>

    <input hidden name="id" value="<?php echo $brand['id']; ?>">
    <div class="actions d-flex justify-content-center my-3">
      <button class="add-new-btn" name="updateBtn">
        Cập nhật
      </button>
    </div>
  </form>
</div>

<script>
  function updatePathImage(value) {
    document.getElementById('preview-image').src = value;
  }
</script>