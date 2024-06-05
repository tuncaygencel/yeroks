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
<link rel="shortcut icon" href="http://www.yeroks.com/img_files/yeroks_icon.png" />
<link href="http://www.yeroks.com/css/shop_setting.css" type="text/css" rel="stylesheet" />
<script type="text/javascript" src="http://www.yeroks.com/scripts/jquery-1.11.1.min.js"></script>
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
</script>
</head>
<body>
<div id="small_banner">
<div id="banner_center">
<div id="logo">
<a href="http://www.yeroks.com/shop_profile.php">
<img src="http://www.yeroks.com/img_files/yeroks_s_logo.png" style="height:28px;border:none;margin:auto;"/>
</a>
</div>


<div id="buttons">
<div id="user_name">
<img src=" <?php
if(array_key_exists( 1, $check_type->p_img_way)){
echo "s_profile_img_small/".$check_type->p_img_way[1];
}else{
echo "img_files/shopping.png";
}
?> " style="height: 27px;width: 27px;float: left;overflow: hidden;padding: 2px;background-color: white;" />
<div id="user_name_text">
<a href="http://www.yeroks.com/shop_profile.php">
<?php
echo $check_type->name;
?>
</a>
</div>
</div>
<a href="shop_index.php?type=1" id="button_of_set_user">Çıkış Yap</a>
<a href="shop_profile.php" id="button_of_set_user">Ana Sayfa</a>
</div>
</div>
</div>

<div id="content_center">
<div id="content">
<div id="left_content">
<a  href="account_setting.php" id="set_button" class="account_set_button" style="border-bottom:1px solid red;">
Hesap Ayarları
</a>
<a href="position_setting.php" id="set_button" class="pos_set_button" >
Konum Ayarları
</a>
<a  href="image_setting.php" id="set_button" class="img_set_button">
Mağaza Resim Ayarları
</a>
</div>
<div id="right_content">
<div id="account_set_area">
<div id="input_area">
<form id="set_form">
<table>
<tbody>
<tr><td class="set_input_text" >Yeni Mağaza İsmi:</td><td><input id="set_name_input" type="text" name="set_u_full_name" maxlength="50" autocomplete="off"  /></td></tr>
<tr><td class="set_input_text" >Yeni E-Posta:</td><td><input id="set_mail_input" type="text" name="set_mail" maxlength="50" autocomplete="off" /></td></tr>
<tr><td class="set_input_text" >Yeni Şifre:</td><td><input id="set_new_pass_input" type="password" name="set_new_pass" maxlength="50" autocomplete="off" /></td></tr>
<tr><td class="set_input_text" >Şifre:</td><td><input  id="pass_input" type="password"   name="pass" autocomplete="off"  ></td></tr>
<tr><td></td><td><input type="button" id="submit_button" onClick="setting()" value="Kaydet" /></td></tr>
</tbody> 
</table>
</form>
</div>
<div id="result_area">
</div>
</div>
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