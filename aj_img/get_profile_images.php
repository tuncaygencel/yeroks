<?php
session_start();
include("../ajax/kjasafhfjaghl.php");
if(isset($_SESSION['user_type'])  and isset($_SESSION['user_no']) and isset($_SESSION['mail_adress']))
	{
	if($_SESSION['user_type']!=2)
	{
	die("{\"press\":0,\"explain\":\"Lütfen Giriş Yapınız...\"}");
	}
	
	$connect=new connect();
	$connect->sql_connect_db();
	$shop_id=$_SESSION['user_no'];
	$sql="SELECT*FROM s_image WHERE shop_id='$shop_id'";
	$sql=mysql_query($sql) or die("{\"press\":0,\"explain\":\"Bir Sorun Oluştu...\"}");
	if(mysql_num_rows($sql)>0)
		{
			$i=1;
			$json="{\"press\":1,\"explain\":\"\",\"results\":[";
			while($res=mysql_fetch_assoc($sql))
				{
				if($i>1){
					$json=$json.",{\"no\":".$res['no'].",\"problem\":0,\"press\":1,\"explain\":\"\",\"image_name\":\"".$res['img_way']."\"}";
					}else
					{
					$json=$json."{\"no\":".$res['no'].",\"problem\":0,\"press\":1,\"explain\":\"\",\"image_name\":\"".$res['img_way']."\"}";
					}
				$i=$i+1;
				}
			$json=$json."]}";
				echo $json;
		}else{
		echo "{\"press\":0,\"explain\":\"\"}";
		}
	}else
	{
	echo "{\"press\":0,\"explain\":\"Bir Sorun Oluştu...\"}";
	}




?>