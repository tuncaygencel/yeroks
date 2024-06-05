<?php
include("sql.php");
$connect=new connect();
$connect->sql_connect_db();

if(!isset($_POST['nelat']) or !isset($_POST['nelng']) or !isset($_POST['swlat']) or !isset($_POST['swlng']) ){ die("Veri Hatasi!"); }

$nelat=mysql_real_escape_string(htmlspecialchars(trim($_POST['nelat'])));
$nelng=mysql_real_escape_string(htmlspecialchars(trim($_POST['nelng'])));
$swlat=mysql_real_escape_string(htmlspecialchars(trim($_POST['swlat'])));
$swlng=mysql_real_escape_string(htmlspecialchars(trim($_POST['swlng'])));
$nelat=round($nelat,6); 
$nelng=round($nelng,6); 
$swlat=round($swlat,6); 
$swlng=round($swlng,6);


$sql="(SELECT id as id, name as name, latitude as latitude, longitude as longitude, '1' as type FROM place WHERE  latitude < '$nelat' AND latitude > '$swlat'  AND longitude > '$swlng' AND longitude < '$nelng' ORDER BY RAND()  LIMIT 10)
	  UNION
	  (SELECT s_id as id, s_name as name, latitude as latitude, longitude as longitude, '0' as type  FROM shop WHERE  latitude < '$nelat' AND latitude > '$swlat'  AND longitude > '$swlng' AND longitude < '$nelng'  AND place_number=0 ORDER BY RAND() LIMIT 30)"; 
$sql=mysql_query($sql) or die(mysql_error());
$count=mysql_num_rows($sql);
	if($count==0)
			{
			echo "{\"result\":0}";
						//echo "cevrede urun bulunamadi...
			}else{
								$i=0;
								echo "{\"result\":1,\"results\":[";
								while($res=mysql_fetch_assoc($sql))
								{
									$i=$i+1;
									echo "{
									\"id\":".$res['id'].",
									\"name\":\"".$res['name']."\",
									\"lati\":".$res['latitude'].",
									\"longi\":".$res['longitude'].",
									\"type\":".$res['type']."
									}";
									if($i!=$count)
									{
									echo ",";
									}
			
								}
							echo "]}";
						}
						
		
						
						