<?php
  //get list
  $query = "Select * from category";
  $categories = mysqli_query($connect, $query);

  
  if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['addNewBtn'])){
    $newCate=$_POST['new_cate_name'];
    $sql_them="INSERT INTO category (name,status) VALUE('".$newCate."', '1'); ";
    mysqli_query($connect,$sql_them);
    header('Location: ?page=category');
  } elseif (isset($_POST['deleteBtn'])) {
    $id=$_POST['delete_cate_id'];
    $sql_xoa="DELETE FROM category WHERE id ='".$id."';";
    mysqli_query($connect,$sql_xoa);
    header('Location: ?page=category');
  } elseif (isset($_POST['updateBtn'])) {
    $cateName=$_POST['update_cate_name'];
    $sql_sua="UPDATE category SET name='".$cateName."' WHERE id='$_POST[update_cate_id]'";
    mysqli_query($connect,$sql_sua);
    header('Location: ?page=category');
  } elseif ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['change_cate_id'])) {
    $cateId = $_POST['change_cate_id'];
    $cateStatus = $_POST['cate_status'];
    $sql_sua="UPDATE category SET status='".$cateStatus."' WHERE id='$cateId'";
    mysqli_query($connect,$sql_sua);
    header('Location: ?page=category');
  }
?>
<style>
  
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
      <th>Tên sản phẩm</th>
      <th>Trang thái</th>
      <th>Hành động</th>
    </tr>
    <?php
      while ($row=mysqli_fetch_array($categories)){
    ?>
      <tr>
        <td><?php echo $row['id'] ?></td>
        <td><?php echo $row['name'] ?></td>
        <td align="center">
          <div class="form-check form-switch form-check-center">
            <input class="form-check-input" type="checkbox" role="switch" id="flexSwitchCheckDefault"
            <?php echo 'onclick="'. 'changeStatus(' . $row['id'] . ',' . $row['status'] . ')"' ?>
            <?php echo $row['status'] == 1 ? 'checked' : '' ?>
            >
          </div>
        </td>
        <td>
          <button class="action-btn update-btn" data-bs-toggle="modal" data-bs-target="#updateModal"
            <?php echo 'data-id="' . $row['id'] . '" data-name="' . $row['name'] . '"'; ?>
          >
            <i class="far fa-pen"></i>
          </button>
          <button class="action-btn delete-btn" data-bs-toggle="modal" data-bs-target="#deleteModal"
            <?php echo 'data-id="' . $row['id'] . '" data-name="' . $row['name'] . '"'; ?>
          >
            <i class="far fa-trash"></i>
          </button>
        </td>
      </tr>
    <?php
      }
    ?>
  </table>
  
  <div class="modal fade" id="addModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <form action="" method="POST">
          <div class="modal-header">
            <h1 class="modal-title fs-5" id="exampleModalLabel">Thêm mới danh mục</h1>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <label for="" class="mb-2">Tên danh mục:</label><br>
            <input class="input-field" type="text" name="new_cate_name">
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
            Bạn có chắc chắn muốn xóa danh mục <span id="cateName"></span> không?
            <input type="hidden" name="delete_cate_id" id="delete_cate_id">
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
            <button name="deleteBtn" class="btn btn-primary">Xóa</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <div class="modal fade" id="updateModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <form action="" method="POST">
          <div class="modal-header">
            <h1 class="modal-title fs-5" id="exampleModalLabel">Cập nhật danh mục</h1>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <label for="" class="mb-2">Tên danh mục:</label><br>
            <input class="input-field" type="text" name="update_cate_name" id="update_cate_name">
            <input type="hidden" name="update_cate_id" id="update_cate_id">
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
            <button name="updateBtn" class="btn btn-primary">Cập nhật</button>
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
    var updateButtons = document.querySelectorAll('.update-btn');
    deleteButtons.forEach(function(button) {
        button.addEventListener('click', function() {
            var cateId = this.getAttribute('data-id');
            var cateName = this.getAttribute('data-name');
            document.getElementById('cateName').textContent = cateName;
            document.getElementById('delete_cate_id').value = cateId;
        });
    });
    updateButtons.forEach(function(button) {
        button.addEventListener('click', function() {
            var cateId = this.getAttribute('data-id');
            var cateName = this.getAttribute('data-name');
            document.getElementById('update_cate_name').value = cateName;
            document.getElementById('update_cate_id').value = cateId;
        });
    });

  });
  function changeStatus(id, status) {
    document.getElementById('change_cate_id').value = id;
    document.getElementById('cate_status').value = status == 1 ? 0 : 1;
    document.getElementById('changeStatusForm').submit();
  }
</script>