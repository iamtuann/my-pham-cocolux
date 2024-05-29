<?php
  $page = "";
  if (isset($_GET['page'])) {
    $page = $_GET['page'];
  }
  switch ($page) {
    case "":
    case "dashboard":
      require 'views/dashboard.php';
      break;
    case "category":
      require 'views/category.php';
      break;
    case "product":
      require 'views/product.php';
      break;
    case "add-product":
      require 'views/product_add.php';
      break;
    case "update-product":
      require 'views/product.php';
      break;
    case "brand":
      require 'views/brand.php';
      break;
    case "update-brand":
      require 'views/brand_update.php';
      break;
    case "user":
      require 'views/user.php';
      break;
    case "order-waiting":
      require 'views/order_wait.php';
      break;
    default:
      require 'views/errors/404.php';
      break;
  }
?>
