<?php
//page_id=16
session_start();
include("ajax/sql.php");
$connect=new connect();
$connect->sql_connect_db();
$connect->enter_active(16,1,0,0,0);

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
function call_shop_list()
			{
			$('#fav_result').html('<img height=40 src="../img_files/cycle_loading.gif"/>');
			$("#fav_area").html("<div id=\"set_title\">Listeler</div><div id=\"list_top\"><div id=\"user_list\" onclick=\"call_shop_list()\" style=\"color: red;border-bottom: 2px solid red;\">Alışveriş Listesi</div><div id=\"fav_list\" onclick=\"call_fav_list()\" >Favoriler</div></div><div id=\"new_list_object\"><input type=\"text\" placeholder=\"Yeni Ürün İsmi \" name=\"shopping_list\" id=\"shopping_list\"/><div id=\"new_shopping_yield\" onclick=\"add_shopping_yield()\">Ekle<img src=\"image/cycle_loading.gif\" height=20 /></div></div>");
				$.ajax({
					type:'POST',
					data:'&type='+1,
					url:'ajax/shop_list.php',
					success: function(info) {
					$('#fav_result').html(info);
								}
						})
			}
function call_fav_list()
			{
					$('#fav_result').html('<img height=40 src="../img_files/cycle_loading.gif"/>');
					$("#fav_area").html("<div id=\"set_title\">Listeler</div><div id=\"list_top\"><div id=\"user_list\" onclick=\"call_shop_list()\">Alışveriş Listesi</div><div id=\"fav_list\" onclick=\"call_fav_list()\" style=\"color: red;border-bottom: 2px solid red;\" >Favoriler</div></div>");
					$.ajax({
					type:'POST',
					data:'&type='+0,
					url:'ajax/shop_list.php',
					success: function(info) {
					$('#fav_result').html(info);
								}
						})
			}
function add_shopping_yield()
				{
					var yield_name;
					if(yield_name=document.getElementById("shopping_list"))
						{
							yield_name=yield_name.value;					
							if(yield_name!='')
								{
								$("#new_shopping_yield img").css("display","inline-block");
								document.getElementById("shopping_list").value='';
								$.ajax({
										type:'POST',
										data:'&yield_name='+yield_name,
										dataType: 'json',
										url:'ajax/new_shopping_yield.php',
										success: function(info) {
											if(info.result===0)
												{
												alerti(info.explain);
												}else
												{
												$('#fav_result p').html('');
												$('#fav_result').prepend("<div id=\"shopping_y_"+info.id+"\" class=\"shopping_y\"><li>"+info.y_name+"</li><img onclick=\"open_y_del("+info.id+")\" src=\"image/waste.png\" height=\"20\"></div>");
												
												}
											}
										})
								}
						}else
						{
						alerti("Bir Hata Oluştu...")
						}
					$("#new_shopping_yield img").css("display","none");
				}
			function open_y_del(y_data)
			{
			var y_name=$("#shopping_y_"+y_data+" li").html();
			$("#shopping_y_"+y_data).html("<div onclick=\"shopping_del_b("+y_data+")\" id=\"shopping_del_b\">Sil</div>"+y_name+"<div onclick=\"shopping_fav_give_up("+y_data+",'"+y_name+"')\" id=\"shopping_fav_give_up\">Vazgeç</div>");
			}
		function open_fav_y_del(y_data)
			{
			$("#fav_y_"+y_data).html("<div onclick=\"shopping_del_b("+y_data+")\" id=\"shopping_del_b\">Sil</div><div onclick=\"fav_y_give_up("+y_data+")\" id=\"shopping_fav_give_up\">Vazgeç</div>");
			}
		function fav_y_give_up(y_data)
			{
			$("#fav_y_"+y_data).html("");
			}
		function shopping_del_b(y_data)
			{
					$.ajax({
					type:'POST',
					data:'&y_data='+y_data,
					dataType: 'json',
					url:'ajax/fav_del.php',
					success: function(info) {
					if(info.result===0)
							{
							alerti(info.explain);
							}else
							{
							$("#shopping_y_"+y_data).remove();
							}
						}
						})
			}
		function shopping_fav_give_up(y_data,y_name)
			{
			$("#shopping_y_"+y_data).html("<li>"+y_name+"</li><img onclick=\"open_y_del("+y_data+")\" src=\"image/waste.png\" height=\"20\">");
			}
		$(document).mouseup(function (e)
			{  var container = $("#buttons");
				if (!container.is(e.target)&& container.has(e.target).length === 0) { container.hide();}
			});
			function go_fav(data,type)
			{
				if(type==2){ window.open('../shop.php?shop_no='+data); }
				if(type==3){ window.open('../place.php?place_no='+data); }
			}
</script>
</head>
<body onload="call_shop_list()">
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
<div id="fav_area">

</div>
<div id="fav_result">

</div>



</div>
</div>


</body>
</html>
<?php
}
?>















