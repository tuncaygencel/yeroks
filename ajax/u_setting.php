<?php
session_start();
include("kjasafhfjaghl.php");
$connect=new connect();
$connect->sql_connect_db();
$name=null;
$new_pass=null;
$pass=null;
$mail=null;
$user_type;
$user_id;
	if( isset($_SESSION['user_type']) and isset($_SESSION['user_no']) and isset($_SESSION['mail_adress']))
		{
	
				
				if(isset($_POST['set_u_full_name']))
					{
						$name=mysql_real_escape_string(htmlspecialchars(trim($_POST['set_u_full_name'])));
						if($name=="")
							{
							$name=null;
							}
					}
				if(isset($_POST['set_new_pass']))
					{
						$new_pass=mysql_real_escape_string(htmlspecialchars(trim($_POST['set_new_pass'])));
						if($new_pass=="")
							{
							$new_pass=null;
							}
					}
				
				if(isset($_POST['set_mail']))
					{
						$mail=mysql_real_escape_string(htmlspecialchars(trim($_POST['set_mail'])));
						if($mail=="")
							{
							$mail=null;
							}
					}
				
				if(isset($_POST['pass']))
					{
						$pass=mysql_real_escape_string(htmlspecialchars(trim($_POST['pass'])));
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
								$check->sent_user_type($user_type);
									if($check->check_id_pass())
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
		
		}else
		{
		echo "<b>-</b>   Bu İşlem İçin Giriş Yapmalısınız....";
		}

















?>
