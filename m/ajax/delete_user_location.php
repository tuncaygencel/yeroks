<?php
session_start();

if(!isset($_SESSION['user_no']) or !isset($_SESSION['user_full_name']) or !isset($_SESSION['user_type']) or !isset($_SESSION['mail_adress']) )
{
die('Bir Sorun Olustu');
 }
 
 if(!isset($_POST['id']) ) 
	{
	echo "Uppps...Bir Hata Oluştu... Lütfen Sayfayı Yenileyiniz...";
	die();
	}
	 include("sql.php");
$connect=new connect();
$connect->sql_connect_db();

 $loc_id=mysql_real_escape_string(htmlspecialchars(trim((int)$_POST['id'])));
 $user_id=$_SESSION['user_no'];
 
$u_id=$_SESSION['user_no'];
$sql="DELETE FROM user_search_location WHERE user_id=$u_id and id=$loc_id";
if(mysql_query($sql)){
echo "Kayıtlı Konumunuz Silindi...";
}else{
echo "Silme İşlemi Sırasında Bir Sorun Oluştu...";
}