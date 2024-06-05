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
		$check_type->get_profile_image();
		$check_type->get_shop_adress();
?>
<html>
<head>
<title> <?php echo $check_type->name; ?> </title>
<meta http-equiv="Content-Type" content="text/HTML"; charset="utf-8" />
<meta name = "viewport" content="width=320, user-scalable=0" />
<link rel="shortcut icon" href="https://www.yeroks.com/img_files/yeroks_icon.png" />
<script type="text/javascript" src="https://www.yeroks.com/scripts/jquery-1.11.1.min.js"></script>
<script type="text/javascript" src="https://www.yeroks.com/scripts/jquery-form.js"></script>
<link href="css/m_s_profile.css" type="text/css" rel="stylesheet" />
<?php
if($check_type->latitude!='0' and $check_type->longitude!='0' )
	{
	?>
	
	<script src="https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false&key="></script>
	<script type="text/javascript">
	 function initialize()
            {
				var shop_pos=new google.maps.LatLng(<?php echo $check_type->latitude.",".$check_type->longitude ;?>);
                map = new google.maps.Map(document.getElementById('map_area'), {
                   zoom: 17,
                   center: shop_pos,
                   mapTypeId: google.maps.MapTypeId.ROADMAP
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

            };
			
			
			google.maps.event.addDomListener(window, 'load', initialize);
	</script>
	
	
	
	<?php
	}
?>

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
	$(document).mouseup(function (e)
			{  var container = $("#buttons");
				if (!container.is(e.target)&& container.has(e.target).length === 0) { container.hide();}
			});
	function add_rayon(y_data){
		$('#yield_search_result').html("");
		$('#input_new_yield').val('');
		$('#black_background1').css('display','block');
		$('#light_box_area1').css('display','block');
		$('#light_box_area1').html('<div id=\"alerti\"><img style="margin-top:74px;" height=40 src="img_files/cycle_loading.gif"/></div>');
		
		$.ajax({
					type:'POST',
					url:'ajax/shop_add_rayon.php',		
					data:'y_data='+y_data,
					dataType: 'json',	
					success: function(info) {
						if(info.error==0){
							$('#rayon').append("<div class=\"yield_area\" id=\"c-"+info.data+"\" ><div id=\"y_image\"><div class=\"yield_count\" >0</div><img src=\"https://www.yeroks.com/y_img_small/"+info.icon+".png\" height=\"100\"></div><div class=\"yield_name\">"+info.name+"</div><div class=\"yield_set\"><div class=\"yield_set_change\"  onclick=\"go_to_yield_page("+info.data+");\">Reyona Ürün Ekle</div><div class=\"b_yield_set_delete\" onclick=\"cat_delete_step_one("+info.data+",0)\">Reyonu Sil</div></div></div>");
							$('#light_box_area1').html("<div id=\"alerti\"><div id=\"y_image\"><img src=\"https://www.yeroks.com/y_img_small/"+info.icon+".png\" height=\"100\"></div><b>"+info.name+"</b> Reyonunuz Reyon Listesinde Oluşturuldu.Reyonunuzun içine "+info.name+" çeşitlerinizi ekleyebilirsiniz... <div id=\"alerti_okay\" onclick=\"hidebackground()\">Tamam</div></div>");
							}else{
							alert(info.exp);
							hidebackground();
							}
						}
					});
		}
	function set_y_values(name,data)
		{
				$('#events_area').html('<div id=\"yield_show_div\">'+name+'</div>');
				yield_name=name;
				$.ajax({
					type:'POST',
					url:'ajax/ask_yield_prop_for_shop.php',		
					data:'data='+data,
					success: function(information) {
					$('#events_area').append(information);
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
		get_selected_new_yield_data();
		var yield_type_data='';
		  var allData = new FormData();
		  
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
				$.ajax({
					type:'POST',
					url:'ajax/new_yield_enter.php',	
					async: true,					
					data:allData,
					success: function(information) { $('#events_area').html(information);close_loader(); },
					cache: false,
					contentType: false,
					processData: false,
					});
			}else
			{
			$('#events_area').html('HATA!');
			}
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
						/*$("#yield_set_"+y_data+'_'+c_data).html(information);*/
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
	get_selected_new_yield_data();
	var yield_type_data='';
	var allData = new FormData();
	var yield_data;
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
			
				allData.append('y_image_1',document.getElementById('y_image_1').files[0]);
				allData.append('y_image_2',document.getElementById('y_image_2').files[0]);
				allData.append('y_image_3',document.getElementById('y_image_3').files[0]);
				
			$.ajax({
					type:'POST',
					url:'https://www.yeroks.com/ajax/set_yield_enter.php',	
					async: true,					
					data:allData,
					success: function(information) {
					$('#lb_events_area').html(information);
					close_lb_loader();
						}
					,
					cache: false,
					contentType: false,
					processData: false
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
			$("#lb_loader_background").css("height",height);
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
	
	
	
function change_order(info0,info1,action,type)
	{
		$.ajax({
					type:'POST',
					url:'ajax/up_down_c_y.php',					
					data:'&info0='+info0+'&info1='+info1+'&action='+action+'&type='+type,
					success: function(information) {
								if(info0==0)
									{
									show_yield_list();
									}else
									{
									get_shop_category_special(info0);
									}
					}
					});
		
	}
	function openbackground() {
	$('#black_background').css('display','block');
	$('#light_box_area').css('display','block');
	$('#lb_events_area').html('');
	};
	
	function hidebackground() {
		$('#black_background1').css('display','none');
		$('#light_box_area1').css('display','none');
		$('#light_box_area1').html('');
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
			$('#black_background1').css('display','block');
			$('#light_box_area1').css('display','block');
			if(yield_num==0){
			$('#light_box_area1').html("<div id=\"alerti\"><div id=\"alerti_text\">Boş Bu Reyonu Silmek İstedğinizden Emin misiniz? </div><div id=\"alerti_okay\" onclick=\"delete_rayon("+y_data+")\">Evet</div><div id=\"alerti_okay\" onclick=\"hidebackground()\">İptal</div></div>");
			}else{
			$('#light_box_area1').html("<div id=\"alerti\"><div id=\"alerti_text\">Bu Reyonda "+yield_num+" Adet Ürününüz Bulunmakta...İçindeki Ürünlerle Birlikte Bu Reyonu Silmek İstediğinizden Eminmisiniz?</div><div id=\"alerti_okay\" onclick=\" delete_rayon("+y_data+")\">Evet</div><div id=\"alerti_okay\" onclick=\"hidebackground()\">İptal</div></div>");
			}
		}
	function delete_rayon(y_num)
		{
		hidebackground();
			$.ajax({
					type:'POST',
					url:'ajax/delete_rayon.php',		
					data:'&y_num='+y_num,
					dataType: 'json',
					success: function(info) {
							if(info.error==0){
							$('#c-'+y_num).remove();
							alert(info.exp);
							}else{
							alert(info.exp);
							}
						}
					});
					
		}
				var y_back_id=0;
//onceki kategori
function get_cat_list(type_number)
	{
	$('#product_list').html('<img style="margin-top:74px;" height=40 src="img_files/cycle_loading.gif"/>');
	$.ajax({
					type:'POST',
					url:'m/ajax/get_category_list.php',
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
									$('#product_list').append("<div id=\"cat_list\"><div id=\"cat_list_text\">&#8226 "+res.y_name+"</div><div id=\"cat_search_icon\" onclick=\"add_rayon("+res.y_type_id+")\"></div></div>");
									}else{
									$('#product_list').append("<div id=\"cat_list\" style=\"cursor:pointer\" onclick=\"get_cat_list("+res.y_type_id+")\"><div id=\"cat_list_text\"> "+res.y_name+"</div><div id=\"cat_list_right\"></div></div>");
									}
							});
					}
							if(yerok.is_show_back_button==1){
							$('#product_list').append("<div id=\"cat_list_back\" onclick=\"go_cate_back()\">Geri Dön</div>");
							}
					$("#results_area ").css("min-height",$("#category_area").height()-41);
					}
						});				
	}
	function go_cate_back()
	{
	get_cat_list(y_back_id);
	}
$( window ).load(function() {
get_cat_list(0);
});
function display_cat(){
$(".r_b_c_b").css({"border-bottom":"2px solid #e60000","color":"#e60000"});
$(".r_b_s_b").css({"border-bottom":"2px solid #000000","color":"black"});
$("#category_area").css("display","inline-block");
$("#s_r_main").css("display","none");
}
function display_search(){
$(".r_b_s_b").css({"border-bottom":"2px solid #e60000","color":"#e60000"});
$(".r_b_c_b").css({"border-bottom":"2px solid #000000","color":"black"});
$("#s_r_main").css("display","inline-block");
$("#category_area").css("display","none");
}
</script>
</head>
<body>
<div id="black_background1" onclick="hidebackground();" style="display:none;background-color:none;">
</div>
<div id="light_box_area1" style="display:none;background-color:none;">
</div>
<div id="banner">
<div class="b_c">
<div id="logo">
<a href="https://www.yeroks.com/shop_profile.php" class="logo_y">
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

<div id="map_holder">
<?php
if($check_type->latitude=='0' or $check_type->longitude=='0' )
	{
	?>
	<div id="map_error_pos">
	Mağaza Konumu Girilmemiş!
	<div id="pos_error">
	İşyerinizin Konumunu Girdiğinizde Kaydettiğiniz Ürünlere İnsanlar Ulaşabilecek...</br>
	<a id="go_to_pos_set" href="position_setting.php" >Konum Ayarlarına Git</a>
	</div>
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
<div id="content_center">
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

<div id="product_title">
+ Yeni Reyon Oluştur
</div>
<div id="new_y_input_table">
<div id="r_b_main">
<div id="r_b_button" class="r_b_s_b" style="float:left; color:#e60000;border-bottom:2px solid #e60000;" onclick="display_search()">Arama İle Yeni Reyon Oluştur</div>
<div id="r_b_button" class="r_b_c_b" style="float:right;" onclick="display_cat()" >Kategoriler ile Yeni Reyon Oluştur</div>
</div>
<div id="s_r_main">
<input type="text" name="input_new_yield" placeholder="Gömlek, Dondurma, Hamburger vb." onkeydown="ask_yield_list()" onkeyup="ask_yield_list()" id="input_new_yield"/>
<div id="yield_search_result"></div>
<div id="new_y_input_table_text">Sattığınız veya Hizmetini Verdiğiniz Ürünün İsmini Girerek Reyonları Oluşturunuz.Ürünlerinizi Bu Reyonların İçine Ekleyeceksiniz...</div>
</div>
<div id="category_area" style="display:none;">
	<div id="category_title">Kategoriler</div>
	<div id="product_list"><!-- Kategori listesinin bulundugu alan --->
	</div>
	<div id="product_cat_link">
	</div><!-- Urun kategori yolu gorunecek --->
	<div id="new_y_input_table_text">Sattığınız veya Hizmetini Verdiğiniz Ürünü Kategorilerden Bularak Reyonları Oluşturunuz.Ürünlerinizi Bu Reyonların İçine Ekleyeceksiniz...</div>
</div>
<div id="rayon_add_result"></div>
</div>	
	
	
<div id="left_all">
<div id="product_title"><img src="https://www.yeroks.com/img_files/list.png" />Reyon Listesi</div>	
<div id="rayon">
<?php
$s_product=new show_yield();
$s_product->show_for_shop($shop_id);
?>
</div>
</div>
<div id="right_content">
<div id="help_info_area">
 <div id="c_right">2016 &#169 Yeroks İstanbul</div>
 <div id="info_butts">
 <a href="shop_index.php">Mağaza Girişi</a><a href="about.php">Hakkımızda</a><a href="product_list.php">Ürün Çeşitleri</a>
</div>
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
			echo '<meta http-equiv="refresh" content=0;URL=https://www.yeroks.com/error_page/error.php>';
		}
	}else
	{
	echo '<meta http-equiv="refresh" content=0;URL=https://www.yeroks.com/shop_index.php>';
	}
?>
