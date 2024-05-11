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
      include('views/product.php');
      break;
    default:
      require 'views/errors/404.php';
      break;
  }
?>
