<?php
  $page = "";
  if (isset($_GET['page'])) {
    $page = $_GET['page'];
  }
  switch ($page) {
    case "":
    case 'home':
      require 'views/home.php';
      break;
    case 'gio-hang':
      include('views/cart.php');
      break;
    case 'san-pham':
      include('views/detail_product.php');
      break;
    case 'danh-muc':
      include('views/category.php');
      break;
    case 'tim-kiem':
      include('views/category.php');
      break;
    default:
      require 'views/errors/404.php';
      break;
  }
?>