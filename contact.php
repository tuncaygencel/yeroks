<?php
error_reporting(null);
//magaza eger reyon olusturup urun eklememıs ıse sql hatası verıyor!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!

//page_id=5
session_start();
include("ajax/kjasafhfjaghl.php");
		$connect=new connect();
		$connect->sql_connect_db();
		$login=new log_in();
		
$error=0;
$y_id=0;
$c_id=0;
$s_id=0;
$is_login=0;
if($login->is_login(1) ){ $is_login=1; }
//error 1 no get value
//error 2 no product's values equal get values
//error 3 no shop equals product's shop data
	$connect->enter_active(25,1,$c_id,$s_id,0);
?>
<html>
<head>
<title>Yeroks İletişim Bilgileri</title>
<meta http-equiv="Content-Type" content="text/HTML"; charset="utf-8" />
<meta name="description" content="Binlerce Ürünü Çevrende Arayabilir ve Onlara Ulaşabilirsin!..." />
<meta name="keywords" content="Çevrende Arama Nerede" />
<meta name = "viewport" content="width=320, user-scalable=0">
<meta http-equiv="content-language" content="tr" />
<link rel="shortcut icon" href="img_files/y_icon.png" />
<script type="text/javascript" src="https://www.yeroks.com/scripts/jquery-1.11.1.min.js"></script>
<link href="css/about.css" type="text/css" rel="stylesheet" />
<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-41307627-1', 'auto');
  ga('send', 'pageview');

</script>
</head>
<body>
<div id="small_banner">
<div id="banner_center">
<div id="logo">
<a href="https://www.yeroks.com">
<img src="https://www.yeroks.com/img_files/b_l_b.png" style="height:30px;border:none;margin:auto;"/>
</a>
</div>
<?php if($is_login==1){
?>
<div class="user_banner">
<div id="user_name">
<a href="http://www.yeroks.com/m/profile.php" id="set_button">
<?php
echo $_SESSION['user_full_name']."</br>";
?>
</a>
</div>
<div id="slide_down" class="but_slide_down" onclick="$('#buttons').toggle();"></div>
<div id="buttons">
<a id="button" href="https://www.yeroks.com/m/list.php">Listeler</a>
<a id="button" href="https://www.yeroks.com/m/setting.php" >Ayarlar</a>
<a id="button" href="https://www.yeroks.com/m/logout.php">Çıkış Yap</a>
</div>
</div>
<?php }else{ ?>
<div id="login_link" onclick="location.href='https://www.yeroks.com/m/'">
Giriş Yap
</div>
<?php
}
?>
</div>
</div>
<div id="infos" style="margin-top:0px;padding-top:70px;">
<div id="infos_c">
<p style="padding:0px 10px;">
Yeroks'a her türlü soru, sorun, görüş ve önerilerinizi mail adreslerimiz aracılığıyla iletebilirsiniz...
</p>
<div id="infos_users" style="    width: 310px;float: none;margin: auto;">
<div style="width:310px;display:inline-block;">
<div id="user" style="border-radius: 100px;width: 122px;float:left;">
<div id="user_img"></div>
</div><div id="mail">Kullanıcılar İçin:<br><b>info@yeroks.com</b></div>
</div>
<div style="width:310px;display:inline-block;margin-top:10px;">
<div id="shop" style="border-radius: 100px;width: 122px;float:left;">
<div id="shop_img"></div>
</div><div id="mail">Marka ve Mağazalar İçin:<br><b>shop@yeroks.com</b></div>
</div>
</div>
</div>
</div>

<div id="help_info_area">
 2016 &#169 Yeroks İstanbul  <a href="shop_index.php">Mağaza Girişi</a><a href="contact.php">İletişim</a><a href="about.php">Hakkımızda</a><a href="product_list.php">Ürün Çeşitleri</a>
</div>
</body>
</html>