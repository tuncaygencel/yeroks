<?php
session_start();
include("kjasafhfjaghl.php");

if(isset($_SESSION['user_type'])  and isset($_SESSION['user_no']) and isset($_SESSION['mail_adress']))
	{
if($_SESSION['user_type']!=2)
	{
	die("Bu Ýþleme Yetkiniz Bulunmamakta...");
	}
			if(isset($_POST['marker_lat']) and isset($_POST['marker_long']) and isset($_POST['optimum_lat']) and isset($_POST['optimum_long']) and isset($_POST['optimum_accuracy']))
				{
				$connect=new connect();
				$connect->sql_connect_db();
				
				$marker_lat=mysql_real_escape_string(htmlspecialchars(trim($_POST['marker_lat'])));
				$marker_long=mysql_real_escape_string(htmlspecialchars(trim($_POST['marker_long'])));
				$optimum_lat=mysql_real_escape_string(htmlspecialchars(trim($_POST['optimum_lat'])));
				$optimum_long=mysql_real_escape_string(htmlspecialchars(trim($_POST['optimum_long'])));
				$optimum_accuracy=mysql_real_escape_string(htmlspecialchars(trim($_POST['optimum_accuracy'])));
				$shop_id=$_SESSION['user_no'];
				$req_data=$_SESSION['req_data'];
					
					$gps_func=new gps_request();
					if($gps_func->save_loc($marker_lat,$marker_long,$optimum_lat,$optimum_long,$optimum_accuracy,$shop_id,$req_data))
						{
						echo "{\"result\":1}";
						}else
						{
						echo "{\"result\":2}";
						}	
				}else
				{
				echo "{\"result\":3}";
				}
	}else
	{
	echo "{\"result\":5}";
	}




?>