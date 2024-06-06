<?php
  //get list
  $query = "Select * from user";
  $users = mysqli_query($connect, $query);

  
  if (isset($_POST['deleteBtn'])) {
    $id=$_POST['delete_cate_id'];
    $sql_xoa="DELETE FROM category WHERE id ='".$id."';";
    mysqli_query($connect,$sql_xoa);
    header('Location: ?page=category');
  } elseif ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['change_user_id'])) {
    $userId = $_POST['change_user_id'];
    $cateStatus = $_POST['user_status'];
    $sql_sua="UPDATE user SET status='".$cateStatus."' WHERE id='$userId'";
    mysqli_query($connect,$sql_sua);
    header('Location: ?page=user');
  }
?>
<style>
  
</style>
<div class="container pt-4">
  <div class="actions text-center my-3">
    <h3>Danh sách tài khoản</h3>
  </div>
  <table>
    <tr>
      <th>Mã</th>
      <th>Họ tên</th>
      <th>Số điện thoại</th>
      <th>Email</th>
      <th>Vai trò</th>
      <th>Trạng thái</th>
    </tr>
    <?php
      while ($row=mysqli_fetch_array($users)) {
    ?>
      <tr>
        <td><?php echo $row['id'] ?></td>
        <td><?php echo $row['full_name'] ?></td>
        <td><?php echo $row['phone_number'] ?></td>
        <td><?php echo $row['email'] ?></td>
        <td>
          <?php echo $row['role_id'] == 1 ? 'Admin' : 'User' ?>
        </td>
        <td align="center">
          <div class="form-check form-switch form-check-center">
            <input class="form-check-input" type="checkbox" role="switch" id="flexSwitchCheckDefault"
            <?php echo 'onclick="'. 'changeStatus(' . $row['id'] . ',' . $row['status'] . ')"' ?>
            <?php echo $row['status'] == 1 ? 'checked' : '' ?>
            >
          </div>
        </td>
      </tr>
    <?php
      }
    ?>
  </table>

  <form id="changeStatusForm" method="POST" action="">
    <input type="hidden" name="change_user_id" id="change_user_id">
    <input type="hidden" name="user_status" id="user_status">
  </form>
</div>

<script>
  function changeStatus(id, status) {
    document.getElementById('change_user_id').value = id;
    document.getElementById('user_status').value = status == 1 ? 0 : 1;
    document.getElementById('changeStatusForm').submit();
  }
</script>