<?php
	include("sql.php");
	$connect=new connect();
	$connect->sql_connect_db();
	$place=new place();
	if(isset($_POST['lat']) and isset($_POST['lng']))
	{
	
		$latitude=mysql_real_escape_string(htmlspecialchars(trim($_POST['lat'])));
		$longitude=mysql_real_escape_string(htmlspecialchars(trim($_POST['lng'])));
		$latitude=round($latitude,6);
		$longitude=round($longitude,6);	
		$place->search_place($latitude,$longitude);	

	}else
	{
	return false;
	}		
?>
