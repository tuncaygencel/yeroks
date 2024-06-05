<?php
session_start();
if(!isset($_SESSION['user_no']) or !isset($_SESSION['user_full_name']) or !isset($_SESSION['user_type']) or !isset($_SESSION['mail_adress']) )
{
die('{"press":0,"text":"Bir Sorun Oluştu... Lütfen Sayfayı Yenileyiniz..."}');
 }
 include("sql.php");
$connect=new connect();
$connect->sql_connect_db();
$u_id=$_SESSION['user_no'];
$sql="SELECT*FROM user_search_location WHERE user_id=$u_id";
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
			echo "{\"result\":1,\"locs\":[";
			while($res=mysql_fetch_assoc($sql))
			{
			$i=$i+1;
				echo "{
							\"id\":".$res['id'].",
							\"name\":\"".$res['loc_name']."\",
							\"lati\":".$res['latitude'].",
							\"longi\":".$res['longitude']."
							}";
				
			if($i!=$count)
				{
				echo ",";
				}
			
			}
			echo "]}";
	}











?>