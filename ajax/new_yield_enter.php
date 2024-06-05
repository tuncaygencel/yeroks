<?php
include("kjasafhfjaghl.php");
session_start();
if(!isset($_SESSION['user_type'])  or !isset($_SESSION['user_no']) or !isset($_SESSION['mail_adress']))
	{
	die("Lütfen Giriş Yapınız...");
	}
if($_SESSION['user_type']!=2)
	{
	die("Bu İşleme Yetkiniz Bulunmamakta...");
	}
if( !isset($_POST['plus_props_value']) or  !isset($_POST['plus_props']) or  !isset($_POST['about']) or !isset($_POST['json_prop']) or !isset($_POST['yield_name']) or !isset($_POST['yield_type_data']) or !isset($_POST['price']) or !isset($_POST['currency']))
	{
	die("Veri Gönderim Hatası Oluştu. Lütfen Tekrar Deneyiniz....");
	}

$connect=new connect();
//connect database
$connect->sql_connect_db();

echo "<div id=\"uploaded_div\">";
$json_prop=trim($_POST['json_prop']);
//WARNING json_prop not 					
//for not to get error first was used json_decode then mysql_real_esca...
$yerok_name=mysql_real_escape_string(htmlspecialchars(trim($_POST['yield_name'])));
$yerok_id=mysql_real_escape_string(htmlspecialchars(trim((int)$_POST['yield_type_data'])));
$price=mysql_real_escape_string(htmlspecialchars(trim((float)$_POST['price'])));
$currency=mysql_real_escape_string(htmlspecialchars(trim((int)$_POST['currency'])));
$about=mysql_real_escape_string(htmlspecialchars(trim($_POST['about'])));
$plus_props=json_decode(stripslashes($_POST['plus_props']));
$plus_props_value=json_decode(stripslashes($_POST['plus_props_value']));



$shop_id=$_SESSION['user_no'];
//set php functions

$new_yerok=new yerok_id_handler();
$shop_info=new user_info();
$yield_order=new yield_order();
$search_data=new search_funtions();
//control for max yield number  
		if(!$search_data->control_max_y_number($shop_id,$yerok_id))
				{
				die("<br>Bir Ürün Kategorisi İçin En Fazla 99 Adet Ürün Ekleyebilirsiniz.Eğer Bu Kategoriye Yeni Bir Ürün Eklemek İstiyorsanız Bu Kategorideki Herhangi Bir Ürününüzü Siliniz...");
				}


//get shop datas from database
$shop_info->get_shop_info($shop_id);
$latitude=$shop_info->user_latitude;
$longitude=$shop_info->user_longitude;
//yield id control
$new_yerok->get_yerok_shop_id($yerok_id,$shop_id);
$new_yerok->yerok_id_control();
//select yield table
$new_yerok->select_table();
//ready arrays to insert proporties in database
$new_yerok->to_ready_arrays($json_prop);
//control properties which have been gotten in database 
$new_yerok->control_array_in_database();
//ready arrays of props and its selected value

if($price>0)
		{
		$new_yerok->ready_price_max_min_values($price);
		$new_yerok->ready_array_max_min_values();
		$new_yerok->check_prices();
		}else
		{
		$new_yerok->ready_array_max_min_values();
		$new_yerok->get_price_to_exact();
		}

$yield_order->set_variables($shop_id,$new_yerok->yerok_id,0,0);
$yield_order->calculate_next_order();
$y_order=$yield_order->y_order;
$c_order=$yield_order->c_order;

$new_yerok->enter_new_yield_in_database($yerok_name,$latitude,$longitude,$currency,$y_order);
$new_yerok->enter_y_type_in_database($c_order);
//reorder categories



$y_id=$new_yerok->inserted_y_id;
$y_type_id=$new_yerok->yerok_id;

	if($y_id==0 or $y_id==null)
		{
		//upload error
		die();
		}
//////////plus_props_operations
$p_count=count($plus_props);
$v_count=count($plus_props_value);

if($p_count>$v_count){$count=$v_count-1;}else{$count=$v_count-1;} 
$values="";
$is_there_props=0;
if($count>10){ $count=10;}
for ($i = 0; $i <= $count; $i++){

	$prop_text=mysql_real_escape_string(htmlspecialchars(trim($plus_props[$i])));
	$value_text=mysql_real_escape_string(htmlspecialchars(trim($plus_props_value[$i])));
	
	if($prop_text!="" and $value_text!=""){
	$values=$values.",(".$y_id.",".$y_type_id.",'".$prop_text."','".$value_text."')";
	$is_there_props=1;
	}
}
	if($is_there_props==1){
		$values = ltrim($values, ",");
		$sql="INSERT INTO y_extra_props(y_id,y_type_id,prop,value) VALUES".$values;
		$sql=mysql_query($sql) or die(mysql_error());
	}
////////
if($about!=""){
$sql="INSERT INTO y_explain_text(y_id,y_type_id,y_explain) VALUES('$y_id','$y_type_id','$about')";
$sql=mysql_query($sql) or die(mysql_error());
}

//////////////////////////image upload proses start
$no=1;
if(isset($_FILES['y_image'])){
	if(isset($_POST) and $_SERVER['REQUEST_METHOD'] == "POST")
			{
				foreach($_FILES['y_image']['error'] as $key => $error) 
				{
					if($error == UPLOAD_ERR_OK) 
					{
						$imageway=$_FILES['y_image']['tmp_name'][$key];
						$imagename=$_FILES['y_image']['name'][$key];
						$type=$_FILES['y_image']['type'][$key];
						$size=$_FILES['y_image']['size'][$key];
						$m_image=new image_handler();
						$m_image->initialize($imageway,$imagename,$type);
						if($m_image->is_valid_type()==false)
						{
								echo "<br>".$no." Nolu Resim Yükleme Sorunu:-Yalnızca JPEG GIF veya PNG formatında Resim Yükleyebilirsiniz...";	
						}elseif(($size/1024/1024)>3)
						{
								echo "<br>".$no." Nolu Resim Yükleme Sorunu:-Lütfen 3MB'dan Daha Küçük Resim Yükleyiniz...";
						}else
						{
							$rand_image_name=$m_image->get_random_image_name("jpg");//We want to save every image in jpg format
							$m_image->import_image();
							$m_image->resize(800,600);
							$m_image->save_image_to("../y_img_big/".$rand_image_name);
							$m_image->resize(200,150);
							$m_image->save_image_to("../y_img_small/".$rand_image_name);							
							$m_image->insert_y_image_to_database( $y_id,$y_type_id,$no,$rand_image_name);
							
								echo "<br>Ürünün ".$no." Nolu Resmi Yüklendi...<br><div id=\"uploaded_img\"><img src=\"y_img_small/".$rand_image_name."\" height=100/></div>";
								
						};
					}
					$no=$no+1;
					if($no>3){break;}
				};
				
			}else
			{
			echo "<br>Resimlerin Yüklenmesi Sırasında Bir Sorun Oluştu...";
			}
	}
echo "</div>"
//////image upload proses finish


// { "data" : [{ "prop":2 , "value":219 },{ "prop":4 , "value":125 },{ "prop":9 , "value":177 },{ "prop":9 , "value":179 }]}
// { "data" : [{ "prop":2 , "value":1018 },{ "prop":4 , "value":108 },{ "prop":9 , "value":178 },{ "prop":9 , "value":180 }]}

?>
