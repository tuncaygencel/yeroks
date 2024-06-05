<?php
include("sql.php");
session_start();
$connect=new connect();
$connect->sql_connect_db();
if(isset($_SESSION['user_no']))
	{
	if( isset($_POST['yield_name']) )
		{
		$user_id=$_SESSION['user_no'];
		$yerok_name=mysql_real_escape_string(htmlspecialchars(trim($_POST['yield_name'])));
			if($yerok_name!='')
				{
				$sql="INSERT INTO shopping_fav_list(user_id,y_name,type,add_time) VALUES('$user_id','$yerok_name',1,NOW())";
					if(mysql_query($sql))
						{
						$id=mysql_insert_id();
						echo "{\"result\":1,\"explain\":\"\",\"y_name\":\"".$yerok_name."\",\"id\":".$id."}";
						}else
						{
						echo "{\"result\":0,\"explain\":\"Bir Hata Oluştu...\"}";
						}
				}else
				{
				echo "{\"result\":0,\"explain\":\"Bir Ürün İsmi Giriniz...\"}";
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