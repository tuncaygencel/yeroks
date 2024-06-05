<?php
include("sql.php");
$connect=new connect();
$connect->sql_connect_db();

if(!isset($_POST['longi']) or !isset($_POST['lati']) ){ die("Veri Hatasi!"); }

$longi=mysql_real_escape_string(htmlspecialchars(trim($_POST['longi'])));
$lati=mysql_real_escape_string(htmlspecialchars(trim($_POST['lati'])));

if( $longi=='' or $lati=='' ){ die("Veri Hatasi!");}
/*
$sql="SELECT shop_y_list.y_type_id, y_name_list.y_name,y_name_list.icon_name FROM shop_y_list 
		LEFT JOIN y_name_list ON shop_y_list.y_type_id=y_name_list.y_type_id 
		WHERE shop_y_list.shop_id IN( SELECT shop.s_id FROM shop ORDER BY ( 6371 * acos( cos( radians($lati) ) * cos( radians( shop.latitude ) ) * cos( radians( shop.longitude ) - radians($longi) ) + sin
		( radians($lati) ) * sin( radians( shop.latitude ) ) ) )  DESC   ) 
		GROUP BY y_name_list.icon_name LIMIT 0, 20 ";
		

			$sql="SELECT shop_y_list.y_type_id, y_name_list.y_name,y_name_list.icon_name,shop.s_id,shop.s_name,shop.place_number, ( 6371 * acos( cos( radians($lati) ) * cos( radians( shop.latitude ) ) * cos( radians( shop.longitude ) - radians($longi) ) + sin
		( radians($lati) ) * sin( radians( shop.latitude ) ) ) ) as distance,place.name FROM shop 
		INNER JOIN shop_y_list ON shop_y_list.shop_id=shop.s_id
		INNER JOIN y_name_list ON y_name_list.y_type_id=shop_y_list.y_type_id 
		LEFT JOIN place ON shop.place_number=place.id 
		GROUP BY shop.s_id ORDER BY distance, RAND() LIMIT 0, 20 ";
		*/
		
		
		$sql="SELECT shop_y_list.y_type_id, y_name_list.y_name,y_name_list.icon_name,shop.s_id,shop.s_name,shop.place_number, ( 6371 * acos( cos( radians($lati) ) * cos( radians( shop.latitude ) ) * cos( radians( shop.longitude ) - radians($longi) ) + sin
		( radians($lati) ) * sin( radians( shop.latitude ) ) ) ) as distance, place.name FROM shop 
		INNER JOIN shop_y_list ON shop_y_list.shop_id=shop.s_id
		INNER JOIN y_name_list ON y_name_list.y_type_id=shop_y_list.y_type_id 
		LEFT JOIN place ON shop.place_number=place.id 
		GROUP BY shop_y_list.y_type_id,shop.shop_number HAVING distance<100 ORDER BY RAND() LIMIT 0, 20 ";
		
$sql=mysql_query($sql) or die(mysql_error());
$count=mysql_num_rows($sql);
	if($count==0)
			{
			echo "{\"result\":0,\"text\":\"Herhangi Bir Ürün Bulunamadý...\"}";
						//echo "cevrede urun bulunamadi...
			}else{
								$i=0;
								echo "{\"result\":1,\"results\":[";
								while($res=mysql_fetch_assoc($sql))
								{
									$i=$i+1;
									echo "{
									\"y_type_id\":".$res['y_type_id'].",
									\"y_name\":\"".$res['y_name']."\",
									\"icon_name\":".$res['icon_name'].",
									\"s_id\":".$res['s_id'].",
									\"s_name\":\"".$res['s_name']."\",
									\"distance\":".$res['distance'].",
									\"place_id\":".$res['place_number'].",
									\"place_name\":\"".$res['name']."\"
									}";
									if($i!=$count)
									{
									echo ",";
									}
			
								}
							echo "]}";
						}

