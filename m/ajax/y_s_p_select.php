<?php
include("sql.php");
$connect=new connect();
$connect->sql_connect_db();
$login=new log_in();
if(!$login->is_login(1)){
die("");
 }
if(!isset($_POST['valuenew'])){  
die("{\"result\":0}");
}
$value=mysql_real_escape_string(htmlspecialchars(trim($_POST['valuenew'])));
if($value==''){ die("");}
$valuenew="<b>".$value."</b>";
$null=" ";
$sql="(SELECT y_type_id as id, y_name as name, '0' as latitude, '0' as longitude, null as address, is_y, icon_name, '1' as r_type FROM y_name_list WHERE y_name LIKE '%$value%' and is_show='1' and is_y='1' ORDER BY
  CASE
    WHEN name LIKE '$value%' THEN 1
    WHEN name LIKE '%$value' THEN 3
    ELSE 2
  END LIMIT 0,10 )
		UNION
		(SELECT id, name, '0', '0', null, '0', '0',  '2' as r_type FROM brand_shop_data WHERE name LIKE '%$value%' LIMIT 0,10 )
		UNION
		(SELECT id, name,latitude,longitude,address, '0', '0',  '3' as r_type FROM place WHERE name LIKE '%$value%' LIMIT 0,10 ) ";

$sql=mysql_query($sql) or die(mysql_error());
//2 altta kategorýsý olan urun mesela utu altta utu masasý felan
$count=mysql_num_rows($sql);
if($count==0)
	{
	echo "{\"result\":0}";
	//echo "<ul>Böyle Bir Ürün Bulunmamakta...</ul>";
	}else
	{
			$i=0;
			echo "{\"result\":1,\"results\":[";
			while($res=mysql_fetch_assoc($sql))
			{
			$i=$i+1;
			$yeroks_name=$res['name'];
			$yeroks_name=str_ireplace($value,$valuenew,$yeroks_name);
				echo "{
							\"id\":".$res['id'].",
							\"name\":\"".$yeroks_name."\",
							\"latitude\":".$res['latitude'].",
							\"longitude\":".$res['longitude'].",
							\"address\":\"".$res['address']."\",
							\"is_y\":".$res['is_y'].",
							\"icon\":".$res['icon_name'].",
							\"r_type\":".$res['r_type']."
							}";
			if($i!=$count)
				{
				echo ",";
				}
			}
			echo "]}";
	}

?>