<?php
//error_reporting(null);
include("kjasafhfjaghl.php");
$connect=new connect();
$connect->sql_connect_db();

if(isset($_POST['change_pass_data']))
	{
	$data=mysql_real_escape_string(htmlspecialchars(trim($_POST['change_pass_data'])));
	if(isset($_POST['change_pass']))
		{
		$password=mysql_real_escape_string(htmlspecialchars(trim($_POST['change_pass'])));
		}else{
		$password="";
		}
	if(isset($_POST['re_change_pass']))
		{
		$re_password=mysql_real_escape_string(htmlspecialchars(trim($_POST['re_change_pass'])));
		}else{
		$re_password="";
		}
		
	$pass=new pass_change_form_not_login();
	$pass->set_data($data);
	$user_id=$pass->data_control();
			if($user_id)
				{
					if(strlen($password)<'6')
						{
						?>
<div id="title">Yeni Şifrenizi Giriniz</div>
<form id="register_form">
<input type="hidden" name="change_pass_data" value="<?php echo $data;?>" />
<input type="password" placeholder="Yeni Şifre" style="width:278px" name="change_pass" value="<?php echo $password;?>"  id="register_pass_input" />
<input type="password" placeholder="Yeni Şifre(Tekrar)" style="width:278px" name="re_change_pass" id="register_pass_input" />
<div onclick="send_datas()" id="new_user_button"> Gönder</div>
</form>	
<?php
						echo "<div id=\"login_result\">Lütfen şifrenizi 5 karakterden fazla olacak şekilde giriniz</div>";
						}elseif($password!=$re_password)
						{
						?>
<div id="title">Yeni Şifrenizi Giriniz</div>
<form id="register_form">
<input type="hidden" name="change_pass_data" value="<?php echo $data;?>" />
<input type="password" placeholder="Yeni Şifre" style="width:278px" name="change_pass" value="<?php echo $password;?>"  id="register_pass_input" />
<input type="password" placeholder="Yeni Şifre(Tekrar)" style="width:278px" name="re_change_pass" id="register_pass_input" />
<div onclick="send_datas()" id="new_user_button"> Gönder</div>
</form>	
						<?php
						echo "<div id=\"login_result\">Şifrenizle tekrar girdiğiniz şifre birbiriyle uyuşmuyor.Lütfen tekrar girdiğiniz şifrenizi kontrol ediniz</div>";
						}else
						{
						$password=sha1($password);
						$type=2;
						if($pass->set_pass($password,$type))
							{
								$pass->set_change_pass_table();
									echo "<div id=\"right_result\">Şifreniz Değiştirildi.</div>";
							}else
							{
							echo "<div id=\"login_result\"><center>Hata oluştu.Lütfen Tekrar Deneyiniz...</center></div>";
							}
						
						}
				}else
				{
					echo "<div id=\"login_result\"><center>Aktif bir Şifre değiştirme isteğiniz Yok. Şifrenizi hatırlamıyorsanız şifrenizi değiştirmek için 
					</br><a href=\"forgetten_password.php\">TIKLAYINIZ</a> </center></div>";
				}	
	}else
	{
	echo "Bir hata oluştu.Lütfen tekrar deneyiniz...";
	}

?>