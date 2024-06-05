<?php
session_start();
include("sql.php");
$connect=new connect();
$connect->sql_connect_db();

if(!isset($_POST['p_data']) or !isset($_POST['longi']) or !isset($_POST['lati']) or !isset($_POST['source']) or !isset($_POST['page_data']) ){ die("Veri Hatasi!"); }

$place_id=mysql_real_escape_string(htmlspecialchars(trim($_POST['p_data'])));
$longi=mysql_real_escape_string(htmlspecialchars(trim($_POST['longi'])));
$lati=mysql_real_escape_string(htmlspecialchars(trim($_POST['lati'])));
$source=mysql_real_escape_string(htmlspecialchars(trim($_POST['source'])));
$page_id=mysql_real_escape_string(htmlspecialchars(trim($_POST['page_data'])));


if($place_id=='' or $longi=='' or $lati=='' ){ die("Veri Hatasi!");}

	$sql="SELECT id, name, latitude, longitude, address,
	( 6371 * acos( cos( radians($lati) ) * cos( radians( latitude ) ) * cos( radians( longitude ) 
	- radians($longi) ) + sin( radians($lati) ) * sin( radians( latitude ) ) ) ) AS distance FROM place 	
	WHERE id=$place_id";

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
					echo "{\"latitude\":".$res['latitude'].",
							\"longitude\":".$res['longitude'].",
							\"place_id\":".$res['id'].",
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
		
		
		
				$connect->enter_search_data(0,$lati,$longi,$place_id,3,$page_id,$source,1);
		
		
		
		
		
		
		
		
		
		
		
		
		
?>