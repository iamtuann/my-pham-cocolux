<?php
ob_start();
 $query = "SELECT * FROM category";
 $result = mysqli_query($connect,$query);
 if($_SERVER['REQUEST_METHOD'] == 'POST') {
    header('Location:?page=tim-kiem&key='.$_SESSION["tukhoa"]);
 }
 ?>
<div class="top-header">
    <div class="container">
        <div class="header-left">
            <i class="fal fa-phone-alt"></i>
            <span class="ms-2">+84 989 382 xxx</span>
        </div>
        <div class="header-right">
            <?php echo empty($_SESSION["email"]) ? '<a href="login.php" class="log-in">Đăng nhập</a>
            <a href="signin.php" class="sign-in">Đăng ký</a>' : "<div class='information'>Welcome, ". $_SESSION["name"] ." <div class='menu-content menu-infor'>
            <div class='menu-item'>
                <a href='?page=info' class='menu-btn'>
                    Thông tin </a>
            </div>
            <div class='menu-item'>
                <a href='login.php?log-out=1' class='menu-btn'>
                    Đăng xuất </a>
            </div>
                </div>
                </div>"?>
        </div>
    </div>
</div>
<header class="header">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center gx-4">
            <a href="http://localhost/my-pham-cocolux">
                <img src="assets/images/logo-full-black-cocolux.png" alt="">
            </a>
            <div class="d-flex align-items-center justify-content-end column-gap-4">
                <form id="searchForm" method="post" class="d-flex align-items-center">
                    <input type="text" onkeydown="handleKeyDown(event)" id="keyword" name="keyword"
                        class="search-input">
                    <button type="button" onclick="submitForm()" class="search-btn">
                        <i class="far fa-search"></i>
                    </button>
                </form>
                <a class="header-action" href="/cart">
                    <div class="position-relative">
                        <i class="fal fa-shopping-cart"></i>
                        <span class="count">1</span>
                    </div>
                    <span>Giỏ hàng</span>
                </a>
                <div class="header-action" href="/cart">
                    <i class="fal fa-user-headset"></i>
                    <span>Hỗ trợ</span>
                </div>
            </div>
        </div>
    </div>
</header>
<div class="nav-bar">
    <div class="container">
        <ul class="list-nav">
            <li class="nav-item item-site ">
                <a href="" class="nav-link">Danh mục sản phẩm</a>
                <div class="menu-content">
                    <?php 
                      $i = 0 ;
                      while($row=mysqli_fetch_array($result)) {
                      $i++;
                      ?>
                    <div class="menu-item">
                        <a href="?page=danh-muc&id=<?php echo $row["id"]?>" class="menu-btn">
                            <?php echo $row["name"] ?>
                        </a>
                    </div>
                    <?php
                     }
                    ?>

                </div>
            </li>
            <li class="nav-item">
                <a href="" class="nav-link">Khuyến mại</a>
            </li>
            <li class="nav-item">
                <a href="" class="nav-link">Thương hiệu</a>
            </li>
            <li class="nav-item">
                <a href="" class="nav-link">Giới thiệu</a>
            </li>
            <li class="nav-item">
                <a href="" class="nav-link">Hàng mới về</a>
            </li>
            <li class="nav-item ms-auto"></li>
            <li class="nav-item">
                <a href="" class="nav-link">Tra cứu đơn hàng</a>
            </li>
        </ul>
    </div>
</div>



<script>
function submitForm() {
    let keyword = document.getElementById("keyword").value;
    if (keyword) {
        let form = document.getElementById("searchForm");
        form.action = "?page=tim-kiem&key=" + encodeURIComponent(keyword);
        form.submit();
    } else {
        alert("Vui lòng nhập vào trường này");
    }

}
const handleKeyDown = (e) => {
    if (e.keyCode == 13 || e.key == "Enter") {
        submitForm()
    }
}
</script>