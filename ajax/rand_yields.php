<?php

$loc_pos=0;
$q_status=0;
include("kjasafhfjaghl.php");
$connect=new connect();
$connect->sql_connect_db();
	if(isset($_POST['loc_pos']) and isset($_POST['q_status']) )
		{
		$loc_pos=mysql_real_escape_string(htmlspecialchars(trim((int)$_POST['loc_pos'])));
		$q_status=mysql_real_escape_string(htmlspecialchars(trim((int)$_POST['q_status'])));
		}else{
		die('{"problem":1,"explain":"Lütfen Sayfayı Tekrar Yükleyiniz..."}');
		}
   
	 function get_client_ip() {
   		 $ipaddress = '';
   		 if (getenv('HTTP_CLIENT_IP'))
     		   	$ipaddress = getenv('HTTP_CLIENT_IP');
    		else if(getenv('HTTP_X_FORWARDED_FOR'))
        		$ipaddress = getenv('HTTP_X_FORWARDED_FOR');
         	 else if(getenv('HTTP_X_FORWARDED'))
      			  $ipaddress = getenv('HTTP_X_FORWARDED');
	        else if(getenv('HTTP_FORWARDED_FOR'))
      			  $ipaddress = getenv('HTTP_FORWARDED_FOR');
  		  else if(getenv('HTTP_FORWARDED'))
     			  $ipaddress = getenv('HTTP_FORWARDED');
  		  else if(getenv('REMOTE_ADDR'))
  		      $ipaddress = getenv('REMOTE_ADDR');
	        else
      		  $ipaddress = 'UNKNOWN';
		    return $ipaddress;
		}

	function change($text)
				{
				$text = trim($text);
				$search = array('Ç','ç','Ğ','ğ','ı','İ','Ö','ö','Ş','ş','Ü','ü',' ','’','&');
				$replace = array('c','c','g','g','i','i','o','o','s','s','u','u','-','-','-');
				$new_text = str_replace($search,$replace,$text);
				return $new_text;
				}
$lati=0;
$longi=0;

					switch ($loc_pos) 
							{
							case  1:
								$lati= 41.037084; $longi=28.985612; 
								break;
							case  2:
								$lati= 40.990502; $longi=29.029118; 
								break;
							case  3:
								$lati= 41.063025; $longi=28.992526; 
								break;
							case  4:
								$lati= 41.007356; $longi=28.977027;
								break;
							default:
								$lati= 41.037084; $longi=28.985612;
								break;
							}
							
					//echo $loc_pos."-".$q_status;	
			if($loc_pos==3)
			{
					switch ($q_status) 
							{
							case  (1):
							$table='y1';
							$sql="SELECT $table.y_id, $table.y_name,$table.latitude,$table.longitude,$table.place_id,( 6371 * acos( cos( radians($lati) ) * cos( radians( $table.latitude ) ) * cos( radians( $table.longitude ) - radians($longi) ) + sin
( radians($lati) ) * sin( radians($table.latitude ) ) ) ) AS distance,shop.s_name,shop.s_id,shop.floor,place.name,place.address,shop_adress.adress_text FROM $table LEFT JOIN shop ON shop.s_id=$table.s_id LEFT JOIN place ON $table.place_id=place.id LEFT JOIN shop_adress ON shop_adress.s_id=shop.s_id  WHERE (y_name_id >'11' and y_name_id < '10000000') and $table.place_id='20' GROUP BY $table.s_id  ORDER BY distance  LIMIT 10";
							break;
							case  (2):
							$table='y1';
								$sql="SELECT $table.y_id, $table.y_name,$table.latitude,$table.longitude,$table.place_id,( 6371 * acos( cos( radians($lati) ) * cos( radians( $table.latitude ) ) * cos( radians( $table.longitude ) - radians($longi) ) + sin
( radians($lati) ) * sin( radians($table.latitude ) ) ) ) AS distance,shop.s_name,shop.s_id,shop.floor,place.name,place.address,shop_adress.adress_text FROM $table LEFT JOIN shop ON shop.s_id=$table.s_id LEFT JOIN place ON $table.place_id=place.id LEFT JOIN shop_adress ON shop_adress.s_id=shop.s_id  WHERE y_name_id ='900010' and $table.place_id='20'  GROUP BY $table.s_id HAVING distance < 30  ORDER BY distance  LIMIT 10";
							break;
							case  (3):
							$table='y3';
								$sql="SELECT $table.y_id, $table.y_name,$table.latitude,$table.longitude,$table.place_id,( 6371 * acos( cos( radians($lati) ) * cos( radians( $table.latitude ) ) * cos( radians( $table.longitude ) - radians($longi) ) + sin
( radians($lati) ) * sin( radians($table.latitude ) ) ) ) AS distance,shop.s_name,shop.s_id,shop.floor,place.name,place.address,shop_adress.adress_text FROM $table LEFT JOIN shop ON shop.s_id=$table.s_id LEFT JOIN place ON $table.place_id=place.id LEFT JOIN shop_adress ON shop_adress.s_id=shop.s_id  WHERE (y_name_id > '40000000' and y_name_id < '50000000') and $table.place_id='20'  GROUP BY $table.s_id HAVING distance < 30  ORDER BY distance  LIMIT 10";
							break;
							case  (4):
							$table='y6';
								$sql="SELECT $table.y_id, $table.y_name,$table.latitude,$table.longitude,$table.place_id,( 6371 * acos( cos( radians($lati) ) * cos( radians( $table.latitude ) ) * cos( radians( $table.longitude ) - radians($longi) ) + sin
( radians($lati) ) * sin( radians($table.latitude ) ) ) ) AS distance,shop.s_name,shop.s_id,shop.floor,place.name,place.address,shop_adress.adress_text FROM $table LEFT JOIN shop ON shop.s_id=$table.s_id LEFT JOIN place ON $table.place_id=place.id LEFT JOIN shop_adress ON shop_adress.s_id=shop.s_id  WHERE y_name_id ='130016020' and $table.place_id='20'  GROUP BY $table.s_id HAVING distance < 30  ORDER BY distance  LIMIT 10";
							break;
							default:
							$table='y1';
								$sql="SELECT $table.y_id, $table.y_name,$table.latitude,$table.longitude,$table.place_id,( 6371 * acos( cos( radians($lati) ) * cos( radians( $table.latitude ) ) * cos( radians( $table.longitude ) - radians($longi) ) + sin
( radians($lati) ) * sin( radians($table.latitude ) ) ) ) AS distance,shop.s_name,shop.s_id,shop.floor,place.name,place.address,shop_adress.adress_text FROM $table LEFT JOIN shop ON shop.s_id=$table.s_id LEFT JOIN place ON $table.place_id=place.id LEFT JOIN shop_adress ON shop_adress.s_id=shop.s_id  WHERE (y_name_id >'11' and y_name_id < '10000000') and $table.place_id='20'  GROUP BY $table.s_id HAVING distance < 30  ORDER BY distance  LIMIT 10";
							break;
							}		
			}else{				
				switch ($q_status) 
							{
							case  (1):
							$table='y1';
							$sql="SELECT $table.y_id, $table.y_name,$table.latitude,$table.longitude,$table.place_id,( 6371 * acos( cos( radians($lati) ) * cos( radians( $table.latitude ) ) * cos( radians( $table.longitude ) - radians($longi) ) + sin
( radians($lati) ) * sin( radians($table.latitude ) ) ) ) AS distance,shop.s_name,shop.s_id,shop.floor,place.name,place.address,shop_adress.adress_text FROM $table LEFT JOIN shop ON shop.s_id=$table.s_id LEFT JOIN place ON $table.place_id=place.id LEFT JOIN shop_adress ON shop_adress.s_id=shop.s_id  WHERE (y_name_id >'11' and y_name_id < '10000000') GROUP BY $table.s_id HAVING distance < 30  ORDER BY distance  LIMIT 10";
							break;
							case  (2):
							$table='y1';
								$sql="SELECT $table.y_id, $table.y_name,$table.latitude,$table.longitude,$table.place_id,( 6371 * acos( cos( radians($lati) ) * cos( radians( $table.latitude ) ) * cos( radians( $table.longitude ) - radians($longi) ) + sin
( radians($lati) ) * sin( radians($table.latitude ) ) ) ) AS distance,shop.s_name,shop.s_id,shop.floor,place.name,place.address,shop_adress.adress_text FROM $table LEFT JOIN shop ON shop.s_id=$table.s_id LEFT JOIN place ON $table.place_id=place.id LEFT JOIN shop_adress ON shop_adress.s_id=shop.s_id  WHERE y_name_id ='900010' GROUP BY $table.s_id HAVING distance < 30  ORDER BY distance  LIMIT 10";
							break;
							case  (3):
								$table='y3';
								$sql="SELECT $table.y_id, $table.y_name,$table.latitude,$table.longitude,$table.place_id,( 6371 * acos( cos( radians($lati) ) * cos( radians( $table.latitude ) ) * cos( radians( $table.longitude ) - radians($longi) ) + sin
( radians($lati) ) * sin( radians($table.latitude ) ) ) ) AS distance,shop.s_name,shop.s_id,shop.floor,place.name,place.address,shop_adress.adress_text FROM $table LEFT JOIN shop ON shop.s_id=$table.s_id LEFT JOIN place ON $table.place_id=place.id LEFT JOIN shop_adress ON shop_adress.s_id=shop.s_id  WHERE (y_name_id >'40000000' and y_name_id < '50000000') GROUP BY $table.s_id HAVING distance < 30  ORDER BY distance ASC, RAND()  LIMIT 10";
							break;
							case  (4):
							$table='y6';
								$sql="SELECT $table.y_id, $table.y_name,$table.latitude,$table.longitude,$table.place_id,( 6371 * acos( cos( radians($lati) ) * cos( radians( $table.latitude ) ) * cos( radians( $table.longitude ) - radians($longi) ) + sin
( radians($lati) ) * sin( radians($table.latitude ) ) ) ) AS distance,shop.s_name,shop.s_id,shop.floor,place.name,place.address,shop_adress.adress_text FROM $table LEFT JOIN shop ON shop.s_id=$table.s_id LEFT JOIN place ON $table.place_id=place.id LEFT JOIN shop_adress ON shop_adress.s_id=shop.s_id  WHERE y_name_id ='130016020' GROUP BY $table.s_id HAVING distance < 30  ORDER BY distance  LIMIT 10";
							break;
							default:
							$table='y1';
								$sql="SELECT $table.y_id, $table.y_name,$table.latitude,$table.longitude,$table.place_id,( 6371 * acos( cos( radians($lati) ) * cos( radians( $table.latitude ) ) * cos( radians( $table.longitude ) - radians($longi) ) + sin
( radians($lati) ) * sin( radians($table.latitude ) ) ) ) AS distance,shop.s_name,shop.s_id,shop.floor,place.name,place.address,shop_adress.adress_text FROM $table LEFT JOIN shop ON shop.s_id=$table.s_id LEFT JOIN place ON $table.place_id=place.id LEFT JOIN shop_adress ON shop_adress.s_id=shop.s_id  WHERE (y_name_id >'11' and y_name_id < '10000000') GROUP BY $table.s_id HAVING distance < 30  ORDER BY distance  LIMIT 10";
							break;
							}
				}	


$sql=mysql_query($sql) or die(mysql_error());
$count=mysql_num_rows($sql);
if($count>0)
	{
	$i=0;
	$ii=0;
	echo '{"problem":0,"explain":"","results":[';
	while($res=mysql_fetch_assoc($sql))
		{
		$i=$i+1;
		$adress="";
		if($res['place_id']==0){ $adress=$res['adress_text'];}else{ $adress=$res['name']."<br>".$res['address'];}
		if($res['distance']<1){$distance=round($res['distance']*1000,0);$distance=$distance." m";}else{$distance=round($res['distance'],2);$distance=$distance." km";}
		
		echo "{
							\"y_name\":\"".$res['y_name']."\",
							\"s_id\":".$res['s_id'].",
							\"s_name\":\"".$res['s_name']."\",
							\"a_s_name\":\"".change($res['s_name'])."\",
							\"lati\":".$res['latitude'].",
							\"longi\":".$res['longitude'].",
							\"distance\":\"".$distance."\",
							\"place\":".$res['place_id'].",
							\"floor\":\"".$res['floor']."\",
							\"address\":\"".$adress."\",
							\"i_n\":".$ii."
							}";
		if($i!=$count)
								{
								echo ",";
								}
		$ii=$ii+1;
		}
		echo "]}";
	$ip_add=get_client_ip();
	if(isset($_POST['type']) )
                {
                $type=mysql_real_escape_string(htmlspecialchars(trim((int)$_POST['type'])));
                if($type==1 or $type==2)
			{
			$m_c_data=$type;
			}else{
			$m_c_data=-2;
			}
                }else{
		$m_c_data=-1;
		}

	$sql1="INSERT INTO search_data(u_id,latitude,longitude,y_name_id,props,s_time,place_id,ip) VALUES(-1,'$lati','$longi','$q_status','$m_c_data',NOW(),'$loc_pos','$ip_add')";	
	mysql_query($sql1);


	}else{
	die('{"problem":1,"explain":"Lütfen Tekrar Deneyiniz..."}');
	}
		?>
