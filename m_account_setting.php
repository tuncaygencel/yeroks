<?php
session_start();
include("ajax/kjasafhfjaghl.php");
if(isset($_SESSION['user_type'])  and isset($_SESSION['user_no']) and isset($_SESSION['mail_adress']))
	{
$connect=new connect();
$connect->sql_connect_db();
$check_type=new check_type_get_values();	
$check_type->sent_values($_SESSION['mail_adress'],$_SESSION['user_type'],$_SESSION['user_no']);
	
	if($check_type->check_and_get()==2)
		{
		$check_type->get_profile_image();
?>
<html>
<head>
<title>
Hesap Ayarları
</title>
<meta http-equiv="Content-Type" content="text/HTML"; charset="utf-8" />
<meta name = "viewport" content="width=320, user-scalable=0" />
<link rel="shortcut icon" href="http://www.yeroks.com/img_files/yeroks_icon.png" />
<link href="css/m_shop_setting.css" type="text/css" rel="stylesheet" />
<script type="text/javascript" src="http://www.yeroks.com/scripts/jquery-1.11.1.min.js"></script>
<script type="text/javascript" src="http://www.yeroks.com/scripts/jquery-form.js"></script>
<script type="text/javascript">
function setting(){
				var form_data=$("#set_form").serialize();
				$('#result_area').html('<img src="img_files/line_style_loading.gif"/>');
				$.ajax({
					type:'POST',
					url:'http://www.yeroks.com/ajax/u_setting.php',		
					data:form_data,
					success: function(information) {
					$('#result_area').html(information);
						}
					});
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
<div id="logo">
<a href="http://www.yeroks.com/m/profile.php" class="logo_y">
<img src="img_files/yeroks_s_logo.png" style="height:25px;border:none;margin:auto;"/>
</a>
</div>
<div class="user_banner">
<div id="user_name">
<img src=" <?php
if(array_key_exists( 1, $check_type->p_img_way)){
echo "s_profile_img_small/".$check_type->p_img_way[1];
}else{
echo "img_files/shopping.png";
}
?> " style="height: 27px;width: 27px;float: left;overflow: hidden;padding: 2px;background-color: white;" />
<div id="user_name_text">
<a href="shop_profile.php">
<?php
echo $check_type->name;
?>
</a>
</div>
</div>
<div id="slide_down" class="but_slide_down" onclick="$('#buttons').toggle();"></div>
<div id="buttons">
<a id="button_t" href="shop_profile.php">Profil</a>
<a id="button_t" href="account_setting.php" >Ayarlar</a>
<a id="button_t" href="shop_index.php?type=1">Çıkış Yap</a>
</div>
</div>
</div>
</div>
<div id="content_center">
<div id="t_but">
<div onclick="window.location='account_setting.php'" id="button" style="border-bottom: 1px solid red;">Hesap Ayarları</div>
<div onclick="window.location='position_setting.php'" id="button">Konum Ayarları</div>
<div onclick="window.location='image_setting.php'" id="button">Resim Ayarları</div>
</div>		
<div id="account_set_area" style="margin-left: 20px;">
<div id="input_area">
<form id="set_form">
<table>
<tbody>
<tr><td><input placeholder="Yeni Mağaza İsmi" id="set_name_input" type="text" name="set_u_full_name" maxlength="50" autocomplete="off"  /></td></tr>
<tr><td><input placeholder="Yeni E-Posta" id="set_mail_input" type="text" name="set_mail" maxlength="50" autocomplete="off" /></td></tr>
<tr><td><input placeholder="Yeni Şifre" id="set_new_pass_input" type="password" name="set_new_pass" maxlength="50" autocomplete="off" /></td></tr>
<tr><td><input placeholder="Şifre"  id="pass_input" type="password"   name="pass" autocomplete="off"  ></td></tr>
<tr><td><input type="button" id="submit_button" onClick="setting()" value="Kaydet" /></td></tr>
</tbody> 
</table>
</form>
</div>
<div id="result_area">
</div>
</div>	
<div id="help_info_area" style="margin-top:50px">
 <div id="c_right">2016 &#169 Yeroks İstanbul</div>
 <div id="info_butts">
 <a href="shop_index.php">Mağaza Girişi</a><a href="about.php">Hakkımızda</a><a href="product_list.php">Ürün Çeşitleri</a>
</div>
 </div>	
</div>	
</body>
</html>

<?php
		}else
		{
		$_SESSION['user_no']="";
		$_SESSION['user_type']= "";
		$_SESSION['user_full_name']="";
		$_SESSION['mail_adress']="";
		$_SESSION['login']=0;
		session_unset();
			echo '<meta http-equiv="refresh" content=0;URL=http://www.yeroks.com/error_page/error.php>';
		}
	}else
	{
	echo '<meta http-equiv="refresh" content=0;URL=http://www.yeroks.com/shop_index.php>';
	}
?>	
		
		
		
		
		
		
		
		
		
		
		
		
		