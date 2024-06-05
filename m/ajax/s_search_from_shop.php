<?php
session_start();
include("sql.php");
$connect=new connect();
$connect->sql_connect_db();

if(!isset($_POST['shop_type']) or !isset($_POST['longi']) or !isset($_POST['lati']) or !isset($_POST['source']) or !isset($_POST['page_data']) ){ die("Veri Hatasi!"); }

$shop_type_id=mysql_real_escape_string(htmlspecialchars(trim($_POST['shop_type'])));
$longi=mysql_real_escape_string(htmlspecialchars(trim($_POST['longi'])));
$lati=mysql_real_escape_string(htmlspecialchars(trim($_POST['lati'])));
$source=mysql_real_escape_string(htmlspecialchars(trim($_POST['source'])));
$page_id=mysql_real_escape_string(htmlspecialchars(trim($_POST['page_data'])));


if($shop_type_id=='' or $longi=='' or $lati=='' ){ die("Veri Hatasi!");}



	$sql="SELECT shop.s_id, shop.s_name, shop.latitude, shop.longitude, shop.floor, shop.place_number,
	( 6371 * acos( cos( radians($lati) ) * cos( radians( shop.latitude ) ) * cos( radians( shop.longitude ) 
	- radians($longi) ) + sin( radians($lati) ) * sin( radians( shop.latitude ) ) ) ) AS distance, place.name FROM shop 
	LEFT JOIN place ON shop.place_number=place.id 	
	WHERE shop.shop_number=$shop_type_id ORDER BY distance LIMIT 0,20";

$sql=mysql_query($sql) or die("{\"type\":-1,\"\":0,\"text\":\"Arama Sýrasýnda Bir Hata Oluþtu...\",\"results\":[]}");
	
	
	$count=mysql_num_rows($sql);
	if($count==0)
		{
		//type=0 means no shop found
		//type=-1 means an error occurd
		//type=1 means everything is okay and shop have found
		echo "{\"type\":0,\"text\":\"Herhangi Bir Sonuç Bulunamadý...\",\"results\":[]}";
		}else
		{
		$i=0;
		echo "{\"type\":1,\"text\":\"\",\"results\":[";
			while($res=mysql_fetch_assoc($sql))
				{
				$i=$i+1;
					echo "{\"s_id\":".$res['s_id'].",
							\"s_name\":\"".$res['s_name']."\",
							\"floor\":".$res['floor'].",
							\"latitude\":".$res['latitude'].",
							\"longitude\":".$res['longitude'].",
							\"place_id\":".$res['place_number'].",
							\"place_name\":\"".$res['name']."\",
							\"distance\":\"".$res['distance']."\",
							\"i_n\":".$i."
							}";
					if($i!=$count)
						{
							echo ",";
						}
				}
		echo "]}";
		}
		
		
			$connect->enter_search_data(0,$lati,$longi,$shop_type_id,2,$page_id,$source,1);
		
		
		
		
		
		
		
		
		
		
		
		
		
		
?>