<?php
session_start();
if(isset($_SESSION['user_type'])  and isset($_SESSION['user_no']) and isset($_SESSION['mail_adress']))
	{
if($_SESSION['user_type']==2){
$shop_id=$_SESSION['user_no'];
include("kjasafhfjaghl.php");
$connect=new connect();
$connect->sql_connect_db();

if(!isset($_POST['y_data'])){ die("{\"error\":1, \"exp\":\"Veri Gonderim Hatasi...\"}"); }
$y_id=mysql_real_escape_string(htmlspecialchars(trim($_POST['y_data'])));
if($y_id==''){ die("");}

$sql="SELECT y_type_id,y_name,icon_name FROM y_name_list WHERE y_type_id=$y_id and is_show='1' and is_y=1 LIMIT 1";

$sql=mysql_query($sql) or die(mysql_error());

	if(mysql_num_rows($sql)==0){
	echo "{\"error\":1, \"exp\":\"Bir Hata Olustu. Lutfen Tekrar Deneyiniz...\"}";
	}else{
	$sql1="SELECT y_type_id FROM shop_y_list WHERE shop_id=$shop_id and y_type_id=$y_id";
	$sql1=mysql_query($sql1) or die(mysql_error());
	if(mysql_num_rows($sql1)>0){
	echo "{\"error\":1, \"exp\":\"Bu Reyonunuz Zaten Bulunmakta...\"}";
	}else{
	$res=mysql_fetch_assoc($sql);
	$sql2="INSERT INTO shop_y_list(shop_id,y_type_id,y_count,orders) VALUES($shop_id,$y_id,0,0) ";
	if(mysql_query($sql2))
		{
		echo "{\"error\":0, \"name\":\"".$res['y_name']."\",\"data\":".$res['y_type_id'].",\"icon\":".$res['icon_name']."}";
		}else{
		echo "{\"error\":1, \"exp\":\"Kayit Sirasinda Bir Hata Olustu...\"}";
		}
	}
	
	
	}
}
}
?>