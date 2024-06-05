<?php
session_start();
include("ajax/kjasafhfjaghl.php");
if(isset($_SESSION['user_type'])  and isset($_SESSION['user_no']) and isset($_SESSION['mail_adress']))
	{
$connect=new connect();
$connect->sql_connect_db();
$check_type=new check_type_get_values();	
$check_type->sent_values($_SESSION['mail_adress'],$_SESSION['user_type'],$_SESSION['user_no']);
	$shop_id=$_SESSION['user_no'];
	if($check_type->check_and_get()==2)
		{
$error=0;
$c_id=0;
if(isset($_GET['data']))
	{
	$c_id=mysql_real_escape_string(htmlspecialchars(trim((int)$_GET['data'])));
	}else{
	$error=1;
	}		
	$sql="SELECT shop_y_list.y_type_id,shop_y_list.y_count,y_name_list.y_name, y_name_list.icon_name FROM shop_y_list 
	LEFT JOIN y_name_list ON y_name_list.y_type_id=shop_y_list.y_type_id
	WHERE shop_y_list.y_type_id=$c_id and shop_y_list.shop_id=$shop_id LIMIT 1";
	$sql=mysql_query($sql) or die(mysql_error());
	if(mysql_num_rows($sql)!=1){
	$error=2;
	}else{
	$res=mysql_fetch_assoc($sql);
	}
	$check_type->get_profile_image();
		$check_type->get_shop_adress();
?>
<html>
<head>
<title>
<?php
echo $check_type->name;
?>
</title>
<meta http-equiv="Content-Type" content="text/HTML"; charset="utf-8" />
<link rel="shortcut icon" href="http://www.yeroks.com/img_files/yeroks_icon.png" />
<script type="text/javascript" src="http://www.yeroks.com/scripts/jquery-1.11.1.min.js"></script>
<script type="text/javascript" src="http://www.yeroks.com/scripts/jquery-form.js"></script>
<link href="css/s_profile.css" type="text/css" rel="stylesheet" />
<link href="css/y_create.css" type="text/css" rel="stylesheet" />
<script type="text/javascript">
var props=[];
var props_data=[];
var props_text=[];
var s_props=[];
var s_props_data=[];
var s_props_text=[];
var json_prop='';
var prop_html='';
var yield_name='';
var yield_type=<?php echo $res['y_type_id'];?>;
var plus_prop_counter=0;
var opening_counter=0;
var plus_props= [];
var plus_props_value= [];

	function open_plus_prop()
			{
				if(opening_counter >= 10){
					alert("En Fazla 10 Adet Yeni Özellik Girişi Açabilirsiniz...");
				}else{
				opening_counter=opening_counter+1;
				plus_prop_counter =plus_prop_counter+1;
				$('#plus_prop_open_area').append("<div class=\"plus_prop_input_area\" id=\"plus_prop_"+plus_prop_counter+"\"><input placeholder=\"Özellik İsmi\" class=\"plus_prop_name\" type=\"text\" name=\"plus_prop_name[]\"/>.<input placeholder=\"Özellik Değeri\" class=\"plus_prop_value\" type=\"text\" name=\"plus_prop_value[]\" /><div id=\"delete_plus_prop\" onclick=\"$('#plus_prop_"+plus_prop_counter+"').remove();reduce_opening_counter();\">Sil!</div></div>");
				}
			}
	function reduce_opening_counter()
			{
			if(opening_counter>0)
			{
			opening_counter=opening_counter-1;
			}
			}
	
	function enter_new_yield()
			{
				$('#show_y_list_button').css('border-bottom','2px solid rgb(219, 219, 219)');
				$('#new_y_button').css('border-bottom','2px solid red');
				$('#new_y_button').css('color','#0084FF');
				$('#show_y_list_button').css('color','#189814');
				$('#events_area').html('<table align=\"center\" id=\"new_y_input_table\"><tbody><tr><td>Sattığınız veya Hizmetini Verdiğiniz Ürünün İsmini Giriniz...</td></tr><tr><td><input type=\"text\" name=\"input_new_yield\" placeholder=\"Gömlek, Dondurma, Hamburger vb.\" onkeydown=\"ask_yield_list()\" onkeyup=\"ask_yield_list()\" id=\"input_new_yield\"/></td></tr></tbody></table><div id=\"yield_search_result\"></div>');
			new_yield_show_div();
			}
	function new_yield_show_div()
			{
			$( "#new_yield_show" ).animate({ 'opacity' : 'show'}, 200 );
			$( ".new_yield_prop_show").html('Yeni Ürün Seçilmedi...');
			}
	function new_yield_hide_div()
			{
			$( "#new_yield_show" ).animate({ 'opacity' : "hide" }, 200 );
			$( ".new_yield_prop_show").html('Yeni Ürün Seçilmedi...');
			}
	
	
	function show_yield_list()
			{
				$('#show_y_list_button').css('border-bottom','2px solid red');
				$('#new_y_button').css('border-bottom','2px solid rgb(219, 219, 219)');
				$('#show_y_list_button').css('color','#0084FF');
				$('#new_y_button').css('color','#189814');
				$('#events_area').html('<div id="product_title" style=" margin-top: -10px;font-size: 28px;color: rgb(36, 36, 36);font-family: Arial; background-color: wheat;text-shadow: 0px 1px 1px rgb(247, 247, 247);border-bottom: 1px solid orange; padding-bottom: 5px;padding-top: 10px;">Ürün Listesi</div>');
				get_yield_list_of_shop();
				new_yield_hide_div();
			}
	function get_yield_list_of_shop()
			{
				$.ajax({
					type:'POST',
					url:'ajax/get_yield_list_of_shop.php',		
					success: function(information) {
					$('#events_area').append(information);
						}
					});
			}
	function ask_yield_list()
			{
				var input_text=document.getElementById('input_new_yield').value;
				if(input_text==""){$('#yield_search_result').html("");}
				else{
				$.ajax({
					type:'POST',
					url:'ajax/ask_yield_list_for_shop.php',	
					dataType: 'json',					
					data:'input_text='+input_text,
					success: function(info) {
						if(info.okay==0){
							$('#yield_search_result').html("");
							alert(info.exp);
						}else{
								$('#yield_search_result').html("");
								$.each(info.list,function(i,res){
									$('#yield_search_result').append("<ul onclick=\"add_rayon("+res.y_id+")\">"+res.y_name+"</ul>");
									});
							}
						}
					});	
					}
			}
	function add_rayon(y_data){
		$('#yield_search_result').html("");
		$.ajax({
					type:'POST',
					url:'ajax/shop_add_rayon.php',		
					data:'y_data='+y_data,
					dataType: 'json',	
					success: function(info) {
						if(info.error==0){
							$('#rayon').prepend("<div class=\"yield_area\" onclick=\"get_shop_category_special("+info.data+")\"><div id=\"y_image\"><div class=\"yield_count\" >0</div><img src=\"http://www.yeroks.com/y_img_small/"+info.icon+".png\" height=\"100\"></div><div class=\"yield_name\">"+info.name+"</div></div>");
							}else{
							alert(info.exp);
							}
						}
					});
		}
	function set_y_values(name,data)
		{		
				openbackground();
				$('#lb_events_area').html('<div id=\"yield_show_div\">'+name+'</div>');
				yield_name=name;
				$.ajax({
					type:'POST',
					url:'ajax/ask_yield_prop_for_shop.php',		
					data:'data='+data,
					success: function(information) {
					$('#lb_events_area').append(information);
						}
					});
		show_yield_name(name);
		}
		
	function show_yield_name(name)
		{
		$('.new_yield_prop_show').html('<div id=\"show_yield\">'+name+'</div>');
		}
//show yield name typing in input or if it is null show list name
	function new_yield_typing(){
				var yield_name_input=document.getElementById("new_yield_name_input").value;
				yield_name_input=yield_name_input.trim();
				if(yield_name_input!='')
				{
					$('#show_yield').html(yield_name_input);
				}else
				{
					$('#show_yield').html(yield_name);
				}
			}
	function onchange_option(prop)
		{
			var changed_prop=document.getElementById("prop_"+prop);
			var changed_prop_number=changed_prop.options[changed_prop.selectedIndex].value;
			var changed_prop_text=changed_prop.options[changed_prop.selectedIndex].text;
			if(changed_prop_number!=0)
				{
				$('.prop_vis_'+prop).remove();
				$('.new_yield_prop_show').append('<div id=\"prop_vis\" class="prop_vis_'+prop+'\"> '+changed_prop_text+'</div>');
				}else
				{
				$('.prop_vis_'+prop).remove();
				}
		}
	
	function onchange_div(prop,name,v)
		{
		if(document.getElementById("p_"+prop+"_v_more_"+v).value==0)
			{
				$('#d_'+prop+'_prop_'+v+' img').css('display','inline-block');
				$('.new_yield_prop_show').append('<div id=\"prop_vis\" class="prop_'+prop+'_vis_'+v+'\"> '+name+'</div>');
				document.getElementById("p_"+prop+"_v_more_"+v).value=v;
			}else
			{
				$('#d_'+prop+'_prop_'+v+' img').css('display','none');
				$('.prop_'+prop+'_vis_'+v).remove();
				document.getElementById("p_"+prop+"_v_more_"+v).value=0;
			}
		}
	function turn_json()
		{
		json_prop='{';
		var k=0;
		for (var i=0; i<props.length; i++)
			{
				if(props[i]!=0)
					{
					k=1;
					if(i==0)
						{
						json_prop=json_prop+props_data[i]+':'+props[i];
						}else
						{
						json_prop=json_prop+','+props_data[i]+':'+props[i];
						}
					}
			}

		for (var i=0; i<s_props.length; i++)
			{
				if(s_props[i]!=0)
					{
					if(i==0 && k==0)
						{
						json_prop=json_prop+s_props_data[i]+':'+s_props[i];
						}else
						{
						json_prop=json_prop+','+s_props_data[i]+':'+s_props[i];
						}
					}
			}
		json_prop=json_prop+'}';
		}
	
	function new_yield_step_two()
		{
		display_lb_loader();
		get_selected_new_yield_data();
		var yield_type_data='';
		var allData = new FormData();
		get_plus_props();
		
		if(yield_type_data=document.getElementById("yield_type_data").value)
			{
				turn_json();
				 allData.append('y_image[]',document.getElementById('y_image_1').files[0]);
				 allData.append('y_image[]',document.getElementById('y_image_2').files[0]);
				 allData.append('y_image[]',document.getElementById('y_image_3').files[0]);
				 allData.append('yield_type_data',yield_type_data);
			 	 allData.append('yield_name',yield_name);
				 allData.append('json_prop',json_prop);
				 allData.append('price',document.getElementById('y_price_input').value);
				 allData.append('currency',document.getElementById('price_type_input').value);
				 allData.append('about',document.getElementById('product_explain').value);
				 allData.append('plus_props',JSON.stringify(plus_props));
				 allData.append('plus_props_value',JSON.stringify(plus_props_value));
				$.ajax({
					type:'POST',
					url:'ajax/new_yield_enter.php',	
					async: true,					
					data:allData,
					success: function(information) { $('#lb_events_area').html(information);close_lb_loader();reset_pro_bar(); },
					cache: false,
					contentType: false,
					processData: false,
					xhr: function(){
        //upload Progress
					var xhr = $.ajaxSettings.xhr();
					if (xhr.upload) {
						xhr.upload.addEventListener('progress', function(event) {
						var percent = 0;
						var position = event.loaded || event.position;
						var total = event.total;
						if (event.lengthComputable) {
						percent = Math.ceil(position / total * 100);
						}
                //update progressbar
						$(".progress-bar").css("width", + percent +"%");
						$(".progress-bar").text( "%"+percent);
						}, true);
					}
					return xhr;
				}
					});
			}else
			{
			alert('HATA!');
			}
		}
		function reset_pro_bar(){
		$(".progress-bar").css("width", 0);
		$(".progress-bar").text( "%0");
		}
		function get_plus_props()
			{
			var i=0;
			$('input[name^="plus_prop_name"]').each(function() {
				plus_props[i]=$(this).val();
				i=i+1;
				});
			i=0;
			$('input[name^="plus_prop_value"]').each(function() {
				plus_props_value[i]=$(this).val();
				i=i+1;
				});
			}
		function new_yield_step_one()
			{
			display_loader();
			new_yield_step_two();
			}
		///status bar functions 
		function display_loader()
			{
			var height=$("#left_content").height();
			$("#loader_background").css("height",height);
			$("#loader_img").css("margin-top",height/2-100);
			$("#loader_img").css("display","block");
            $("#loader_background").css("display","block");
			}
	   function close_loader()
			{
			$("#loader_img").css("display","none");
            $("#loader_background").css("display","none");
			}
		
		//////
		function get_selected_new_yield_data()
		{
				var prop_list;
				var counter_input=0;
				var s_counter_input=0;
				var s_v_counter_input=0;
				//clean props and props_text for researching and to remove last search data
				props.length=0;
				props_text.length=0;
				props_data.length=0;
				
				s_props.length=0;
				s_props_text.length=0;
				s_props_data.length=0;
				
				if(yield_name!='')
					{
					var entered_yield_name=document.getElementById("new_yield_name_input").value;
						if(entered_yield_name!='')
							{
							yield_name=entered_yield_name;
							}
					}
					
				if((prop_list= document.getElementById("props_list")) && (counter_input=document.getElementById("prop_counter")))
					{
					var counter=counter_input.value;
					for (var i=0; i<counter; i++)
						{
						props_data[i]=document.getElementById("prop_"+i+"_data").value;
						var props_form=document.getElementById("prop_"+i);
						props[i]=props_form.options[props_form.selectedIndex].value;
						props_text[i]=props_form.options[props_form.selectedIndex].text;
						}
					}else
					{
					props[0]=0;
					props_text[0]='-';
					props_data[0]=0;
					return false;
					}
				if((s_prop_list= document.getElementById("props_list")) && (s_counter_input=document.getElementById("s_prop_counter")))
					{
					var s_counter=s_counter_input.value;
					for (var i=0; i<s_counter; i++)
						{
						var s_prop=document.getElementById("s_prop_"+i+"_data").value;
						var s_prop_value_counter=document.getElementById("s_prop_"+i+"_value_counter").value;
							for (var ii=0; ii<s_prop_value_counter; ii++)
								{	
									s_props_data[ii]=s_prop;
									s_props[ii]=$(".p_"+s_prop+"_v_m_"+ii).val();
								}
						}
						return true;
					}else
					{
					s_props[0]=0;
					s_props_text[0]='-';
					s_props_data[0]=0;
					return false;
					}			
					
		}
function get_props_html()
			{
				if( props_text.length > 0)
					{
					for (var i=0; i<props_text.length; i++)
							{
								if(props[i]!=0)
									{
									prop_html=prop_html+"<div id=\"prop_vis\">"+props_text[i]+"</div>";
									}
							}
					return true;
					}else
					{				
					return false;
					}
			}
function get_shop_category_special(cat_id)
	{
	$.ajax({
					type:'POST',
					url:'ajax/shop_category_special.php',		
					data:'&cat_id='+cat_id,
					success: function(information) {
					$('#y-'+cat_id).html(information);
						}
					});
	}
function yield_set_delete(y_data,c_data)
	{
	$("#yield_set_"+y_data+'_'+c_data).css('display','block');
	$("#yield_set_"+y_data+'_'+c_data).html('Emin misiniz?<br><div class="yield_set_change" onclick="abandon_to_yield_set('+y_data+','+c_data+');" >Vazgeç</div><div class="yield_set_delete" onclick="yield_set_delete_last('+y_data+','+c_data+');" >Sil</div>');	
	}
function yield_set_delete_last(y_data,c_data)
	{
	$.ajax({
					type:'POST',
					url:'ajax/yield_set_delete.php',
					dataType: 'json',					
					data:'&y_data='+y_data+'&c_data='+c_data,
					success: function(information) {
						//$("#yield_set_"+y_data+'_'+c_data).html(information);
						
						if(information.status==0)
							{
							$("#yield_set_"+y_data+'_'+c_data).html(information.text);
							}else if(information.status==1)
							{
							$("#yield_set_"+y_data+'_'+c_data).html(information.text);
							$(".show_y_special_"+y_data+'_'+c_data).remove();
							$("#yield_count_"+c_data).html(information.count+" Çeşit");
							}else
							{
							$("#yield_set_"+y_data+'_'+c_data).html('HATA!');
							}
							
					}
					});
	}
function abandon_to_yield_set(y_data,c_data)
	{
	$("#yield_set_"+y_data+'_'+c_data).removeAttr('style');
	$("#yield_set_"+y_data+'_'+c_data).html('<div class="yield_set_change" id="yield_set_change_'+y_data+'_'+c_data+'" onclick="yield_set_change('+y_data+','+c_data+');" >Düzenle</div><div class="yield_set_delete" id="yield_set_delete_'+y_data+'_'+c_data+'" onclick="yield_set_delete('+y_data+','+c_data+');" >Sil</div>');
	}
	
function yield_set_change(y_data,c_data)
	{
	openbackground();
	$.ajax({
					type:'POST',
					url:'ajax/yield_set.php',					
					data:'&y_data='+y_data+'&c_data='+c_data,
					success: function(info) {
							$("#lb_events_area").html(info);	
						}
					});
	}
function yield_set_two()
	{
	display_lb_loader();
	get_selected_new_yield_data();
	var yield_type_data='';
	var allData = new FormData();
	var yield_data;
	get_plus_props();
	if((yield_type_data=document.getElementById("yield_type_data").value) && (yield_data=document.getElementById("yield_data").value))
			{
				turn_json();
				
				var delete_1;
				var delete_2;
				var delete_3;
				
				delete_1=document.getElementById("delete_1");
				if(delete_1)
					{
					delete_1=document.getElementById("delete_1").checked;
					}else{
					delete_1=false;
					}
				delete_2=document.getElementById("delete_2");
				if(delete_2)
					{
					delete_2=document.getElementById("delete_2").checked;
					}else{
					delete_2=false;
					}
				delete_3=document.getElementById("delete_3");
				if(delete_3)
					{
					delete_3=document.getElementById("delete_3").checked;
					}else{
					delete_3=false;
					}
				if(document.getElementById("new_yield_name_input").value=='')
					{
					yield_name=document.getElementById("yield_name_data").value;
					}else{
					yield_name=document.getElementById("new_yield_name_input").value
					}
				allData.append('yield_data',yield_data);
				allData.append('yield_type_data',yield_type_data);
				allData.append('json_prop',json_prop);
				allData.append('yield_name',yield_name);
				allData.append('price',document.getElementById('y_price_input').value);
				allData.append('currency',document.getElementById('price_type_input').value);
				allData.append('delete_1',delete_1);
				allData.append('delete_2',delete_2);
				allData.append('delete_3',delete_3);
				allData.append('about',document.getElementById('product_explain').value);
				allData.append('plus_props',JSON.stringify(plus_props));
				allData.append('plus_props_value',JSON.stringify(plus_props_value));
				allData.append('y_image_1',document.getElementById('y_image_1').files[0]);
				allData.append('y_image_2',document.getElementById('y_image_2').files[0]);
				allData.append('y_image_3',document.getElementById('y_image_3').files[0]);
				
			$.ajax({
					type:'POST',
					url:'ajax/set_yield_enter.php',	
					async: true,					
					data:allData,
					success: function(information) {$('#lb_events_area').html(information);close_lb_loader();reset_pro_bar();},
					cache: false,
					contentType: false,
					processData: false,
					xhr: function(){
        //upload Progress
					var xhr = $.ajaxSettings.xhr();
					if (xhr.upload) {
						xhr.upload.addEventListener('progress', function(event) {
						var percent = 0;
						var position = event.loaded || event.position;
						var total = event.total;
						if (event.lengthComputable) {
						percent = Math.ceil(position / total * 100);
						}
                //update progressbar
						$(".progress-bar").css("width", + percent +"%");
						$(".progress-bar").text( "%"+percent);
						}, true);
					}
					return xhr;
				}
					}
					);
			}else{
			alert("HATA!");
			}
	}
	
	function yield_set_one()
			{
			display_lb_loader();
			yield_set_two();
			}
	
	function display_lb_loader()
			{
			var height=$("#lb_events_area").height();
			var height_=$("#light_box_area").scrollTop();
			$("#lb_loader_img").css("margin-top",height_+40);
			$("#lb_loader_img").css("display","block");
            $("#lb_loader_background").css("display","block");
			$("#light_box_area").css("overflow-y","hidden");
			$("#light_box_area").css("overflow-x","hidden");
			}
	   function close_lb_loader()
			{
			$("#lb_loader_img").css("display","none");
            $("#lb_loader_background").css("display","none");
			$("#light_box_area").css("overflow-y","scroll");
			$("#light_box_area").css("overflow-x","scroll");
			}

	function openbackground() {
	$('#black_background').css('display','block');
	$('#light_box_area').css('display','block');
	$('#lb_events_area').html('');
	};
	
	function hidebackground() {
		$('#black_background').css('display','none');
		$('#light_box_area').css('display','none');
		$('#lb_events_area').html('');
		};
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
	function go_to_yield_page(y_data)
		{
		window.open("shop_yields.php?data="+y_data);
		}
	function cat_delete_step_one(y_data,yield_num)
		{
			$('#black_background').css('display','block');
			$('#light_box_area').css('display','block');
			if(yield_num==0){
			$('#light_box_area').html("<div id=\"alerti\"><div id=\"alerti_text\">Boş Bu Reyonu Silmek İstedğinizden Emin misiniz? </div><div id=\"alerti_okay\" onclick=\"\">Evet</div><div id=\"alerti_okay\" onclick=\"\">İptal</div></div>");
			}else{
			$('#light_box_area').html("<div id=\"alerti\"><div id=\"alerti_text\">Bu Reyonda "+yield_num+" Adet Ürününüz Bulunmakta...İçindeki Ürünlerle Birlikte Bu Reyonu Silmek İstediğinizden Eminmisiniz?</div><div id=\"alerti_okay\" onclick=\"\">Araç İle</div><div id=\"alerti_okay\" onclick=\"\">Yürüyerek</div></div>");
			}
		}
</script>

</head>
<body>
<div id="black_background" onclick="get_plus_props();" style="display:none;background-color:none;">
</div>
<div id="light_box_area" style="display:none;background-color:none;">
<div id="exit_l" onclick="hidebackground();" ></div>
<div id="lb_loader_img">
 Yükleniyor...<br>
 <img src="img_files/cycle_loading.gif" height="70" style="margin-top: 20px;">
 <div id="progress-wrp"><div class="progress-bar">%0</div ></div>
 </div>
<div id="lb_loader_background" style="position: absolute;height: 50000px;width: 100%;background-color: rgb(60, 60, 60); z-index: 95;opacity: 0.6;display: none;">
</div>
<div id="lb_events_area">
</div>

</div>
<div id="small_banner">
<div id="banner_center">
<div id="logo">
<a href="shop_profile.php">
<img src="img_files/yeroks_s_logo.png" style="height:28px;border:none;margin:auto;"/>
</a>
</div>


<div id="buttons">
<div id="user_name">
<img src="<?php
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
<a href="shop_index.php?type=1" id="button_of_set_user">Çıkış Yap</a>
<a href="account_setting.php" id="button_of_set_user">
Ayarlar</a>
</div>
</div>

</div>
<div id="content_center">

<?php
if($check_type->latitude=='0' or $check_type->longitude=='0' )
	{
	?>
	<div id="pos_error">
	İşyerinizin Konumunu Girdiğinizde Kaydettiğiniz Ürünlere İnsanlar Ulaşabilecek...</br>
	<a id="go_to_pos_set" href="position_setting.php" >Konum Ayarlarına Git</a>
	</div>
	<?php
	}
if($error==0){
?>
<div id="left_all">
<div id="title">
Mağazanızın <?php echo $res['y_name']." Çeşitleri"; ?>
</div>
<div id="left_content">
<div id="events_area">
<div id="rayon">
<?php
$new_yerok=new yerok_id_handler();
		$new_yerok->get_yerok_shop_id($c_id,$shop_id);
		$new_yerok->yerok_id_control();
		$new_yerok->select_table();
	//////
		if($new_yerok->get_props_to_y_show()){
		$new_yerok->get_props_value_to_y_show();
		$new_yerok->get_y_icon();
		$new_yerok->get_y_first_images();
		$new_yerok->press_yields_from_list();
		}else{
		echo "Bu Reyonda Herhangi Bir Ürününüz Bulunmamakta...";
		}
?>
</div>
</div>
</div>
</div>
<div id="right_content">
<div id="add_new_yield">
<img id="new_yield_icon" src="y_img_big/<?php echo $res['icon_name']; ?>.png" height=80 />
<div id="new_yield_text" onclick="set_y_values(<?php echo "'".$res['y_name']."','".$res['y_type_id']."'"; ?>);" >Yeni <?php echo $res['y_name']." Ekle"; ?> </div>
</div>

<div id="shop_info">
		<div id="shop_p_img" style="background-image:url('<?php if(array_key_exists( 1, $check_type->p_img_way)){ echo "s_profile_img_big/".$check_type->p_img_way[1]; }else{ echo "img_files/shopping.png";} ?>');">
		</div>
		<div id="s_name">
		<?php echo $check_type->name; ?>
		</div>
		<div id="s_address">
		<?php   
		if($check_type->tel_number!=""){
		echo "<div id=\"tel_number\"><b>Tel:</b>".$check_type->tel_number."</div>";
		}
		if($check_type->place_number!=0){
		echo $check_type->adress;
		?>
		<div id="s_place"> <?php echo $check_type->place_name."'de ".$check_type->s_floor.". Kat" ?></div>
		<?php
		}else{
		echo $check_type->adress;
		}
		?>
		</div>
	</div>
<div id="new_yield_show">
<div class="new_yield_explain">
Yeni Ürünün Özellikleri
</div>
<div class="new_yield_prop_show">
Yeni Ürün Seçilmedi...
</div>

</div>
</div>
<div id="help_info_area">
  2016 &#169 Yeroks İstanbul &#183 <a target="_blank" href="support.php">Yardım</a> &#183 <a href="#">Neler Yapabilirsin?</a><a href="about.php">Hakkımızda</a><a href="product_list.php">Ürün Çeşitleri</a>

</div>
<?php
}else{
?>
<div style="margin-top:20px;">
Bu Şekilde Bir Reyonunuz Bulunamadı. Lütfen İlk Önce Profil Sayfanızdan 
Eklemek İstediğiniz Ürüne Ait Bir Reyon Oluşturunuz...
</div>
<a id="go_to_pos_set" href="shop_profile.php" >Profil Sayfasına Git</a>
<?php
}
?>
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
