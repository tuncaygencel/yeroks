<?php
session_start();
if(!isset($_SESSION['user_type'])  or !isset($_SESSION['user_no']) or !isset($_SESSION['mail_adress']))
	{
	die("Lütfen Giriş Yapınız...");
	}
if($_SESSION['user_type']!=2)
	{
	die("Bu İşleme Yetkiniz Bulunmamakta...");
	}
if(!isset($_POST['y_data']) or !isset($_POST['c_data']) )
	{
	die("<ul>Veri Gönderim Hatası</ul>");
	}
include("kjasafhfjaghl.php");
$connect=new connect();
$connect->sql_connect_db();

$y_id=mysql_real_escape_string(htmlspecialchars(trim((int)$_POST['y_data'])));
$c_id=mysql_real_escape_string(htmlspecialchars(trim((int)$_POST['c_data'])));
$shop_id=$_SESSION['user_no'];

$search_data=new search_funtions();
$search_data->data_get($c_id,$y_id);
$search_data->get_yield_props($shop_id);




if($search_data->get_property_for_new_yield())
		{
		$search_data->press_name_input();
		$search_data->get_is_add_more();
		$search_data->get_property_value_for_new_yield();
		$search_data->get_plus_props();
		$search_data->get_about();
		$search_data->press_property_for_set_yield();
		}else
		{
		die("HATA!");
		}

?>