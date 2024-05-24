<?php
    include("../config/connect.php");
    session_start();
    if(isset($_GET["log-out"])) {
        $_SESSION['email'] = null;
        $_SESSION['user_id']= null;
        $_SESSION['name']= null;
    }
	if(isset($_POST['dangnhap'])){
		$email = $_POST['email'];
		$matkhau = md5($_POST['password']);
		$sql = "SELECT * FROM user WHERE email='".$email."' AND password='".$matkhau."'  LIMIT 1";
		$row = mysqli_query($connect,$sql);
		$count = mysqli_num_rows($row);
		if($count>0){
			$row_data = mysqli_fetch_array($row);
            if($row_data["role_id"] == 2) {
                $_SESSION['email'] = $row_data['email'];
                $_SESSION['user_id']= $row_data['id'];
                $_SESSION['name']= $row_data['last_name'];
                header("Location:../index.php");
            } elseif($row_data["role_id"] == 1) {
                header("Location:../admin/index.php");
            }
		}
        else{
			$message = "Tài khoản mật khẩu không đúng";
            echo "<script type='text/javascript'>alert('$message');</script>";
        }
	} 
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style_login.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/8.0.1/normalize.min.css"
        integrity="sha512-NhSC1YmyruXifcj/KFRWoC561YpHpc5Jtzgvbuzx5VozKpWvQ+4nXhPdFgmx8xqexRcpAglTj9sIBWINXa8x5w=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <title>Đăng nhập</title>
    <style>
    img {
        width: 100%;
    }

    .login {
        height: 100vh;
        width: 100%;
        background: radial-gradient(#653d84, #332042);
        position: relative;
    }

    .login_box {
        width: 1050px;
        height: 600px;
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        background: #fff;
        border-radius: 10px;
        box-shadow: 1px 4px 22px -8px #0004;
        display: flex;
        overflow: hidden;
    }

    .login_box .left {
        width: 41%;
        height: 100%;
        padding: 25px 25px;

    }

    .login_box .right {
        width: 59%;
        height: 100%
    }

    .left .top_link a {
        color: #452A5A;
        font-weight: 400;
        display: flex;
        align-items: center;
    }

    .left .top_link {
        height: 20px
    }

    .left .contact {
        display: flex;
        align-items: center;
        justify-content: center;
        align-self: center;
        height: 100%;
        width: 73%;
        margin: auto;
    }

    .left h3 {
        text-align: center;
        margin-bottom: 40px;
    }

    .left input {
        border: none;
        width: 80%;
        margin: 15px 0px;
        border-bottom: 1px solid #4f30677d;
        padding: 7px 9px;
        width: 100%;
        overflow: hidden;
        background: transparent;
        font-weight: 600;
        font-size: 14px;
    }

    .left {
        background: linear-gradient(-45deg, #dcd7e0, #fff);
    }

    .submit {
        border: none;
        padding: 15px 70px;
        border-radius: 8px;
        display: block;
        margin: auto;
        margin-top: 120px;
        background: #583672;
        color: #fff;
        font-weight: bold;
        -webkit-box-shadow: 0px 9px 15px -11px rgba(88, 54, 114, 1);
        -moz-box-shadow: 0px 9px 15px -11px rgba(88, 54, 114, 1);
        box-shadow: 0px 9px 15px -11px rgba(88, 54, 114, 1);
    }



    .right {
        background: linear-gradient(212.38deg, rgba(242, 57, 127, 0.7) 0%, rgba(175, 70, 189, 0.71) 100%), url(https://luxshopping.vn/Uploads/UserFiles/images/banner%20dong%20ho%20frederique%20constaint.png);
        color: #fff;
        position: relative;
    }

    .right .right-text {
        height: 100%;
        position: relative;
        transform: translate(0%, 45%);
    }

    .right-text h2 {
        display: block;
        width: 100%;
        text-align: center;
        font-size: 50px;
        font-weight: 500;
    }

    .right-text h5 {
        display: block;
        width: 100%;
        text-align: center;
        font-size: 19px;
        font-weight: 400;
    }

    .right .right-inductor {
        position: absolute;
        width: 70px;
        height: 7px;
        background: #fff0;
        left: 50%;
        bottom: 70px;
        transform: translate(-50%, 0%);
    }

    .top_link img {
        width: 28px;
        padding-right: 7px;
        /* margin-top: -3px; */
    }
    </style>
</head>

<body>
    <section class="login">
        <div class="login_box">
            <div class="left">
                <div class="top_link"><a href="http://localhost/my-pham-cocolux/"><img
                            src="https://drive.google.com/u/0/uc?id=16U__U5dJdaTfNGobB_OpwAJ73vM50rPV&export=download"
                            alt="">Về trang chủ</a></div>
                <div class="contact">
                    <form action="" method="POST">
                        <h3>ĐĂNG NHẬP</h3>
                        <input type="text" name="email" placeholder="USERNAME">
                        <input type="password" name="password" placeholder="PASSWORD">
                        <button class="submit" name="dangnhap">ĐĂNG NHẬP</button>
                    </form>
                </div>
            </div>
            <div class="right">
                <div class="right-text">
                    <h2>Đồng hồ LUXURY</h2>
                    <h5>ĐẲNG CẤP 5 SAO</h5>
                </div>
                <div class="right-inductor"><img
                        src="https://lh3.googleusercontent.com/fife/ABSRlIoGiXn2r0SBm7bjFHea6iCUOyY0N2SrvhNUT-orJfyGNRSMO2vfqar3R-xs5Z4xbeqYwrEMq2FXKGXm-l_H6QAlwCBk9uceKBfG-FjacfftM0WM_aoUC_oxRSXXYspQE3tCMHGvMBlb2K1NAdU6qWv3VAQAPdCo8VwTgdnyWv08CmeZ8hX_6Ty8FzetXYKnfXb0CTEFQOVF4p3R58LksVUd73FU6564OsrJt918LPEwqIPAPQ4dMgiH73sgLXnDndUDCdLSDHMSirr4uUaqbiWQq-X1SNdkh-3jzjhW4keeNt1TgQHSrzW3maYO3ryueQzYoMEhts8MP8HH5gs2NkCar9cr_guunglU7Zqaede4cLFhsCZWBLVHY4cKHgk8SzfH_0Rn3St2AQen9MaiT38L5QXsaq6zFMuGiT8M2Md50eS0JdRTdlWLJApbgAUqI3zltUXce-MaCrDtp_UiI6x3IR4fEZiCo0XDyoAesFjXZg9cIuSsLTiKkSAGzzledJU3crgSHjAIycQN2PH2_dBIa3ibAJLphqq6zLh0qiQn_dHh83ru2y7MgxRU85ithgjdIk3PgplREbW9_PLv5j9juYc1WXFNW9ML80UlTaC9D2rP3i80zESJJY56faKsA5GVCIFiUtc3EewSM_C0bkJSMiobIWiXFz7pMcadgZlweUdjBcjvaepHBe8wou0ZtDM9TKom0hs_nx_AKy0dnXGNWI1qftTjAg=w1920-h979-ft"
                        alt=""></div>
            </div>
        </div>
    </section>
</body>

</html>