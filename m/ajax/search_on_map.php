<?php
include("sql.php");
$connect=new connect();
$connect->sql_connect_db();

if(!isset($_POST['map_s_value']) or !isset($_POST['lat']) or !isset($_POST['long']) or !isset($_POST['position']) )
	{
	die("{\"press\":0,\"explain\":\"Bir Hata Oluþtu...\"}");
	}

$yerok_id=mysql_real_escape_string(htmlspecialchars(trim((int)$_POST['map_s_value'])));
$lat=mysql_real_escape_string(htmlspecialchars(trim($_POST['lat'])));
$long=mysql_real_escape_string(htmlspecialchars(trim($_POST['long'])));
$position=mysql_real_escape_string(htmlspecialchars(trim((int)$_POST['position'])));

if($yerok_id<0 and $yerok_id>8)
	{
	die("{\"press\":0,\"explain\":\"Bir Hata Oluþtu...\"}");
	}
$yerok_handler=new yerok_id_handler();
$yerok_handler->yerok_id_control();
$yerok_handler->get_yerok_shop_id($yerok_id,0);
$yerok_handler->search_right_left_id();
$yerok_handler->select_table();
$yerok_handler->yeroks_search_on_map($lat,$long);

?>

