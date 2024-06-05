<?php
session_start();
if(isset($_SESSION['user_type'])  and isset($_SESSION['user_no']) and isset($_SESSION['mail_adress']) and isset($_SESSION['login']))
	{
	if($_SESSION['login']==1 and $_SESSION['user_type']==2 )
		{
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
<link href="css/gps_request_profile.css" type="text/css" rel="stylesheet" />
<script type="text/javascript" src="scripts/jquery-1.11.1.min.js"></script>
<script src="http://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false"></script>
<script type="text/javascript">
var live_position=1;
var center_pos=1;
var taksim_position=new google.maps.LatLng(41.036988, 28.985674);
var red_marker;
var current_lat;
var current_long;
var map;
var marker_lat;
var marker_long;
var optimum_lat;
var optimum_long;
var infowindowArray=[];
var accuracy=18000;
var optimum_accuracy=100000;
var circle;
var position_data=0;
var time=0;
var geo_state=-1;
var current_resize_map=-1;
var refreshIntervalId;

function translate_coord(lati,longi)
	{
	var trans_coord=new google.maps.LatLng(lati, longi);
	return trans_coord;
	}
function change_current_pos()
	{
	//position of taksim 
	current_lat=41.036988;
	current_long=28.985674;
	}

function Location(){
   if(navigator.geolocation){
      // timeout at 60000 milliseconds (60 seconds)
      var options = {enableHighAccuracy: true,
  timeout: 5000,
  maximumAge: 0};
      watchID = navigator.geolocation.watchPosition(callback, 
                                     errorHandler,
                                     options);
      geo_state=0;
	  //0 means suport geo property
   }else{
      geo_state=3;
	  //3 means not suport geo property
   }
}

function callback(position)
	{
	 
	 current_lat = position.coords.latitude;
	 current_long = position.coords.longitude;
 	 accuracy=position.coords.accuracy;
	 check_optimum();
	 geo_state=0;
	}
	
	function check_optimum()
	{
	if(live_position===1 && optimum_accuracy>accuracy)
		{
		red_marker.setPosition(translate_coord(current_lat, current_long));
		push_marker_pos_in_variables();
		circle.setRadius(accuracy);
		circle.setCenter(translate_coord(current_lat, current_long));
		map.fitBounds(circle.getBounds());
		optimum_lat=current_lat;
	    optimum_long=current_long;
		optimum_accuracy=accuracy;
		if(center_pos===1)
				{
				map.setCenter(translate_coord(marker_lat, marker_long));
				}
		}
	}
	
function is_geo_working()
	{
		if(geo_state==1){
			$("#map_area").html("<div id=\"alert\">Lütfen Konumunuzu Paylaşınız veya Cihazınızın Konum Özelliğini Açınız ve Sayfayı Yenileyiniz...</div>");
			}else if(geo_state==3){
			$("#map_area").html("<div id=\"alert\">Mobil Cihazınız GPS Sistemini Desteklemiyor. Lütfen Konum Servisini Destekleyen Bir Mobil Cihazla Bağlantı Yapınız...</div>");
			}else if(geo_state==2){
			$("#map_area").html("<div id=\"alert\">Malesef Şu Anda Konumunuza Ulaşılamıyor...</div>");
			}
	
	}
function proses()
	{
	if(live_position!=2)
		{
			$("#proses_data").html("<br>Konum Tespit Hassasiyeti="+accuracy+" m ve Geçen Zaman "+time+" Saniye");
		}
	if(optimum_accuracy<15 && live_position!=2)
		{
		live_position=2;
		center_pos=2;
		stop_inter();
		explain_proses();
		//to jump save
		}else if(time>10 && time<20 && optimum_accuracy<35 && live_position!=2)
		{
		live_position=2;
		center_pos=2;
		stop_inter();
		explain_proses();
		//to jump save
		}else if(time>20 && time<30 && optimum_accuracy<55 && live_position!=2)
		{
		live_position=2;
		center_pos=2;
		stop_inter();
		explain_proses();
		//to jump save
		}else if(time>30 && optimum_accuracy<105 && live_position!=2)
		{
		live_position=2;
		center_pos=2;
		stop_inter();
		explain_proses();
		//to jump save
		}else if(time>105 && live_position!=2)
		{
		live_position=2;
		center_pos=2;
		stop_inter();
		var size= window.innerHeight-51;
		$("#gps_proses").css("height",size);
		$("#gps_proses_info").html("Kullandığınız Cihaz 105 saniye İçerisinde 105 metre Hassasiyetler Konumunuzu Bulmakta Başarısız Oldu.Tekrar Denemek İçin Sayfayı Yenileyiniz veya Başka Bir Cihazla Tekrar Deneyiniz...");
		}
	
	}
		function stop_inter()
		{
		clearInterval(refreshIntervalId);
		}
	function explain_proses()
		{
		$('#gps_proses_info').html("Konumunuz "+accuracy+" metre Hassasiyette Tespit Edildi.Mağazanız Muhtemelen Kırmızı Dairenin İçinde.Mağaza Simgesini Haritada Mağazanızın Konumuna Sürükleyip KONUMU KAYDET Düğmesine Tıklayınız...<div onclick=\"last_operation()\" id=\"jump_to_save_button\">İLERLE -></div>");
		red_marker.setDraggable(true);
		}
	function last_operation()
		{
		$('#gps_proses_info').html("<div onclick=\"last_save()\" id=\"loc_save_button\">KAYDET</div>");		
		resize_map();
		}
function resize_map()
	{
	var size= window.innerHeight-(80+document.getElementById("gps_proses").clientHeight);
	$("#gps_map").css("height",size);
	map.fitBounds(circle.getBounds());
	circle.setCenter(translate_coord(optimum_lat, optimum_long));
	}

function create_accuracy_circle(accuracy,position)
		{
				circle = new google.maps.Circle({
                center: position,
                radius: accuracy,
                map: map,
                fillColor: '#FF0000',
                fillOpacity: 0.15,
                strokeColor: '#FF0000',
                strokeOpacity: 1.0
				});
		}

function get_red_marker_to_center()
		{
				if(center_pos==1)
					{
					center_pos=2;
					}else
					{
						center_pos=1;
						map.setCenter(translate_coord(marker_lat, marker_long));
					}
		}
		
function errorHandler(err) {
  if(err.code == 1) {
   // alert("Hata:Bağlantı İsteği Reddetildi! Lütfen Mağazanızın Konumunu Ayarlamak İçin Konumunuzu Paylaşınız...");
	geo_state=1;
	is_geo_working();
  }else if( err.code == 2) {
    //alert("Hata: Konumunuza Ulaşılamıyor!");
	geo_state=2;
	is_geo_working();
  }
}

function current_pos_to_marker()
	{
	marker_lat=current_lat;
	marker_long=current_long;	
	}
		
	function initialize()
            {
                map = new google.maps.Map(document.getElementById('gps_map'), {
                   zoom: 12,
                   center: taksim_position,
                   mapTypeId: google.maps.MapTypeId.HYBRID
                 });
				 var image =new google.maps.MarkerImage('img_files/shop_icon3.png',
                new google.maps.Size(60, 60),
                new google.maps.Point(0, 0),
                new google.maps.Point(30, 30));
				
				red_marker = new google.maps.Marker({
				position: taksim_position,
				map: map,
				size: google.maps.Size(60, 60),
				draggable:false,
				icon: image,
				title:"Konumun"
				});
				
				create_accuracy_circle(0,taksim_position);
				
				google.maps.event.addListener(red_marker ,'dragend',function(overlay,point){
				live_position=2;
				center_pos=2;
				push_marker_pos_in_variables();
				});
            };
			
function push_marker_pos_in_variables()
				{
				marker_lat=red_marker.getPosition().lat();
				marker_long=red_marker.getPosition().lng();
				}
	function start_func()
		{
		Location();
		initialize();
		refreshIntervalId=setInterval(inter_func, 1000);
		}
	function inter_func()
		{
		time=time+1;
		proses();
		}
    function last_save()
		{
			$.ajax({
					type:'POST',
					url:'ajax/gps_last_save.php',
					dataType: 'json',
					data:'&marker_lat='+marker_lat+'&marker_long='+marker_long+'&optimum_lat='+optimum_lat+'&optimum_long='+optimum_long+'&optimum_accuracy='+optimum_accuracy,
					success: function(info) {
					
						if(info.result==0)
						{
						$('#gps_proses_info').html("<div id=\"result_in\">Bir Sorun Oluştu..</div>");
						}else if(info.result==1){
						$('#gps_proses_info').html("<div id=\"result_in\">Mağazanızın Konum Kayıt İşlemi Başarıyla Gerçekleştirildi...</div>");
						var size= window.innerHeight-51;
						$("#gps_proses").css("height",size);
						}else if(info.result==2){
						$('#gps_proses_info').html("<div id=\"result_in\">Kayıt Sırasında Hata Oluştu. Lütfen Kayıt İşlemini Tekrar Deneyiniz...<div onclick=\"last_save()\" id=\"loc_save_button\">KAYDET</div></div>");
						}else if(info.result==3){
						$('#gps_proses_info').html("<div id=\"result_in\">Bir Sorunla Karşılaşıldı. Lütfen Sayfayı Yenileyerek İşlemi Tekrar Ediniz...</div>");
						}else if(info.result==4){
						$('#gps_proses_info').html("<div id=\"result_in\">Lütfen Giriş Yapınız...</div>");
						}else if(info.result==5){
						$('#gps_proses_info').html("<div id=\"result_in\">Lütfen Giriş Yapınız...</div>");
						}else{
						$('#gps_proses_info').html("<div id=\"result_in\">Bir Sorun Oluştu...</div>");
						}
								}
						})
		}
	google.maps.event.addDomListener(window, 'load', start_func);
</script>
</head>
<body>
<div id="small_banner">
<div id="logo">
<img src="img_files/logo_grad.png" style="height:37px;border:none;margin:auto;"/>
</div>
</div>
<div id="map_area">
<div id="gps_map">
</div>
</div>
<div id="gps_proses">
<div id="gps_proses_info">
<div id="proses_img">Konumunuz Bulunuyor. Lütfen Bekleyiniz <img src="img_files/cycle_loading.gif" style="height:30;margin-bottom:-8px;margin-left:10px;" /></div>

<div id="proses_data"></div>

</div>
</div>
</body>
</html>
<?php
	}else
	{
		echo '<meta http-equiv="refresh" content=0;URL=gps_request_index.php>';
	}
}else
{
echo '<meta http-equiv="refresh" content=0;URL=gps_request_index.php>';
}
?>
