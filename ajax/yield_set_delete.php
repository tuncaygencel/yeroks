<?php
include("kjasafhfjaghl.php");
session_start();
if(!isset($_SESSION['user_type'])  or !isset($_SESSION['user_no']) or !isset($_SESSION['mail_adress']))
	{
	die("Lütfen Giriþ Yapýnýz...");
	}
if($_SESSION['user_type']!=2)
	{
	die("Bu Ýþleme Yetkiniz Bulunmamakta...");
	}
	$connect=new connect();
	$connect->sql_connect_db();
if(isset($_POST['y_data']) and isset($_POST['c_data']))
	{
	$y_id=mysql_real_escape_string(htmlspecialchars(trim((int)$_POST['y_data'])));
	$c_id=mysql_real_escape_string(htmlspecialchars(trim((int)$_POST['c_data'])));
	}else
	{
	die("{\"status\":0,\"text\":\"Ýþlem Sýrasýnda Hata Oluþtu!...\"}");
	}

$shop_id=$_SESSION['user_no'];
$user_type=$_SESSION['user_type'];
//user_type have to be 2 because a user id can equal to a shop id and this user can delete shop's yields when user is login
	if($user_type!=2)
		{
		die('{"status":0,"text":"HATA!..."}');
		}


$image=new image_handler();
$set=new yield_set();
$set->get_y_id($y_id);
$set->get_shop_id($shop_id);
$set->get_y_type_id($c_id);
$set->control_y_id();
$set->select_table();
$set->control_yield_own();
$set->delete_yield();
$set->y_category_count_set();
$image->delete_y_image_in_databese($y_id,$c_id,1);
$image->delete_y_image_in_databese($y_id,$c_id,2);
$image->delete_y_image_in_databese($y_id,$c_id,3);
$set->json_echo();
?>
