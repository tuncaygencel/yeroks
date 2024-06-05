<?php
session_start();
include("kjasafhfjaghl.php");
$connect=new connect();
$connect->sql_connect_db();

if(!isset($_POST['pass']))
	{
	die("{\"result\":2}");
	//2 means post not gotten
	}
$pass=mysql_real_escape_string(htmlspecialchars(trim($_POST["pass"])));

if($pass=="")
	{
	die("{\"result\":3}");
	//3 means pass not written
	}

if(isset($_SESSION['user_type'])  and isset($_SESSION['user_no']) and isset($_SESSION['mail_adress']) and isset($_SESSION['login']))
	{
if($_SESSION['user_type']!=2)
	{
	die("{\"result\":0}");
	}
	
	$s_id=$_SESSION['user_no'];
	$sql="SELECT s_password FROM shop WHERE s_id='$s_id'";	
	$sql=mysql_query($sql) or die(mysql_error());
	if(mysql_num_rows($sql)=='1')
		{
		$res=mysql_fetch_assoc($sql);
		$pass=sha1($pass);
			if($res['s_password']!=$pass)
				{
				echo "{\"result\":5}";
				//5 means entered password is wrong
				}else
				{
				echo "{\"result\":1}";
				$_SESSION['login']=1;
				//1 means entered password is true
				}
		
		}else
		{
		echo "{\"result\":4}";
		//4 means session shop_id wrong
		}
	
	}else
	{
	echo "{\"result\":0}";
	//0 means session not created
	}
	
	
	
	
?>