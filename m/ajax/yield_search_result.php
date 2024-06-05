<?php
session_start();
if(!isset($_SESSION['user_no']) or !isset($_SESSION['user_full_name']) or !isset($_SESSION['user_type']) or !isset($_SESSION['mail_adress']) )
{
die('{"press":0,"text":"Bir Sorun Oluştu... Lütfen Sayfayı Yenileyiniz..."}');
 }
$u_id=$_SESSION['user_no'];

include("sql.php");
$connect=new connect();
$connect->sql_connect_db();
	if(isset($_POST['page_no']))
		{
			$page_no=(int)$_POST['page_no'];
			if($page_no==null )
				{
				die('Hata! Lütfen Sayfayı Tekrar Yükleyiniz!');
				}
		}else
		{
			$page_no=1;
		}	
	if(isset($_POST['props']) and isset($_POST['yerok_name']) and isset($_POST['lat']) and isset($_POST['long']) and isset($_POST['position']) and isset($_POST['live_position'])){	
		$props=mysql_real_escape_string(htmlspecialchars(trim($_POST['props'])));
		$yerok_id=mysql_real_escape_string(htmlspecialchars(trim((int)$_POST['yerok_name'])));
		$lat=mysql_real_escape_string(htmlspecialchars(trim($_POST['lat'])));
		$long=mysql_real_escape_string(htmlspecialchars(trim($_POST['long'])));
		$position=mysql_real_escape_string(htmlspecialchars(trim((int)$_POST['position'])));
		$live_position=mysql_real_escape_string(htmlspecialchars(trim((int)$_POST['live_position'])));
	}else{
		die('{"press":0,"text":"Bir Sorun Oluştu... Lütfen Sayfayı Tekrar Yükleyeniniz..."}');
	}
$yerok_handler=new yerok_id_handler();
$yerok_handler->yerok_id_control();
$yerok_handler->get_yerok_shop_id($yerok_id,0);
$yerok_handler->search_right_left_id();
$yerok_handler->props_prepare($props);
$yerok_handler->select_table();

	if($position==0)
		{
			$yerok_handler->yeroks_search($lat,$long,$page_no);
		}else
		{
			$yerok_handler->yeroks_search_in_place($lat,$long,$page_no,$position);
		}

		$sqll="INSERT INTO search_data(u_id,latitude,longitude,y_name_id,props,s_time,page_no,place_id,live_position) VALUES('$u_id','$lat','$long','$yerok_id','$props',now(),'$page_no','$position','$live_position')";
		$sqll=mysql_query($sqll) or die(mysql_error());

?>

