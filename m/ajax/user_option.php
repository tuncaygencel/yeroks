<?php
include("sql.php");
session_start();
$connect=new connect();
$connect->sql_connect_db();
if(!isset($_POST['option_mail']) or !isset($_POST['option_pass']) or !isset($_POST['verif_pass'])) 
	{
	echo "Uppps...Bir Hata Oluştu... Lütfen Sayfayı Yenileyiniz...";
	die();
	}

if(!isset($_SESSION['user_no']) or !isset($_SESSION['user_full_name']) or !isset($_SESSION['user_type']) or !isset($_SESSION['mail_adress']) )
{
echo '<meta http-equiv="refresh" content=0;URL=index.php>';
}else
{


$name=null;
$new_pass=null;
$mail=null;
$pass=null;
if(isset($_POST['option_name']))
					{
						$name=mysql_real_escape_string(htmlspecialchars(trim($_POST['option_name'])));
						if($name=="")
							{
							$name=null;
							}
					}
				if(isset($_POST['option_pass']))
					{
						$new_pass=mysql_real_escape_string(htmlspecialchars(trim($_POST['option_pass'])));
						if($new_pass=="")
							{
							$new_pass=null;
							}
					}
				
				if(isset($_POST['option_mail']))
					{
						$mail=mysql_real_escape_string(htmlspecialchars(trim($_POST['option_mail'])));
						if($mail=="")
							{
							$mail=null;
							}
					}
				
				if(isset($_POST['verif_pass']))
					{
						$pass=mysql_real_escape_string(htmlspecialchars(trim($_POST['verif_pass'])));
						if($pass=="")
							{
							$pass=null;
							}
					}
				
				//process start
					if($name!=null or $new_pass!=null or  $mail!=null)
						{
							if($pass=='' or $pass==null)
								{
								echo "<b>-</b>   Lütfen Şu Andaki Şifrenizi Şifre Kısmına Giriniz...";
								}else
								{
								$user_type=$_SESSION['user_type'];
								$user_id=$_SESSION['user_no'];
								$check=new check_functions();
								$check->sent_user_id($user_id);
								$check->sent_sha1_pass($pass);
									if($check->check_user_id_pass())
										{
											$change=new change_user_info();
											$change->set_user_id($user_id);
											$change->set_user_mail($mail);
											$change->set_pass($new_pass);
											$change->set_sha1_pass();
											$change->set_user_name($name);
											
											if($name!=null)
												{
												$change->change_name();
												}
											
											if($new_pass!=null)
												{
												$change->change_pass();
												}
											if($mail!=null)
												{
												$change->change_mail();
												}

										}else
										{
										echo "<b>-</b>   Yanlış Şifre Girdiniz...";
										}
								
								}
						}else
						{
						echo "<b>-</b>   Herhangi Bir İşlem Yapılmadı...";

						}





}
?>