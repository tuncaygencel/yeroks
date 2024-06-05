<?php
session_start();
if(!isset($_SESSION['user_type'])  or !isset($_SESSION['user_no']) or !isset($_SESSION['mail_adress']))
	{
	die("{\"okay\":0,\"exp\":\"Lütfen Giriş Yapınız...\"}");
	}
if($_SESSION['user_type']!=2)
	{
	die("{\"okay\":0,\"exp\":\"Bu İşleme Yetkiniz Bulunmamakta...\"}");
	}


	if(!isset($_POST['input_text']))
		{
		die("{\"okay\":0,\"exp\":\"Sorgulama Gönderim Hatası...Lütfen Sayfayı Yenileyiniz...\"}");
		}	
	include("kjasafhfjaghl.php");
		$connect=new connect();
		$connect->sql_connect_db();
		$input_text=mysql_real_escape_string(htmlspecialchars(trim($_POST['input_text'])));

		if($input_text=='')
			{
			die();
			}
		
		$valuenew="<b>".$input_text."</b>";
		$sql="SELECT*FROM y_name_list WHERE y_name LIKE '%$input_text%' and is_y='1' LIMIT 0,20";
		$sql=mysql_query($sql) or die(mysql_error());
		$count=mysql_num_rows($sql);
		$i=0;
		echo "{\"okay\":1,\"list\":[";
		if($count==0)
			{
				echo "{\"y_name\":\"Böyle Bir Ürün Bulunmamakta...\",y_id\":0}";
			}else
			{
			while($res=mysql_fetch_assoc($sql))
			{
			$i=$i+1;
			$yeroks_name=$res['y_name'];
			$yeroks_name=str_ireplace($input_text,$valuenew,$yeroks_name);
							echo "{
							\"y_name\":\"".$yeroks_name."\",
							\"y_id\":".$res['y_type_id']."
							}";
							if($i!=$count)
							{
							echo ",";
							}
			}
			}
			echo "]}";



		
?>