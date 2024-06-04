<?php
    include("config/connect.php");
    session_start();
    ob_start();
    if(isset($_GET["log-out"])) {
        unset($_SESSION['email']) ;
        unset($_SESSION['user_id']);
        unset($_SESSION['name']);
        unset($_SESSION['role']);
    }
    if (isset($_SESSION["isSign-in"]) && $_SESSION["isSign-in"] == "success") {
        unset($_SESSION["isSign-in"]);
        echo '<script>alert("Đăng ký thành công")</script>';
    } else if (isset($_SESSION["isChangePassword"]) && $_SESSION["isChangePassword"] == "success") {
        unset($_SESSION["isChangePassword"]);
        echo '<script>alert("Thay đổi mật khẩu thành công")</script>';
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
                header("Location:index.php");
            } elseif($row_data["role_id"] == 1) {
                $_SESSION['email'] = $row_data['email'];
                $_SESSION['user_id']= $row_data['id'];
                $_SESSION['name']= $row_data['last_name'];
                $_SESSION['role'] = 'admin';
                header("Location:admin/index.php");
            }
		}
        else{
			$message = "Tài khoản hoặc mật khẩu không đúng";
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
        background: linear-gradient(212.38deg, rgba(242, 57, 127, 0.7) 0%, rgba(175, 70, 189, 0.71) 100%), url(https://scontent.fhan5-1.fna.fbcdn.net/v/t39.30808-6/441898899_852103643621047_7500792948689136637_n.jpg?stp=dst-jpg_s960x960&_nc_cat=103&ccb=1-7&_nc_sid=5f2048&_nc_eui2=AeHc-X-BCrZNiXK68Z9vjdiQdhYmr9qtSzp2Fiav2q1LOrEy62irX-weT6_oRKqbXeJOLU41Nyo3x5pIb1OOL8ex&_nc_ohc=dOR_owzXorcQ7kNvgFiOYiD&_nc_ht=scontent.fhan5-1.fna&oh=00_AYBliLLkcJrc0zNkCUqBYi3ny4POXx97ImvLS_G4NLurbQ&oe=66553214);
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
                <div class="top_link"><a href="index.php"><img
                            src="https://scontent.fhan5-1.fna.fbcdn.net/v/t39.30808-6/441898899_852103643621047_7500792948689136637_n.jpg?stp=dst-jpg_s960x960&_nc_cat=103&ccb=1-7&_nc_sid=5f2048&_nc_eui2=AeHc-X-BCrZNiXK68Z9vjdiQdhYmr9qtSzp2Fiav2q1LOrEy62irX-weT6_oRKqbXeJOLU41Nyo3x5pIb1OOL8ex&_nc_ohc=dOR_owzXorcQ7kNvgFiOYiD&_nc_ht=scontent.fhan5-1.fna&oh=00_AYBliLLkcJrc0zNkCUqBYi3ny4POXx97ImvLS_G4NLurbQ&oe=66553214"
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
                    <h2>Mỹ phẩm COCOLUX</h2>
                    <h5>THƯƠNG HIỆU SỐ 1 VN</h5>
                </div>
                <div class="right-inductor"><img
                        src="https://scontent.fhan5-1.fna.fbcdn.net/v/t39.30808-6/441898899_852103643621047_7500792948689136637_n.jpg?stp=dst-jpg_s960x960&_nc_cat=103&ccb=1-7&_nc_sid=5f2048&_nc_eui2=AeHc-X-BCrZNiXK68Z9vjdiQdhYmr9qtSzp2Fiav2q1LOrEy62irX-weT6_oRKqbXeJOLU41Nyo3x5pIb1OOL8ex&_nc_ohc=dOR_owzXorcQ7kNvgFiOYiD&_nc_ht=scontent.fhan5-1.fna&oh=00_AYBliLLkcJrc0zNkCUqBYi3ny4POXx97ImvLS_G4NLurbQ&oe=66553214"
                        alt=""></div>
            </div>
        </div>
    </section>
</body>

</html>
<?php ob_end_flush();?>