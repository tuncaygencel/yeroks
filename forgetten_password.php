<?php
//page_id=20
session_start();
include("ajax/kjasafhfjaghl.php");
$connect=new connect();
$connect->sql_connect_db();
$connect->enter_active(20,1,0,0,0);
?>
<html>
<head>
<title>
Yeroks-Şifre Değiştirme
</title>
<link rel="shortcut icon" href="visual_files/yeroks_icon.png">
<link href="css/forgotten_password.css" type="text/css" rel="stylesheet"/>
<meta http-equiv="Content-Type" content="text/HTML"; charset="utf-8" />
<script type="text/javascript" src="scripts/jquery-1.11.1.min.js"></script>
<script type="text/javascript">
		function send_datas(){
	var pass_change_data=document.getElementById("change_pass_input").value;
	var type=2;
	$('.pass_change_input_area').html('<img src="img_files/line_style_loading.gif"/>');
	$.ajax({
		type:'POST',
		url:'ajax/send_change_datas.php',	
		data: '&type='+type+'&pass_change_data='+pass_change_data,
		success: function(data) {
			 	$('.pass_change_input_area').html(data);
			}
});
}
</script>
</head>
<body>
<div id="banner">
<div class="logo">
<a href="shop_index.php">
<img src="img_files/b_l_b.png" style="margin:auto;height:51px;border:0px;"/>
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


<div id="pass_change_datas_area">
<div class="pass_changer_type">
Mağaza Şifre Değişimi
</div>
<div class="pass_change_input_area">
<form id="pass_change_form">
<table>
<tbody>
<tr>
<td class="change_pass_info_text" >
E-Posta Adresinizi Giriniz:
</td>
<td>
</td>
</tr>
<tr>
<td>
<input type="text" name="mail_input_to_change_pass" id="change_pass_input"/>
</td>
<td>
<div onclick="send_datas()" class="mail_sent_button">
Gönder
</div>
</td>
</tr>
</tbody>
</table>
</form>
</div>
</div>

<div id="links" style="width: 600px;">
2016 &#169 Yeroks
</div>

</div>
</div>








</body>
</html>