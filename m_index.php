<?php
//page_id=2
include("ajax/kjasafhfjaghl.php");
$connect=new connect();
$connect->sql_connect_db();
$connect->enter_active(2,1,0,0,0);
?>
<html>
<head>
<title>
Yeroks
</title>
<meta http-equiv="Content-Type" content="text/HTML"; charset="utf-8" />
<meta http-equiv="content-language" content="tr">
<meta name="keywords" content=" Çevrende Arama Ürünleri Yakında Hemen Ulaşım" />
<meta name="description" content=" Yeroks ile Çevrendeki Binlerce Mağazanın Ürünlerini Arayabilir ve Onlara Ulaşabilirsin!..." />
<meta name = "viewport" content="width=320, user-scalable=0" />
<link rel="shortcut icon" href="img_files/y_icon.png" />
<link href="css/m_index.css" type="text/css" rel="stylesheet"/>
<meta name="google-site-verification" content="w8qCEn7iPfagw_GzhkVy0SkwNaJjEG_Yhc6m2MYlhUM" />
<script type="text/javascript" src="http://www.yeroks.com/scripts/jquery-1.11.1.min.js"></script>
<script src="https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false&key="></script>
<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-41307627-1', 'auto');
  ga('send', 'pageview');

</script>
<script type="text/javascript">
var longi=28.985612; 
var lati=41.037084;
var p_name=0;
var p_data=0;
var y_name='';
var page=2;
var s_icon ={
				url:"http://www.yeroks.com/img_files/loc_marker2.png",
                scaledSize:new google.maps.Size(46, 60),
                origin:new google.maps.Point(0, 0),
                anchor: new google.maps.Point(23, 60)
				};
var markers=[];
var infowindowArray=[];
var loc1=new google.maps.LatLng(41.037084, 28.985612);
var bounds;
var zoom1=15;
var map;
var page_number=1;

	function initialize()
            {
                map = new google.maps.Map(document.getElementById('map_area'), {
                   zoom: zoom1,
                   center: loc1,
				   draggable:false,
				   scrollwheel: false,
                   mapTypeId: google.maps.MapTypeId.ROADMAP
                 });
				 var image =new google.maps.MarkerImage('http://www.yeroks.com/img_files/p_loc_marker3.png',
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

            };
			
	google.maps.event.addDomListener(window, 'load', initialize);
	$(document).mouseup(function (e)
			{  var container = $("#loc_exp");
				if (!container.is(e.target)&& container.has(e.target).length === 0) { container.hide();}
			});	
	function chance_province(no,name,latitude,longitude)
			{
			longi=longitude;
			lati=latitude;
			$('#q_address').html(name);
			map.setZoom(13);
			map.setCenter(translate_coord(latitude,longitude));
			nondisplay_exp();
			loc_marker.setPosition(translate_coord(latitude,longitude));
			}
function get_shop_category_special(cat_id)
	{
	window.open('http://www.yeroks.com/shop.php?s_no='+s_data);
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
					url:'m/ajax/y_select.php',
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
				$('#search_area').html('<div id="search_text_box"><div id="y_icon" style="background-image:url(\'http://www.yeroks.com/y_img_small/'+i+'.png\')"></div><div id="search_text_box_text">'+v+'</div><div OnClick="search_text_box_exit()" id="search_text_box_exit"></div></div>');
				};
				
			function search_text_box_exit(){
				$('#search_area').html('<div id="search_in_place_input_outline"><input type="text" onkeydown="text_typing()" onkeyup="text_typing()" placeholder="Gömlek, Yemek, Dondurma vb." id="search_in_place_input"/></div>');
				$('#yerok_input_data').val(0);
				$('#s_result').html('');
				$('#shops_of_place').css("display","inline-block");
				y_name='';
				deleteMarkers();
				$('#product_title').html('Başka Bir Sürü Arama Yapabilirsin...');
				$('#m_title').html('Ayarlı Konumun');
				var dis_scroll=0;
				var des_des=$('#search_area_main').height()-200;
				dis_scroll=des_des-$('body').scrollTop();
				$('body').animate( { scrollTop: '+='+dis_scroll }, 400);
				map.setZoom(zoom1);
				map.setCenter(loc1);
				map.setOptions({draggable: false});
				map.setOptions({scrollwheel: false});
				}
				//shop_from_yield_search
			function s_from_y_search(source)
				{
				deleteMarkers();
				$('#product_title').html('<b>'+y_name+'</b> Bulabileceğiniz Mağazalar');
				$('#s_result').html('<img height=40 src="http://www.yeroks.com/img_files/cycle_loading.gif"/>');
				$('#shops_of_place').css("display","none");
				var cat_id=document.getElementById('yerok_input_data').value;
				if(cat_id!=0 || cat_id=='')
					{
					$.ajax({
						type:'POST',
						url:'ajax/shop_search_from_yield.php',
						dataType: 'json',					
						data:'&cat_id='+cat_id+'&p_data='+p_data+'&lati='+lati+'&longi='+longi+'&page='+page_number+'&source='+source+'&page_data='+page,
						success: function(yerok) {
						//$('#s_result').html(yerok);
		
						if(yerok.type!=1)
								{
								$('#m_title').html('Ayarlı Konumun');	
								alert(yerok.text);
								$('#s_result').html('');
								}else if(yerok.type==1)
								{
								var result_num=0;
								$('#s_result').html('');
								var distance=0;
								bottom_scroll();
								map.setOptions({draggable: true});
								map.setOptions({scrollwheel: true});
								$.each(yerok.results,function(i,result){
								if(result.distance>1)
								{
								distance=parseFloat(result.distance).toFixed(2)+" Km";
								}else{
								distance=(parseFloat(result.distance).toFixed(2))*1000+" m";
								}
									if(result.place_id==0){
									$('#s_result').append("<div id=\"r_main\"><div id=\"r_shop_main\"><div id=\"r_shop_icon\"></div><div id=\"r_shop_text\" onclick=\"window.open('http://www.yeroks.com/shop.php?shop_no="+result.s_id+"');\" >"+result.s_name+"</div></div><div id=\"r_avm_main\"><div id=\"r_distance\">"+distance+"</div></div></div>");				
									}else{
									$('#s_result').append("<div id=\"r_main\"><div id=\"r_shop_main\"><div id=\"r_shop_icon\"></div><div id=\"r_shop_text\" onclick=\"window.open('http://www.yeroks.com/shop.php?shop_no="+result.s_id+"');\" >"+result.s_name+"</div></div><div id=\"r_avm_main\"><div id=\"r_avm_text\" onclick=\"window.open('http://www.yeroks.com/place.php?place_no="+result.place_id+"');\" >"+result.place_name+"</div><div id=\"r_distance\">"+distance+"</div></div></div>");	
									}
									marker_enter(result.latitude,result.longitude,result.s_name,result.s_id,result.place_id,result.place_name,distance,result.i_n);
									result_num=result_num+1;
									});
								$('#m_title').html('Ayarlı Konumunuza En Yakın '+result_num+' Sonuç');	
								add_click_event();
								}else{
								alert("Bir Sorun Oluştu...");
								$('#s_result').html('');
								}
						set_bound();
							}
						})
					}else{
					alert("Bir Sorun Olustu!");
					}
				}
				
		function  marker_enter(latitude,longitute,s_name,s_data,place_id,place_name,distance,marker_id){
				var Latlng = translate_coord(latitude,longitute);
				
				var marker = new google.maps.Marker({
				position: Latlng,
				map: map,
				icon:s_icon,
				title: s_name,
				id:marker_id
				});
				markers.push(marker);
				if(place_id==0){
				addInfowindow("<div id=\"r_main\"><div id=\"r_shop_main\"><div id=\"r_shop_icon\"></div><div id=\"r_shop_text\" onclick=\"window.open('http://www.yeroks.com/shop.php?shop_no="+s_data+"');\" >"+s_name+"</div></div><div id=\"r_avm_main\"><div id=\"r_distance\">"+distance+"</div></div></div>");				
				}else{
				addInfowindow("<div id=\"r_main\"><div id=\"r_shop_main\"><div id=\"r_shop_icon\"></div><div id=\"r_shop_text\" onclick=\"window.open('http://www.yeroks.com/shop.php?shop_no="+s_data+"');\" >"+s_name+"</div></div><div id=\"r_avm_main\"><div id=\"r_avm_text\" onclick=\"window.open('http://www.yeroks.com/place.php?place_no="+place_id+"');\" >"+place_name+"</div><div id=\"r_distance\">"+distance+"</div></div></div>");
									
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
		function deleteMarkers() {
				for (var i = 0; i < markers.length; i++) {
				markers[i].setMap(null);
				}
				markers = [];
				infowindowArray= [];
				}
		function get_exp_yields(){
		var type=2;
		var cat_num=document.getElementById('y_exp_input').value;
		$.ajax({
					type:'POST',
					url:'ajax/random_cat_export.php',		
					data:'&cat_num='+cat_num+'&type='+type+'&place='+0,    
					success: function(info) {
					$('#y_icons').html(info);
						}
					});
		
		}
		
   var show_exp=1;
   function display_exp()
	{ 
	if( show_exp==1) { $("#loc_exp").slideDown("fast");}
	}
	function nondisplay_exp()
	{
	if( show_exp==1) { $("#loc_exp").slideUp("fast");}
	}
	function dont_show_exp()
	{
	show_exp=0;
	$("#loc_exp").slideUp("fast");
	}
	function left_scroll()
		{
		 $('#y_icons_area').animate( { scrollLeft: '+=100' }, 100);
		return false;
		}
	function right_scroll()
		{
		$('#y_icons_area').animate( { scrollLeft: '-=100' }, 100);
		return false;
		}	
	 function bottom_scroll()
		{
			var dis_scroll=0;
			var des_des=80+$('#search_area_main').height();
			dis_scroll=des_des-$('body').scrollTop();
			if(dis_scroll>0){
			$('body').animate( { scrollTop: '+='+dis_scroll }, 400);
			}
			return false;
		} 
</script>
</head>
<body>
<div id="banner">
<div class="b_c">
<div class="b">
<div id="logo">
<a href="http://www.yeroks.com/index.php"><img src="http://www.yeroks.com/img_files/b_l_b.png" style="margin:auto;height:27px;border:0px;"/></a>
</div>
<div id="login_link" onclick="location.href='http://www.yeroks.com/m/'">
Giriş Yap
</div>
</div>
</div>
<div id="all_search_area">
	<div id="search_area_c">
		<div id="loc_area">
		<div id="loc_c"  onclick="$('#loc_exp').toggle();">
		<div id="loc_icon"></div>
		<div id="q_address">Taksim,istanbul</div>
		<div id="slide_down" class="but_slide_down"></div>
		</div>
		<div id="loc_exp"><div id="loc_exp_t">Giriş Yaparak Gerçek Konumunla Arama Yapabilirsin...</div><div id="loc_exp_p"><div id="province" onclick="chance_province(34,'Taksim, İstanbul',41.036993,28.985579)">Taksim, İstanbul</div><div id="province" onclick="chance_province(35,'Konak, İzmir',38.418755,27.128810)">Konak, İzmir</div><div id="province" onclick="chance_province(6,'Kızılay, Ankara',39.920799,32.854055)">Kızılay,Ankara</div><div id="province" onclick="chance_province(1,'Adana',37.001110,35.323340)">Adana</div><div id="province" onclick="chance_province(2,'Adıyaman',37.760392,38.274726)">Adıyaman</div><div id="province" onclick="chance_province(3,'Afyon',38.754295,30.540226)">Afyon</div><div id="province" onclick="chance_province(4,'Ağrı',39.720882,43.052546)">Ağrı</div><div id="province" onclick="chance_province(5,'Amasya',40.649968,35.828748)">Amasya</div><div id="province" onclick="chance_province(7,'Antalya',36.880528,30.706547)">Antalya</div><div id="province" onclick="chance_province(8,'Artvin',41.180061,41.819271)">Artvin</div><div id="province" onclick="chance_province(9,'Aydın',37.846801,27.843746)">Aydın</div><div id="province" onclick="chance_province(10,'Balıkesir',39.647578,27.884471)">Balıkesir</div><div id="province" onclick="chance_province(11,'Bilecik',40.142945,29.979222)">Bilecik</div><div id="province" onclick="chance_province(12,'Bingöl',38.885459,40.496527)">Bingöl</div><div id="province" onclick="chance_province(13,'Bitlis',38.400583,42.109456)">Bitlis</div><div id="province" onclick="chance_province(14,'Bolu',40.730404,31.602713)">Bolu</div><div id="province" onclick="chance_province(15,'Burdur',37.718634,30.284902)">Burdur</div><div id="province" onclick="chance_province(16,'Bursa',40.182630,29.067488)">Bursa</div><div id="province" onclick="chance_province(17,'Çanakkale',40.147936,26.405545)">Çanakkale</div><div id="province" onclick="chance_province(18,'Çankırı',40.598683,33.619210)">Çankırı</div><div id="province" onclick="chance_province(19,'Çorum',40.550610,34.956180)">Çorum</div><div id="province" onclick="chance_province(20,'Denizli',37.775096,29.086491)">Denizli</div><div id="province" onclick="chance_province(21,'Diyarbakır',37.917824,40.221431)">Diyarbakır</div><div id="province" onclick="chance_province(22,'Edirne',41.675345,26.553201)">Edirne</div><div id="province" onclick="chance_province(23,'Elazığ',38.675004,39.220154)">Elazığ</div><div id="province" onclick="chance_province(24,'Erzincan',39.746580,39.493747)">Erzincan</div><div id="province" onclick="chance_province(25,'Erzurum',39.905837,41.270998)">Erzurum</div><div id="province" onclick="chance_province(26,'Eskişehir',39.778007,30.515913)">Eskişehir</div><div id="province" onclick="chance_province(27,'Gaziantep',37.067679,37.369124)">Gaziantep</div><div id="province" onclick="chance_province(28,'Giresun',40.917134,38.386564)">Giresun</div><div id="province" onclick="chance_province(29,'Gümüşhane',40.458868,39.481091)">Gümüşhane</div><div id="province" onclick="chance_province(30,'Hakkari',37.577421,43.736522)">Hakkari</div><div id="province" onclick="chance_province(31,'Hatay',36.202634,36.160291)">Hatay</div><div id="province" onclick="chance_province(32,'Isparta',37.763212,30.553084)">Isparta</div><div id="province" onclick="chance_province(33,'Mersin',36.799347,34.628322)">Mersin</div><div id="province" onclick="chance_province(36,'Kars',40.606011,43.097812)">Kars</div><div id="province" onclick="chance_province(37,'Kastamonu',41.378981,33.777017)">Kastamonu</div><div id="province" onclick="chance_province(38,'Kayseri',38.722095,35.489052)">Kayseri</div><div id="province" onclick="chance_province(39,'Kırklareli',41.733643,27.222900)">Kırklareli</div><div id="province" onclick="chance_province(40,'Kırşehir',39.144974,34.160652)">Kırşehir</div><div id="province" onclick="chance_province(41,'Kocaeli',40.763677,29.929410)">Kocaeli</div><div id="province" onclick="chance_province(42,'Konya',37.870814,32.492434)">Konya</div><div id="province" onclick="chance_province(43,'Kütahya',39.418312,29.981786)">Kütahya</div><div id="province" onclick="chance_province(44,'Malatya',38.348324,38.315621)">Malatya</div><div id="province" onclick="chance_province(45,'Manisa',38.614000,27.429566)">Manisa</div><div id="province" onclick="chance_province(46,'Kahramanmaraş',37.578887,36.929950)">Kahramanmaraş</div><div id="province" onclick="chance_province(47,'Mardin',37.322455,40.720968)">Mardin</div><div id="province" onclick="chance_province(48,'Muğla',37.215409,28.363710)">Muğla</div><div id="province" onclick="chance_province(49,'Muş',38.734822,41.491183)">Muş</div><div id="province" onclick="chance_province(50,'Nevşehir',38.625115,34.715032)">Nevşehir</div><div id="province" onclick="chance_province(51,'Niğde',37.965874,34.672154)">Niğde</div><div id="province" onclick="chance_province(52,'Ordu',40.986367,37.876974)">Ordu</div><div id="province" onclick="chance_province(53,'Rize',41.024500,40.519691)">Rize</div><div id="province" onclick="chance_province(54,'Sakarya',40.778184,30.400439)">Sakarya</div><div id="province" onclick="chance_province(55,'Samsun',41.289499,36.333000)">Samsun</div><div id="province" onclick="chance_province(56,'Siirt',37.934853,41.940120)">Siirt</div><div id="province" onclick="chance_province(57,'Sinop',42.026585,35.151337)">Sinop</div><div id="province" onclick="chance_province(58,'Sivas',39.747142,37.012286)">Sivas</div><div id="province" onclick="chance_province(59,'Tekirdağ',40.977858,27.510639)">Tekirdağ</div><div id="province" onclick="chance_province(60,'Tokat',40.318734,36.552102)">Tokat</div><div id="province" onclick="chance_province(61,'Trabzon',41.005776,39.728435)">Trabzon</div><div id="province" onclick="chance_province(62,'Tunceli',39.106918,39.548397)">Tunceli</div><div id="province" onclick="chance_province(63,'Şanlıurfa',37.160071,38.792040)">Şanlıurfa</div><div id="province" onclick="chance_province(64,'Uşak',38.679795,29.404661)">Uşak</div><div id="province" onclick="chance_province(65,'Van',38.498723,43.393990)">Van</div><div id="province" onclick="chance_province(66,'Yozgat',39.822111,34.808077)">Yozgat</div><div id="province" onclick="chance_province(67,'Zonguldak',41.453326,31.789471)">Zonguldak</div><div id="province" onclick="chance_province(68,'Aksaray',38.370301,34.027161)">Aksaray</div><div id="province" onclick="chance_province(69,'Bayburt',40.260420,40.227131)">Bayburt</div><div id="province" onclick="chance_province(70,'Karaman',37.182575,33.220083)">Karaman</div><div id="province" onclick="chance_province(71,'Kırıkkale',39.842359,33.507039)">Kırıkkale</div><div id="province" onclick="chance_province(72,'Batman',37.896520,41.130446)">Batman</div><div id="province" onclick="chance_province(73,'Şırnak',37.520800,42.457270)">Şırnak</div><div id="province" onclick="chance_province(74,'Bartın',41.630819,32.337622)">Bartın</div><div id="province" onclick="chance_province(75,'Ardahan',41.112544,42.701038)">Ardahan</div><div id="province" onclick="chance_province(76,'Iğdır',39.922475,44.045681)">Iğdır</div><div id="province" onclick="chance_province(77,'Yalova',40.658228,29.269897)">Yalova</div><div id="province" onclick="chance_province(78,'Karabük',41.196272,32.615721)">Karabük</div><div id="province" onclick="chance_province(79,'Kilis',36.716563,37.114606)">Kilis</div><div id="province" onclick="chance_province(80,'Osmaniye',37.072811,36.248745)">Osmaniye</div><div id="province" onclick="chance_province(81,'Düzce',40.839868,31.159410)">Düzce</div></div></div>
		</div>
			<div id="search_area">
			<div id="search_in_place_input_outline">
			<input type="text" onkeydown="text_typing()" onkeyup="text_typing()" placeholder="Ürün Ara! (Gömlek, Pizza, Bilgisayar......)" id="search_in_place_input"/>
			</div>
			</div>
			<div id="p_search_result_area"></div>
			<input type="hidden" id="yerok_input_data" name="yerok_name" value="0" />	
	</div>
	<div id="s_exp">İhtiyacın Olan Ürünün Hangi Mağazalarda Olduğunu Öğrenmek İçin Arama Yap...</div>
	</div>

</div>
<noscript>
<div id="noscript">
Lütfen Tarayıcınızın Javascript Ayarlarını Aktif Edin.</br>
Sistemimiz Bol Miktarda Javascript Kullanır.
</div>
</noscript>
<div id="search_area_main">
<div id="des_des">
<div id="main_title">Yeroks Seni Çevrendeki Mağaza ve Ürünlere Ulaştırarak Çok Daha Verimli Alışveriş Yapmanı Sağlar...</div>
	<div id="des_area_c">
	<div id="des_area">
	<div id="descpart">
	<div id="part"><div id="part_img"><img src="img_files/search_icon.png" height="100"></div><div style="padding-top:19px;" id="main_text">Ürün veya Mağaza Seç,</div></div>
	</div>
	<div id="descpart">
	<div id="part"><div id="part_img"><img src="img_files/shop_products.png" height="100"></div><div style="padding-top:19px;" id="main_text" >Sonuçları İncele,</div></div>
	</div>
	<div id="descpart">
	<div id="part"><div id="part_img"><img src="img_files/route_shop.png"  height="100"></div><div id="main_text">En Uygununu Al veya Hizmetten Yararlan...</div></div>
	</div>
	</div>
	</div>
<div id="y_icons_area_all">
<div id="y_icons_area_center">
<div id="left" onclick="left_scroll()"></div><div id="right" onclick="right_scroll()"></div>
<div id="y_icons_area">
<div class="y_text">Ürün Seç ve Çevrende Ara...</div>
	<div id="y_icons">
<?php
$sql="SELECT DISTINCT y_type_id,icon_name,y_name FROM y_name_list WHERE  is_y=1 and icon_name!=0 GROUP BY icon_name ORDER BY RAND() LIMIT 0,20";
$sql=mysql_query($sql) or die(mysql_error());

while($res=mysql_fetch_assoc($sql))
	{
	echo "<div id=\"yield_show\" onclick=\"change_value('".$res['y_name']."',".$res['y_type_id'].",".$res['icon_name'].",2)\"><img src=\"http://www.yeroks.com/y_img_small/".$res['icon_name'].".png\" height=40 title=\"".$res['y_name']."\" id=\"e_y_icon\" /><div id=\"yield_show_text\">".$res['y_name']."</div></div>";
	}
?>
</div>
</div>

</div>
</div>
</div>
</div>
<div id="y_exp_change">
<select id="y_exp_input" onchange="get_exp_yields()">
  <option value="1">Tüm Kategoriler</option>
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
  <option value="11">Oyuncak ve Eğlence</option>
</select>

</div>
	
	
	<div id="product_title">Ürün Seçerek Arama Yapabilirsin...</div>
	<div id="s_result"></div>
<div id="map_holder">
	<div id="map_area"></div>
</div>
<div id="r_m_title">
<div id="m_title">
Ayarlı Konumun
</div>
</div>



<div id="description">
<div id="des_title" style="margin-top:30px;font-size:25px;" >Türkiye Genelinde</div>
	<div class="des_area">
	<div id="descpart">
	<div class="part"><div class="part_img"><img src="img_files/shops.png" height="120"></div><div style="font-size:34px;">40.000+ Mağaza</div></div>
	</div>
	<div id="descpart">
	<div class="part"><div class="part_img"><img src="img_files/product.png" height="120"></div><div style="font-size:34px;" >400 bin+ Ürün</div></div>
	</div>
	<div id="descpart">
	<div class="part"><div class="part_img"><img src="img_files/places.png"  height="120"></div><div style="font-size:34px;">250+ AVM</div></div>
	</div>
	</div>
</div>
<div id="description">
	<div id="des_title" style="margin-top:30px;font-size:22px;" >Yeroks Sokakta</div>
	<div class="des_area">
	<div id="descpart">
	<div class="part"><div class="part_img"><img src="img_files/search_icon.png" height="120"></div><div class="part_text" > <b>&#8226;</b> Alacağın Ürünün Çevrende Hangi Kayıtlı Mağazalarda Olduğunu Gösterelim</div></div>
	</div>
	<div id="descpart">
	<div class="part"><div class="part_img"><img src="img_files/shop_products.png" height="120"></div><div class="part_text"> <b>&#8226;</b> Profilindeki Keşfet Butonuyla Bölgendeki Kayıtlı Mağazaları Bilgisayarından Gez</div></div>
	</div>
	<div id="descpart">
	<div class="part"><div class="part_img"><img src="img_files/route_shop.png" height="120"></div><div class="part_text"> <b>&#8226;</b> Ürününü Beğendiğin Mağazanın Kapısına Kadar Seni Yol Tarifi İle Götürelim</div></div>
	</div>
	</div>
</div>
<div id="description">
	<div id="des_title" style="margin-top:30px;font-size:22px;" >Yeroks AVM'lerde</div>
	<div class="des_area">
	<div id="descpart">
	<div class="part"><div class="part_img"><img src="img_files/shops_floors.png" height="120"></div><div class="part_text"> <b>&#8226;</b> Alacağın Ürünün Hangi Kayıtlı Mağazalarda Olduğunu Gör<br> <b>&#8226;</b> Hepsini İncele ve En Uygunu Satın Al</div></div>
	</div>
	<div id="descpart">
	<div class="part"><div class="part_img"><img src="img_files/shops_products.png" height="120"></div><div class="part_text"><b>&#8226;</b> AVM'lerdeki Mağazaları ve Sattıklarını Gör<br> <b>&#8226;</b> Arayıp Durma, İstediğine Direk Ulaş!</div></div>
	</div>
	<div id="descpart">
	<div class="part"><div class="part_img"><img src="img_files/products_foors.png" height="120"></div><div class="part_text"><b>&#8226;</b> AVM'lerde Belli Bir Kategoride Hangi Ürünlerin Olduğunu Gör <br> <b>&#8226;</b> Örneğin Yemek Kategorisini Seç, AVM'deki Mağazalarda Bulunan Yemek Çeşitlerini Gör, Beğendiğini Seç</div></div>
	</div>
	</div>
</div>

<div id="infos">
<div id="infos_c">
<div id="infos_text">
<p>Çevrendeki Ürünlere Daha Rahat Ulaşmak İçin Kaydol veya Mağaza ve Ürünlerinin Bölgende Görünür Olması İçin Mağazanı Kaydet! </p>
</div>
<div id="infos_users">
<div id="user">
<div id="user_img"></div>
<div id="user_button" onclick="location.href='http://www.yeroks.com/m/'" >Yeroks'a Katıl</div>
</div>
<div id="shop">
<div id="shop_img"></div>
<div id="shop_button" onclick="location.href='http://www.yeroks.com/shop_index.php'" >Mağazanı Kaydet</div>
</div>



</div>
</div>
</div>

<div id="help_info_area">
 2016 &#169 Yeroks İstanbul 
<div style="margin-top:20px;"> <a href="shop_index.php">Mağaza Girişi</a><a href="about.php">Hakkımızda</a><a href="product_list.php">Ürün Çeşitleri</a>
</div>
</div>

</body>
</html>
