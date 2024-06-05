<?php
session_start();
include("ajax/kjasafhfjaghl.php");
$check=new gps_request();
	$connect=new connect();
	$connect->sql_connect_db();
$step=0;
$data="";
if(isset($_GET["data"]))
	{
	$data=mysql_real_escape_string(htmlspecialchars(trim($_GET["data"])));
	$step=1;
	}else
	{
	$check->destroy_session();
	$step=0;
	}
	
if($step!=0)
	{

		if($check->check_gps_data($data))
			{
			$step=1;
			$check->create_session();
			}else
			{
			$check->destroy_session();
			$step=-1;
			}
	}
	//step=-1 means there is data value in link and there is not in database
	//step=0 means there is no data value in link
?>


<html>
<head>
<title>
GPS SİSTEMİ
</title>
<meta http-equiv="Content-Type" content="text/HTML"; charset="utf-8" />
<meta name = "viewport" content="width=320, user-scalable=0">
<meta content="yes" name="apple-mobile-web-app-capable" />
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=2, user-scalable=0"/>
<link rel="shortcut icon" href="img_files/yeroks_icon.png" />
<link href="css/gps_request_index.css" type="text/css" rel="stylesheet" />
<script type="text/javascript" src="scripts/jquery-1.11.1.min.js"></script>
<script type="text/javascript">

function check_pass()
	{
	var pass=document.getElementById("gps_pass").value;
			$.ajax({
					type:'POST',
					url:'ajax/gps_shop_check.php',
					dataType: 'json',
					data:'&pass='+pass,
					success: function(info) {
		
						if(info.result==0)
						{
						$('#gps_pass_result').html("<div id=\"result_in\">Bir Sorun Oluştu..</div>");
						}else if(info.result==1){
						$('#gps_pass_result').html("<div id=\"result_in\">Şifreniz Kabul Edildi. GPS Sistemine Yönlendiriliyorsunuz...</div>");
						window.location="gps_request_profile.php";
						}else if(info.result==2){
						$('#gps_pass_result').html("<div id=\"result_in\">Veri Gönderme Hatası Oluştu...</div>");
						}else if(info.result==3){
						$('#gps_pass_result').html("<div id=\"result_in\">Lütfen Yeroks Şifrenizi Giriniz...</div>");
						}else if(info.result==4){
						$('#gps_pass_result').html("<div id=\"result_in\">Bir Sorun Oluştu...</div>");
						}else if(info.result==5){
						$('#gps_pass_result').html("<div id=\"result_in\">GPS İsteği İle Şifre Uyuşmuyor...</div>");
						}else{
						$('#gps_pass_result').html("<div id=\"result_in\">Bir Sorun Oluştu...</div>");
						}
							
								
								}
						})
	
	}

</script>
</head>
<body>
<div id="small_banner">
<div id="logo">
<img src="img_files/logo_grad.png" style="height:37px;border:none;margin:auto;"/>
</div>
</div>
<?php
if($step==0 or $step==-1)
{
	?>
	<div id="warning_gps">
	<?php
	if($step==0){ echo "Yanlış Bir Linke Tıkladınız. Eğer GPS Sistemine Ulaşmak İstiyorsanız Lütfen Ayarlar Kısmından Yeni Bir İstek Oluşturunuz veya Doğru E-Posta Linkine Tıkladığınızdan Emin Olunuz...";}
	if($step==-1){ echo "Böyle Bir GPS İsteği Bulunamadı.Eğer GPS Konum Ayarlarına Ulaşmak İstiyorsanız Lütfen Ayarlar Kısmından Yeni Bir GPS İsteği Oluşturunuz..."; }
	?>
	</div>
	<?php
}else
{
?>
<div id="gps_shop_name">
<img src="img_files/shop.png" height=30;/>
<?php
echo $_SESSION['s_full_name'];
?>
</div>

<div id="main">

<form id="login_to_pos">
<table>
<tbody>
<tr>
<td id="gps_text">Devam Etmek İçin Şifrenizi Giriniz...</td>
</tr>
<tr>
<td><input type="password" name="pass" id="gps_pass"/></td>
</tr>
<tr>
<td><div id="gps_pass_submit" onclick="check_pass()">DEVAM</div></td>
</tr>
<tr>
<td><div id="gps_pass_result"></div></td>
</tr>

</tbody>
</table>
</form>
</div>
<?php
}

?>
</body>
</html>