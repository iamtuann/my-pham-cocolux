<div class="admin-header">
    <div class="flex-grow-1"></div>
    <div class="">
        <span>Admin
            <?= isset($_SESSION["name"]) && $_SESSION["role"] == "admin" ? $_SESSION["name"] : ""  ?></span>
        <a class="btn btn-primary mx-2" href="../login.php?log-out=1">Đăng xuất</a>
    </div>
</div>