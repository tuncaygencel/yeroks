<?php
session_start();
include("kjasafhfjaghl.php");
		$connect=new connect();
		$connect->sql_connect_db();

$mail=new change_pass();
$shop_name="";
$shop_id=43111;
$shop_mail="";
$mail->shop_mail_sent($shop_name,$shop_id,$shop_mail);






?>