<?php
/*
magaza cesitleri

restoran
Cafe
Temizleme






*/
//page_id=13
session_start();
include("ajax/sql.php");
$connect=new connect();
$connect->sql_connect_db();
$connect->enter_active(13,1,0,0,0);
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
<link href="css/c_profile.css" type="text/css" rel="stylesheet" />
<script type="text/javascript" src="script/jquery-1.9.1.min.js"></script>
</head>
<body onload="resize();">
<div id="black_background" onclick="hidebackground();" style="display:none;background-color:none;">
</div>
<div id="light_box_area" style="display:none;background-color:none;">
</div>
<div id="top_banner">
<div id="y_banner_center">
<div id="yeroks_logo">
<img src="../img_files/b_l_b.png" height=31 style="float:left;margin:0px 0px 0px 10px;"  />
</div>
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
<div id="search_area">
		<div id="search_select_area">
		<div id="loc_area">
		<div id="loc_area_box">
		<div id="loc_icon"></div>
		<div id="q_address">Canlı Konum</div>
		<div class="slide_down"></div>
		</div>
		<div id="loc_slide">
		<div onclick="set_draggable_loc()" id="loc_select">Harita Üzerinde Seçim Modu</div>
		<div onclick="set_live_loc()" id="loc_select">Canlı Konum</div>
		<div onclick="new_s_loc_form()" id="new_loc_enter">Yeni Arama Konumu Gir</div>
		</div>
		</div>
		<div id="search_input_area">
		<div id="search_in_place_input_outline">
		<input type="text" onkeydown="text_typing()" onkeyup="text_typing()" placeholder="Ürün, Mağaza, Avm ara! (Gömlek, Dondurma......)" id="search_in_place_input">
		</div>
		</div>
		<div id="p_search_result_area"></div>
		<input type="hidden" id="yerok_input_data" name="yerok_name" value="0"/>
		</div>
	</div>
</div>
</div>

<div id="main_area">
	<div id="main_c">
	<div id="r_type_area">
			<div id="r_type" class="list_button" onclick="list_toggle()" style="border-bottom: 5px solid #d00000;background-image:url('../img_files/list.png');background-color: #f0f0f0;">Liste Sonuçlar</div>
			<div id="r_type" class="map_button" onclick="map_toggle()" style="background-image:url('image/map_icon.png');" >Harita</div>
			<div id="r_type" class="explore_button" onclick="explore_toggle()" style="background-image:url('../img_files/explore.png');" >Keşfet</div>
	</div>
	<div id="category_area">
	<div id="category_title">Kategoriler</div>
	<div id="product_list"><!-- Kategori listesinin bulundugu alan --->
	</div>
	<div id="product_cat_link">
	</div><!-- Urun kategori yolu gorunecek --->
	<div id="product_show_area"><!-- Kategori listesinin bulundugu alan --->
	</div>
	</div>
	
	<div id="r_result_area">
	
	<div id="results_area">
	<div id="text_area">
	Ürün Seçerek Arama Yapabilirsin...
<!--liste sonuclar gosterilir-->
	</div>
	<div id="map_area">
<!--sonuclar haritada gosterilir-->
	<div id="map"></div>
	</div>
	<div><div id="backforw_area"></div></div>
	</div>
	</div>
	
	</div>
</div>

<div id="content">
<div id="c_center">
<div id="exp_of_around">
<!--cevredeki oneriler burada gosterilir-->
</div>

</div>
</div>
<script src="https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false&key="></script>
<script type="text/javascript">
var yield_name='';
//search_type aramanin magaza, urun veya avm mi oldugunu belirler
search_type=0;
var loc_choose=1;//1 is live 2 is draggable 3 is user location
var longi=28.985612; 
var lati=41.037084;
var p_name=0;
var p_data=0;
var y_name='';
var page=13;
var markers=[];
var infowindowArray=[];
var loc1=new google.maps.LatLng(41.037084, 28.985612);
var bounds;
var zoom1=15;
var map;
var is_explore=0;
var icons = {
							1: {icon: '../img_files/loc_marker2.png'},
							2: {icon: '../img_files/loc_marker6.png'}
			};
var page_number=1;
var is_there_result=0;//arama sonucu olmadini ve sonraki butonunu basilmamasini gosterir
///harita funksiyonlari
function Location(){
   if(navigator.geolocation){
      // timeout at 60000 milliseconds (60 seconds)
      var options = {timeout:60000};
      watchID = navigator.geolocation.watchPosition(callback, 
                                     errorHandler,
                                     options);	
   }else{
      alerti("Tarayıcınız Konum Bulma Servisini Desteklemiyor.Konumunuzu Taksim Istanbul olarak ayarladık...");
   }
}

	$( window ).resize(function() {
		resize();
	});
	
function resize()
	{
		var main_c=$("#main_c").width();
		var c_area=$("#category_area").width();
		var result_area=main_c-c_area-1;
		$('#r_result_area').css('width',result_area);
		$('#map_area').css('width',result_area);
		$('#r_type_area').css('width',result_area);
		if(main_c>1160){
		$('#search_area').css('margin-left',"143px");
		}else if(main_c>1100){
		$('#search_area').css('margin-left',"80px");
		}else{
		$('#search_area').css('margin-left',"5px");
		}
	}
function callback(position)
	{
		if(loc_choose==1){
	 lati = position.coords.latitude;
	 longi = position.coords.longitude;
	loc_marker.setPosition(translate_coord(lati, longi));
		}
	}

function errorHandler(err) {
	if(loc_choose==1){
  if(err.code == 1) {
    alerti("Hata:Bağlantı İsteği Reddedildi! Konumunuzu Taksim İstanbul Olarak Ayarladık...");
  }else if( err.code == 2) {
    alerti("Hata: Konumunuza Ulaşılamıyor! Konumunuzu Taksim İstanbul Olarak Ayarladık...");
  }
  }
}
function change_s_location(lati_n,longi_n,loc_name)
			{
			lati = lati_n;
			longi = longi_n;
			loc_marker.setPosition(translate_coord(lati_n, longi_n));
			live_position=3;
			$('#q_address').html(loc_name);
			map.setCenter(translate_coord(lati_n, longi_n));
			loc_marker.setOptions({draggable: false});
			}
function set_live_loc()
			{
			live_position=1;
			$('#q_address').html("Canlı Konum");
			location;
			loc_marker.setOptions({draggable: false});
			map.setCenter(translate_coord(lati, longi));
			}
function set_draggable_loc(){
			live_position=2;
			$('#q_address').html("Harita Üzerinde Seçim Modu");
			loc_marker.setOptions({draggable: true});
			map.setCenter(translate_coord(lati, longi));
}
function alerti(text)
	{
	$('#black_background').css('display','block');
	$('#light_box_area').css('display','block');
	$('#light_box_area').html("<div id=\"alerti\"><div id=\"alerti_text\">"+text+"</div><div id=\"alerti_okay\" onclick=\"hidebackground()\">Tamam</div></div>");
	}
function hidebackground() {
		$('#black_background').css('display','none');
		$('#light_box_area').css('display','none');
		$('#light_box_area').html('');
		};
function initialize()
            {
			Location();
                map = new google.maps.Map(document.getElementById('map'), {
                   zoom: zoom1,
                   center: loc1,
                   mapTypeId: google.maps.MapTypeId.ROADMAP
                 });
				 var image =new google.maps.MarkerImage('../img_files/p_loc_marker3.png',
                new google.maps.Size(50, 50),
                new google.maps.Point(0, 0),
                new google.maps.Point(25, 50));
				
				loc_marker = new google.maps.Marker({
				position: loc1,
				map: map,
				size: google.maps.Size(60, 60),
				draggable:false,
				icon: image,
				title:"Ayarlı Konumun"
				});
				map.addListener('dragend', function() {
							if(is_explore==1){explore_shop_place();}
						});
						map.addListener('zoom_changed', function() {
							if(is_explore==1){explore_shop_place();}
						});
				google.maps.event.addListener(loc_marker ,'dragend',function(overlay,point){
				lati=loc_marker.getPosition().lat();
				longi=loc_marker.getPosition().lng();
				});
            };
			
	google.maps.event.addDomListener(window, 'load', initialize);
				
		function  marker_enter(latitude,longitute,s_name,s_data,place_id,place_name,distance,marker_id,icon_type,design_type){
				var Latlng = translate_coord(latitude,longitute);
				
				var marker = new google.maps.Marker({
				position: Latlng,
				map: map,
				icon:icons[icon_type].icon,
				title: s_name,
				id:marker_id
				});
				markers.push(marker);
				if(design_type==1){
						if(place_id==0){
						addInfowindow("<div id=\"r_main\"><div id=\"r_shop_main\"><div id=\"r_shop_icon\"></div><div id=\"r_shop_text\" onclick=\"window.open('../shop.php?shop_no="+s_data+"');\" >"+s_name+"</div></div><div id=\"r_avm_main\"><div id=\"r_distance\">"+distance+"</div></div></div>");				
						}else{
						addInfowindow("<div id=\"r_main\"><div id=\"r_shop_main\"><div id=\"r_shop_icon\"></div><div id=\"r_shop_text\" onclick=\"window.open('../shop.php?shop_no="+s_data+"');\" >"+s_name+"</div></div><div id=\"r_avm_main\"><div id=\"r_avm_text\" onclick=\"window.open('../place.php?place_no="+place_id+"');\" >"+place_name+"</div><div id=\"r_distance\">"+distance+"</div></div></div>");
						}
					}else{
						if(place_id==0){
						addInfowindow("<div id=\"r_main\"><div id=\"r_shop_main\"><div id=\"r_shop_icon\"></div><div id=\"r_shop_text\" onclick=\"window.open('../shop.php?shop_no="+s_data+"');\" >"+s_name+"</div></div></div>");				
						}else{
						addInfowindow("<div id=\"r_main\"><div id=\"r_shop_main\"><div id=\"r_avm_icon\"></div><div id=\"r_shop_text\" onclick=\"window.open('../place.php?place_no="+place_id+"');\" >"+place_name+"</div></div></div>");	
						}
					}
				};
			
			//infowindow create
			function addInfowindow(string) {
			infowindow = new google.maps.InfoWindow({content: string});
			infowindowArray.push(infowindow);
			}
			
			function add_click_event(){
				bounds = new google.maps.LatLngBounds();
						for( var i=0;i<markers.length;i++) {
						 google.maps.event.addListener(markers[i], 'click', (function(i) {
						return function() {
						infowindowArray[i].open(map,markers[i]);
						}
						})(i));
						}		
				}
			function set_bound()
				{
				bounds.extend(loc_marker.getPosition());
				for( var i=0;i<markers.length;i++) {
				bounds.extend(markers[i].getPosition());
				}
				map.fitBounds(bounds);
				}
			function deleteMarkers() {
				for (var i = 0; i < markers.length; i++) {
				markers[i].setMap(null);
				}
				markers = [];
				infowindowArray= [];
			
				}
		function translate_coord(lati,longi)
			{
			var trans_coord=new google.maps.LatLng(lati, longi);
			return trans_coord;
			}

//arama kismina yazilinca otomatik urun kategorilerini cagirir
function text_typing(){
				var valuenew=document.getElementById('search_in_place_input').value;
				if(valuenew==""){
				$('#p_search_result_area').html("");
				$('#p_search_result_area').css("display","none");
				}else{
				$('#p_search_result_area').css("display","inline-block");
				$.ajax({
					type:'POST',
					url:'ajax/y_s_p_select.php',
					dataType: 'json',					
					data:"valuenew="+valuenew,
					success: function(yerok) {
					$("#p_search_result_area").html(yerok);
					
					if(yerok.result==0)
					{
					$('#p_search_result_area').html("<div id=\"no_select\">Herhangi Bir Sonuç Bulunamadı...</div>");	
					}else
					{
						var y_count=0;
						var s_count=0;
						var p_count=0;
						$('#p_search_result_area').html("");
						$.each(yerok.results,function(i,res){
							if(res.r_type==1){
							$('#p_search_result_area').append("<div id=\"y_select\" onClick=\"change_value('"+res.name+"',"+res.id+","+res.icon+","+res.r_type+",1)\" >"+res.name+"</div>");
								y_count=y_count+1;
								}
						});
							if(y_count==0){ $('#p_search_result_area').append("<div id=\"no_select\">Herhangi Bir Ürün Bulunamadı...</div>"); }
						$.each(yerok.results,function(i,res){
							if(res.r_type==2){
							$('#p_search_result_area').append("<div id=\"s_select\" onClick=\"change_value('"+res.name+"',"+res.id+",'shopping1',"+res.r_type+",1)\"><div id=\"r_s_icon\"></div>"+res.name+"</div>");
								}
						});
						$.each(yerok.results,function(i,res){
							if(res.r_type==3){
							$('#p_search_result_area').append("<div id=\"p_select\" onClick=\"change_value('"+res.name+"',"+res.id+",'place_icon1',"+res.r_type+",1)\"><div id=\"r_p_icon\"></div>"+res.name+"</div>");
								}
						});
					}
					
							}
						});
						}
					}

function s_search_from_y(source)
				{
				if(is_explore==1){map_toggle();}
				deleteMarkers();
				$('#backforw_area').html('');
				$('#text_area').html('<img height=40 src="../img_files/cycle_loading.gif"/>');
				var cat_id=document.getElementById('yerok_input_data').value;
				if(cat_id!=0 || cat_id!='')
					{
					$.ajax({
						type:'POST',
						url:'../ajax/shop_search_from_yield.php',
						dataType: 'json',					
						data:'&cat_id='+cat_id+'&p_data='+p_data+'&lati='+lati+'&longi='+longi+'&page='+page_number+'&source='+source+'&page_data='+page,
						success: function(yerok) {
						if(yerok.type!=1)
								{
								alert(yerok.text);
								$('#text_area').html('');
								is_there_result=0;
								}else if(yerok.type==1)
								{
								var result_num=0;
								$('#text_area').html('');
								var distance=0;
								$.each(yerok.results,function(i,result){
								if(result.distance>1)
								{
								distance=parseFloat(result.distance).toFixed(2)+" Km";
								}else{
								distance=(parseFloat(result.distance).toFixed(2))*1000+" m";
								}
									if(result.place_id==0){
									$('#text_area').append("<div id=\"r_main\"><div id=\"r_shop_main\"><div id=\"r_shop_icon\"></div><div id=\"r_shop_text\" onclick=\"window.open('../shop.php?shop_no="+result.s_id+"');\" >"+result.s_name+"</div></div><div id=\"r_avm_main\"><div id=\"r_distance\">"+distance+"</div></div></div>");				
									}else{
									$('#text_area').append("<div id=\"r_main\"><div id=\"r_shop_main\"><div id=\"r_shop_icon\"></div><div id=\"r_shop_text\" onclick=\"window.open('../shop.php?shop_no="+result.s_id+"');\" >"+result.s_name+"</div></div><div id=\"r_avm_main\"><div id=\"r_avm_text\" onclick=\"window.open('../place.php?place_no="+result.place_id+"');\" >"+result.place_name+"</div><div id=\"r_distance\">"+distance+"</div></div></div>");
									}
									marker_enter(result.latitude,result.longitude,result.s_name,result.s_id,result.place_id,result.place_name,distance,result.i_n,1,1);
									result_num=result_num+1;
									});	
								add_click_event();
								is_there_result=1;
								}else{
								alert("Bir Sorun Oluştu...");
								$('#text_area').html('');
								is_there_result=0;
								}
						set_bound();
						press_back_forw_buttons(source);
							}
						})
					}else{
					alert("Bir Sorun Oluştu!");
					}
				}
function press_back_forw_buttons(source)
	{
	//page_number o anda basili olan sayfa numarasi degerindedir.
	$('#backforw_area').html('');
	if(page_number==1 && is_there_result==0){
	$('#backforw_area').html('');
	}else if(page_number==1){
	$('#backforw_area').html('<div id="forward_page_n_area"><div id="page_number">'+page_number+'</div><div id="forward_r" onclick="go_forward('+source+')" >Sonraki</div></div>');
	}else if(page_number!=1 && is_there_result==0){
	$('#backforw_area').html('<div id="backward_r" onclick="go_backward('+source+')" >Önceki</div><div id="forward_page_n_area"><div id="page_number">'+page_number+'</div></div>');
	}else{
	$('#backforw_area').html('<div id="backward_r" onclick="go_backward('+source+')" >Önceki</div><div id="forward_page_n_area"><div id="page_number">'+page_number+'</div><div id="forward_r" onclick="go_forward('+source+')" >Sonraki</div></div>');
	}
	}
function go_forward(source){page_number=page_number+1; s_search_from_y(source); }
function go_backward(source){page_number=page_number-1;s_search_from_y(source); }

function s_search_from_shop(source)
				{
				if(is_explore==1){map_toggle();}
				deleteMarkers();
				$('#text_area').html('<img height=40 src="../img_files/cycle_loading.gif"/>');
				var shop_type=document.getElementById('yerok_input_data').value;
				if(shop_type!=0 || shop_type!='')
					{
					$.ajax({
						type:'POST',
						url:'ajax/s_search_from_shop.php',
						dataType: 'json',					
						data:'shop_type='+shop_type+'&lati='+lati+'&longi='+longi+'&source='+source+'&page_data='+page,
						success: function(yerok) {
						//	$('#text_area').html(yerok);
						
						if(yerok.type!=1)
								{	
								alert(yerok.text);
								$('#text_area').html('');
								}else if(yerok.type==1)
								{
									var result_num=0;
									$('#text_area').html('');
									var distance=0;
									$.each(yerok.results,function(i,result){
										if(result.distance>1)
										{
										distance=parseFloat(result.distance).toFixed(2)+" Km";
										}else{
										distance=(parseFloat(result.distance).toFixed(2))*1000+" m";
										}
										if(result.place_id==0){
										$('#text_area').append("<div id=\"r_main\"><div id=\"r_shop_main\"><div id=\"r_shop_icon\"></div><div id=\"r_shop_text\" onclick=\"window.open('../shop.php?shop_no="+result.s_id+"');\" >"+result.s_name+"</div></div><div id=\"r_avm_main\"><div id=\"r_distance\">"+distance+"</div></div></div>");				
										}else{
										$('#text_area').append("<div id=\"r_main\"><div id=\"r_shop_main\"><div id=\"r_shop_icon\"></div><div id=\"r_shop_text\" onclick=\"window.open('../shop.php?shop_no="+result.s_id+"');\" >"+result.s_name+"</div></div><div id=\"r_avm_main\"><div id=\"r_avm_text\" onclick=\"window.open('../place.php?place_no="+result.place_id+"');\" >"+result.place_name+"</div><div id=\"r_distance\">"+distance+"</div></div></div>");
										}
										marker_enter(result.latitude,result.longitude,result.s_name,result.s_id,result.place_id,result.place_name,distance,result.i_n,1,1);
										result_num=result_num+1;
										});	
								add_click_event();
								}else{
								alert("Bir Sorun Oluştu...");
								$('#text_area').html('');
								}
						set_bound();
						
							}
						})
					}else{
					alert("Bir Sorun Oluştu!");
					}
				}
function p_search_from_place(source)
				{
				if(is_explore==1){map_toggle();}
				deleteMarkers();
				$('#text_area').html('<img height=40 src="../img_files/cycle_loading.gif"/>');
				var p_data=document.getElementById('yerok_input_data').value;
				if(p_data!=0 || p_data!='')
					{
					$.ajax({
						type:'POST',
						url:'ajax/place_search_from_place.php',
						dataType: 'json',					
						data:'p_data='+p_data+'&lati='+lati+'&longi='+longi+'&source='+source+'&page_data='+page,
						success: function(yerok) {
						if(yerok.type!=1)
								{
								alert(yerok.text);
								$('#text_area').html('');
								}else if(yerok.type==1)
								{
									var result_num=0;
									$('#text_area').html('');
									var distance=0;
									$.each(yerok.results,function(i,result){
										if(result.distance>1)
										{
										distance=parseFloat(result.distance).toFixed(2)+" Km";
										}else{
										distance=(parseFloat(result.distance).toFixed(2))*1000+" m";
										}
										$('#text_area').append("<div id=\"exp_p_main\"><div id=\"exp_p_place_icon\"></div><div id=\"exp_p_avm_main\"><div id=\"exp_p_avm_text\" onclick=\"window.open('../place.php?place_no="+result.place_id+"');\" >"+result.place_name+"</div><div id=\"exp_p_distance\">"+distance+"</div></div></div>");
										marker_enter(result.latitude,result.longitude," "," ",result.place_id,result.place_name,distance,result.i_n,2,2);
										result_num=result_num+1;
										});	
								add_click_event();
								}else{
								alert("Bir Sorun Oluştu...");
								$('#text_area').html('');
								}
						set_bound();
							}
						})
					}else{
					alert("Bir Sorun Oluştu!");
					}
				}
//
function change_value(v,k,i,type,source){
//v is name of search term
//i is icon of search term
// k is search data of search term
//type is to show searching type which might be product, shop or shopping center 
				$('#p_search_result_area').css("display","none");
				$('#y_input').val(v);
				$('#yerok_input_data').val(k);
				$('#p_search_result_area').html('');
				y_name=v.replace("<b>", "");
				y_name=y_name.replace("</b>", "");
				input_change_to_box(v,i);
				if(type==1){ s_search_from_y(source);}
				if(type==2){ s_search_from_shop(source);}
				if(type==3){ p_search_from_place(source);}
				page_number=1;
				}
function input_change_to_box(v,i){
				$('#search_input_area').html('<div id="search_text_box"><div id="y_icon" style="background-image:url(\'../y_img_small/'+i+'.png\')"></div><div id="search_text_box_text">'+v+'</div><div OnClick="search_text_box_exit()" id="search_text_box_exit"></div></div>');
				};
				//search functions
				
function search_text_box_exit(){
				$('#search_input_area').html('<div id="search_in_place_input_outline"><input type="text" onkeydown="text_typing()" onkeyup="text_typing()" placeholder="Ürün, Mağaza, Avm ara! (Gömlek, Dondurma......)" id="search_in_place_input"/></div>');
				$('#yerok_input_data').val(0);
				$('#text_area').html('');
				$('#backforw_area').html('');
				$('#shops_of_place').css("display","inline-block");
				$("#results_area ").css("min-height",$("#category_area").height()-41);
				y_name='';
				deleteMarkers();
				page_number=1;
				}	
				
$("#loc_area_box").click(function(e) {
    e.preventDefault();
    e.stopPropagation();
	$("#loc_slide").css("display","inline-block");
});

$(document).click(function() {
	$("#loc_slide").css("display","none");
	$("#buttons").css("display","none");
});

$(".but_slide_down").click(function(e) {
    e.preventDefault();
    e.stopPropagation();
	$("#buttons").css("display","inline-block");
});

function list_toggle(){
$( "#map_area" ).css("left","-1000%");
$( "#map_area" ).css("position","absolute");
$( "#text_area" ).css("display","inline-block");
$( ".list_button" ).css("border-bottom","5px solid #d00000");
$( ".map_button" ).css("border","none");
$( ".explore_button" ).css("border","none");
$( ".list_button" ).css("background-color","#f0f0f0");
is_explore=0;
}
function map_toggle(){
$( "#map_area" ).css("left","0");
$( "#map_area" ).css("position","relative");
$( "#text_area" ).css("display","none");
$( ".list_button" ).css("border","none");
$( ".map_button" ).css("border-bottom","5px solid #d00000");
$( ".explore_button" ).css("border","none");
$( ".map_button" ).css("background-color","#f0f0f0");
if(is_explore==1){ deleteMarkers(); is_explore=0; }
}
function explore_toggle()
{
$( "#map_area" ).css("left","0");
$( "#map_area" ).css("position","relative");
$( "#text_area" ).css("display","none");
$( ".list_button" ).css("border","none");
$( ".map_button" ).css("border","none");
$( ".explore_button" ).css("border-bottom","5px solid #d00000");
is_explore=1;
search_text_box_exit();
explore_shop_place();
}
	
var y_back_id=0;
//onceki kategori
function get_cat_list(type_number)
	{
	$('#product_list').html('<img style="margin-top:74px;" height=40 src="../img_files/cycle_loading.gif"/>');
	$('#product_show_area').css('opacity','0.1');
	$.ajax({
					type:'POST',
					url:'ajax/get_category_list.php',
					dataType: 'json',					
					data:"type_number="+type_number,
					success: function(yerok) {
					$('#product_cat_link').html("");
					$.each(yerok.category_way,function(i,way){
							$("#product_cat_link").append("<div id=\"w_ca\" onclick=\"get_cat_list("+way.type_no+")\" >&#8226 "+way.c_name+" </div>");
					});
					if(yerok.result==0)
					{
					$('#product_list').html(yerok.exp);	
					}else
					{
						$('#product_list').html("");
						y_back_id=yerok.y_back_id;
						$.each(yerok.results,function(i,res){
									if(res.is_y==1)
									{
									$('#product_list').append("<div id=\"cat_list\"><div id=\"cat_list_text\">&#8226 "+res.y_name+"<div id=\"cat_search_icon\" onclick=\"change_value('"+res.y_name+"',"+res.y_type_id+","+res.icon_name+",1,3)\"></div></div></div>");
									}else{
									$('#product_list').append("<div id=\"cat_list\" style=\"cursor:pointer\" onclick=\"get_cat_list("+res.y_type_id+")\"><div id=\"cat_list_text\"> "+res.y_name+"</div><div id=\"cat_list_right\"></div></div>");
									}
							});
						$('#product_show_area').html("");	
							$.each(yerok.products,function(i,res1){
							$('#product_show_area').append("<div id=\"yield_show\" onclick=\"change_value('"+res1.y_name+"',"+res1.y_type_id+","+res1.icon_name+",1,2)\"><img src=\"../y_img_small/"+res1.icon_name+".png\" height=\"40\" title=\""+res1.y_name+"\" id=\"e_y_icon\"><div id=\"yield_show_text\">"+res1.y_name+"</div></div>");
							});
							
					}
							if(yerok.is_show_back_button==1){
							$('#product_list').append("<div id=\"cat_list_back\" onclick=\"go_cate_back()\">Geri Dön</div>");
							}
					$('#product_show_area').css('opacity','1');
					$("#results_area ").css("min-height",$("#category_area").height()-41);
					}
						});
						
						
	}
	
	function get_product_at_around()
		{
			$.ajax({
					type:'POST',
					url:'ajax/get_product_at_around.php',
					dataType: 'json',					
					data:'&lati='+lati+'&longi='+longi,
					success: function(yerok) {
					if(yerok.result==0)
					{
					$('#exp_of_around').html(yerok.text);	
					}else
					{
					$('#exp_of_around').append("<div>");
						var distance=0;
						$.each(yerok.results,function(i,result){
								if(result.distance>1)
								{
								distance=parseFloat(result.distance).toFixed(2)+" Km";
								}else{
								distance=(parseFloat(result.distance).toFixed(2))*1000+" m";
								}
									if(result.place_id==0){
									$('#exp_of_around').append("<div id=\"r_main\"><div id=\"yield_show\"><img src=\"../y_img_small/"+result.icon_name+".png\" height=\"40\" title=\""+result.y_name+"\" id=\"e_y_icon\"><div id=\"yield_show_text\">"+result.y_name+"</div></div><div id=\"r_shop_main\"><div id=\"r_shop_icon\"></div><div id=\"r_shop_text\" onclick=\"window.open('../shop.php?shop_no="+result.s_id+"');\" >"+result.s_name+"</div></div><div id=\"r_avm_main\"><div id=\"r_distance\">"+distance+"</div></div></div>");				
									}else{
									$('#exp_of_around').append("<div id=\"r_main\"><div id=\"yield_show\"><img src=\"../y_img_small/"+result.icon_name+".png\" height=\"40\" title=\""+result.y_name+"\" id=\"e_y_icon\"><div id=\"yield_show_text\">"+result.y_name+"</div></div><div id=\"r_shop_main\"><div id=\"r_shop_icon\"></div><div id=\"r_shop_text\" onclick=\"window.open('../shop.php?shop_no="+result.s_id+"');\" >"+result.s_name+"</div></div><div id=\"r_avm_main\"><div id=\"r_avm_text\" onclick=\"window.open('place.php?place_no="+result.place_id+"');\" >"+result.place_name+"</div><div id=\"r_distance\">"+distance+"</div></div></div>");
									
									}
									});
					$('#exp_of_around').append("<div>");									
					}
							}
						});
		}
		function get_shops_and_places_at_around()
		{
			$.ajax({
					type:'POST',
					url:'ajax/get_shops_and_places_at_around.php',
					dataType: 'json',					
					data:'&lati='+lati+'&longi='+longi,
					success: function(yerok) {
					if(yerok.result==0)
					{
					$('#exp_of_around').html(yerok.text);	
					}else
					{
$('#exp_of_around').append("<div>");
$('#exp_of_around').append("<div id=\"exp_title\">Ürünlerini İnceleyebileceğin Bazı Mağazalar<div>");
						var distance=0;
						$.each(yerok.results,function(i,result){
						if(result.type==0){
								if(result.distance>1)
								{
								distance=parseFloat(result.distance).toFixed(2)+" Km";
								}else{
								distance=(parseFloat(result.distance).toFixed(2))*1000+" m";
								}
									if(result.type==0){
									$('#exp_of_around').append("<div id=\"exp_s_main\"><div id=\"exp_s_shop_main\"><div id=\"exp_s_shop_icon\"></div><div id=\"exp_s_shop_text\" onclick=\"window.open('../shop.php?shop_no="+result.id+"');\" >"+result.name+"</div></div><div id=\"exp_s_avm_main\"><div id=\"exp_s_distance\">"+distance+"</div></div></div>");				
									}else{
									$('#exp_of_around').append("<div id=\"exp_s_main\"><div id=\"exp_s_shop_main\"><div id=\"exp_s_shop_icon\"></div><div id=\"exp_s_shop_text\" onclick=\"window.open('../shop.php?shop_no="+result.id+"');\" >"+result.name+"</div></div><div id=\"exp_s_avm_main\"><div id=\"exp_s_avm_text\" onclick=\"window.open('../place.php?place_no="+result.place_id+"');\" >"+result.place_name+"</div><div id=\"exp_s_distance\">"+distance+"</div></div></div>");
									
									}
								}
									});
$('#exp_of_around').append("</div>");
$('#exp_of_around').append("<div>");	
$('#exp_of_around').append("<div id=\"exp_title\">Ürün ve Mağazalarını İnceleyebileceğin Bazı AVMler<div>");								
						$.each(yerok.results,function(i,result){
						if(result.type==0){
								if(result.distance>1)
								{
								distance=parseFloat(result.distance).toFixed(2)+" Km";
								}else{
								distance=(parseFloat(result.distance).toFixed(2))*1000+" m";
								}
									$('#exp_of_around').append("<div id=\"exp_p_main\"><div id=\"exp_p_place_icon\"></div><div id=\"exp_p_avm_main\"><div id=\"exp_p_avm_text\" onclick=\"window.open('../place.php?place_no="+result.place_id+"');\" >"+result.place_name+"</div><div id=\"exp_p_distance\">"+distance+"</div></div></div>");
								}
									});
$('#exp_of_around').append("</div>");
					}
					
							}
						});
		}
		
		///kesfet fonksiyonu
		function explore_shop_place()
			{
				deleteMarkers();
				var bounds=map.getBounds();
				var ne = bounds.getNorthEast();
				var sw = bounds.getSouthWest();
				var nelat=ne.lat();
				var nelng=ne.lng();
				var swlat=sw.lat();
				var swlng=sw.lng();
				$.ajax({
					type:'POST',
					url:'ajax/explore_shop_place.php',		
					dataType: 'json',
					data:'&nelat='+nelat+'&nelng='+nelng+'&swlat='+swlat+'&swlng='+swlng,
					success: function(info) {				
					var i_n=0;
					$.each(info.results,function(i,result){
					i_n=i_n+1;
						if(result.type==0){
						marker_enter(result.lati,result.longi,result.name,result.id,0,"","",i_n,1,2);
						}else{
						marker_enter(result.lati,result.longi,"","",result.id,result.name,"",i_n,2,2);
						}
					});
					add_click_event();
						
						
						}
					});
			}
function go_cate_back()
	{
	get_cat_list(y_back_id);
	}
$( window ).load(function() {
get_cat_list(0);
get_shops_and_places_at_around();
get_user_s_location();
});

function get_user_s_location(){
					$.ajax({
					type:'POST',
					url:'ajax/get_user_s_locations.php',		
					dataType: 'json',
					success: function(info) {
					$('#loc_slide').html('');
					if(info.result==1){
					$.each(info.locs,function(i,result){
					$('#loc_slide').append('<div onclick="change_s_location('+result.lati+','+result.longi+',\''+result.name+'\')" id="loc_select">'+result.name+'<div onclick="delete_loc_level1(\''+result.name+'\','+result.id+')" id="waste"></div></div>');	
					});
					}
					$('#loc_slide').append('<div onclick="set_draggable_loc()" id="loc_select">Harita Üzerinde Seçim Modu</div><div onclick="set_live_loc()" id="loc_select">Canlı Konum</div><div onclick="new_s_loc_form()" id="new_loc_enter">Yeni Arama Konumu Gir</div>');	
						}
					});
}
function delete_loc_level1(name,id){
	$('#black_background').css('display','block');
	$('#light_box_area').css('display','block');
	$('#light_box_area').html("<div id=\"alerti\"><div id=\"alerti_text\"><b>"+name+"</b> Adındaki Kayıtlı Konumunuzu Silmek İstediğinizden Emin misiniz?</div><div id=\"alerti_okay\" onclick=\"hidebackground()\">Vazgeç</div><div id=\"alerti_okay\" onclick=\"delete_loc_level2('"+name+"',"+id+")\">Sil</div></div>");
}


function delete_loc_level2(name,id){
				$('#alerti').html('<img height=40 src="../img_files/cycle_loading.gif"/>');
				$.ajax({
					type:'POST',
					url:'ajax/delete_user_location.php',		
					data:'&id='+id,
					success: function(info) {
						alerti(info);
						get_user_s_location();
						}
					});

}

function new_s_loc_form()
	{
	map_toggle();
	$("#loc_slide").css("display","none");
	$('#black_background').css('display','block');
	$('#light_box_area').css('display','block');
	$('#light_box_area').html('<div id="new_loc_enter_area"><div id="search_in_place_input_outline"><input type="text" placeholder="Arama Konumu İsmi(Ev, İşyeri vb.)" class="new_loc_input" id="search_in_place_input"></div><div id="alerti_okay" style="float:left" onclick="hide_loc_form()">Vazgeç</div><div onclick="save_new_loc()" style="float:right" id="alerti_okay">Kaydet</div></div>');
	$('#map_area').css({'z-index':'999','left':'0','position' : 'fixed','top' : '140px'});
	loc_marker.setOptions({draggable: true});
	}
function hide_loc_form()
	{
	hidebackground();
	$('#map_area').css({'position' : 'absolute','top' : 'auto','z-index':'1'});
	loc_marker.setOptions({draggable: false});
	map_toggle();
	}
function save_new_loc()
	{
	var loc_name=$('.new_loc_input').val();
	loc_name=loc_name.trim();
	if(loc_name!=''){
	hide_loc_form();
	var latitude=loc_marker.getPosition().lat();
	var longitude=loc_marker.getPosition().lng();
	$('#black_background').css('display','block');
	$('#light_box_area').css('display','block');
	$('#light_box_area').html('<div id="alerti"><img height=40 src="../img_files/cycle_loading.gif"/></div>');
				$.ajax({
					type:'POST',
					url:'ajax/save_user_location.php',		
					data:'&loc_name='+loc_name+'&latitude='+latitude+'&longitude='+longitude,
					success: function(info) {
						alerti(info);
						get_user_s_location();
						}
					});
			}
	}
</script>
</body>
</html>
<?php
}
?>
