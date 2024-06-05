<?php
//page_id=11
session_start();
include("ajax/sql.php");
$connect=new connect();
$connect->sql_connect_db();
$connect->enter_active(11,1,0,0,0);
$login=new log_in();
$type=0;
if($login->is_login())
	{
	if($_SESSION['user_type']==1){
		die('<meta http-equiv="refresh" content=0;URL=profile.php>');
		}
	}
	$redirect="";
	if(isset($_GET['redirect']))
	{
	$redirect=mysql_real_escape_string(htmlspecialchars(trim($_GET['redirect'])));
	}
	if(isset($_GET['type']))
	{
	$type=mysql_real_escape_string(htmlspecialchars(trim($_GET['type'])));
	}
?>
<!DOCTYPE html>
<meta http-equiv="Content-Type" content="text/HTML"; charset="utf-8" />
<html>
<head>
<title>
Yeroks'a Hosgeldiniz...
</title>
<meta name = "viewport" content="width=320", user-scalable=0 >
<script type="text/javascript" src="script/jquery-1.9.1.min.js" ></script>
<link rel="shortcut icon" href="image/yeroks_icon.png">
<link href="css/index.css" type="text/css" rel="stylesheet"/>
<script>
function get_login_form() {	
  $( "#login" ).animate({ top: "90"  }, 400 );
  exit_forget_pass_form();
  $( "#main" ).hide();
};

function exit_login_form() {	
  $( "#login" ).animate({ top: "130%"  }, 500 );
  $( "#main" ).show();
};

function forget_pass_form() {	
  $( "#forget_pass" ).animate({ top: "90"  }, 400 );
   exit_login_form();
   $( "#main" ).hide();
   
};
function exit_forget_pass_form() {	
  $( "#forget_pass" ).animate({ top: "130%"  }, 500 );
  $( "#main" ).show();
};

$(document).ready(function (){
		$('#new_user_button').click(function (){
	var u_form_join=$("#register_form").serialize(); 
	$.ajax({
		type:'POST',
		dataType: 'json',
		url:'ajax/new_user.php',	
		data: u_form_join,
		success: function(u_join) {
				if(u_join.result==1)
					{
					$('#register_result').html(u_join.explain);
					var r_mail= document.getElementById('register_mail_input').value;
					var r_pass = document.getElementById('register_pass_input').value;
					document.getElementById('email_input').value=r_mail;
					document.getElementById('pass_input').value=r_pass;
					$('#login_button').click();
					}else
					{
					$('#register_result').html(u_join.explain);
					}
		}
});
})
});

function change_pass_mail(){
		var u_forget_mail= $("#forget_pass_input").val(); 
		$.ajax({
			type:'POST',
			url:'ajax/forget_pass_mail.php',	
			data:'forget_pass_input='+ u_forget_mail,
			success: function(forget_pass) {
			 	$('#forget_pass').html(forget_pass);
				}
		});
		};
//type=1 display=forget pass form
</script>
</head>
<body <?php if($type==1){ echo "onload=\"forget_pass_form()\"";} ?> >
<div id="banner" >
<a href="http://www.yeroks.com">
<img src="image/b_l_b.png" id="logo_big" height='44'/>
</a>
</div>
<div class="contents_center">
<div onclick="get_login_form()"  id="login_display">
Giriş Yap
</div>
<div id="main">	
<div id="userscontents">
<div id="register_content">
<div id="title">Kaydol</div>
<form id="register_form">
<input type="text" placeholder="Tam isim" name="register_name_input" id="register_name_input"  >
<input type="text" placeholder="E-Posta" name="register_mail_input" id="register_mail_input"  >
<input type="password" placeholder="Şifre" name="register_pass_input" id="register_pass_input" />
<div id="new_user_button" >
Kaydol
</div>
</form>
<div id="register_result">

</div>

</div>
</div>
</div>
<div id="help_info_area">
 <div id="c_right">2016 &#169 Yeroks İstanbul</div>
 <div id="info_butts">
 <a href="../shop_index.php">Mağaza Girişi</a><a href="../about.php">Hakkımızda</a><a href="../product_list.php">Ürün Çeşitleri</a>
</div>
 </div>
</div>

<div id="login">
<div id="exit" onclick="exit_login_form()" >
	<img src="image/close.png" height=26px  >
</div>
<div id="title">Kullanıcı Girişi</div>
<form name="login_form" id="login_form" method="POST" action="login.php<?php if($redirect!=""){echo "?redirect=".$redirect;}?>" >
<input type="text" placeholder="E-Posta" name="email_input" id="email_input"  >
<input type="password" placeholder="Şifre" name="pass_input"  id="pass_input" />
<input type="submit" id="login_button" value="Giriş Yap" />
<div id="login_result">
</div>
</form>
<div id="bottom_area" onclick="forget_pass_form()">
Şifremi unuttum
</div>
</div>
<div id="forget_pass">
<div id="exit" onclick="exit_forget_pass_form()" >
	<img src="image/close.png" height=26px  >
</div>
<div id="title">Şifre Yenileme</div>
<div id="register_content">
<input type="text" placeholder="E-Posta Adresiniz..." name="forget_pass_input" id="forget_pass_input"  >
<div id="forget_button" onclick="change_pass_mail();" >
Gönder
</div>
<div id="forget_result">

</div>

</div>

</div>


</body>
</html>


