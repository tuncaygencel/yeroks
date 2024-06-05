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
Profil Resim Ayarı
</title>
<meta http-equiv="Content-Type" content="text/HTML"; charset="utf-8" />
<meta name = "viewport" content="width=320, user-scalable=0" />
<link rel="shortcut icon" href="http://www.yeroks.com/img_files/yeroks_icon.png" />
<link href="css/m_shop_setting.css" type="text/css" rel="stylesheet" />
<script type="text/javascript" src="http://www.yeroks.com/scripts/jquery-1.11.1.min.js"></script>
<script type="text/javascript" src="http://www.yeroks.com/scripts/jquery-form.js"></script>
<script type="text/javascript">

$(document).ready(function (e){
		$('#s_profile_img').on('submit',(function(e) {
		display_loading_img();
		$('input:submit').attr("disabled", true);
		e.preventDefault();
$.ajax({
url: "aj_img/s_img_save.php", // Url to which the request is send
type: "POST", 
dataType: 'json',          // Type of request to be send, called as method
data: new FormData(this), // Data sent to server, a set of key/value pairs (i.e. form fields and values)
contentType: false,       // The content type used when sending data to the server.
cache: false,             // To unable request pages to be cached
processData:false,        // To send DOMDocument or non processed data file it is set to false
success: function(data)   // A function to be called if request succeeds
{

	if(data.press==1)
		{
		$.each(data.results,function(i,result){
			if(result.problem==0)
				{
					if(result.press==1)
						{
							$('.shop_image_'+result.no).html("<div id=\"pr_im_delete\" onclick=\"open_delete_button("+result.no+")\">Sil</div>");
							$('.shop_image_'+result.no).css("background-image","url('s_profile_img_big/"+result.image_name+"')");
							$('#ImageBrowse_'+result.no).val("");
						}
				}else
				{
					$('.shop_image_'+result.no).html("<div style=\"background-color:white;pading:5px;\">"+result.explain+"</div>");
				}
		
		});
		}else
		{
		$("#image_result").html(data.explain);
		}

}
});
$('input:submit').attr("disabled", false);

}));
});
$(document).mouseup(function (e)
			{  var container = $("#buttons");
				if (!container.is(e.target)&& container.has(e.target).length === 0) { container.hide();}
			});

function open_delete_button(no)
	{
	$('.shop_image_'+no).html("<div id=\"open_img_delete\"><div id=\"give_up_to_delete\" onclick=\"give_up_to_delete("+no+")\">Vazgeç</div>  <div id=\"image_delete\" onclick=\" image_delete("+no+")\">Sil</div>     </div>");
	}

function give_up_to_delete(no)
	{
	$('.shop_image_'+no).html("<div id=\"pr_im_delete\" onclick=\"open_delete_button("+no+")\">Sil</div>");
	}
	
function image_delete(no)
	{
	$.ajax({
					type:'POST',
					url:'aj_img/delete_profile_images.php',	
					dataType: 'json',
					data:'no='+no,
					success: function(data) {
					if(data.press==1)
							{
								if(data.success==1)
									{
									$('.shop_image_'+no).html("<div style=\"background-color:white;pading:5px;\">"+data.explain+"</div>");
									$('.shop_image_'+no).css( "background-image", "url('img_files/shop_image.png')");
									}else
									{
									$('.shop_image_'+no).html("<div style=\"background-color:white;pading:5px;\">"+data.explain+"</div>");
									}
							
							}else
							{
							alert(data.explain);
							}
						}
			});
	}
function get_profile_images()
	{
	$.ajax({
					type:'POST',
					url:'aj_img/get_profile_images.php',	
					dataType: 'json',
					success: function(data) {	
							if(data.press==1)
									{
										$.each(data.results,function(i,result){
										if(result.problem==0)
											{
												if(result.press==1)
												{
												$('.shop_image_'+result.no).html("");
												$('.shop_image_'+result.no).css("background-image","url('s_profile_img_big/"+result.image_name+"')");
												$('.shop_image_'+result.no).html("<div id=\"pr_im_delete\" onclick=\"open_delete_button("+result.no+")\">Sil</div>");
												$('#ImageBrowse_'+result.no).val("");
												}
											}else
											{
												$('.shop_image_'+result.no).html("<div style=\"background-color:white;pading:5px;\">"+result.explain+"</div>");
											}
		
									});
									}else
									{
										$("#image_result").html(data.explain);
									}
									
									
							}
					});
	}
	
	function display_loading_img()
		{
		if($("#ImageBrowse_1").val() != ''){ input_load_img("shop_image_1"); }
		if($("#ImageBrowse_2").val() != ''){ input_load_img("shop_image_2"); }
		if($("#ImageBrowse_3").val() != ''){ input_load_img("shop_image_3"); }
		if($("#ImageBrowse_4").val() != ''){ input_load_img("shop_image_4"); }
		}
	function input_load_img(class_value)
		{
		$("."+class_value).html("<div style=\"display:inline-block;background-color:white;padding: 15px;margin-top: 46px;opacity: 0.9;font-family:Arial;font-size:18px;color:black;text-align:center;\">Yükleniyor...<br><img src=\"img_files/cycle_loading.gif\" style=\"height:50px;margin-top:10px;\" /></div>");
		}
	function control_file(id)
		{
			var file = document.getElementById(id).files[0];
			if( (file.size/1024/1024) > 3 ){
				alert( "Lütfen 3 MB'dan Daha Düşük Boyutta Bir Resim Seçiniz. Yüklemeye Çalıştığınız " + file.name + " İsimli Dosya " + (file.size/1024/1024).toFixed(2) + " MB'dır");
				null_input(id);
				}
			if( !control_type(id))
				{
				alert( "Lütfen JPEG, PNG veya GIF Uzantılı Bir Resim Seçiniz. Yüklemeye Çalıştığınız " + file.name + " İsimli Dosya " +file.type+ " Uzantılıdır...");
				null_input(id);
				}
		}
		//if image type is available function returns true...
	function control_type(id)
		{
		var file = document.getElementById(id).files[0];
		file=file.type;
			if(file=='image/jpeg' || file=='image/jpg' || file=='image/gif' || file=='image/png'){
			return true;
			}else{
			return false;
			}
		}
	function null_input(id)
		{
		 $("#"+id).val("");
		}
</script>	
</head>
<body onload="get_profile_images()">
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
<div onclick="window.location='position_setting.php'" id="button">Konum Ayarları</div>
<div onclick="window.location='image_setting.php'" id="button" style="border-bottom: 1px solid red;">Resim Ayarları</div>
</div>
<div id="img_area" >
<form action="" method="POST" enctype="multipart/form-data"  id="s_profile_img">
<div id="image_div">
<div id="image_title">
Profil Resmi 1
</div>
<div id="shop_image" class="shop_image_1"></div>
<div id="image_file_area">
<input type="file" name="image[]" onchange="control_file('ImageBrowse_1')" id="ImageBrowse_1" />
</div>
</div>
<div id="image_div">
<div id="image_title">
Profil Resmi 2
</div>
<div id="shop_image"  class="shop_image_2"></div>
<div id="image_file_area">
<input type="file" name="image[]"  onchange="control_file('ImageBrowse_2')"  id="ImageBrowse_2" />
</div>
</div>
<div id="image_div">
<div id="image_title">
Profil Resmi 3
</div>
<div id="shop_image"  class="shop_image_3"></div>
<div id="image_file_area">
<input type="file" name="image[]"  onchange="control_file('ImageBrowse_3')"  id="ImageBrowse_3"/>
</div>
</div>
<div id="image_div">
<div id="image_title">
Profil Resmi 4
</div>
<div id="shop_image"  class="shop_image_4"></div>
<div id="image_file_area">
<input type="file" name="image[]"  onchange="control_file('ImageBrowse_4')"  id="ImageBrowse_4" />
</div>
</div>

<input style="margin-top:50px;" type="submit" id="submit_button" value="Resimleri Kaydet"/>

</form>

<div id="image_result">

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









