<?php
session_start();
include("../ajax/kjasafhfjaghl.php");
if(isset($_SESSION['user_type'])  and isset($_SESSION['user_no']) and isset($_SESSION['mail_adress']) )
	{
	
	if($_SESSION['user_type']!=2)
	{
	die("{\"press\":0,\"explain\":\"Lütfen Giriş Yapınız...\"}");
	}
	$connect=new connect();
	$connect->sql_connect_db();
	if(isset($_POST['no']))
		{
		$no=mysql_real_escape_string(htmlspecialchars(trim((int)$_POST['no'])));
		}else
		{
		die("{\"press\":0,\"explain\":\"Bir Sorun Oluştu...\"}");
		}
	
	if($no!=1 and $no!=2 and $no!=3 and $no!=4)
		{
		die("{\"press\":0,\"explain\":\"Bir Sorun Oluştu...\"}");
		}
	
		$shop_id=$_SESSION['user_no'];
		$m_image=new image_handler();
		if( $m_image->get_image_to_delete($shop_id,$no) and $m_image->delete_image_in_database($shop_id,$no))
			{
			echo "{\"press\":1, \"success\":1, \"no\":".$no.", \"explain\":\"Profil Fotoğrafı Kaldırıldı...\"}";
			}else
			{
			echo "{\"press\":1, \"success\":0, \"no\":".$no.", explain\":\"Silme İşlemi Sırasında Bir Sorun Oluştu...\"}";
			}
	}else
	{
	echo "{\"press\":0,\"explain\":\"Bir Sorun Oluştu...\"}";
	}