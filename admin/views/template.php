<?php
  $page = "";
  if (isset($_GET['page'])) {
    $page = $_GET['page'];
  }
  switch ($page) {
    case "":
      require 'views/dashboard.php';
      break;
    default:
      require 'views/errors/404.php';
      break;
  }
?>
