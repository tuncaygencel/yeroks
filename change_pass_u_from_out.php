<?php
//error_reporting(null);

include("ajax/kjasafhfjaghl.php");
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
						$sql="DELETE FROM change_pass_data WHERE id='$this->change_pass_id' ";
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
if(isset($_GET['data']))
	{
	$data=mysql_real_escape_string(htmlspecialchars(trim($_GET['data'])));
	if($data!='')
		{
		
		$pass=new pass_change_u_form_not_login();
	
	$pass->set_data($data);
			if($pass->data_control())
				{
					$result='1';
				}else
				{
					$result='2';
				}
		}else
		{
		$result='3';
		}
	}else
	{
	$result='4';
	}
?>
<meta http-equiv="Content-Type" content="text/HTML"; charset="utf-8" />
<html>
<head>
<title>
Yeroks Şifre Değişimi
</title>
<link rel="shortcut icon" href="visual_files/yeroks_icon.png" />
<meta name = "viewport" content="width=320", user-scalable=0 >
<link href="m/css/index.css" type="text/css" rel="stylesheet"/>
<script type="text/javascript" src="scripts/jquery-1.11.1.min.js"></script>
</head>
<script type="text/javascript">
		function send_datas(){
	var pass_change_data=$("#register_form").serialize(); 
	$('#register_content').html('<img src="img_files/cycle_loading.gif" style="height:60px; margin-top:48px;">');
	$.ajax({
		type:'POST',
		url:'ajax/unforgetten_u_pass_change.php',	
		data: pass_change_data,
		success: function(result) {
			 	$('#register_content').html(result);
			}
});
}
</script>
</head>
<body style="background-color: rgb(236, 236, 236);">
<div id="banner" >
<a href="http://www.yeroks.com">
<img src="img_files/b_l_b.png" id="logo_big" height='44'/>
</a>
</div>
<noscript>
<div id="noscript">
Lütfen Tarayıcınızın Javascript Ayarlarını Aktif Edin.</br>
Sistemimiz Bol Miktarda Javascript Kullanır.
</div>
</noscript>


<div class="contents_center">
<div id="main">	
<div id="userscontents">
<?php
if($result=='1')
	{
?>
<div id="register_content" style="text-align:center;">
<div id="title">Yeni Şifrenizi Giriniz</div>
<form id="register_form">
<input type="hidden" name="change_pass_data" value="<?php echo $data;?>" />
<input type="password" placeholder="Yeni Şifre" style="width:278px" name="change_pass"  id="register_pass_input" />
<input type="password" placeholder="Yeni Şifre(Tekrar)" style="width:278px" name="re_change_pass" id="register_pass_input" />
<div onclick="send_datas()" id="new_user_button"> Gönder</div>
</form>
</div>
</div>
</div>
</div>
<?php
}elseif($result=='2')
{
echo "<div id=\"login_result\">Aktif bir Şifre Değiştirme İsteğiniz Yok. Şifrenizi Hatırlamıyorsanız Lütfen Uygulamayı Kullanarak Yeni Bir İstek Oluşturunuz...</div>";
}else
{
echo "<div id=\"login_result\">Lütfen Doğru Bir Linke Tıkladığınızdan Emin Olunuz...</div>";
}
?>
</body>
</html>