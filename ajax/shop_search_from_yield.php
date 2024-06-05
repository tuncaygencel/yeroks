<?php
session_start();
include("kjasafhfjaghl.php");
$connect=new connect();
$connect->sql_connect_db();

if(!isset($_POST['cat_id']) or !isset($_POST['p_data']) or !isset($_POST['longi']) or !isset($_POST['lati']) or !isset($_POST['page']) or !isset($_POST['source']) or !isset($_POST['page_data']) )
{ die("{\"type\":-1,\"\":0,\"text\":\"Veri Gönderim Hatası...Lütfen Sayfayı Yenileyiniz...\",\"results\":[]}"); }

$cat_id=mysql_real_escape_string(htmlspecialchars(trim($_POST['cat_id'])));
$place_id=mysql_real_escape_string(htmlspecialchars(trim($_POST['p_data'])));
$longi=mysql_real_escape_string(htmlspecialchars(trim($_POST['longi'])));
$lati=mysql_real_escape_string(htmlspecialchars(trim($_POST['lati'])));
$page=mysql_real_escape_string(htmlspecialchars(trim($_POST['page'])));
$source=mysql_real_escape_string(htmlspecialchars(trim($_POST['source'])));
$page_id=mysql_real_escape_string(htmlspecialchars(trim($_POST['page_data'])));

$result_number=20;
$start_number=($page-1)*$result_number;

if($cat_id=='' or $place_id=='' or $longi=='' or $lati=='' ){ die("{\"type\":-1,\"\":0,\"text\":\"Arama Sirasinda Bir Hata Olustu...\",\"results\":[]}");}

$y_order=new yield_order();

$table=$y_order->select_table($cat_id);

if($place_id!=0){
$sql="SELECT $table.s_id, shop.s_name, shop.floor FROM $table INNER JOIN shop ON shop.s_id=$table.s_id 
WHERE $table.y_name_id=$cat_id and $table.place_id=$place_id GROUP BY $table.s_id";
}else{
	$sql="SELECT ".$table.".latitude,".$table.".longitude,
	( 6371 * acos( cos( radians($lati) ) * cos( radians( ".$table.".latitude ) ) * cos( radians( ".$table.".longitude ) 
	- radians($longi) ) + sin( radians($lati) ) * sin( radians( ".$table.".latitude ) ) ) ) AS distance,
	".$table.".s_id,shop.s_name,shop.floor,shop.place_number,place.name FROM ".$table." 
	LEFT JOIN place ON ".$table.".place_id=place.id  
	LEFT JOIN shop ON ".$table.".s_id=shop.s_id  	
	WHERE  ".$table.".y_name_id=$cat_id GROUP BY ".$table.".s_id HAVING distance < 1200  ORDER BY distance LIMIT $start_number,$result_number";
}
$sql=mysql_query($sql) or die("{\"type\":-1,\"\":0,\"text\":\"Arama Sirasinda Bir Hata Olustu...\",\"results\":[]}");
	
	
	$count=mysql_num_rows($sql);
	if($count==0)
		{
		//type=0 means no shop found
		//type=-1 means an error occurd
		//type=1 means everything is okay and shop have found
		echo "{\"type\":0,\"text\":\"Herhangi Bir Sonuç Bulunamadı...\",\"results\":[]}";
		}elseif($place_id==0)
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
		}else{
		$i=0;
		echo "{\"type\":1,\"text\":\"\",\"results\":[";
			while($res=mysql_fetch_assoc($sql))
				{
				$i=$i+1;
					echo "{\"s_id\":".$res['s_id'].",
							\"s_name\":\"".$res['s_name']."\",
							\"floor\":".$res['floor'].",
							\"i_n\":".$i."
							}";
					if($i!=$count)
						{
							echo ",";
						}
				}
		echo "]}";
		}
		
		$connect->enter_search_data($place_id,$lati,$longi,$cat_id,1,$page_id,$source,$page);
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
?>