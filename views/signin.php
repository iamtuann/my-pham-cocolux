<link rel="stylesheet" href="../assets/css/user/signin.css">

<div class="main">
    <form action="" method="POST" class="form" id="form-1">
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
            <label for="fullname" class="form-label">Số điện thoại</label>
            <input id="fullname" name="dienthoai" type="text" placeholder="Số điện thoại" class="form-control" />
            <span class="form-message"></span>
        </div>
        <!-- <div class="form-group">
            <label for="fullname" class="form-label">Địa chỉ</label>
            <input id="fullname" name="diachi" type="text" placeholder="Địa chỉ" class="form-control" />
        </div> -->
        <span class="form-message"></span>
        <input class="form-submit" type="submit" name="dangky" value="Đăng ký">
        <!-- <button class="form-submit" name="dangky" >Đăng ký</button> -->
        <a style="margin-top:12px; font-size:14px;" href="login.php">Đăng nhập nếu có tài
            khoản</a>
    </form>
    <div>
        <?php
                    include("../config/connect.php");
                    session_start();
                    if(isset($_POST['dangky'])) {
                        $tenkhachhang = trim($_POST['fullname'],"");
                        $array = preg_split('/\s+/', $tenkhachhang);
                        $email= $_POST['email'];
                        $matkhau = md5($_POST['matkhau']);
                        $rematkhau=  md5($_POST['rematkhau']);
                        $email = $_POST['email'];
                        $dienthoai = $_POST['dienthoai'];
                       $role_id = 2;
                        if (!$tenkhachhang || !$email || !$matkhau || !$rematkhau || !$dienthoai)
                        {
                            echo "Vui lòng nhập đầy đủ thông tin.";
                            
                            
                        }elseif($matkhau!=$rematkhau){
                            echo "mat khau chua trung"; 

                        }
                        else{
                            $sql_dangky = "INSERT INTO user(first_name,last_name,full_name,phone_number,email,password,role_id) VALUE('".$array[0]."','".$array[count($array)-1]."','".$tenkhachhang."','".$dienthoai."','".$email."','".$matkhau."','".$role_id."')";
                            $query_dangky=mysqli_query($connect,$sql_dangky);
                            if($query_dangky){
                                echo '<script>alert("Đăng ký thành công")</script>';
                                $_SESSION['dangky'] = $tenkhachhang;
                                $_SESSION['email'] = $email;
                                $_SESSION['id_khachhang'] = mysqli_insert_id($connect);
                                
                                }
                            }
                    }
                
                ?>
    </div>
</div>