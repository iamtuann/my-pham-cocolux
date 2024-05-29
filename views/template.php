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
    case 'all-product':
      include('views/category.php');
      break;   
    case "thuong-hieu":
      include("views/brand.php");
      break;  
    case 'info':
      include('views/info.php');
      break;  
    case 'change-password':
    include('views/change-password.php');           
      break;      
    case 'checkout':
      include('views/checkout.php');
      break;
    case 'checkout-success':
      include('views/checkout_success.php');
      break;
    case 'checkout-payment':
      include('views/checkout_payment.php');
      break;
    case 'info':
      include('views/info.php');
      break;        
    default:
      require 'views/errors/404.php';
      break;
  }
?>