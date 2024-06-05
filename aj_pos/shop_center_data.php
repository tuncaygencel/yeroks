<?php
session_start();
if(!isset($_SESSION['user_type'])  or !isset($_SESSION['user_no']) or !isset($_SESSION['mail_adress']))
	{
	die("Lütfen Giriş Yapınız...");
	}
if($_SESSION['user_type']!=2)
	{
	die("Bu İşleme Yetkiniz Bulunmamakta...");
	}
include("../ajax/kjasafhfjaghl.php");
$connect=new connect();
$connect->sql_connect_db();
if(isset($_POST["value"]))
	{
	$province_id=mysql_real_escape_string(htmlspecialchars(trim((int)$_POST["value"])));
		if($province_id>0 and $province_id<82)
			{
			$sql="SELECT*FROM place WHERE province='$province_id' ORDER BY name ASC";
			$sql=mysql_query($sql) or die(mysql_error());
			if(mysql_num_rows($sql)!=0)
				{
					echo "<div class=\"shop_type_text\">AVM'nizi Seçin? </div>";
					while($res=mysql_fetch_assoc($sql))
						{
						echo "<div id=\"shop_center\" onclick=\"shop_center_select('".$res['name']."',".$res['id'].")\">".$res['name']."</div>";
						}
				}else
				{
				echo "<div class=\"shop_type_text\">Bu İlde Kayıtlı Bir Alışveriş Merkezi Bulunamadı...</div>";
				}
			}else
			{
			echo "";
			}
	}else
	{
	echo "<div class=\"shop_type_text\">Hata Olustu...</div>";
	}








?>