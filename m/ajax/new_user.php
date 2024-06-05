<?php
include("sql.php");
$connect=new connect();
$connect->sql_connect_db();
if(!isset($_POST['register_name_input']) or !isset($_POST['register_mail_input']) or !isset($_POST['register_pass_input'])) 
	{
	echo "{\"result\":0,\"explain\":\"Uppps...Bir Hata Oluştu... Lütfen Uygulamayı Tekrar Açınız...\"}";
	die();
	}
	
$u_join_full_name=mysql_real_escape_string(htmlspecialchars(trim($_POST['register_name_input'])));
$u_join_mail=mysql_real_escape_string(htmlspecialchars(trim($_POST['register_mail_input'])));
$u_join_password=mysql_real_escape_string(htmlspecialchars(trim($_POST['register_pass_input'])));

$i=0;

if($u_join_full_name==""){
$i=$i+1;
}
if($u_join_mail==""){
$i=$i+1;
}
if($u_join_password==""){
$i=$i+1;
}

	if($i>1)
	{
		echo "{\"result\":0,\"explain\":\"Lütfen Bütün Alanları Doldurunuz...\"}";
	}elseif($i==1)
	{
	if($u_join_full_name=="")
		{
			echo "{\"result\":0,\"explain\":\"Lütfen Tam İsminizi Giriniz...\"}";
		}
	
	if($u_join_mail=="")
		{
			echo "{\"result\":0,\"explain\":\"Lütfen E-Postanızı Giriniz...\"}";
		}
	if($u_join_password=="")
		{
			echo "{\"result\":0,\"explain\":\"Lütfen Şifrenizi Giriniz...\"}";
		}

	}elseif($i=='0')
	{
		if(strlen($u_join_full_name)< '4')
			{
				echo "{\"result\":0,\"explain\":\"Lütfen isminizi 3 karakterden daha fazla olacak şekilde giriniz...\"}";
			}elseif(strlen($u_join_password) < '6' )
			{
				echo "{\"result\":0,\"explain\":\"Lütfen Şifrenizi 5 Karakterden Fazla Olacak Şekilde Giriniz...\"}";
			}else
			{
				if (!preg_match('#^[a-z0-9.!\#$%&\'*+-/=?^_`{|}~]+@([0-9.]+|([^\s\'"<>]+\.+[a-z]{2,6}))$#si', $u_join_mail))
					{ 
				echo "{\"result\":0,\"explain\":\"Lütfen Geçerli Bir E-Posta Adresi Giriniz...\"}";
					}
					else
					{
					$new_u_insert_db=new insert_new_u_db();
					
						if($new_u_insert_db->control_u_mail($u_join_mail)>0)
							{
								echo "{\"result\":0,\"explain\":\"Bu E-postanızla İlgili Bir Hesap Bulunmakta. Şifrenizle Hesabınıza Ulaşabilirsiniz...\"}";
							}else
							{
								$new_u_insert_db->u_insert_new_user($u_join_full_name,$u_join_mail,$u_join_password,2 );
							}
					
					
					
					}
			
			};
	}

?>
