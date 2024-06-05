<?php
//page_id=7
session_start();
include("ajax/kjasafhfjaghl.php");
$connect=new connect();
$connect->sql_connect_db();
$login=new log_in();
$place=new place();	
$error=0;
$shop_id=0;
$is_login=0;
$place_id=0;
$t_no;
	if(isset($_GET['place_no'])) { 
	$place_id=mysql_real_escape_string(htmlspecialchars(trim((int)$_GET['place_no']))); 
	}elseif(isset($_GET['t_no'])){
		$t_no=mysql_real_escape_string(htmlspecialchars(trim($_GET['t_no'])));
		if( $place->is_there_place($t_no)){$place_id=$place->place_id; }else{ $error=1;}
	}else{
	$error=1;
	}
	if($error!=1){ if(!$place->get_place_id($place_id)){ $error=1;} }
	if($error!=1){ if(!$place->get_info_of_place()){$error=3;}  }
	
	if($error==0){ 
	
	if($login->is_login(1) ){ $is_login=1; }
	$connect->enter_active(7,1,0,0,$place_id);
	$place->get_place_img();
?>
<html>
<head>
<title> <?php
	echo $place->name;
?> </title>
<meta name="description" content="<?php echo $place->name."’deki";?> ve Çevrendeki Diğer Yüzlerce AVM'nin Ürün ve Mağazalarını Görebilir ve İnceleyebilirsin!..." />
<meta name="keywords" content="<?php echo $place->name;?> Ürünleri Mağazaları " />
<meta http-equiv="Content-Type" content="text/HTML"; charset="utf-8" />
<meta http-equiv="content-language" content="tr"/>
<link rel="shortcut icon" href="https://www.yeroks.com/img_files/y_icon.png" />
<script type="text/javascript" src="https://www.yeroks.com/scripts/jquery-1.11.1.min.js"></script>
<script type="text/javascript" src="https://www.yeroks.com/scripts/jquery-form.js"></script>
<link href="https://www.yeroks.com/css/place.css" type="text/css" rel="stylesheet" />
<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-41307627-1', 'auto');
  ga('send', 'pageview');


<?php
if($place->latitude!='0' and $place->longitude!='0' )
	{
	?>
	</script>
	<script src="https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false&key="></script>
	<script type="text/javascript">
	var map;
	var directionsDisplay;
	var directionsService = new google.maps.DirectionsService();
	 function initialize()
            {
				var shop_pos=new google.maps.LatLng(<?php echo $place->latitude.",".$place->longitude ;?>);
                map = new google.maps.Map(document.getElementById('map_area'), {
                   zoom: 12,
                   center: shop_pos,
                   mapTypeId: google.maps.MapTypeId.ROADMAP
                 });
				  directionsDisplay = new google.maps.DirectionsRenderer();
				 var image =new google.maps.MarkerImage('https://www.yeroks.com/img_files/loc_marker6.png',
                new google.maps.Size(66, 60),
                new google.maps.Point(0, 0),
                new google.maps.Point(33, 60));
				
				red_marker = new google.maps.Marker({
				position: shop_pos,
				map: map,
				size: google.maps.Size(60, 60),
				draggable:false,
				icon: image,
				title:"Mağazanızın Konumu"
				});
			directionsDisplay.setMap(map);
			directionsDisplay.setOptions( { suppressMarkers: true } );
			create_loc_marker();
            };
			
			
			google.maps.event.addDomListener(window, 'load', initialize);	
	
	<?php	}	?>
	
var latitude=<?php echo $place->latitude;?>;
var longitude=<?php echo $place->longitude;?>;
var Mode='DRIVING';
var lati=0;
var longi=0;
var page=7;
var p_name='<?php echo $place->name; ?>';
var p_data=<?php echo $place_id; ?>;
var fav_busy=0;
var login=<?php echo $is_login; ?> ;
var y_name='';
var page_number=1;
var p_image=<?php if(array_key_exists( 1, $place->place_img)){ echo "'".$place->place_img[1]."'";}else{ echo "''";}	?>;

function get_shop_category_special(cat_id)
	{
	window.open('https://www.yeroks.com/shop.php?s_no='+s_data);
	}
	
	function add_fav()
	{
		if(login==1){
		if(fav_busy==0){
		fav_busy=1;
					$('#add_fav').html('<img height="16" src="https://www.yeroks.com/img_files/cycle_loading.gif"/>');
					$.ajax({
					type:'POST',
					data:'&data='+p_data+'&type_data='+0+'&type='+3,
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
		window.location="https://www.yeroks.com/m/index.php?redirect=../place.php?place_no="+p_data;
		}
	}
	
	
	
	function text_typing(){
				var valuenew=document.getElementById('search_in_place_input').value;
				if(valuenew==""){
				$('#p_search_result_area').html("");
				$('#p_search_result_area').css("display","none");
				}else{
				$('#p_search_result_area').css("display","inline-block");
				$.ajax({
					type:'POST',
					url:'https://www.yeroks.com/m/ajax/y_select.php',
					dataType: 'json',					
					data:"valuenew="+valuenew,
					success: function(yerok) {
					
					if(yerok.result==0)
					{
					$('#p_search_result_area').html("<ul>Böyle Bir Ürün Bulunmamakta...</ul>");	
					}else
					{
						$('#p_search_result_area').html("");
						$.each(yerok.y_names,function(i,res){
						
							if(res.type==1)
								{
									$('#p_search_result_area').append("<ul onClick=\"change_value('"+res.y_name+"',"+res.y_id+","+res.icon+",1)\" return false;>"+res.y_name+"</ul>");
								}else if(res.type==0)
								{
									$('#p_search_result_area').append("<ul style=\"border-bottom:1px solid red;\" onClick=\"change_value('"+res.y_name+"',"+res.y_id+")\" return false;>"+res.y_name+"</ul>");
								}
							});	
					}
					
							}
						});
						}
					}
					
			function change_value(v,k,i,source){
				$('#p_search_result_area').css("display","none");
				$('#y_input').val(v);
				$('#yerok_input_data').val(k);
				input_change_to_box(v,i);
				$('#p_search_result_area').html('');
				y_name=v.replace("<b>", "");
				y_name=y_name.replace("</b>", "");
				s_from_y_search(source);
				}
			function input_change_to_box(v,i){
				$('#search_input_area').html('<div id="search_text_box"><div id="y_icon" style="background-image:url(\'https://www.yeroks.com/y_img_small/'+i+'.png\')"></div><div id="search_text_box_text">'+v+'</div><div OnClick="search_text_box_exit()" id="search_text_box_exit"></div></div>');
				};
				
			function search_text_box_exit(){
				$('#search_input_area').html('<div id="search_in_place_input_outline"><input type="text" onkeydown="text_typing()" onkeyup="text_typing()" placeholder="Ürün Ara(Gömlek, Pizza vb." id="search_in_place_input"/></div>');
				$('#yerok_input_data').val(0);
				$('#s_result').html('');
				$('#shops_of_place').css("display","inline-block");
				y_name='';
				$('#product_title').html('<img src="https://www.yeroks.com/img_files/list.png"/>'+p_name+'\'deki Mağazalar');
				$('body').animate( { scrollTop:0 }, 400);
				}
				//shop_from_yield_search
			function s_from_y_search(source)
				{
				$('#product_title').html('<img src="https://www.yeroks.com/img_files/list.png"/><b>'+y_name+'</b> Bulabileceğiniz Mağazalar');
				$('#s_result').html('<img height=40 src="https://www.yeroks.com/img_files/cycle_loading.gif"/>');
				$('#shops_of_place').css("display","none");
				var cat_id=document.getElementById('yerok_input_data').value;
				if(cat_id!=0 || cat_id=='')
					{
					$.ajax({
						type:'POST',
						url:'https://www.yeroks.com/ajax/shop_search_from_yield.php',
						dataType: 'json',					
						data:'&cat_id='+cat_id+'&p_data='+p_data+'&lati='+lati+'&longi='+longi+'&page='+page_number+'&source='+source+'&page_data='+page,						
						success: function(yerok) {
						//$('#show_area').html(yerok);
						
						if(yerok.type!=1)
								{
								alert(yerok.text);
								$('#s_result').html('');
								}else if(yerok.type==1)
								{
								$('#s_result').html('');
								var floor_num=-100;
								$.each(yerok.results,function(i,result){
										if(floor_num!=result.floor){
										//$('#s_result').append("<div id=\"floor_num\">"+result.floor+". Kat</div>");
										floor_num=result.floor;
										}
										$('#s_result').append("<div onclick=\"window.open('https://www.yeroks.com/shop.php?shop_no="+result.s_id+"');\" id=\"p_shop\"><div id=\"p_shop_ic\"></div>"+result.s_name+"<div id=\"s_floor\">"+result.floor+". Kat</div></div>");								
									});
								bottom_scroll();
								}else{
								alert("Bir Sorun Olustu...");
								$('#s_result').html('');
								}
					
							}
						})
					}else{
					alert("Bir Sorun Olustu!");
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
			
		window.onbeforeunload = confirmExit;
		
		function confirmExit(){
			
			return null;
		}
			$(document).mouseup(function (e)
			{  var container = $("#buttons");
				if (!container.is(e.target)&& container.has(e.target).length === 0) { container.hide();}
			});
			
		function get_exp_yields(type){
		var cat_num=document.getElementById('y_exp_input').value;
		$.ajax({
					type:'POST',
					url:'https://www.yeroks.com/ajax/random_cat_export.php',		
					data:'&cat_num='+cat_num+'&type='+type+'&place='+p_data,   
					success: function(info) {
					$('#exp_y_show').html(info);
						}
					});
		
		}	
		function bottom_scroll()
		{
			var dis_scroll=0;
			dis_scroll=400-$('body').scrollTop();
			if(dis_scroll>0){
			$('body').animate( { scrollTop: '+='+dis_scroll }, 400);
			}
			return false;
		} 	
			function fire()
			{
			var he=$("#place_info").height();
			$('#map_holder').css("height",he);
			$('#map_area').css("height",he);
			google.maps.event.trigger(map, "resize");
			}	
			function change_p_img(img_data)
			{
			$('#place_image').css("background-image", "url('https://www.yeroks.com/p_img_big/"+img_data+"')");
			p_image=img_data;
			}
		function open_big_image()
			{
			$('#black_background').css('display','block');
			$('#light_box_area').css('display','block');
			$('#light_box_area').html("<img src=\"p_img_big/"+p_image+"\"/>");
			}
</script>
</head>
<body onload="fire()" >
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


<div id="search_area">
		<div id="search_select_area">
		<div id="loc_area">
		<div id="loc_area_box">
		<div id="loc_icon"></div>
		<div id="q_address"><?php echo $place->name; ?></div>
		</div>
		</div>
		<div id="search_input_area">
		<div id="search_in_place_input_outline">
		<input type="text" onkeydown="text_typing()" onkeyup="text_typing()" placeholder="Ürün Ara! (Gömlek, Pizza ......)" id="search_in_place_input">
		</div>
		</div>
		<div id="p_search_result_area"></div>
		<input type="hidden" id="yerok_input_data" name="yerok_name" value="0"/>
		</div>
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
<b>
<?php
echo $place->name;
?></b> ve Çevrendeki Diğer Yüzlerce AVM'nin Ürünlerine Ulaşmak İçin Giriş Yap!...
</div>
<?php } ?>
</div>
<div id="content">
<div id="content_center">
<div id="contents">

<div id="left_content">
<div id="place_info">
<div id="place_image" <?php if(array_key_exists( 1, $place->place_img)){ echo " onclick=\"open_big_image()\" style=\"background-image:url('https://www.yeroks.com/p_img_big/".$place->place_img[1]."');\" >"; }else{ echo "><img style=\"margin-top:30px;\" height=120 src=\"https://www.yeroks.com/img_files/place_icon.png\"/>";} ?> </div>
<div id="place_images">
<?php
for ($i = 1; $i <= count($place->place_img); $i++) {
   if( array_key_exists( $i, $place->place_img)){
   echo "<div id=\"p_img_small\" onclick=\"change_p_img('".$place->place_img[$i]."')\" style=\"background-image:url('https://www.yeroks.com/p_img_small/".$place->place_img[$i]."')\"></div>";
   }
}
?>
</div>

<div id="place_name">
<?php
echo $place->name;
?>
</div>
<div id="r_fav_area">
<div id="add_fav" onclick="add_fav()">
<?php
$fav=new favorite();
if($is_login==1){
	if($fav->fav_control($place_id,3,$_SESSION['user_no'],0)){
	echo "<img src=\"https://www.yeroks.com/img_files/added_fav.png\" style=\"height:18px;margin-right:5px;display:inline-block;float:left;margin-top:-4px;\">Favori!";
	}else{
	echo "Favorilere Ekle";
	}
	}else{
	echo "Favorilere Ekle";
	}
?>
</div>
<div id="route"  onclick="route()" >Yol Tarifi</div>
</div>
<div id="shop_adress">
<div id="i_name">Adres:</div>
<div id="i_value"> <?php echo $place->address; ?> </div>
</div>

</div>

<div id="map_holder">
<?php
if($place->latitude=='0' or $place->longitude=='0' )
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
</div>
</div>
</div>

<div id="exp_yields">
<div id="exp_y_title">
<?php echo $place->name; ?>'nin Bazı Ürünleri  <select id="y_exp_input" onchange="get_exp_yields(1)">
 <option value="1">Bütün Kategoriler</option>
  <option value="30">Yiyecek</option>
  <option value="31">İçecek</option>
  <option value="32">Yemek Aparatları</option>
  <option value="40">Bayan Giyim</option>
  <option value="41">Erkek Giyim</option>
  <option value="42">Çocuk Giyim</option>
  <option value="4">Kozmetik</option>
  <option value="5">Elektronik</option>
  <option value="6">Ev Gereçleri</option>
  <option value="7">Dekorasyon-Mobilya</option>
  <option value="8">Kitap-Dergi</option>
  <option value="9">Market Ürünleri</option>
  <option value="10">Spor, Outdoor</option>
  <option value="11">Oyuncak ve Eğlence Ürünleri</option>
</select> 
</div>
<div id="exp_y_show">
<?php
$sql="SELECT shop_y_list.y_type_id, y_name_list.y_name,y_name_list.icon_name FROM shop_y_list LEFT JOIN
  y_name_list ON shop_y_list.y_type_id=y_name_list.y_type_id WHERE   
  shop_y_list.shop_id IN( SELECT shop.s_id FROM shop WHERE shop.place_number=$place_id  ) GROUP BY y_name_list.icon_name ORDER BY RAND() LIMIT 0, 20 ";
$sql=mysql_query($sql) or die(mysql_error());
while($res=mysql_fetch_assoc($sql))
	{
	?>
	<div id="yield_show" onclick="change_value('<?php echo $res['y_name']."',".$res['y_type_id'].",".$res['icon_name'].",2"; ?>)">
	<img src="https://www.yeroks.com/y_img_small/<?php echo $res['icon_name'].".png";?>" height="40" title="<?php echo $res['y_name']?>" id="e_y_icon">
	<div id="yield_show_text"><?php echo $res['y_name']?></div></div>
	<?php
	}
?>
</div>
</div>

<div id="content">
<div id="content_center">
<div id="contents">
<div id="right_all">
<div id="events_area">
<div id="show_area">
<div id="product_title"><img src="img_files/list.png"/> <?php echo $place->name."'deki Mağazalar"; ?></div>
	<div id="s_result"></div>
	<div id="shops_of_place">
	<?php $place->get_shops_of_place(); ?>
	</div>
</div>
</div>
</div>

</div>
<div id="help_info_area">
 2016 &#169 Yeroks İstanbul  <a href="https://www.yeroks.com/shop_index.php">Mağaza Girişi</a><a href="https://www.yeroks.com/about.php">Hakkımızda</a><a href="https://www.yeroks.com/product_list.php">Ürün Çeşitleri</a>
</div>
</div>
</div>
</body>
</html>
<?php
}else{
include("error_page/error.php");
}
?>
