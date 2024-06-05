<?php
session_start();
include("../ajax/kjasafhfjaghl.php");
if(isset($_SESSION['user_type'])  and isset($_SESSION['user_no']) and isset($_SESSION['mail_adress']))
	{
	if($_SESSION['user_type']!=2)
	{
	die("{\"problem\":1,\"express\":\"Lütfen Giriş Yapınız...\"}");
	}
	$connect=new connect();
	$connect->sql_connect_db();
	$check_type=new check_type_get_values();
	$loc_func=new shop_map_set();
	$shop_id=$_SESSION['user_no'];
	$check_type->sent_values($_SESSION['mail_adress'],$_SESSION['user_type'],$shop_id);
		if($check_type->check_and_get()=='2')
		{
		if(!isset($_POST["loc_lat"]) or !isset($_POST["loc_long"]) or !isset($_POST["zoom"]))
			{
			die("{\"problem\":1,\"express\":\"Veri Transfer Hatası. Lütfen Sayfayı Yenileyiniz...\"}");
			}
		$loc_lat=mysql_real_escape_string(htmlspecialchars(trim((float)$_POST["loc_lat"])));
		$loc_long=mysql_real_escape_string(htmlspecialchars(trim((float)$_POST["loc_long"])));
		$zoom=mysql_real_escape_string(htmlspecialchars(trim((int)$_POST["zoom"])));
		//controls
		if(!$loc_func->is_there_adress_data($shop_id))
			{
			die("{\"problem\":1,\"express\":\"".$loc_func->problem."\"}");
			}
		if(!$loc_func->check_map_data($loc_lat,$loc_long,$zoom))
			{
			die("{\"problem\":1,\"express\":\"".$loc_func->problem."\"}");
			}
			//save location data
		if($loc_func->street_map_data_save($loc_lat,$loc_long,$zoom,$shop_id))
			{
			echo "{\"problem\":0,\"express\":\"Kayıt Tamam...\"}";
			}else
			{
			die("{\"problem\":1,\"express\":\"".$loc_func->problem."\"}");
			}
		}else
		{
		echo "{\"problem\":1,\"express\":\"Lütfen Giriş Yapınız...\"}";
		}
	}else
	{
	echo "{\"problem\":1,\"express\":\"Lütfen Giriş Yapınız...\"}";
	}
?>
