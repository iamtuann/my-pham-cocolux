<?php 
    if(isset($_POST["update"])) {
        $currentPassword = md5($_POST["currentPassword"]) ;
        $newPassword = md5($_POST["newPassword"]) ;
        $query = "SELECT * FROM user where id = '".$_GET["id"]. "' ";
        $result = mysqli_query($connect,$query);
        $row = mysqli_fetch_array($result);
        if($row["password"] != $currentPassword) {
            echo '<script>alert("Mật khẩu hiện tại không đúng")</script>';
        }else if($currentPassword == $newPassword) {
            echo '<script>alert("Vui lòng nhập lại mật khẩu mới")</script>';
        }
         else {
            $update = "UPDATE user
            SET password = '" .$newPassword. "'
            WHERE id = '".$_GET["id"]. "' ";
            $resultUpdate = mysqli_query($connect,$update);
            if($resultUpdate) {
                $_SESSION["isChangePassword"] = "success";
                header("Location:login.php");
                exit();
           

            }
        }
    }
?>

<form method="post" class="container my-4 mx-auto">
    <h2 style="text-align:center">Đổi mật khẩu</h2>
    <div class="row mb-3 justify-content-center">
        <label for="colFormLabelSm" class="col-sm-2 col-form-label ">Mật khẩu hiện tại</label>
        <div class="col-sm-4">
            <input type="password" class="form-control " name="currentPassword" id="colFormLabelSm" placeholder="">
        </div>
    </div>
    <div class="row mb-3 justify-content-center">
        <label for="colFormLabel" class="col-sm-2 col-form-label">Mật khẩu mới</label>
        <div class="col-sm-4">
            <input type="password" class="form-control" name="newPassword" id="colFormLabel" placeholder="">
        </div>
    </div>
    <div class="col-3 mx-auto justify-content-center d-flex">
        <button type="submit" name="update" class="btn btn-primary">Cập nhật</button>
    </div>

</form>