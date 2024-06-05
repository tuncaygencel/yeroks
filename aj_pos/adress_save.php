<?php
session_start();
include("../ajax/kjasafhfjaghl.php");
$connect=new connect();
if(isset($_SESSION['user_type'])  and isset($_SESSION['user_no']) and isset($_SESSION['mail_adress']))
	{
	if($_SESSION['user_type']!=2)
	{
	die("{\"problem\":1,\"explain\":\"Bu İşlem İçin Lütfen Giriş Yapınız\"}");
	}
	$connect->sql_connect_db();
	if(!isset($_POST['type']))
	{
	die("{\"problem\":1,\"explain\":\"Veri Gönderim Hatası. Lütfen Sayfayı Tekrar Yükleyiniz...\"}");
	}else{
	$type=mysql_real_escape_string(htmlspecialchars(trim((int)$_POST['type'])));
	}
	$shop_id=$_SESSION['user_no'];
	if($type==1)
	{
		if( !isset($_POST['adress']) or !isset($_POST['tel']))
			{
			die("{\"problem\":1,\"explain\":\"Bir Hata Oluştu...\"}");
			}else
			{
				$adress=substr(mysql_real_escape_string(htmlspecialchars(trim($_POST['adress']))), 0, 400);
				$tel=substr(mysql_real_escape_string(htmlspecialchars(trim($_POST['tel']))), 0, 80);
				
				if($adress=='')
					{
					die("{\"problem\":1,\"explain\":\"Lütfen Zorunlu Bütün Kısımları Doldurunuz...\"}");
					}else{
						$sql="SELECT s_id FROM shop_adress WHERE s_id='$shop_id'";
						$sql=mysql_query($sql) or die(mysql_error());
							if(mysql_num_rows($sql)>0)
								{
								$sql1="UPDATE shop_adress SET adress_text='$adress' , tel_number='$tel' WHERE s_id='$shop_id' LIMIT 1";
								}else
								{
								$sql1="INSERT INTO shop_adress(s_id ,adress_text,tel_number) VALUES('$shop_id','$adress','$tel')";
								}
								if(mysql_query($sql1))
									{
									echo "{\"problem\":0,\"explain\":\"Adress Kaydı Gerçekleştirildi...\"}";
									}else
									{
									die("{\"problem\":1,\"explain\":\"Adres Kaydı Sırasında Bir Hata Oluştu...\"}");
									}		
					}
			}
		}elseif($type==2)
		{
		if( !isset($_POST['adress']) or !isset($_POST['tel']))
			{
			die("{\"problem\":1,\"explain\":\"Bir Hata Oluştu...\"}");
			}else
			{
			$tel=substr(mysql_real_escape_string(htmlspecialchars(trim($_POST['tel']))), 0, 80);
			if(strlen($tel)>9 or strlen($tel)==0)
					{
						$connect->sql_connect_db();
						$sql="SELECT s_id FROM shop_adress WHERE s_id='$shop_id'";
						$sql=mysql_query($sql) or die(mysql_error());
							if(mysql_num_rows($sql)>0)
								{
								$sql1="UPDATE shop_adress SET adress_text='' , tel_number='$tel' WHERE s_id='$shop_id' LIMIT 1";
								}else
								{
								$sql1="INSERT INTO shop_adress(s_id ,adress_text,tel_number) VALUES('$shop_id','','$tel')";
								}
								if(mysql_query($sql1))
									{
									echo "{\"problem\":0,\"explain\":\"Telefon Kaydı Gerçekleştirildi...\"}";
									}else
									{
									die("{\"problem\":1,\"explain\":\"Telefon Kaydı Sırasında Bir Hata Oluştu...\"}");
									}
					}elseif(strlen($tel)<10 and strlen($tel)>0){
					die("{\"problem\":1,\"explain\":\"Lütfen Telefon Numaranızı Tam Olarak Giriniz...\"}");
					}else{
					echo "{\"problem\":0,\"explain\":\"Telefon Numara Kayıt İşlemi Geçiliyor...\"}";
					}
			}
		}else{
		die("{\"problem\":1,\"explain\":\"Veri Gönderim Hatası. Lütfen Sayfayı Tekrar Yükleyiniz...\"}");
		}
	}else
	{
	die("{\"problem\":1,\"explain\":\"Bu İşlem İçin Lütfen Giriş Yapınız\"}");
	}
?>