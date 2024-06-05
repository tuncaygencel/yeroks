<?php

include("sql.php");
$connect=new connect();
$connect->sql_connect_db();
if(!isset($_POST['type_number'])){ die("{\"result\":0,\"exp\":\"Ürün Kategorileri Listelenirken Bir Hata Oluştu...\"}");}
$value=mysql_real_escape_string(htmlspecialchars(trim((int)$_POST['type_number'])));
if($value==''){ die("{\"result\":0,\"exp\":\"Ürün Kategorileri Listelenirken Bir Hata Oluştu...\"}");}


$list=new product_list();
$list->press_category($value);



















?>

