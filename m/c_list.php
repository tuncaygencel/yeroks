<?php
//page_id=15
session_start();
include("ajax/sql.php");
$connect=new connect();
$connect->sql_connect_db();
$connect->enter_active(15,1,0,0,0);
$login=new log_in();
if(!$login->is_login(1)){ 
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
<link rel="shortcut icon" href="../img_files/y_icon.png" />
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=2, user-scalable=0"/>
<link href="css/user_setting.css" type="text/css" rel="stylesheet" />
<script type="text/javascript" src="script/jquery-1.9.1.min.js"></script>
<script type="text/javascript">

var fav_busy=0;
var login=1;
function add_shopping_list(y_data,y_type_data)
	{
		if(login==1){
		if(fav_busy==0){
		fav_busy=1;
					$('#add_s_list').html('<img height="16" src="http://www.yeroks.com/img_files/cycle_loading.gif"/>');
					$.ajax({
					type:'POST',
					data:'&data='+y_data+'&type_data='+y_type_data+'&type='+4,
					dataType: 'json',
					url:'ajax/add_fav.php',
					success: function(info) {
					if(info.result==0)
							{
							alert(info.explain);
							$('#add_s_list').html('Hata');
							}else
							{
								if(info.fav_status==1){
								$('#add_s_list').html("Alışveriş Listesinde");
								$('#add_s_list').css("background-color","#0ebb31");
								}else{
								$('#add_s_list').html("Alışveriş Listesine Ekle");
								$('#add_s_list').css("background-color","#d00f0f");
								}
							}
							
							fav_busy=0;
						}
						})
			}
		}else{
		window.location="http://www.yeroks.com/m/index.php?redirect=../shop_product.php?s_no="+s_data+"&c_no="+y_type_data;
		}
	}
	function load_b_img(img_name)
			{
			$('#lb_b_img_d').html("<img id=\"lb_b_img\" src=\"../y_img_big/"+img_name+"\"/>");
			}
function hidebackground() {
				$('#black_background').css('display','none');
				$('#light_box_area').css('display','none');
				$('#light_box_area').html('');
				};
function show_pr_lb(y_type_data,y_data,s_data)
			{
			$('#black_background').css('display','block');
			$('#light_box_area').css('display','block');
			$('#light_box_area').html("<div id=\"lb_main\"><div id=\"lb_img\"><div id=\"lb_b_img_d\"></div><div id=\"lb_s_img_d\"></div></div><div id=\"lb_exp_main\"><div id=\"lb_exp\"><div id=\"lb_y_name\"></div><div id=\"lb_y_price\"></div><div id=\"lb_y_prop\"></div><div id=\"lb_y_exp\"></div></div><div id=\"add_s_list\" onclick=\"add_shopping_list("+y_data+","+y_type_data+")\">Alışveriş Listesine Ekle</div></div></div>");
			$.ajax({
					type:'POST',
					data:'&s_data='+s_data+'&y_data='+y_data+'&y_type_data='+y_type_data,
					dataType: 'json',
					url:'../ajax/pr_lb_data.php',
					success: function(info) {
					if(info.error==1)
							{
								alert(info.exp);
							}else
							{
								$('#lb_s_img_d').html("");
								var ii=0;
								$.each(info.imgs,function(i,result){
								$('#lb_s_img_d').append("<img onclick=\"load_b_img('"+result.img+"')\" id=\"lb_s_img\" src=\"../y_img_small/"+result.img+"\"/>");
								ii=ii+1;
								if(ii==1){ $('#lb_b_img_d').html("<img id=\"lb_b_img\" src=\"../y_img_big/"+result.img+"\"/>"); }
								});
								if(ii==0){ $('#lb_b_img_d').html("<img id=\"lb_b_img\" style=\"margin-top:30%;height:80px;\" src=\"../y_img_big/"+info.icon+"\"/>"); }
								$('#lb_y_name').html(info.y_name);
								if(info.price!=0){
								$('#lb_y_price').html(info.price);
								if(info.price_type==1){ $('#lb_y_price').append(" TL"); }else if(info.price_type==2){ $('#lb_y_price').append(" Dolar"); }else if(info.price_type==3){ $('#lb_y_price').append(" Euro"); }
								}
								$('#lb_y_prop').html("<table id=\"s_y_list\" align=\"center\"><tbody>");
								$.each(info.props,function(i,re){
								$('#lb_y_prop').append("<tr><td><div id=\"p_name_vis\">"+re.p_name+"</div></td><td>:<div id=\"prop_vis\" >"+re.p_value+"</div></td></tr>");
								});
								$('#lb_y_prop').append("</tbody></table>");
								$('#lb_y_exp').html("<p>"+info.explain+"</p>");
								if(info.is_fav==1){
								$('#add_s_list').html("Alışveriş Listesinde");
								$('#add_s_list').css("background-color","#0ebb31"); 
								}else{
								$('#add_s_list').html("Alışveriş Listesine Ekle");
								$('#add_s_list').css("background-color","#d00f0f"); 
								}
							}
						}
						})
			}
function call_product_list()
			{
			$('#fav_result').html('<img height=40 src="../img_files/cycle_loading.gif"/>');
			set_buttons('#product_list','#user_list, #fav_list');
			$("#new_list_object").css("display","none");
			$.ajax({
					type:'POST',
					data:'&type='+2,
					url:'ajax/shop_list.php',
					success: function(info) {
					$('#fav_result').html(info);
								}
						})
			}
function set_buttons(div_name,other_div)
			{
			$(div_name).css({'color':'red','border-bottom':'2px solid red','background-color':'#f0f0f0'});
			$(other_div).css({'color':'rgb(60, 60, 60)','border-bottom':'2px solid rgb(60, 60, 60)','background-color':'#ffffff'});
			}
function call_shop_list()
			{
			$('#fav_result').html('<img height=40 src="../img_files/cycle_loading.gif"/>');
			$("#new_list_object").css("display","inline-block");
			set_buttons('#user_list','#product_list , #fav_list');
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
					set_buttons('#fav_list','#product_list , #user_list');
					$("#new_list_object").css("display","none");
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
					success: function(info){
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
		function go_fav(data,type)
			{
				if(type==2){ window.open('../shop.php?shop_no='+data); }
				if(type==3){ window.open('../place.php?place_no='+data); }
			}
</script>
</head>
<body onload="call_shop_list()">
<div id="black_background" onclick="hidebackground();" style="display:none;background-color:none;">
</div>
<div id="light_box_area" style="display:none;background-color:none;">
</div>
<div id="top_banner">
<div id="y_banner_center">
<div id="yeroks_logo">
<img src="../img_files/b_l_b.png" height=26 style="float:left;margin:0px 0px 0px 10px;"  />
</div>
<div id="profile_infos">
<a href="profile.php" id="set_button">
<?php
echo $_SESSION['user_full_name']."</br>";
?>
</a>
<a id="set_button" href="list.php" >
Listeler
</a>
<a id="set_button" href="setting.php">
Ayarlar
</a>
<a href="logout.php" id="set_button">
Çıkış Yap
</a>
</div>
</div>
</div>

<div id="content">
<div id="c_center">
<div id="fav_area">
<div id="set_title">Listeler</div>
<div id="list_top">
<div id="user_list" onclick="call_shop_list()">Alışveriş Listesi</div>
<div id="fav_list" onclick="call_fav_list()" style="color: red;border-bottom: 2px solid red;" >Favori Yerler</div>
<div id="product_list" onclick="call_product_list()" >Favori Ürünler</div>
</div>
</div>
<div id="new_list_object">
<input type="text" placeholder="Yeni Ürün İsmi " name="shopping_list" id="shopping_list">
<div id="new_shopping_yield" onclick="add_shopping_yield()">Ekle<img src="image/cycle_loading.gif" height="20"></div>
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















