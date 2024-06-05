<?php


if(!isset($_POST['y_type_data']) or !isset($_POST['y_data']) or !isset($_POST['s_data']) ){ die("{\"error\":1, \"exp\":\"Veri Gonderim Hatasi...\"}"); }
include("kjasafhfjaghl.php");
$connect=new connect();
$connect->sql_connect_db();
$login=new log_in();
$fav=new favorite();
$is_fav=0;

$y_id=mysql_real_escape_string(htmlspecialchars(trim($_POST['y_data'])));
$c_id=mysql_real_escape_string(htmlspecialchars(trim($_POST['y_type_data'])));
$s_id=mysql_real_escape_string(htmlspecialchars(trim($_POST['s_data'])));
$user_id=0;

if($y_id=='' or $c_id=='' or $s_id=='' ){ die("{\"error\":1, \"exp\":\"Veri Gonderim Hatasi...\"}");}


$new_yerok=new yerok_id_handler();
		$new_yerok->get_yerok_shop_id($c_id,$s_id);
		$new_yerok->yerok_id_control();
		$new_yerok->select_table();
		$new_yerok->get_props_to_one_y_show($y_id);
		$new_yerok->get_y_icon();
		$new_yerok->get_y_first_images();
		$new_yerok->get_props_value_to_y_show();

if($login->is_login(1) ){ 
$user_id=$_SESSION['user_no'];
 if($fav->fav_control($y_id,4,$user_id,$c_id))
	{
	$is_fav=1;
	}else{
	$is_fav=0;
	}
 }		
		
	$imgs="";$i=0;
	if(array_key_exists( $y_id, $new_yerok->y_first_image))
			{
			if (array_key_exists( 1, $new_yerok->y_first_image[$y_id])) 
				{
				$imgs=$imgs."{\"img\":\"".$new_yerok->y_first_image[$y_id][1]."\"}";
				$i=1;
				}	
			if (array_key_exists( 2, $new_yerok->y_first_image[$y_id])) 
				{
				if($i>0){ $imgs=$imgs.","; }
				$imgs=$imgs."{\"img\":\"".$new_yerok->y_first_image[$y_id][2]."\"}";
				$i=2;
				}
			if (array_key_exists( 3, $new_yerok->y_first_image[$y_id])) 
				{
				if($i>0){ $imgs=$imgs.","; }
				$imgs=$imgs."{\"img\":\"".$new_yerok->y_first_image[$y_id][3]."\"}";
				$i=3;
				}
			}
			
	$props="";$i=0;
							if (array_key_exists($y_id, $new_yerok->y_data_ids)) 
									{
											foreach ( $new_yerok->y_data_ids[$y_id] as $q_id => $value_id)
												{
												if($i>0){ $props=$props.","; }
												$props=$props."{\"p_name\":\"".$new_yerok->prop_text[$q_id]."\", \"p_value\":\"";
													foreach($value_id as $v_id =>$vv_id)
														{
												$props=$props."".$new_yerok->value_text[$v_id]." ";
														}
												$props=$props."\"}";
												$i=$i+1;
												}	
									}
							if (array_key_exists($y_id,  $new_yerok->extra_prop)) 
									{
											foreach ( $new_yerok->extra_prop[$y_id] as $p_id => $p_name)
												{
												$i=$i+1;
												if($i>0){ $props=$props.","; }
												$props=$props."{\"p_name\":\"".$new_yerok->extra_prop[$y_id][$p_id]."\",";
												$props=$props."\"p_value\":\"".$new_yerok->extra_value[$y_id][$p_id]."\"";
												$props=$props."}";
												}
									}
	$exp=$new_yerok->get_exp_data($y_id);
	
	echo "{\"error\":0,\"exp\":\"\",\"y_name\":\"".$new_yerok->show_y_name[$y_id]."\",\"y_data\":".$y_id.",\"s_data\":".$s_id.",\"c_data\":".$c_id.",
	\"icon\":\"".$new_yerok->y_icon."\",
	\"imgs\":[".$imgs."],
	\"props\":[".$props."],\"is_fav\":[".$is_fav."],
	\"price\":\"".$new_yerok->price[$y_id][0]."\",\"price_type\":".$new_yerok->price[$y_id][1].",\"explain\":\"".$exp."\"}";
		






?>