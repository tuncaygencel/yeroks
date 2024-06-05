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
if( !isset($_POST['plus_props_value']) or  !isset($_POST['plus_props']) or  !isset($_POST['about']) or !isset($_POST['json_prop']) or !isset($_POST['yield_name']) or !isset($_POST['yield_type_data']) or !isset($_POST['yield_data']) or !isset($_POST['price']) or !isset($_POST['currency']))
	{
	die("Veri Gönderim Hatası Oluştu. Lütfen Tekrar Deneyiniz....");
	}
$shop_id=$_SESSION['user_no'];
echo "<div id=\"uploaded_div\">";
$json_prop=trim($_POST['json_prop']);
//WARNING json_prop not 					
//for not to get error first was used json_decode then mysql_real_esca...

$connect=new connect();	
	
//connect database
$connect->sql_connect_db();

$yerok_name=mysql_real_escape_string(htmlspecialchars(trim($_POST['yield_name'])));
$yerok_id=mysql_real_escape_string(htmlspecialchars(trim((int)$_POST['yield_type_data'])));
//yield type id
$yield_id=mysql_real_escape_string(htmlspecialchars(trim((int)$_POST['yield_data'])));
//yield table y_id
$price=mysql_real_escape_string(htmlspecialchars(trim((float)$_POST['price'])));
$currency=mysql_real_escape_string(htmlspecialchars(trim((int)$_POST['currency'])));	
$delete[1]=mysql_real_escape_string(htmlspecialchars(trim($_POST['delete_1'])));
$delete[2]=mysql_real_escape_string(htmlspecialchars(trim($_POST['delete_2'])));
$delete[3]=mysql_real_escape_string(htmlspecialchars(trim($_POST['delete_3'])));

$about=mysql_real_escape_string(htmlspecialchars(trim($_POST['about'])));
$plus_props=json_decode(stripslashes($_POST['plus_props']));
$plus_props_value=json_decode(stripslashes($_POST['plus_props_value']));
	
$new_yerok=new yerok_id_handler();
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
	
	if( $new_yerok->set_yield_in_database($yield_id,$shop_id,$yerok_name,$currency)	)
	{
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
	$values=$values.",(".$yield_id.",".$yerok_id.",'".$prop_text."','".$value_text."')";
	$is_there_props=1;
	}
}
		$sql="DELETE FROM y_extra_props WHERE y_id='$yield_id' and y_type_id='$yerok_id'";
		$sql=mysql_query($sql) or die(mysql_error());
	
	if($is_there_props==1){
		$values = ltrim($values, ",");
		$sql="INSERT INTO y_extra_props(y_id,y_type_id,prop,value) VALUES".$values;
		$sql=mysql_query($sql) or die(mysql_error());
	}
////////
if($about!=""){
	$sql="SELECT y_explain FROM y_explain_text WHERE y_type_id='$yerok_id' and y_id='$yield_id' ";
	$sql=mysql_query($sql) or die(mysql_error());
	if(mysql_num_rows($sql)>0){
	$sql="UPDATE y_explain_text SET y_explain='$about' WHERE y_id='$yield_id' and y_type_id='$yerok_id' ";
	$sql=mysql_query($sql) or die(mysql_error());	
	}else{
	$sql="INSERT INTO y_explain_text(y_id,y_type_id,y_explain) VALUES('$y_id','$yerok_id','$about')";
	$sql=mysql_query($sql) or die(mysql_error());
	}
}else{
$sql="DELETE FROM y_explain_text WHERE y_id='$yield_id' and y_type_id='$yerok_id'";
$sql=mysql_query($sql) or die(mysql_error());
}

//////////////////////////image upload proses start
	///////////
$m_image=new image_handler();
		for($no=1;$no<=3;$no++)
		{
			if(($delete[$no]=='true'))
						{
						$m_image->delete_y_image_in_databese( $yield_id,$yerok_id,$no);
						}else
						{
								$file_name="y_image_".$no;
										if( isset($_FILES[$file_name]['type']) and ($_SERVER['REQUEST_METHOD'] == "POST"))
											{
							if($_FILES[$file_name]['error'] == UPLOAD_ERR_OK) 
							{
								$imageway=$_FILES[$file_name]['tmp_name'];
								$imagename=$_FILES[$file_name]['name'];
								$type=$_FILES[$file_name]['type'];
								$size=$_FILES[$file_name]['size'];
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
									$m_image->delete_y_image_in_databese( $yield_id,$yerok_id,$no);	
									//firstly delete after insert if we put deleting after updating, mysql delete the last inserted image data row 									
									$m_image->update_y_image_to_database( $yield_id,$yerok_id,$no,$rand_image_name);
									echo "<br>Ürünün ".$no." Nolu Resmi Yüklendi...<br><div id=\"uploaded_img\"><img src=\"y_img_small/".$rand_image_name."\" height=100/></div>";
								};
							}
							if($no>3){break;}
											}
						}
		}
		$m_image->set_order_images($yield_id,$yerok_id);
		//eger bu siralama fonksiyonunu for dongusu icinde yapilirsa 1. silinince geri kalan 3 ve 2 nolu resimler 1 ve 2 ile siralanacak 2. silinmesi gereken 1 olacagi icin silme islemi gerceklesmeyecektir...
	}else{
	echo "<br>Bir Sorun Oluştu...";
	}
	
	echo "</div>"
	
	
	
	
	
	
?>
