<?php
//magazanin urunleri listelendiginde ozel olarak altta ayrintili sekilde listelenmesini saglayan sayfa
include("sql.php");
session_start();
if(isset($_SESSION['user_no']))
	{
	if(isset($_POST['y_data']))
		{
		$user_id=$_SESSION['user_no'];
		$connect=new connect();
		$connect->sql_connect_db();
		$yerok_id=mysql_real_escape_string(htmlspecialchars(trim((int)$_POST['y_data'])));
		
		$sql="DELETE FROM shopping_fav_list WHERE id='$yerok_id' and user_id='$user_id' LIMIT 1";
		if(mysql_query($sql))
			{
			echo "{\"result\":1,\"explain\":\"Ürün listenizden ürün silindi...\"}";
			}else
			{
			echo "{\"result\":0,\"explain\":\"Bir Hata Oluştu...\"}";
			}
		
		}else
		{
		echo "{\"result\":0,\"explain\":\"Bir Hata Oluştu...\"}";
		}
	}else
	{
		echo "{\"result\":0,\"explain\":\"Lütfen Giriş Yapınız...\"}";
	}


?>