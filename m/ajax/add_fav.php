<?php
//magazanin urunleri listelendiginde ozel olarak altta ayrintili sekilde listelenmesini saglayan sayfa
include("sql.php");
session_start();
if(isset($_SESSION['user_no']))
	{
	if(isset($_POST['data']) and isset($_POST['type']) and isset($_POST['type_data']) )
		{
		$user_id=$_SESSION['user_no'];
		$connect=new connect();
		$connect->sql_connect_db();
		$fav_id=mysql_real_escape_string(htmlspecialchars(trim((int)$_POST['data'])));
		$type_data=mysql_real_escape_string(htmlspecialchars(trim((int)$_POST['type_data'])));
		$type=mysql_real_escape_string(htmlspecialchars(trim((int)$_POST['type'])));
		$fav=new favorite();
		
		$fav->get_values($fav_id,$type,$user_id);
			if($type==2){
			$fav->fav_s_operation();
			}elseif($type==3)
			{
			$fav->fav_p_operation();
			}elseif($type==4){
			$fav->fav_y_operation($user_id,$type_data,$fav_id);
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