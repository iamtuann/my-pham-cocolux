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
    case "brand":
      require 'views/brand.php';
      break;
    case "user":
      require 'views/user.php';
      break;
    default:
      require 'views/errors/404.php';
      break;
  }
?>
