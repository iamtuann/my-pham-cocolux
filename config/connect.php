<?php
    $severname="localhost";
    $username="root";
    $password="";
    $database="my_pham_cocolux";
    $port="3307";
    $connect = new mysqli($severname,$username,$password,$database, $port);
    if ($connect->connect_error) {
      die("Kết nối thất bại: " . $connect->connect_error);
    }
?>