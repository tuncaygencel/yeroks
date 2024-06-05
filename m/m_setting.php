<?php
//page_id=18
session_start();
include("ajax/sql.php");
$connect=new connect();
$connect->sql_connect_db();
$connect->enter_active(18,1,0,0,0);
if(!isset($_SESSION['user_no']) or !isset($_SESSION['user_full_name']) or !isset($_SESSION['user_type']) or !isset($_SESSION['mail_adress']) )
{
echo '<meta http-equiv="refresh" content=0;URL=index.php>';
 }else
{
if($_SESSION['user_type']!=1){
die('<meta http-equiv="refresh" content=0;URL=index.php>');
}
?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/HTML" charset="utf-8" />
<meta name = "viewport" content="width=320, user-scalable=0">
<meta content="yes" name="apple-mobile-web-app-capable" />
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=2, user-scalable=0"/>
<link href="css/m_user_setting.css" type="text/css" rel="stylesheet" />
<script type="text/javascript" src="script/jquery-1.9.1.min.js"></script>
<script type="text/javascript">
function set_option()
			{
			var option_name=document.getElementById("option_name").value;
			var option_mail=document.getElementById("option_mail").value;
			var option_pass=document.getElementById("option_pass").value;
			var verif_pass=document.getElementById("verif_pass").value;
			$('#result_area').html('<img src="image/line_style_loading.gif"/>');
			$.ajax({
					type:'POST',
					url:'ajax/user_option.php',
					data:'&option_name='+option_name+'&option_mail='+option_mail+'&option_pass='+option_pass+'&verif_pass='+verif_pass,
					success: function(information) {
					$('#option_result').html(information);
								}
						})
			}

$(document).mouseup(function (e)
{  var container = $("#buttons");
    if (!container.is(e.target)&& container.has(e.target).length === 0) { container.hide();}
});
</script>
</head>
<body>
<div id="banner">
<div class="b_c">
<a href="m_profile.php" class="logo_y">
<img src="../img_files/b_l_b.png" style="margin:auto;height:27px;border:0px;"/>
</a>
<div class="user_banner">
<div id="user_name">
<a href="profile.php" id="set_button">
<?php
echo $_SESSION['user_full_name']."</br>";
?>
</a>
</div>
<div id="slide_down" class="but_slide_down" onclick="$('#buttons').toggle();" style="margin-top:-2px;"></div>
<div id="buttons">
<a id="button" href="list.php">Listeler</a>
<a id="button" href="setting.php" >Ayarlar</a>
<a id="button" href="logout.php">Çıkış Yap</a>
</div>
</div>
</div>
</div>

<div id="content">
<div id="c_center">
<div id="forms">
<div id="option_area">
<table>
<tbody>
<tr><td id="set_title">Ayarlar</td></tr>
<tr><td>
<input type="text" id="option_name" placeholder="Yeni İsim" name="option_re_mail" class="input_option">
</td></tr>
<tr><td>
<input type="text" id="option_mail" placeholder="Yeni E-Posta" name="option_re_mail" class="input_option">
</td></tr>
<tr><td>
<input type="password" id="option_pass" placeholder="Yeni Şifre" name="option_re_mail" class="input_option">
</td></tr>
<tr><td><input type="password" id="verif_pass" placeholder="Onay İçin Şu Anki Şifre" name="option_re_mail" class="input_option">
</td></tr>
<tr><td><div onclick="set_option();" id="option_submit">Kaydet</div></td></tr>
<tr><td><div id="option_result"></div></td></tr>
</tbody>
</table>
</div>
</div>
</div>
</div>
</body>
</html>
<?php
}
?>