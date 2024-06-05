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
<?php
$shop_id=$_SESSION['user_no'];
$pos_func=new shop_map_set();
$pos_func->is_pos_okay($shop_id);
echo $_SESSION['user_full_name'];
?>
</title>
<meta http-equiv="Content-Type" content="text/HTML"; charset="utf-8" />
<link rel="shortcut icon" href="http://www.yeroks.com/img_files/yeroks_icon.png" />
<link href="css/m_shop_setting.css" type="text/css" rel="stylesheet" />
<meta name = "viewport" content="width=320, user-scalable=0" />
<script type="text/javascript" src="http://www.yeroks.com/scripts/jquery-1.11.1.min.js"></script>
<script type="text/javascript" src="http://www.yeroks.com/scripts/jquery-form.js"></script>
<script src="https://maps.googleapis.com/maps/api/js?v=3.exp&signed_in=true&libraries=places&key="></script>
<script type="text/javascript">

var is_map_working=0;
var map;
<?php
	if($pos_func->is_set_okay==1)
		{
		?>
		var is_set_okay=1;
		<?php
		}else
		{
		?>
		var is_set_okay=0;
		<?php
		}
?>
var center_position=new google.maps.LatLng(40.631341, 35.433772);
var shop_marker;

function get_shops_center_to_select()
		{
		var e = document.getElementById("province_option");
		var value = e.options[e.selectedIndex].value;
		$.ajax({
					type:'POST',
					url:'aj_pos/shop_center_data.php',		
					data:'value='+value,
					success: function(information) {
					$('#shop_center_data').html(information);
						}
					});
		}
	
	$(document).on('keyup', '.adress_input', function() {
		var adress=document.getElementById("adress_input").value;
		
		if(adress.length > 10)
			{
				$("#adress_next").css("display","block");
			}else{
			$("#adress_result").html("");
			$("#adress_next").css("display","none");
			
			}
		
		
		});
function adress_save(type)
		{
		$("#adress_next img").css("display","block");
		$("#right_image").css("display","none");
		if(type==1)
		{
		var tel=document.getElementById("tel_input").value;
		var adress=document.getElementById("adress_input").value;
		}else{
		var tel=document.getElementById("tel_avm_input").value;
		var adress='';
		}
		$.ajax({
					type:'POST',
					url:'aj_pos/adress_save.php',	
					dataType: 'json',					
					data:'&adress='+adress+'&tel='+tel+'&type='+type,
					success: function(data) {
					if(data.problem==0)
							{
								if(type==1)
								{
								$("#adress_result").html(data.explain);
								$("div").remove("#get_adress_area");
								$("div").remove("#shop_center_select");
								$("div").remove(".shop_type_select");
								$("#street_shop_select_area").css("display","block");
								initialize();
								}else{
								$("div").remove("#get_avm_adress_area");
								$("#shop_center_select").css("display","block");
								}
							}else if(data.problem==1)
							{
							alert(data.explain);
							}else
							{
							alert("Bir Hata Oluştu");
							}
						}
					});
		$("#adress_next img").css("display","none");
		$("#right_image").css("display","block");
		}
		
function get_adress()
		{
		$('.shop_type_select').css('display','none');
		$('#get_adress_area').css('display','inline-block');
		}
function street_shop_select()
		{
		$('.shop_type_select').css('display','none');
		$('#street_shop_select_area').css('display','inline-block');
		initialize();
		google.maps.event.trigger(map, 'resize');
		}
function shop_center_select(center_name,data)
		{
		$("#shop_center_data").html("<div id=\"selected_shop_center\">"+center_name+"</div>");
		$("#shop_center_data").append("<div class=\"shop_type_text\">Kaçıncı Kattasınız?</div>");
		$("#shop_center_data").append("<div id=\"floor_div\"><select id=\"floor\" onchange=\"display_button()\"><option value=-9>-9.Kat</option><option value=-8>-8.Kat</option><option value=-7>-7.Kat</option><option value=-6>-6.Kat</option><option value=-5>-5.Kat</option><option value=-4>-4.Kat</option><option value=-3>-3.Kat</option><option value=-2>-2.Kat</option><option value=-1>-1.Kat</option><option value=0>0.Kat(Zemin)</option><option value=1>1.Kat</option><option value=2>2.Kat</option><option value=3>3.Kat</option><option value=4>4.Kat</option><option value=5>5.Kat</option><option value=6>6.Kat</option><option value=7>7.Kat</option><option value=8>8.Kat</option><option value==9>9.Kat</option></select><div id=\"save_button_area\"></div><div id=\"shop_center_result\"></div></div>");
		document.getElementById("selected_shop_center").value=data;
		}
function display_button()
		{
		var e = document.getElementById("floor");
		var floor = e.options[e.selectedIndex].value;
		document.getElementById("selected_floor").value=floor;
		$("#save_button_area").html("<div id=\"save_button\" onclick=\"save_shop_center_pos()\">Kaydet</div>");
		}

function initialize() {

  var markers = [];
  map = new google.maps.Map(document.getElementById('big_map_area'), {
    zoom: 5,
    center: center_position,
    mapTypeId: google.maps.MapTypeId.HYBRID
  });
	var shop_icon =new google.maps.MarkerImage('img_files/shop_icon3.png',
                new google.maps.Size(60, 60),
                new google.maps.Point(0, 0),
                new google.maps.Point(30, 30));
				
				shop_marker = new google.maps.Marker({
				position: center_position,
				map: map,
				size: google.maps.Size(40, 40),
				draggable:true,
				icon: shop_icon,
				title:"Konumun"
				});
  // Create the search box and link it to the UI element.
  var input = /** @type {HTMLInputElement} */(
      document.getElementById('pac-input'));
  map.controls[google.maps.ControlPosition.TOP_LEFT].push(input);

  var searchBox = new google.maps.places.SearchBox(
    /** @type {HTMLInputElement} */(input));

  // Listen for the event fired when the user selects an item from the
  // pick list. Retrieve the matching places for that item.
  google.maps.event.addListener(searchBox, 'places_changed', function() {
    var places = searchBox.getPlaces();

    if (places.length == 0) {
      return;
    }
    for (var i = 0, marker; marker = markers[i]; i++) {
      marker.setMap(null);
    }

    // For each place, get the icon, place name, and location.
    markers = [];
    var bounds = new google.maps.LatLngBounds();
    for (var i = 0, place; place = places[i]; i++) {
      var image = {
        url: place.icon,
        size: new google.maps.Size(71, 71),
        origin: new google.maps.Point(0, 0),
        anchor: new google.maps.Point(17, 34),
        scaledSize: new google.maps.Size(25, 25)
      };

      // Create a marker for each place.
      var marker = new google.maps.Marker({
        map: map,
        icon: image,
        title: place.name,
        position: place.geometry.location
      });

      markers.push(marker);

      bounds.extend(place.geometry.location);
    }

    map.fitBounds(bounds);
	shop_marker.setPosition(map.getCenter());
  });

  // Bias the SearchBox results towards places that are within the bounds of the
  // current map's viewport.
  google.maps.event.addListener(map, 'bounds_changed', function() {
    var bounds = map.getBounds();
    searchBox.setBounds(bounds);
  });
  is_map_working=1;
}

	function initialize_re_set()
            {
				var shop_pos=new google.maps.LatLng(<?php echo $pos_func->latitude.",".$pos_func->longitude ;?>);
                map = new google.maps.Map(document.getElementById('set_okay_map'), {
                   zoom: 17,
                   center: shop_pos,
                   mapTypeId: google.maps.MapTypeId.HYBRID
                 });
				 var image =new google.maps.MarkerImage('img_files/shop_icon3.png',
                new google.maps.Size(60, 60),
                new google.maps.Point(0, 0),
                new google.maps.Point(30, 30));
				
				red_marker = new google.maps.Marker({
				position: shop_pos,
				map: map,
				size: google.maps.Size(60, 60),
				draggable:false,
				icon: image,
				title:"Mağazanızın Konumu"
				});
			is_map_working=1;
            };
$(document).mouseup(function (e)
			{  var container = $("#buttons");
				if (!container.is(e.target)&& container.has(e.target).length === 0) { container.hide();}
			});
function save_shop_center_pos()
		{
		var place=document.getElementById('selected_shop_center').value;
		var floor=document.getElementById('selected_floor').value;
			$.ajax({
					type:'POST',
					url:'aj_pos/shop_set_center.php',	
					dataType: 'json',					
					data:'&place='+place+'&floor='+floor,
					success: function(data) {
					if(data.problem==0)
							{
							$('#shop_center_result').html('Konum Kaydı Başarıyla Gerçekleştirildi...');
							window.location='position_setting.php';
							}else if(data.problem==1)
							{
							$('#shop_center_result').html(data.express);
							}else
							{
							$('#shop_center_result').html("Bir Hata Oluştu");
							}
						}
					});
		
		
		}
function re_set_pos()
		{
		$.ajax({
					type:'POST',
					url:'aj_pos/map_setting_part.php',
					success: function(information) {
					$('#recent_pos').html(information);
						}
					});
		}
function map_loc_save()
		{
		$("#map_loc_save img").css("display","block");
		var loc_lat=shop_marker.getPosition().lat();
		var loc_long=shop_marker.getPosition().lng();
		var zoom=map.getZoom();
		$.ajax({
					type:'POST',
					url:'aj_pos/street_shop_set.php',
					dataType: 'json',
					data:'&loc_lat='+loc_lat+'&loc_long='+loc_long+'&zoom='+zoom,
					success: function(data) {
					$('#map_loc_result').html(data);
					if(data.problem==0)
							{
							$('#map_loc_result').html('Konum Kaydı Başarıyla Gerçekleştirildi...');
							window.location='position_setting.php';
							}else if(data.problem==1)
							{
							$('#map_loc_result').html(data.express);
							}else
							{
							$('#map_loc_result').html("Bir Hata Oluştu");
							}
						}
					});
		$("#map_loc_save img").css("display","none");
		}
function gps_request()
		{
		$("#step_1_img").html("<img src=\"img_files/yeroks_mail_okay.png\" height=\"50\" style=\"margin: 10px;\">");
		$(".gps-request-button").css("display","none");
		$("#gps-exp-1").html("E-Posta Kayıtlı E-Postanıza Gönderildi.");
		
		}
function gps_request_from()
		{
		$.ajax({
					type:'POST',
					url:'aj_pos/gps_request_form.php',
					success: function(information) {
					$('#display_operation').html(information);
						}
					});
		$('#recent_pos').css('display','none');
		$('#display_operation').css('display','inline-block');
		}

</script>
</head>
<body onload="initialize_re_set()">
<div id="banner">
<div class="b_c">
<div id="logo">
<a href="http://www.yeroks.com/m/profile.php" class="logo_y">
<img src="img_files/yeroks_s_logo.png" style="height:25px;border:none;margin:auto;"/>
</a>
</div>
<div class="user_banner">
<div id="user_name">
<img src=" <?php
if(array_key_exists( 1, $check_type->p_img_way)){
echo "s_profile_img_small/".$check_type->p_img_way[1];
}else{
echo "img_files/shopping.png";
}
?> " style="height: 27px;width: 27px;float: left;overflow: hidden;padding: 2px;background-color: white;" />
<div id="user_name_text">
<a href="shop_profile.php">
<?php
echo $check_type->name;
?>
</a>
</div>
</div>
<div id="slide_down" class="but_slide_down" onclick="$('#buttons').toggle();"></div>
<div id="buttons">
<a id="button_t" href="shop_profile.php">Profil</a>
<a id="button_t" href="account_setting.php" >Ayarlar</a>
<a id="button_t" href="shop_index.php?type=1">Çıkış Yap</a>
</div>
</div>
</div>
</div>
<div id="content_center">
<div id="t_but">
<div onclick="window.location='account_setting.php'" id="button">Hesap Ayarları</div>
<div onclick="window.location='position_setting.php'" id="button" style="border-bottom: 1px solid red;">Konum Ayarları</div>
<div onclick="window.location='image_setting.php'" id="button">Resim Ayarları</div>
</div>	

<div id="pos_set_area">
<div id="recent_pos">
<?php
	if($pos_func->is_set_okay==1)
		{
		?>
		<div id="map_set_big">
		<div style="  width: 100%;display: inline-block;">
		<div id="re_set_pos" onclick="re_set_pos()" >
		Yeni Konum Ayarı
		</div>
		</div>
		<div id="set_okay_map">
		</div>
		<?php if($pos_func->get_adress_tel($shop_id)){?>
		<div id="address"><b>Adres:</b><?php echo $pos_func->adress;  ?></div>
		<div id="tel_number"><b>Telefon Numarası:</b><?php echo $pos_func->tel_number; ?></div>
		<?php } ?>
		</div>
		<?php
		}else
		{
		include("aj_pos/map_setting_part.php");
		}
?>
</div>
</div>

<div id="help_info_area" style="margin-top:50px">
 <div id="c_right">2016 &#169 Yeroks İstanbul</div>
 <div id="info_butts">
 <a href="shop_index.php">Mağaza Girişi</a><a href="about.php">Hakkımızda</a><a href="product_list.php">Ürün Çeşitleri</a>
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



















