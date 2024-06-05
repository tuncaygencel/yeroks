<?php
//error_reporting(null);
include("ajax/kjasafhfjaghl.php");
$connect=new connect();
$connect->sql_connect_db();
if(isset($_GET['data']))
	{
	$data=mysql_real_escape_string(htmlspecialchars(trim($_GET['data'])));
	if($data!='')
		{
		
		$pass=new pass_change_form_not_login();
	
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
<link href="m/css/index.css" type="text/css" rel="stylesheet"/>
<script type="text/javascript" src="scripts/jquery-1.11.1.min.js"></script>
</head>
<script type="text/javascript">
		function send_datas(){
	var pass_change_data=$("#register_form").serialize(); 
	$('#register_content').html('<img style=\"margin-left:80px;\" src="visual_files/small_loading.gif"/>');
	$.ajax({
		type:'POST',
		url:'ajax/unforgetten_pass_change.php',	
		data: pass_change_data,
		success: function(result) {
			 	$('#register_content').html(result);
			}
});
}
</script>
</head>
<body>
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
echo "<div class=\"login_result\"><center>Aktif bir Şifre değiştirme isteğiniz Yok. Şifrenizi hatırlamıyorsanız şifrenizi değiştirmek için 
</br><a href=\"../forgetten_password.php\">TIKLAYINIZ</a> </center></div>";
}else
{
echo "<div class=\"login_result\"><center>Hata Oluştu.Lütfen Sonra Tekrar Deneyiniz...</center></div>";
}
?>
</div>

</div>

</div>
</body>
</html>