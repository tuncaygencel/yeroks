<?php
session_start();

if(isset($_SESSION['user_type'])  and isset($_SESSION['user_no']) and isset($_SESSION['mail_adress']))
	{
	if($_SESSION['user_type']!=2)
	{
	die("<div class=\"shop_type_text\">İşlem Sırasında Bir Hata Oluştu.Lütfen Tekrar Deneyiniz...</div>");
	}
$shop_id=$_SESSION['user_no'];
include("ajax/kjasafhfjaghl.php");
?>
<html>
<head>
<title>
GPS SİSTEMİ
</title>
<meta http-equiv="Content-Type" content="text/HTML"; charset="utf-8" />
<link rel="shortcut icon" href="img_files/yeroks_icon.png" />
<link href="css/pos_gps_request.css" type="text/css" rel="stylesheet" />
<script type="text/javascript" src="scripts/jquery-1.11.1.min.js"></script>
<script type="text/javascript">
function gps_request(){
				$('#gps-request-button').css("display","block");
				$.ajax({
					type:'POST',
					url:'aj_pos/create_gps_request.php',
					dataType: 'json',
					success: function(info) {
						if(info.result==1)
								{
								$('#gps_result').html("<div id=\"gps_r_text\">GPS İsteği E-Postanıza Gönderildi...</div>");
								$('div').remove(".gps-request-button");
								$('#gps_warning').html("");
								$('#okay_img').css("display","inline-block");
								}else if(info.result==2)
								{
								$('#gps_result').html("<div id=\"gps_r_text\">Lütfen Bu İşlem İçin Giriş Yapınız...</div>");
								}else{
								$('#gps_result').html("<div id=\"gps_r_text\">İşlem Sırasında Bir Hata Oluştu.Lütfen Tekrar Deneyiniz...</div>");
								}
								
						}
					});
					$('#gps-request-button').css("display","none");
		}
</script>
</head>
<body>
<div id="small_banner">
<div id="logo">
<img src="img_files/logo_grad.png" style="height:50px;border:none;margin:auto;"/>
</div>
</div>
<div id="content_center">
<div id="buttons">
<div id="user_name">
<a href="shop_profile.php">
<?php
echo $_SESSION['user_full_name'];
?>
</a>
</div>
<a href="shop_index.php?type=1" id="button_of_set_user">Çıkış Yap</a>
<a href="shop_profile.php" id="button_of_set_user">
Ana Sayfa</a>
</div>
<div id="gps_title">
<img src="img_files/config.png" style="margin-bottom:-16px;  opacity: 0.3;" height="70">
GPS Sistemi
</div>
<?php
$connect=new connect();
$connect->sql_connect_db();
$gps_request=new gps_request();

$status=0;
$sql="SELECT*FROM shop_adress WHERE s_id='$shop_id'";
$sql=mysql_query($sql) or die(mysql_error());
	if(mysql_num_rows($sql)>0)
	{
	
		if($gps_request->is_there_active_request($shop_id))
			{
			$status=1;
			}
?>
<div id="gps-area" style="display:inline-block;width:1000px;  padding-bottom: 20px;background-color:rgb(236, 236, 236);">
<div class="step_1" id="step">
<div id="step_1_img" style="display:inline-block;height: 80px;width: 100%;">
<img src="img_files/yeroks_mail.png" height="50" style="margin: 10px;">
<img src="img_files/green_okay_cycle.png" id="okay_img" height="90" style="display:none;margin-top: 70px;">
</div>
<div class="gps-exp" id="gps-exp-1">Kayıtlı E-Postana GPS Sistemi İsteği Gönder </div>
</div>
<div class="step_2" id="step">
<img src="img_files/shop_phone.png" height="60" style="margin: 10px;">
<div class="gps-exp" id="gps-exp-2">Mağazanın Kapısına Akıllı Bir Telefonla Git ve E-Postana Gönderilen Linke Tıkla </div>
</div>
<div class="step_3" id="step">
<img src="img_files/loc_save.png" height="60" style="margin: 10px;">
<div class="gps-exp" id="gps-exp-3">GPS Sistemi Konumunu Bulsun ve Kaydet </div>
</div>
</div>
<div id="gps_result">
</div>
<?php
if($status==1)
	{
?>
<div id="gps_warning">
Kayıtlı Bir GPS İsteği Bulunmakta. İster Daha Önce E-Postanıza Gönderilmiş GPS İsteğini Kullanabilir veya Yeni Bir GPS İsteği Oluşturabilirsiniz... 
</div>
<div class="gps-request-button" onclick="gps_request()">Yeni GPS İsteği<img src="img_files/cycle_loading.gif" height="35" style="position:relative;float:right;margin-right: 10px;display:none;background-color: rgb(33, 178, 211);margin-left:-90px;"></div>
<?php
	}else
	{
?>
<div class="gps-request-button" onclick="gps_request()">Başla<img src="img_files/cycle_loading.gif" height="35" style="position:relative;float:right;margin-right: 10px;display:none;background-color: rgb(33, 178, 211); margin-left:-90px;"></div>
<?php
	}
}else{
	die("<div class=\"shop_type_text\">Lütfen Öncelikle Ayarlar Kısmından İşyeri Adresinizi Giriniz...</div> <a href=\"position_setting.php\" class=\"gps-request-button\">Ayarlara Git</a>");
	}
?>
</div>
</body>
</html>
<?php
}else{
	die("<meta http-equiv=\"refresh\" content=0;URL=shop_index.php>");
}
?>

