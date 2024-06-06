<link rel="stylesheet" href="assets/css/user/signin.css">

<div class="main">
    <form action="" method="POST" class="form" id="sign-in-form">
        <h3 class="heading">Thành viên đăng ký</h3>
        <p class="desc">Cùng nhau mua mỹ phẩm tại COCOLUX ❤️</p>

        <div class="spacer"></div>
        <div class="form-group">
            <label for="fullname" class="form-label">Tên đầy đủ</label>
            <input id="fullname" name="fullname" type="text" placeholder="VD: Dao Xuan Truong" class="form-control" />
            <span class="form-message"></span>
        </div>


        <div class="form-group">
            <label for="email" class="form-label">Email</label>
            <input id="email" name="email" type="text" placeholder="VD: email@domain.com" class="form-control" />
            <span class="form-message"></span>
        </div>

        <div class="form-group">
            <label for="password" class="form-label">Mật khẩu</label>
            <input id="password" name="matkhau" type="password" placeholder="Nhập mật khẩu" class="form-control" />
            <span class="form-message"></span>
        </div>

        <div class="form-group">
            <label for="password_confirmation" class="form-label">Nhập lại mật khẩu</label>
            <input id="password_confirmation" name="rematkhau" placeholder="Nhập lại mật khẩu" type="password"
                class="form-control" />
            <span class="form-message"></span>
        </div>
        <div class="form-group">
            <label for="phonenumber" class="form-label">Số điện thoại</label>
            <input id="phonenumber" name="dienthoai" type="text" placeholder="Số điện thoại" class="form-control" />
            <span class="form-message"></span>
        </div>
        <!-- <div class="form-group">
            <label for="fullname" class="form-label">Địa chỉ</label>
            <input id="fullname" name="diachi" type="text" placeholder="Địa chỉ" class="form-control" />
        </div> -->
        <span class="form-message"></span>
        <button class="form-submit" type="button" onclick="handleSignIn()"> Đăng ký </button>
        <!-- <button class="form-submit" name="dangky" >Đăng ký</button> -->
        <a style="margin-top:12px; font-size:14px;" href="login.php">Đăng nhập nếu có tài
            khoản</a>
    </form>
    <div>
        <?php
                    include("config/connect.php");
                    session_start();
                    if(isset($_POST['fullname']) && $_POST["fullname"] != '') {
                        $tenkhachhang = trim($_POST['fullname'],"");
                        $array = preg_split('/\s+/', $tenkhachhang);
                        $email= $_POST['email'];
                        $matkhau = md5($_POST['matkhau']);
                        $rematkhau=  md5($_POST['rematkhau']);
                        $email = $_POST['email'];
                        $dienthoai = $_POST['dienthoai'];
                       $role_id = 2;
                       $checkEmail = "SELECT * FROM user WHERE email = '".$email."'";
                       $queryCheck = mysqli_query($connect,$checkEmail) ;
                       if(mysqli_num_rows($queryCheck) > 0) {
                             echo '<script>alert("Email đã tồn tại")</script>';
                       } else {
                        $sql_dangky = "INSERT INTO user(first_name,last_name,full_name,phone_number,email,password,role_id,status) VALUE('".$array[0]."','".$array[count($array)-1]."','".$tenkhachhang."','".$dienthoai."','".$email."','".$matkhau."','".$role_id."', 1)";
                            $query_dangky=mysqli_query($connect,$sql_dangky);
                        
                            if($query_dangky){
                                $_SESSION["isSign-in"] = "success";
                                header("Location:login.php");
                            }else {
                                $_SESSION["isSign-in"] = "failed";
                            }
                       }
                    }
                
                ?>
    </div>
</div>
<script>
const handleSignIn = () => {
    let fullname = document.getElementById("fullname").value
    let email = document.getElementById("email").value
    let password = document.getElementById("password").value
    let confirmPassword = document.getElementById("password_confirmation").value
    let phoneNumber = document.getElementById("phonenumber").value
    if (!fullname || !email || !password || !confirmPassword || !phoneNumber) {
        alert("vui lòng nhập đầy đủ")
    } else if (password !== confirmPassword) {
        alert("vui lòng nhập lại mật khẩu")
    } else {
        document.getElementById("sign-in-form").submit()

    }
}
</script>