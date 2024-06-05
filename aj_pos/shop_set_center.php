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
	$check_type->sent_values($_SESSION['mail_adress'],$_SESSION['user_type'],$_SESSION['user_no']);
		if($check_type->check_and_get()=='2')
		{
		if(!isset($_POST["place"]) or !isset($_POST["floor"]))
		{
		die("{\"problem\":1,\"express\":\"Veri Gönderim Hatası. Lütfen Sayfayı Tekrar Yükleyiniz...\"}");
		}
		
		$shop_center_id=mysql_real_escape_string(htmlspecialchars(trim((int)$_POST["place"])));
		$floor=mysql_real_escape_string(htmlspecialchars(trim((int)$_POST["floor"])));
		//controls
		if(!$loc_func->floor_check($floor))
			{
			die("{\"problem\":1,\"express\":\"".$loc_func->problem."\"}");
			}
		if(!$loc_func->s_center_check($shop_center_id))
			{
			die("{\"problem\":1,\"express\":\"".$loc_func->problem."\"}");
			}
		if($loc_func->save_in_s_center($_SESSION['user_no']))
			{
			echo "{\"problem\":0,\"express\":\"Kayıt Başarıyla Gerçekleştirildi...\"}";
			//save oparation is successfull
			}else
			{
			die("{\"problem\":1,\"express\":\"Kayıt Sırasında Bir Hata İle Karşılaşıldı.Lütfen Tekrar Deneyiniz...\"}");
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