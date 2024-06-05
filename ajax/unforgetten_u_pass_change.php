<?php
include("kjasafhfjaghl.php");
class pass_change_u_form_not_login
				{
					private $user_id;
					private $data;
					private $change_pass_id;
					
					public function set_data($data)
						{
						$this->data=$data;
						}
						
					public function data_control()
						{
						$sql="SELECT*FROM change_u_pass_data WHERE change_pass_data='$this->data' ";
						$sql=mysql_query($sql) or die("HATA!");
						if(mysql_num_rows($sql)==1)
							{
								$res=mysql_fetch_assoc($sql);
								if(self::get_time_diff($res['sent_time_to_mail'])<1500)
									{
									$this->change_pass_id=$res['id'];
									$this->user_id=$res['user_id'];
									return true;
									}else
									{
									return false;
									}
							}else
							{
								return false;
							}
						}
				
					private function get_time_diff($sent_time)
						{
						$current_time=time();
						$time_diff=$current_time-$sent_time;
						return $time_diff;
						}
						
					public function set_pass($password,$type)
						{ 
							if($type==1)
							{
							$sql="UPDATE user SET u_password='$password' WHERE u_id=' $this->user_id ' LIMIT 1";
							}elseif($type==2){
							$sql="UPDATE shop SET s_password='$password' WHERE s_id=' $this->user_id ' LIMIT 1";
							}else{
							return false;
							}
						
						if(mysql_query($sql) or die(mysql_error()))
							{
							return true;
							}else
							{
							return false;
							}
						}
					public function set_change_pass_table()
						{
						$sql="DELETE FROM change_u_pass_data WHERE id='$this->change_pass_id' ";
						if(mysql_query($sql))
							{
							return true;
							}else
							{
							return false;
							}
						
						}

				};
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

	$pass=new pass_change_u_form_not_login();
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
						$type=1;
						if($pass->set_pass($password,$type))
							{
								$pass->set_change_pass_table();
									echo "<div id=\"login_result\">Şifreniz Değiştirildi...</div>";
							}else
							{
							echo "<div id=\"login_result\"><center>Hata oluştu.Lütfen Tekrar Deneyiniz...</center></div>";
							}
						
						}
				}else
				{
					echo "<div id=\"login_result\"><center>Aktif bir Şifre Değiştirme İsteğiniz Yok. Şifrenizi Hatırlamıyorsanız Lütfen Uygulamayı Kullanarak Yeni Bir İstek Oluşturunuz...</div>";
				}	
	}else
	{
	echo "Bir Hata Oluştu. Lütfen Tekrar Deneyiniz...";
	}

?>