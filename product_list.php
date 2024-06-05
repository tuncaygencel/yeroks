<?php
//page_id=5
session_start();
include("ajax/kjasafhfjaghl.php");
		$connect=new connect();
		$connect->sql_connect_db();
		$login=new log_in();
$error=0;
$y_id=0;
$c_id=0;
$s_id=0;
$is_login=0;
if($login->is_login(1) ){ $is_login=1; }
//error 1 no get value
//error 2 no product's values equal get values
//error 3 no shop equals product's shop data
	$connect->enter_active(5,1,$c_id,$s_id,0);
?>
<html>
<head>
<title>Desteklediğimiz Ürünler</title>
<meta http-equiv="Content-Type" content="text/HTML"; charset="utf-8" />
	<meta name="description" content="Binlerce Ürünü Çevrende Arayabilir ve Onlara Ulaşabilirsin!..." />
	<meta name="keywords" content="Çevrende Arama Nerede" />
<meta name = "viewport" content="width=320, user-scalable=0" />
<meta http-equiv="content-language" content="tr" />
<link rel="shortcut icon" href="img_files/y_icon.png" />
<script type="text/javascript" src="http://www.yeroks.com/scripts/jquery-1.11.1.min.js"></script>
<link href="css/product_list.css" type="text/css" rel="stylesheet" />
<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-41307627-1', 'auto');
  ga('send', 'pageview');

</script>
<script type="text/javascript">
		function fire()
			{
			var he=$("#shop_area").height();
			he=he+20;
			$('#map_holder').css("height",he);
			$('#map_area').css("height",he);
			google.maps.event.trigger(map, "resize");
			}
	$(document).mouseup(function (e)
			{  var container = $("#buttons");
				if (!container.is(e.target)&& container.has(e.target).length === 0) { container.hide();}
			});
	</script>
</head>
<body>
<div id="black_background" onclick="hidebackground();" style="display:none;background-color:none;">
</div>
<div id="light_box_area" style="display:none;background-color:none;">
</div>
<div id="small_banner">
<div id="banner_center">

<div id="logo">
<a href="http://www.yeroks.com">
<img src="http://www.yeroks.com/img_files/b_l_b.png" style="height:30px;border:none;margin:auto;"/>
</a>
</div>
<?php if($is_login==1){
?>
<div class="user_banner">
<div id="user_name">
<a href="http://www.yeroks.com/m/profile.php" id="set_button">
<?php
echo $_SESSION['user_full_name']."</br>";
?>
</a>
</div>
<div id="slide_down" class="but_slide_down" onclick="$('#buttons').toggle();"></div>
<div id="buttons">
<a id="button" href="http://www.yeroks.com/m/list.php">Listeler</a>
<a id="button" href="http://www.yeroks.com/m/setting.php" >Ayarlar</a>
<a id="button" href="http://www.yeroks.com/m/logout.php">Çıkış Yap</a>
</div>
</div>
<?php }else{ ?>
<div id="login_link" onclick="location.href='http://www.yeroks.com/m/'">
Giriş Yap
</div>
<?php
}
?>
</div>
</div>
<div id="content">
<div id="content_c">
<div id="title">Yeroks'un Desteklediği Ürünler
<?php   
	$sql="SELECT y_name,is_y FROM y_name_list WHERE (is_y=1 or fathers_id=0) and y_type_id!=0 ORDER BY y_type_id";
	$sql=mysql_query($sql) or die(mysql_error());
	$i=0;
	while($res=mysql_fetch_assoc($sql))
		{
			if($res['is_y']==0){
			echo "</div><div id=\"pr_block_".$i."\" class=\"pr_block\"><div id=\"p_t\">".$res['y_name']."</div>";
			$i=$i+1;
			}else{
			echo "<div id=\"p\">&#8226; ".$res['y_name']."</div>";
			}
		}
    ?>
	</div>
	<div class="pr_block" style='margin-top:30px;'>
	<div id="p_t">Desteklenmeyen Ürünler</div>
	<div class="p">&#8226; Alkol, Uyuşturucu ve Türevleri</div>
	<div class="p">&#8226; Şans Oyunları, Bahis, Bahis Ürünleri ve Türevleri</div>
	<div class="p">&#8226; Pornografik Ürünler</div>
	<div class="p">&#8226; Sahte, Kaçak ve İthalatı Yasak Ürünler</div>
	<div class="p">&#8226; Kültür ve Tabiat Varlıkları</div>
	<div class="p">&#8226; İnsan Organları</div>
	<div class="p">&#8226; Ateşli Silahlar ve Yanıcı Patlayıcı Maddeler</div>
	<div class="p">&#8226; Tütün Mamülleri</div>
	<div class="p">&#8226; Kişisel Veriler</div>
	</div>
</div>
</div>
<div id="help_info_area">
 2016 &#169 Yeroks İstanbul  <a href="shop_index.php">Mağaza Girişi</a><a href="about.php">Hakkımızda</a><a href="product_list.php">Ürün Çeşitleri</a>
</div>
</body>
</html>


