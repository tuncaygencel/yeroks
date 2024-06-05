<?php
session_start();
if(isset($_SESSION['user_type'])  and isset($_SESSION['user_no']) and isset($_SESSION['mail_adress']))
	{
if($_SESSION['user_type']!=2)
	{
	die("Bu İşleme Yetkiniz Bulunmamakta...");
	}
	
	include("kjasafhfjaghl.php");
		$shop_id=$_SESSION['user_no'];
		$connect=new connect();
		$connect->sql_connect_db();
		$show_y_class=new show_yield();
		$show_y_class->show($shop_id);	
	}else
	{
	echo "Lütfen Giriş Yapınız..."; 
	}











?>