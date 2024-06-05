<?php
include("sql.php");
$connect=new connect();
$connect->sql_connect_db();

 if(!isset($_POST['y_type_id']) or !isset($_POST['shop_id']) )
	{
	die("Uppps...Bir Hata Oluþtu... Lütfen Sayfayý Yenileyiniz...");
	}

$y_type_id=mysql_real_escape_string(htmlspecialchars(trim($_POST['y_type_id'])));
$shop_id=mysql_real_escape_string(htmlspecialchars(trim($_POST['shop_id'])));

$yerok=new yerok_id_handler();
$yerok->get_yerok_shop_id($y_type_id,$shop_id);
$yerok->select_table();
$yerok->press_all_s_product();

?>
