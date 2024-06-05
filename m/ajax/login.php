<?php

include("sql.php");
session_start();
$connect=new connect();
$connect->sql_connect_db();
if(isset($_POST['email_input']) and isset($_POST['pass_input']))
	{
	$u_mail=mysql_real_escape_string(htmlspecialchars(trim($_POST['email_input'])));
	$u_pass=mysql_real_escape_string(htmlspecialchars(trim($_POST['pass_input'])));
	}else
	{
	echo "{\"result\":0,\"text\":\"Lütfen Sayfayı Yenileyiniz...\"}";
	}
$login=new log_in();

if($login->login_func($u_mail,$u_pass,1)== true )
{
echo "{\"result\":1,\"text\":\"\"}";
}else{
echo "{\"result\":0,\"text\":\"".$login->error_type_return()."\"}";
}

?>






