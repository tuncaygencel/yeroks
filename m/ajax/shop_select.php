<?php
include("sql.php");
$connect=new connect();
$connect->sql_connect_db();

if(!isset($_POST['valuenew'])){  
die("");
}

$value=mysql_real_escape_string(htmlspecialchars(trim($_POST['valuenew'])));
if($value==''){ die("");}
$valuenew="<b>".$value."</b>";
$sql="SELECT id, name FROM brand_shop_data WHERE name LIKE '%$value%' LIMIT 0,20";
$sql=mysql_query($sql) or die(mysql_error());
//2 altta kategorısı olan urun mesela utu altta utu masası felan
$count=mysql_num_rows($sql);
if($count==0)
	{
	echo "{\"result\":0}";
	//echo "<ul>Böyle Bir Ürün Bulunmamakta...</ul>";
	}else
	{	
			$i=0;
			echo "{\"result\":1,\"s_names\":[";
			while($res=mysql_fetch_assoc($sql))
			{
			$i=$i+1;
			$yeroks_name=$res['name'];
			$yeroks_name=str_ireplace($value,$valuenew,$yeroks_name);
			
					//echo "<ul onClick=\"change_value('".$res['y_name']."','".$res['y_type_id']."')\" return false;>".$yeroks_name."</ul>";
				echo "{
							\"s_name\":\"".$yeroks_name."\",
							\"s_id\":".$res['id']."
							}";
				
			if($i!=$count)
				{
				echo ",";
				}
			
			}
			echo "]}";
	}












?>
