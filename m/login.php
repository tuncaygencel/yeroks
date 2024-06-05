<?php
//page_id=12
session_start();
include("ajax/sql.php");
$connect=new connect();
$connect->sql_connect_db();
$connect->enter_active(12,1,0,0,0);
if(!isset($_SESSION['user_no']) or !isset($_SESSION['user_full_name']) or !isset($_SESSION['user_type']) or !isset($_SESSION['mail_adress']) )
{
$login=new log_in();
$login->is_login();
}
if(isset($_SESSION['user_type'])  and isset($_SESSION['user_no']) and isset($_SESSION['mail_adress']) and isset($_SESSION['user_full_name']))
	{
		if($_SESSION['user_type']==1){
			die( '<meta http-equiv="refresh" content=0;URL=profile.php>');
		}
	}
	
	if(isset($_POST['email_input']) and isset($_POST['pass_input']))
	{
	$u_mail=mysql_real_escape_string(htmlspecialchars(trim($_POST['email_input'])));
	$u_pass=mysql_real_escape_string(htmlspecialchars(trim($_POST['pass_input'])));
	}else
	{
	$u_mail="";
	$u_pass="";
	}
	$login=new log_in();
	
	$redirect="";
	if(isset($_GET['redirect']))
	{
	$redirect=mysql_real_escape_string(htmlspecialchars(trim($_GET['redirect'])));
	}
if($login->login_func($u_mail,$u_pass,1)== true )
{
	if($redirect!=""){
	echo '<meta http-equiv="refresh" content=0;URL='.$redirect.'>';
	}else{
	echo '<meta http-equiv="refresh" content=0;URL=profile.php>';
	}
}else{
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
function get_register_form() {	
  $( "#register" ).animate({ top: "80"  }, 400 );
  $( "#main" ).hide();
};

function exit_register_form() {	
  $( "#register" ).animate({ top: "130%"  }, 500 );
  $( "#main" ).show();
};

function forget_pass_form() {	
  $( "#forget_pass" ).animate({ top: "80"  }, 400 );
   $( "#main" ).hide();
};
function exit_forget_pass_form() {	
  $( "#forget_pass" ).animate({ top: "130%"  }, 500 );
  $( "#main" ).show();
};

$(document).ready(function (){
		$('#new_user_button_1').click(function (){
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

</script>
</head>
<body>
<div id="banner" >
<a href="http://www.yeroks.com">
<img src="image/b_l_b.png" id="logo_big" height='36'/>
</a>
</div>
<div class="contents_center">
<div id="main" style="margin-bottom:20px">	
<div id="userscontents">
<div id="title">Giriş Yap</div>
<form name="login_form" id="login_form" method="POST" action="login.php<?php if($redirect!=""){echo "?redirect=".$redirect;}?>" >
<input type="text" placeholder="E-Posta" name="email_input" id="email_input"  >
<input type="password" placeholder="Şifre" name="pass_input"  id="pass_input" />
<input type="submit" id="login_button" value="Giriş Yap" />
<div id="login_result">
<?php 
$login->error_type_press();
?>
</div>
</form>
</div>
</div>
<div id="bottom_area" onclick="window.location='index.php?type=1'">
Şifremi unuttum
</div>
<div id="new_user_button" onclick="window.location='index.php'" >
Kaydol
</div>

<div id="help_info_area">
 <div id="c_right">2016 &#169 Yeroks İstanbul</div>
 <div id="info_butts">
 <a href="../shop_index.php">Mağaza Girişi</a><a href="../about.php">Hakkımızda</a><a href="../product_list.php">Ürün Çeşitleri</a>
</div>
 </div>



</div>



</body>
</html>
<?php
}
?>

