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
$sql="SELECT y_type_id, y_name, is_y, icon_name FROM y_name_list WHERE y_name LIKE '%$value%' and is_show='1' and is_y!=0 LIMIT 0,20";
$sql=mysql_query($sql) or die(mysql_error());
//2 altta kategorısı olan urun mesela utu altta utu masası felan
$count=mysql_num_rows($sql);
if($count==0)
	{
	echo "{\"result\":0}";
	}else
	{		
			$i=0;
			echo "{\"result\":1,\"y_names\":[";
			while($res=mysql_fetch_assoc($sql))
			{
			$i=$i+1;
			$yeroks_name=$res['y_name'];
			$yeroks_name=str_ireplace($value,$valuenew,$yeroks_name);
			if($res['is_y']==1 or $res['is_y']==2 )
				{
				echo "{
							\"y_name\":\"".$yeroks_name."\",
							\"y_id\":".$res['y_type_id'].",
							\"icon\":".$res['icon_name'].",
							\"type\":1
							}";
				}else
				{
				echo "{
							\"y_name\":\"".$yeroks_name."\",
							\"y_id\":".$res['y_type_id'].",
							\"icon\":".$res['icon_name'].",
							\"type\":0
							}";
				}
			if($i!=$count)
				{
				echo ",";
				}
			
			}
			echo "]}";
	}












?>
