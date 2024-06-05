<?php
//page_id=9
session_start();
$type=0;
include("ajax/kjasafhfjaghl.php");
$connect=new connect();
$connect->sql_connect_db();

$ref=0;
$page=9;
if(isset($_GET['ref']))
	{
	$ref=mysql_real_escape_string(htmlspecialchars(trim((int)$_GET['ref'])));
	}

if($ref==1){
//faceboook adverds
$page=90;
}
if($ref==10){
//google adverds
$page=91;
}

$connect->enter_active($page,1,0,0,0);
$login=new log_in();



if(isset($_GET['type']))
	{
	$type=mysql_real_escape_string(htmlspecialchars(trim((int)$_GET['type'])));
	}
if(isset($_SESSION['user_type'])  and isset($_SESSION['user_no']) and isset($_SESSION['mail_adress']) and $type!=1)
	{
		if($_SESSION['user_type']==2)
			{
			die('<meta http-equiv="refresh" content=0;URL=shop_profile.php>');
			}else{
			$type=1;
			}
	}
//type=2 means display register form
//type=1 means logout
if($type==1)
	{
		$_SESSION['user_no']="";
		$_SESSION['user_type']= "";
		$_SESSION['user_full_name']="";
		$_SESSION['mail_adress']="";
		$_SESSION['login']=0;
		session_unset();
	}
$login->create_form_data();
$form_data=$_SESSION['f'];
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
<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-41307627-1', 'auto');
  ga('send', 'pageview');

</script>
<script type="text/javascript">
function open_login_form()
	{
	$("#light_box_area").css("display","inline-block");
	$("#black_background").css("display","inline-block");
	}
function close_login_form()
	{
	$("#light_box_area").css("display","none");
	$("#black_background").css("display","none");
	}
function display_login_form()
	{
	$("#login_area").css("display","inline-block");
	$("#new_shop_button_area").css("display","inline-block");
	$("#shop_register").css("display","none");
	}
	$(document).ready(function (){
	$('#s_new_user_button').click(function (){
	var s_form_join=$("#register_form").serialize(); 
	$('#s_result').html('<img style=\"margin-left:0px;\" src="img_files/line_style_loading.gif"/>');
	$.ajax({
		type:'POST',
		url:'ajax/s_new_user.php',	
		dataType: 'json',
		data: s_form_join,
		success: function(s_join) {
			if(s_join.result===0)
				{
				$('#s_result').html(s_join.explain);
				}else
				{
				$('#s_result').html(s_join.explain);
				var new_user_mail=document.getElementById('new_user_mail').value;
				var new_user_pass=document.getElementById('new_user_pass').value;
				document.getElementById('login_mail').value=new_user_mail;
				document.getElementById('login_pass').value=new_user_pass;
				document.forms["shop_login_form"].submit();
				}
		}
});
})
});

$(window).on("load resize", function () {
    var $win = $(this);
    if($win.width() < 500){
        $(".des_holder").css("width","240px");
    } else {
        $(".des_holder").css("width","500px");
    }
}).resize();

</script>
</head>
<?php
if($type==2)
	{
	echo "<body onload=\"display_join_form()\">";
	}else
	{
	echo "<body>";
	}
?>
<div id="black_background" onclick="close_login_form()">
</div>
<div id="banner">
<div class="logo">
<a href="https://www.yeroks.com/">
<img src="https://www.yeroks.com/img_files/b_l_b.png" style="margin:auto;height:51px;border:0px;"/>
</a>
</div>
</div>
<noscript>
<div id="noscript">
Lütfen Tarayıcınızın Javascript Ayarlarını Aktif Edin.</br>
Sistemimiz Bol Miktarda Javascript Kullanır.
</div>
</noscript>

<div class="contents">
<div class="contents_center">
<?php
if($type==1)
	{
?>
<div id="logout-text">Oturumunuz Sonlandırıldı...</div>
<?php
	}
?>
<div id="light_box_area">
<div id="login_area">
<form action="shop_login.php" id="shop_login_form" method="POST">
<table align="right">
<tbody>
<tr><td id="title">Mağaza Girişi</td></tr>
<tr><td><input id="login_mail" placeholder="E-Posta" class="login_input" type="text" name="s_mail" maxlength="50" color="#56474C" aria-required="1"  /></td></tr>
<tr><td><input id="login_pass" placeholder="Şifre"  class="login_input" type="password"  name="s_password" maxlength="30" aria-required="1"/></td></tr>
<tr><td><input type="submit" name="Login" id="login_button" align="right" value="Giriş Yap" /></td></tr>
<tr><td colspan="2" align="left" style="font-size:14px;" class="login_input_text"><input type="checkbox" name="s_remember">Beni Hatırla - 
<a class="login_input_text" style="font-size:14px;"  href="forgetten_password.php?type=shopper">Şifremi Unuttum</a></td></tr>
<input type="hidden" name="f" value="<?php echo $form_data;?>" />
</tbody>
</table>
</form>
</div>
</div>

<div id="login_form_button" onclick="open_login_form()">
Giriş Yap
</div>
<div id="shop_register">
<div id="register-l" style="display:inline-block">
<form id="register_form" style="display:block;">
<table align="right">
<tbody>
<tr><td id="title">Mağazanı Kaydet<td><tr>
<tr><td><input placeholder="Mağaza İsmi" id="new_user_name" class="new_user_input" type="text" name="s_full_name" maxlength="50" autocomplete="off"  /></td></tr>
<tr><td><input placeholder="E-Posta" id="new_user_mail" class="new_user_input" type="text" name="s_mail" maxlength="50" autocomplete="off" /></td></tr>
<tr><td><input placeholder="E-Posta(Tekrar)" id="new_user_re_mail" class="new_user_input" type="text" name="re_s_mail" maxlength="50" autocomplete="off" /></td></tr>
<tr><td><input placeholder="Şifre" id="new_user_pass" class="new_user_input" type="password"   name="s_password" autocomplete="off"  /><input type="hidden" name="f" value="<?php echo $form_data;?>" /></td></tr>
<tr><td>
<select name="shop_type" id="shop_type">
  <option value="0">Mağaza Çeşidi</option>
  <option value="1">Market</option>
  <option value="2">Giyim</option>
  <option value="3">Restorant-Cafe</option>
  <option value="4">Ev Eşyaları</option>
  <option value="5">Elektronik</option>
  <option value="6">Oyuncak ve Hobi</option>
  <option value="7">Kozmetik</option>
  <option value="8">Sağlık-Tıp</option>
  <option value="9">Spor,Outdoor Mağazası</option>
  <option value="10">Pet Shop</option>
  <option value="11">Kitap-Dergi-Kırtasiye</option>
  <option value="12">Sinema-Eğlence</option>
  <option value="13">Müzik Aletleri</option>
  <option value="14">Diğer</option>
</select>
</tr></td>
<tr><td><input type="button" name="Kayit" id="s_new_user_button" align="right" value="Kaydol" /></td></tr>
</tbody> 
</table>
</form>
</div>
<div id="s_result">
</div>
</div>
</div>

<div class="des1">
Binlerce Mağazanın Yanında Sende Ücretsiz Yeral!
</div>
<div id="des_area">
<div class="des">
<div class="des_holder">
<img src="img_files/shop_position.png" class="des_img" />
<div class="des_exp">
Mağazanı Konumuyla Birlikte Ücretsiz Kaydet
</div>
</div>
</div>
<div class="des">
<div class="des_holder">
<img src="img_files/shop_products.png" class="des_img" />
<div class="des_exp">
Mağazandaki Ürünleri Yine Ücretsiz Ekle
</div>
</div>
</div>
<div class="des">
<div class="des_holder">
<img src="img_files/route_shop.png" class="des_img" />
<div class="des_exp">
Ürünlerini Almak İsteyen Müşterileri Mağazana Yönlendirelim...
</div>
</div>
</div>
<div class="des5">
Mağazanı Konumuyla Birlikte Kaydedersin ve Ürünlerini de Eklersin. Bu Ürünleri Görüp 
Almak İsteyen İnsanları Mağazana Yönlendiririz. Bu İşlem İçin Bir Ücret Ödemezsin. Ürününü 
Gören ve Beğenen İnsanlar Mağazana Gelir ve Ürününü Alır...
</div>
</div>
</div>



<div id="help_info_area">
 <div id="c_right">2016 &#169 Yeroks İstanbul</div>
 <div id="info_butts">
 <a href="shop_index.php">Mağaza Girişi</a><a href="about.php">Hakkımızda</a><a href="product_list.php">Ürün Çeşitleri</a>
</div>
 </div>
</div>
</body>
</html>