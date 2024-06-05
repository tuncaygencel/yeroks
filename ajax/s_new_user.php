<?php
//error_reporting(null);
session_start();
include("kjasafhfjaghl.php");
$connect=new connect();
if(!isset($_POST['s_full_name']) or !isset($_POST['s_mail']) or !isset($_POST['s_password']) or !isset($_POST['re_s_mail']) or !isset($_POST['f']) or !isset($_POST['shop_type'])) 
	{
	echo "{\"result\":0,\"explain\":\"Hata! Lütfen Sayfayı Tekrar Yükleyiniz...\"}";
	die();
	}
$connect->sql_connect_db();
$f=mysql_real_escape_string(htmlspecialchars(trim($_POST['f'])));
if($_SESSION['f']!=$f)
	{
	echo "{\"result\":0,\"explain\":\"Hata! Lütfen Sayfayı Tekrar Yükleyiniz...\"}";
	die();
	}

$s_join_full_name=mysql_real_escape_string(htmlspecialchars(trim($_POST['s_full_name'])));
$s_join_mail=mysql_real_escape_string(htmlspecialchars(trim($_POST['s_mail'])));
$re_s_join_mail=mysql_real_escape_string(htmlspecialchars(trim($_POST['re_s_mail'])));
$s_join_password=mysql_real_escape_string(htmlspecialchars(trim($_POST['s_password'])));
$shop_type=mysql_real_escape_string(htmlspecialchars(trim((int)$_POST['shop_type'])));

$i=0;
if($s_join_full_name==""){
$i=$i+1;
}
if($s_join_mail==""){
$i=$i+1;
}
if($re_s_join_mail==""){
$i=$i+1;
}
if($s_join_password==""){
$i=$i+1;
}
if($shop_type>14 or $shop_type<0){
die("{\"result\":0,\"explain\":\"Hata! Lütfen Sayfayı Tekrar Yükleyiniz...\"}");
}
if($shop_type==0){
$i=$i+1;
}
	if($i>1)
	{
		echo "{\"result\":0,\"explain\":\"Lütfen Bütün Alanları Doldurunuz...\"}";
	}elseif($i==1)
	{
	if($s_join_full_name=="")
		{
			echo "{\"result\":0,\"explain\":\"Lütfen İşyerinizin Tam İsmini Giriniz...\"}";
		}
	
	if($s_join_mail=="")
		{
			echo "{\"result\":0,\"explain\":\"Lütfen E-Postanızı Giriniz...\"}";
		}
	if($s_join_password=="")
		{
			echo "{\"result\":0,\"explain\":\"Lütfen Şifrenizi giriniz...\"}";
		}
	if($re_s_join_mail=="")
		{
			echo "{\"result\":0,\"explain\":\"Lütfen E-Postanızı Tekrar Giriniz...\"}";
		}
	if($shop_type==0)
		{
				echo "{\"result\":0,\"explain\":\"Lütfen Mağaza Çeşidini Seçiniz...\"}";
		}
	}elseif($s_join_mail!=$re_s_join_mail)
	{
			echo "{\"result\":0,\"explain\":\"Girdiğiniz E-Postalar Birbiriyle Uyuşmuyor. Lütfen E-Postanızı Doğru Girdiğinizden Emin Olunuz...\"}";
	}elseif($i=='0')
	{
		if(strlen($s_join_full_name)< '3')
			{
				echo "{\"result\":0,\"explain\":\"Lütfen İşyerinizin İsmini 2 Karakterden Daha Fazla Olacak Şekilde Giriniz...\"}";
			}elseif(strlen($s_join_password) < '6' )
			{
				echo "{\"result\":0,\"explain\":\"Lütfen Şifrenizi 5 Karakterden Fazla Olacak Şekilde Giriniz...\"}";
			}else
			{
				if (!preg_match('#^[a-z0-9.!\#$%&\'*+-/=?^_`{|}~]+@([0-9.]+|([^\s\'"<>]+\.+[a-z]{2,6}))$#si', $s_join_mail))
					{ 
				echo "{\"result\":0,\"explain\":\"Lütfen Geçerli Bir E-Posta Adresi Giriniz...\"}";
					}
					else
					{	
					$new_s_insert_db=new insert_new_s_db();
						if($new_s_insert_db->control_s_mail($s_join_mail)>0)
							{
							$connect->connect_close();
								echo "{\"result\":0,\"explain\":\"Girdiğiniz E-Posta Adresiyle İlgili Bir İşyeri Hesabı Bulunmakta. Bu Hesaba Şifrenizle Ulaşabilirsiniz...\"}";
							}else
							{
								$connect->sql_connect_db();
								$new_s_insert_db->s_insert_new_user($s_join_full_name,$s_join_mail,$s_join_password,$shop_type);
								$connect->connect_close();
							}
					
					
					
					}
			
			};
	}