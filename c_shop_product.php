<?php

//magaza eger reyon olusturup urun eklememıs ıse sql hatası verıyor!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!

//page_id=5
session_start();
include("ajax/kjasafhfjaghl.php");
		$connect=new connect();
		$connect->sql_connect_db();
		$login=new log_in();
		$text=new text();
		
$error=0;
$y_id=0;
$c_id=0;
$s_id=0;
$is_login=0;
if($login->is_login(1) ){ $is_login=1; }
//error 1 no get value
//error 2 no product's values equal get values
//error 3 no shop equals product's shop data
	function change($text)
				{
				$text = trim($text);
				$search = array("Ç","ç","Ğ","ğ","ı","İ","Ö","ö","Ş","ş","Ü","ü"," ","’","'","&",".","(",")","/",'\'');
				$replace = array("C","c","G","g","i","I","O","o","S","s","U","u","-","-","-","-","","-","","-",'-');
				return str_replace($search,$replace,$text);
				}
if(isset($_GET['s_no']) and isset($_GET['c_no']))
	{
	$s_id=mysql_real_escape_string(htmlspecialchars(trim((int)$_GET['s_no'])));
	$c_id=mysql_real_escape_string(htmlspecialchars(trim((int)$_GET['c_no'])));
	}else{
	$error=1;
	}
	
	if($error==0){
		$new_yerok=new yerok_id_handler();
		$new_yerok->get_yerok_shop_id($c_id,$s_id);
		$new_yerok->yerok_id_control();
		$new_yerok->select_table();
	//////
	
		if(!$new_yerok->get_yields_datas()){
		$error=1;
		}
	
	/////
	
		
		$check_type=new check_type_get_values();	
		$check_type->sent_values("",2,$new_yerok->shop_id);
		
		if($check_type->check_and_get()!=2)
			{
			$error=3;
			}
		
		if($error==0){
			//$new_yerok->get_props_value_to_y_show();
			//////
			$new_yerok->get_y_icon();
			$new_yerok->get_y_first_images();
			////
			$check_type->get_profile_image();
			$check_type->get_shop_adress();
			}
	}
	if($error==0)
	{
	$connect->enter_active(5,1,$c_id,$s_id,0);
?>
<html>
<head>
<title> <?php
if($check_type->place_number!=0)
	{
		echo $check_type->place_name."-".$check_type->name."-";
		echo $new_yerok->show_c_name();
	}else{
		echo $check_type->name."-";
		echo $new_yerok->show_c_name();
	}
?> </title>
<meta http-equiv="Content-Type" content="text/HTML"; charset="utf-8" />
<?php
if($check_type->place_number!=0)
	{
	?>
	<meta name="description" content="<?php echo $check_type->place_name."-".$check_type->name." Mağazasındaki ";?><?php echo $new_yerok->show_c_name();?> ve Diğer Binlerce Ürünü Çevrende Arayabilir ve Onlara Ulaşabilirsin!..." />
	<meta name="keywords" content="<?php echo $new_yerok->show_c_name()." ".$check_type->name." ".$check_type->place_name;?> Çevrende Arama Nerede " />
	<?php
	}else{
	?>
	<meta name="description" content=" <?php echo $new_yerok->show_c_name();?> ve Diğer Binlerce Ürünü Çevrende Arayabilir ve Onlara Ulaşabilirsin!..." />
	<meta name="keywords" content="<?php echo $new_yerok->show_c_name()." ".$check_type->name;?> Çevrende Arama Nerede" />
	<?php
	}
?>

<meta http-equiv="content-language" content="tr" />
<link rel="shortcut icon" href="https://www.yeroks.com/img_files/y_icon.png" />
<script type="text/javascript" src="https://www.yeroks.com/scripts/jquery-1.11.1.min.js"></script>
<link href="https://www.yeroks.com/css/shop1.css" type="text/css" rel="stylesheet" />
<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-41307627-1', 'auto');
  ga('send', 'pageview');

</script>
<?php
if($check_type->latitude!='0' and $check_type->longitude!='0' )
	{
	?>
	
	<script src="https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false&key="></script>
	<script type="text/javascript">
	var latitude=<?php echo $check_type->latitude;?>;
	var longitude=<?php echo $check_type->longitude;?>;
	var s_data=<?php echo $s_id; ?> ;
	var fav_busy=0;
	var login=<?php echo $is_login; ?> ;
	var Mode='DRIVING';
	var lati=0;
	var longi=0;
	var p_image=<?php if(array_key_exists( 1, $check_type->p_img_way)){ echo "'".$check_type->p_img_way[1]."'";}else{ echo "''";}	?>;
	var map;
	var directionsDisplay;
	var directionsService = new google.maps.DirectionsService();
	
	 function initialize()
            {
				var shop_pos=new google.maps.LatLng(<?php echo $check_type->latitude.",".$check_type->longitude ;?>);
                map = new google.maps.Map(document.getElementById('map_area'), {
                   zoom: 15,
                   center: shop_pos,
                   mapTypeId: google.maps.MapTypeId.ROADMAP
                 });
				 var image =new google.maps.MarkerImage('https://www.yeroks.com/img_files/loc_marker2.png',
                new google.maps.Size(60, 60),
                new google.maps.Point(0, 0),
                new google.maps.Point(23, 60));
				
				red_marker = new google.maps.Marker({
				position: shop_pos,
				map: map,
				size: google.maps.Size(60, 60),
				draggable:false,
				icon: image,
				title:"Mağazanın Konumu"
				});
				directionsDisplay = new google.maps.DirectionsRenderer();
			directionsDisplay.setMap(map);
			directionsDisplay.setOptions( { suppressMarkers: true } );
			create_loc_marker();
            };
			
			
			google.maps.event.addDomListener(window, 'load', initialize);
			
			$(document).mouseup(function (e)
			{  var container = $("#buttons");
				if (!container.is(e.target)&& container.has(e.target).length === 0) { container.hide();}
			});
			function add_fav()
				{
				if(login==1){
				if(fav_busy==0){
				fav_busy=1;
					$('#add_fav').html('<img height="16" src="https://www.yeroks.com/img_files/cycle_loading.gif"/>');
					$.ajax({
					type:'POST',
					data:'&data='+s_data+'&type_data='+0+'&type='+2,
					dataType: 'json',
					url:'m/ajax/add_fav.php',
					success: function(info) {
					//$("#events_area").html(info);
					if(info.result==0)
							{
							alert(info.explain);
							$('#add_fav').html('Hata');
							}else
							{
								if(info.fav_status==1){
								$('#add_fav').html('<img src="https://www.yeroks.com/img_files/added_fav.png" style="height:18px;margin-right:5px;display:inline-block;float:left;margin-top:-4px;">Favori!');
								}else{
								$('#add_fav').html('Favorilere Ekle');
								}
								alert(info.explain);
							}
							fav_busy=0;
						}
						})
					}
						}else{
						window.location="https://www.yeroks.com/m/index.php?redirect=../shop.php?shop_no="+s_data;
						}
				}
				////route functions
			function route()
			{
			Location();
			loc_marker.setVisible(true);
			alerti();
			}
			function call_route_when_data_ready() {
				setTimeout(function () {
						if(lati!=0 && longi!=0){	calcRoute();}else{call_route_when_data_ready();}
					}, 1000);
				}
			function Location(){
				if(navigator.geolocation){
				var options = {timeout:60000};
				watchID = navigator.geolocation.watchPosition(callback, errorHandler, options);	
				}else{
				alert("Tarayıcınız Konum Bulma Servisini Desteklemiyor. Malesef Bu Hizmeti Veremiyoruz...");
				}
			}
			function create_loc_marker()
			{
			var image =new google.maps.MarkerImage('https://www.yeroks.com/img_files/p_loc_marker3.png',
                new google.maps.Size(50, 50),
                new google.maps.Point(0, 0),
                new google.maps.Point(25, 50));
			var start_position=translate_coord(lati, longi);
			loc_marker = new google.maps.Marker({
				position: start_position,
				map: map,
				size: google.maps.Size(60, 60),
				draggable:false,
				icon: image,
				title:"Konumun"
				});
				loc_marker.setVisible(false);
			}
			function callback(position)
			{
				lati = position.coords.latitude;
				longi = position.coords.longitude;
				loc_marker.setPosition(translate_coord(lati, longi));		
			}

			function errorHandler(err) {
				if(err.code == 1) {
				alerti("Hata:Bağlantı İsteği Reddetildi! Malesef Konumunuza Ulaşılamıyor...");
				}else if( err.code == 2) {
				alerti("Hata: Malesef Konumunuza Ulaşılamıyor!");
				}
			}
			
			function calcRoute() 
				{
					var start = translate_coord(lati,longi);
					var route_end = translate_coord(latitude,longitude);
					var request = {
						origin:start,
						destination:route_end,
						travelMode: google.maps.TravelMode[Mode]
						};
				directionsService.route(request, function(response, status) {
						if (status == google.maps.DirectionsStatus.OK) {
							directionsDisplay.setDirections(response);
							}
						});
				};
				function change_mode(r_mode)
					{
					Mode=r_mode;
					hidebackground();
					call_route_when_data_ready();
					}
			function translate_coord(lati,longi)
				{
				var trans_coord=new google.maps.LatLng(lati, longi);
				return trans_coord;
				}
			function hidebackground() {
				$('#black_background').css('display','none');
				$('#light_box_area').css('display','none');
				$('#light_box_area').html('');
				};
		function alerti()
			{
			$('#black_background').css('display','block');
			$('#light_box_area').css('display','block');
			$('#light_box_area').html("<div id=\"alerti\"><div id=\"alerti_text\">Buraya Nasıl Gideceksiniz?</div><div id=\"alerti_okay\" onclick=\"change_mode('DRIVING')\">Araç İle</div><div id=\"alerti_okay\" onclick=\"change_mode('WALKING')\">Yürüyerek</div></div>");
			}	
function add_shopping_list(y_data,y_type_data)
	{
		if(login==1){
		if(fav_busy==0){
		fav_busy=1;
					$('#add_s_list').html('<img height="16" src="https://www.yeroks.com/img_files/cycle_loading.gif"/>');
					$.ajax({
					type:'POST',
					data:'&data='+y_data+'&type_data='+y_type_data+'&type='+4,
					dataType: 'json',
					url:'https://www.yeroks.com/m/ajax/add_fav.php',
					success: function(info) {
					//$("#add_s_list").html(info);
					
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
		window.location="https://www.yeroks.com/m/index.php?redirect=../shop_product.php?s_no="+s_data+"&c_no="+y_type_data;
		}
	}
		function show_pr_lb(y_type_data,y_data)
			{
			$('#black_background').css('display','block');
			$('#light_box_area').css('display','block');
			$('#light_box_area').html("<div onclick=\"hidebackground()\" id=\"search_text_box_exit\"></div><div id=\"lb_main\"><div id=\"lb_img\"><div id=\"lb_b_img_d\"></div><div id=\"lb_s_img_d\"></div></div><div id=\"lb_exp_main\"><div id=\"lb_exp\"><div id=\"lb_y_name\"></div><div id=\"lb_y_price\"></div><div id=\"lb_y_prop\"></div><div id=\"lb_y_exp\"></div></div><div id=\"add_s_list\" onclick=\"add_shopping_list("+y_data+","+y_type_data+")\">Alışveriş Listesine Ekle</div></div></div>");
			$.ajax({
					type:'POST',
					data:'&s_data='+s_data+'&y_data='+y_data+'&y_type_data='+y_type_data,
					dataType: 'json',
					url:'https://www.yeroks.com/ajax/pr_lb_data.php',
					success: function(info) {
					//$("#lb_b_img_d").html(info);
					if(info.error==1)
							{
								alert(info.exp);
							}else
							{
								$('#lb_s_img_d').html("");
								var ii=0;
								$.each(info.imgs,function(i,result){
								$('#lb_s_img_d').append("<img onclick=\"load_b_img('"+result.img+"')\" id=\"lb_s_img\" src=\"https://www.yeroks.com/y_img_small/"+result.img+"\"/>");
								ii=ii+1;
								if(ii==1){ $('#lb_b_img_d').html("<img id=\"lb_b_img\" src=\"https://www.yeroks.com/y_img_big/"+result.img+"\"/>"); }
								});
								if(ii==0){ $('#lb_b_img_d').html("<img id=\"lb_b_img\" style=\"margin-top:30%;height:80px;\" src=\"https://www.yeroks.com/y_img_big/"+info.icon+"\"/>"); }
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
			
		function load_b_img(img_name)
			{
			$('#lb_b_img_d').html("<img id=\"lb_b_img\" src=\"https://www.yeroks.com/y_img_big/"+img_name+"\"/>");
			}
		function fire()
			{
			var he=$("#shop_area").height();
			he=he+20;
			$('#map_holder').css("height",he);
			$('#map_area').css("height",he);
			google.maps.event.trigger(map, "resize");
			}
			function change_p_img(img_data)
			{
			$('#shop_image').css("background-image", "url('https://www.yeroks.com/s_profile_img_small/"+img_data+"')");
			p_image=img_data;
			}
		function open_big_image()
			{
			$('#black_background').css('display','block');
			$('#light_box_area').css('display','block');
			$('#light_box_area').html("<img src=\"https://www.yeroks.com/s_profile_img_big/"+p_image+"\"/>");
			}
	</script>
	<?php
	}
?>
</head>
<body onload="fire()">
<div id="black_background" onclick="hidebackground();" style="display:none;background-color:none;">
</div>
<div id="light_box_area" style="display:none;background-color:none;">
</div>
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
<a href="https://www.yeroks.com/m/profile.php" id="set_button">
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

<div id="show_the_fire">
<?php if($is_login!=1){?>
<div id="s_f_center">
Çevrendeki 
<b>
<?php
echo $new_yerok->show_c_name();
?></b>'ları Görmek ve Onlara Ulaşmak İçin Giriş Yap!...
</div>
<?php }?>
</div>
<div id="content_center">
<div id="contents">
<div id="shop_i_area">
<div id="shop_area">
<div id="shop_image" <?php if(array_key_exists( 1, $check_type->p_img_way)){ echo " onclick=\"open_big_image()\" style=\"  background-image:url('https://www.yeroks.com/s_profile_img_small/".$check_type->p_img_way[1]."')\"";}   ?>></div>
<div id="shop_datas">
<div id="shop_name">
<?php
echo "<a href=\"https://www.yeroks.com/".$s_id."/".$text->set_text($check_type->name)."\">".$check_type->name."</a>";
?>
</div>
<div id="add_fav" onclick="add_fav()" >
<?php
$fav=new favorite();
if($is_login==1){
	if($fav->fav_control($s_id,2,$_SESSION['user_no'],0)){
	echo "<img src=\"https://www.yeroks.com/img_files/added_fav.png\" style=\"height:18px;margin-right:5px;display:inline-block;float:left;margin-top:-4px;\">Favori!";
	}else{
	echo "Favorilere Ekle";
	}
	}else{
	echo "Favorilere Ekle";
	}
?>
</div>
<div id="route" onclick="route()" >Yol Tarifi</div>
</div>
<div id="shop_images">
<?php
for ($i = 1; $i <= 4; $i++) {
   if( array_key_exists( $i, $check_type->p_img_way)){
   echo "<div id=\"s_img_small\" onclick=\"change_p_img('".$check_type->p_img_way[$i]."')\" style=\"background-image:url('https://www.yeroks.com/s_profile_img_small/".$check_type->p_img_way[$i]."')\"></div>";
   }
}
?>
</div>
<?php
if($check_type->place_number!=0)
	{
	echo "<div id=\"avm_data\"><a href=\"https://www.yeroks.com/".$check_type->text_id."\" target=\"_blank\" >".$check_type->place_name."</a> ".$check_type->s_floor.". Kat </div>";
	}
	if($check_type->tel_number!="")
	{
	echo "<div id=\"tel_number\"><div id=\"i_name\">Tel</div> : <div id=\"i_value\">".$check_type->tel_number."</div></div>";
	}
?>
<!--<div id=\"s_center_icon\"></div>-->

<div id="shop_adress">
<?php
echo "<div id=\"i_name\">Adres</div> : <div id=\"i_value\">".$check_type->adress."</div>";
?>
<!--<div id="address_icon"></div>-->
</div>
</div>
<div id="map_holder">
<?php
if($check_type->latitude=='0' or $check_type->longitude=='0' )
	{
	?>
	<div id="map_error_pos">
	Mağaza Konumu Girilmemiş!
	</div>
	<?php
	}else
	{
	?>
	<div id="map_area"></div>
	<?php
	}
	?>
</div>
</div>
<div id="product_area">
<div id="product_title"><img src="https://www.yeroks.com/img_files/list.png"/>Mağazanın <?php echo $new_yerok->show_c_name()." Çeşitleri"; ?> </div>	
<div id="events_area">
<?php
$new_yerok->press_yields_for_web();
?>
</div>
</div>
</div>
<div id="help_info_area">
 2016 &#169 Yeroks İstanbul  <a href="https://www.yeroks.com/shop_index.php">Mağaza Girişi</a><a href="https://www.yeroks.com/about.php">Hakkımızda</a><a href="https://www.yeroks.com/product_list.php">Ürün Çeşitleri</a>
</div>
</div>
</body>
</html>
<?php
}else{
include("error_page/error.php");
}
?>

