<?php
session_start();
 include("sql.php");
$connect=new connect();
$connect->sql_connect_db();

if(!isset($_SESSION['user_no']) or !isset($_SESSION['user_full_name']) or !isset($_SESSION['user_type']) or !isset($_SESSION['mail_adress']) )
{
die('Lütfen Giriþ Yapýnýz...');
 }
 
 if(!isset($_POST['loc_name']) or !isset($_POST['latitude'])  or !isset($_POST['longitude'])  ) 
	{
	echo "Uppps...Bir Hata Oluþtu... Lütfen Sayfayý Yenileyiniz...";
	die();
	}
 $loc_name=mysql_real_escape_string(htmlspecialchars(trim($_POST['loc_name'])));
 $lati=mysql_real_escape_string(htmlspecialchars(trim($_POST['latitude'])));
 $longi=mysql_real_escape_string(htmlspecialchars(trim($_POST['longitude'])));
 $user_id=$_SESSION['user_no'];
 
$sql="INSERT INTO user_search_location(user_id,loc_name,latitude,longitude) VALUES('$user_id','$loc_name','$lati','$longi')";
if(mysql_query($sql)){
echo "Yeni Konum Kaydedildi";
}else{
echo "Kayýt Ýþlemi Sýrasýnda Bir Sorun Oluþtu...";
}

?>