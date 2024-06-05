<?php
include("sql.php");
$connect=new connect();
$connect->sql_connect_db();

if(!isset($_POST['longi']) or !isset($_POST['lati']) ){ die("Veri Hatasi!"); }

$longi=mysql_real_escape_string(htmlspecialchars(trim($_POST['longi'])));
$lati=mysql_real_escape_string(htmlspecialchars(trim($_POST['lati'])));

if( $longi=='' or $lati=='' ){ die("Veri Hatasi!");}

		$sql="(SELECT shop.s_id as id ,shop.s_name as name ,shop.place_number as place_id, ( 6371 * acos( cos( radians($lati) ) * cos( radians( shop.latitude ) ) * cos( radians( shop.longitude ) - radians($longi) ) + sin
		( radians($lati) ) * sin( radians( shop.latitude ) ) ) ) as distance, place.name as place_name, '0' as type  FROM shop 
		LEFT JOIN place ON shop.place_number=place.id 
		GROUP BY shop.shop_number HAVING distance<20 ORDER BY RAND() LIMIT 20) 
		UNION 
		(SELECT place.id as id, place.name as name,'0' as place_id,( 6371 * acos( cos( radians($lati) ) * cos( radians( place.latitude ) ) * cos( radians( place.longitude ) - radians($longi) ) + sin
		( radians($lati) ) * sin( radians( place.latitude ) ) ) ) as distance,'' as place_name,'1' as type FROM place ORDER BY distance LIMIT 10 )  ";
		
		/*

$sql="(SELECT y_type_id as id, y_name as name, '0' as latitude, '0' as longitude, null as address, is_y, icon_name, '1' as r_type FROM y_name_list WHERE y_name LIKE '%$value%' and is_show='1' and is_y='1' LIMIT 0,10 )
		UNION
		(SELECT id, name, '0', '0', null, '0', '0',  '2' as r_type FROM brand_shop_data WHERE name LIKE '%$value%' LIMIT 0,10 )
		UNION
		(SELECT id, name,latitude,longitude,address, '0', '0',  '3' as r_type FROM place WHERE name LIKE '%$value%' LIMIT 0,10 ) ";		
		
		*/
		
		
		
$sql=mysql_query($sql) or die(mysql_error());
$count=mysql_num_rows($sql);
	if($count==0)
			{
			echo "{\"result\":0,\"text\":\"Herhangi Bir Öneri Bulunamadý...\"}";
						//echo "cevrede magaza ve avm bulunamadi...
			}else{
								$i=0;
								echo "{\"result\":1,\"results\":[";
								while($res=mysql_fetch_assoc($sql))
								{
									$i=$i+1;
									echo "{
									\"id\":".$res['id'].",
									\"name\":\"".$res['name']."\",
									\"distance\":".$res['distance'].",
									\"place_id\":".$res['place_id'].",
									\"place_name\":\"".$res['place_name']."\",
									\"type\":".$res['type']."
									}";
									if($i!=$count)
									{
									echo ",";
									}
			
								}
							echo "]}";
						}

