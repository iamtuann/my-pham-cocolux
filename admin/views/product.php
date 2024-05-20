<?php
  //get list
  $query = "Select id, name, price_original, price_final, quantity, status, sold from product";
  $products = mysqli_query($connect, $query);
  //delete
  if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['deleteBtn'])){
    $id=$_POST['delete_product_id'];
    $sql_xoa="DELETE FROM product WHERE id ='".$id."';";
    mysqli_query($connect,$sql_xoa);
    header('Location: ?page=product');
  } elseif ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['change_product_id'])) {
    $productId = $_POST['change_product_id'];
    $productStatus = $_POST['product_status'];
    $sql_sua="UPDATE product SET status='".$productStatus."' WHERE id='$productId'";
    mysqli_query($connect,$sql_sua);
    header('Location: ?page=product');
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
    <a href="<?php echo '?page=add-product'?>">
      <button class="add-new-btn">
        Thêm mới
      </button>
    </a>
  </div>
  <table>
    <tr>
      <th>Mã</th>
      <th>Tên</th>
      <th>Giá gốc</th>
      <th>Giá bán</th>
      <th>Số lượng còn</th>
      <th>Đã bán</th>
      <th>Trang thái</th>
      <th>Hành động</th>
    </tr>
    <?php
      if (mysqli_num_rows($products) > 0){
        while ($row=mysqli_fetch_array($products )){
    ?>
      <tr>
        <td><?php echo $row['id']; ?></td>
        <td><?php echo $row['name']; ?></td>
        <td><?php echo number_format($row['price_original']); ?></td>
        <td><?php echo number_format($row['price_final']); ?></td>
        <td><?php echo $row['quantity']; ?></td>
        <td><?php echo $row['sold'] ? $row['sold'] : 0; ?></td>
        <td align="center">
          <div class="form-check form-switch form-check-center">
            <input class="form-check-input" type="checkbox" role="switch" id="flexSwitchCheckDefault"
            <?php echo 'onclick="'. 'changeStatus(' . $row['id'] . ',' . $row['status'] . ')"'; ?>
            <?php echo $row['status'] == 1 ? 'checked' : ''; ?>
            >
          </div>
        </td>
        <td align="center">
          <a href="<?php echo '?page=update-product&id=' . $row['id'] ?>">
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

  <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <form action="" method="POST">
          <div class="modal-header">
            <h1 class="modal-title fs-5" id="exampleModalLabel">Xóa sản phẩm</h1>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            Bạn có chắc chắn muốn xóa sản phẩm <span id="prodcutName"></span> không?
            <input type="hidden" name="delete_product_id" id="delete_product_id">
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
            <button name="deleteBtn" class="btn btn-danger">Xóa</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <form id="changeStatusForm" method="POST" action="">
    <input type="hidden" name="change_product_id" id="change_product_id">
    <input type="hidden" name="product_status" id="product_status">
  </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    var deleteButtons = document.querySelectorAll('.delete-btn');
    deleteButtons.forEach(function(button) {
        button.addEventListener('click', function() {
            var productId = this.getAttribute('data-id');
            var prodcutName = this.getAttribute('data-name');
            document.getElementById('prodcutName').textContent = prodcutName;
            document.getElementById('delete_product_id').value = productId;
        });
    });

  });
  function updatePathImage(value) {
    document.getElementById('preview-image').src = value;
  }
  function changeStatus(id, status) {
    document.getElementById('change_product_id').value = id;
    document.getElementById('product_status').value = status == 1 ? 0 : 1;
    document.getElementById('changeStatusForm').submit();
  }
  function formatPrice(price) {
    return new Intl.NumberFormat('en-US').format(price);
  }
</script>