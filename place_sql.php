<?php

include("ajax/kjasafhfjaghl.php");
$connect=new connect();
$connect->sql_connect_db();
/*
function change($text)
				{
				$text = trim($text);
				$search = array("Ç","ç","Ğ","ğ","ı","İ","Ö","ö","Ş","ş","Ü","ü"," ","’","'","&",".","(",")","/");
				$replace = array("C","c","G","g","i","I","O","o","S","s","U","u","-","-","-","-","","","","-");
				return str_replace($search,$replace,$text);
				}


$text_name="";
*/
$sql="SELECT text_id FROM place";
$sql=mysql_query($sql) or die(mysql_error());
while($res=mysql_fetch_assoc($sql))
	{
			echo			"
							<url>
							<loc>https://www.yeroks.com/".$res['text_id']."</loc>
							<changefreq>weekly</changefreq>
							</url>";
	}





?>