<?php
//page_id=19
 session_start();
 include("ajax/sql.php");
$connect=new connect();
$connect->sql_connect_db();
$connect->enter_active(22,1,0,0,0);
unset($_SESSION['user_no']);
unset($_SESSION['user_type']);
unset($_SESSION['cook_data']);
unset($_SESSION['user_full_name']);
unset($_SESSION['mail_adress']);
session_destroy();
if(isset($_COOKIE['mail']) && isset($_COOKIE['info'])){
   setcookie("mail", "", time()-60*60*24*100, "/");
   setcookie("info", "", time()-60*60*24*100, "/");
}
?>
<html>
<head>
<meta name = "viewport" content="width=320", user-scalable=0 >
<script type="text/javascript" src="script/jquery-1.9.1.min.js" ></script>
<link rel="shortcut icon" href="image/yeroks_icon.png">
<link href="css/index.css" type="text/css" rel="stylesheet"/>
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
<form name="login_form" id="login_form" method="POST" action="login.php" >
<input type="text" style="width:280px;" placeholder="E-Posta" name="email_input" id="email_input"  >
<input type="password" style="width:280px;" placeholder="Şifre" name="pass_input"  id="pass_input" />
<input type="submit" id="login_button" value="Giriş Yap" />
<div id="login_result">
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
