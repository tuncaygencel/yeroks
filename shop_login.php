<?php
//page_id=10
session_start();
include("ajax/kjasafhfjaghl.php");
$login=new log_in();
$connect=new connect();
$connect->sql_connect_db();
$connect->enter_active(10,1,0,0,0);
//form data get from in server
if(isset($_SESSION['f']))
	{
	$form_data=$_SESSION['f'];
	}else
	{
	$login->create_form_data();
	$form_data=$_SESSION['f'];
	}

	if(!isset($_POST['s_mail']) or !isset($_POST['s_password']) or !isset($_POST['f']))
	{
		$s_mail='';
		$s_pass='';
		$f='';
	}else
	{
		$s_mail=mysql_real_escape_string(htmlspecialchars(trim($_POST['s_mail'])));
		$s_pass=mysql_real_escape_string(htmlspecialchars(trim($_POST['s_password'])));
		$f=mysql_real_escape_string(htmlspecialchars(trim($_POST['f'])));
	}
	

if( $form_data==$f and $login->login_func($s_mail,$s_pass,2)== true)
{
echo '<meta http-equiv="refresh" content=0;URL=shop_profile.php>';
}else{
	$login->create_form_data();
	$recent_form_data=$_SESSION['f'];
?>
<meta http-equiv="Content-Type" content="text/HTML"; charset="utf-8" />
<html>
<head>
<title>
Yeroks
</title>
<link rel="shortcut icon" href="img_files/yeroks_icon.png"/>
<link href="css/shop_index.css" type="text/css" rel="stylesheet"/>
<meta name = "viewport" content="width=320, user-scalable=0" />
<script type="text/javascript" src="scripts/jquery-1.11.1.min.js"></script>
</head>
<body>
<div id="banner">
<div class="logo">
<a href="shop_index.php">
<img src="img_files/b_l_b.png" style="margin:auto;height:51px;border:0px;"/>
</a>
</div>
</div>
<div class="contents">
<div class="login_contents_center">
<noscript>
<div id="noscript">
Lütfen Tarayıcınızın Javascript Ayarlarını Aktif Edin.</br>
Sistemimiz Bol Miktarda Javascript Kullanır.
</div>
</noscript>
<div id="login_area">
<form action="shop_login.php" method="POST">
<table align="right">
<tbody>
<tr><td id="title">Mağaza Girişi</td></tr>
<tr><td><input id="login_mail" placeholder="E-Posta" class="login_input" type="text" name="s_mail" maxlength="50" color="#56474C" aria-required="1"  /></td></tr>
<tr><td><input id="login_pass" placeholder="Şifre"  class="login_input" type="password"  name="s_password" maxlength="30" aria-required="1"/></td></tr>
<tr><td><input type="submit" name="Login" id="login_button" align="right" value="Giriş Yap" /></td></tr>
<tr><td colspan="2" align="left" style="font-size:14px;" class="login_input_text"><input type="checkbox" name="s_remember">Beni Hatırla - 
<a class="login_input_text" style="font-size:14px;"  href="forgetten_password.php?type=shopper">Şifremi Unuttum</a></td></tr>
<input type="hidden" name="f" value="<?php echo $recent_form_data;?>" />
</tbody>
</table>
</form>
</div>
<div id="login_result">
<?php
if($form_data!=$f)
	{
	echo "Bir Hata Oluştu...Lütfen Tekrar Giriş Yapınız...";
	}else
	{
	$login->error_type_press();
	}
?>
</div>
<div id="new_shop_button_area">Mağazan Halen Kayıtlı Değil mi? <br>
<a href="shop_index.php" id="new_shop_button">
Yeni Mağaza Kayıt
</a>
</div>
<div id="help_info_area">
 <div id="c_right">2016 &#169 Yeroks İstanbul</div>
 <div id="info_butts">
 <a href="shop_index.php">Mağaza Girişi</a><a href="about.php">Hakkımızda</a><a href="product_list.php">Ürün Çeşitleri</a>
</div>
 </div>
</div>
</div>
</body>
</html>
<?php
}
?>





