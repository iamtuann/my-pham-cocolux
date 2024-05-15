<?php
  //get list
  $query = "Select * from brand";
  $brands = mysqli_query($connect, $query);
  //add new
  if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['addNewBtn'])){
    $name=$_POST['new_name'];
    $country=$_POST['new_country'];
    $description=$_POST['new_description'];
    $image=$_POST['new_image'];
    $sql_them="INSERT INTO brand (name,country,description,image) VALUE('".$name."', '".$country."', '".$description."', '".$image."'); ";
    mysqli_query($connect,$sql_them);
    header('Location: ?page=brand');
  } elseif (isset($_POST['deleteBtn'])) {
    $id=$_POST['delete_brand_id'];
    $sql_xoa="DELETE FROM brand WHERE id ='".$id."';";
    mysqli_query($connect,$sql_xoa);
    header('Location: ?page=brand');
  }
?>
<style>
  .row-image {
    height: 60px;
    width: auto;
    object-fit: cover;
  }
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
  <div class="actions d-flex justify-content-end my-3">
    <button class="add-new-btn" data-bs-toggle="modal" data-bs-target="#addModal">
      Thêm mới
    </button>
  </div>
  <table>
    <tr>
      <th>Mã</th>
      <th>Tên thương hiệu</th>
      <th>Quốc gia</th>
      <th>Ảnh</th>
      <th>Hành động</th>
    </tr>
    <?php
      if (mysqli_num_rows($brands) > 0){
        while ($row=mysqli_fetch_array($brands)){
    ?>
      <tr>
        <td><?php echo $row['id'] ?></td>
        <td><?php echo $row['name'] ?></td>
        <td><?php echo $row['country'] ?></td>
        <td align="center"><img class="row-image" src="<?php echo $row['image'] ?>" alt="<?php echo $row['name'] ?>"></td>
        <td align="center">
          <a href="<?php echo '?page=update-brand&id=' . $row['id'] ?>">
            <button class="action-btn update-btn">
              <i class="far fa-pen"></i>
            </button>
          </a>
          <button class="action-btn delete-btn" data-bs-toggle="modal" data-bs-target="#deleteModal"
            <?php echo 'data-id="' . $row['id'] . '" data-name="' . $row['name'] . '"'; ?>
          >
            <i class="far fa-trash"></i>
          </button>
        </td>
      </tr>
    <?php
        }
      } else {
        echo '<tr><td colspan="100%" align="center">Không có dữ liệu</td></tr>';
      }
    ?>
  </table>
  
  <div class="modal fade" id="addModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <form action="" method="POST">
          <div class="modal-header">
            <h1 class="modal-title fs-5" id="exampleModalLabel">Thêm mới thương hiệu</h1>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <label for="" class="mb-2">Tên thương hiệu:</label><br>
            <input class="input-field" type="text" name="new_name">
            <label for="" class="mb-2">Quốc gia:</label><br>
            <input class="input-field" type="text" name="new_country">
            <label for="" class="mb-2">Mô tả:</label><br>
            <input class="input-field" type="text" name="new_description">
            <label for="" class="mb-2">Đường dẫn ảnh:</label><br>
            <input oninput="updatePathImage(this.value)" class="input-field" type="text" name="new_image">
            <div class="my-2 text-center">Xem trước ảnh</div>
            <div class="preview-img-wrap">
              <img id="preview-image">
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
            <button name="addNewBtn" class="btn btn-primary">Thêm</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <form action="" method="POST">
          <div class="modal-header">
            <h1 class="modal-title fs-5" id="exampleModalLabel">Xóa danh mục</h1>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            Bạn có chắc chắn muốn xóa thương hiệu <span id="brandName"></span> không?
            <input type="hidden" name="delete_brand_id" id="delete_brand_id">
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
            <button name="deleteBtn" class="btn btn-primary">Xóa</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <form id="changeStatusForm" method="POST" action="">
    <input type="hidden" name="change_cate_id" id="change_cate_id">
    <input type="hidden" name="cate_status" id="cate_status">
  </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    var deleteButtons = document.querySelectorAll('.delete-btn');
    deleteButtons.forEach(function(button) {
        button.addEventListener('click', function() {
            var brandId = this.getAttribute('data-id');
            var brandName = this.getAttribute('data-name');
            document.getElementById('brandName').textContent = brandName;
            document.getElementById('delete_brand_id').value = brandId;
        });
    });

  });
  function updatePathImage(value) {
    document.getElementById('preview-image').src = value;
  }
</script>