<?php
	function select_table($yerok_id)
				{
				
					switch ($yerok_id) 
							{
							case  ($yerok_id  > '1' and $yerok_id < '19000000' ):
								return 'y1';
								break;
							case  ($yerok_id  >'20000000' and $yerok_id < '30000000' ):
								return 'y2';
								break;
							case  ($yerok_id  > '40000000' and $yerok_id < '50000000' ):
								return 'y3';
								break;
							case  ($yerok_id  > '60000000' and $yerok_id < '80000000' ):
								return 'y4';
								break;
							case  ($yerok_id  > '90000000' and $yerok_id < '120000000' ):
								return 'y5';
								break;
							case  ($yerok_id  > '130000000' and $yerok_id < '150000000' ):
								return 'y6';
								break;
							case  ($yerok_id  > '170000000' and $yerok_id < '190000000' ):
								return 'y7';
								break;
							case  ($yerok_id  > '200000000' and $yerok_id < '220000000' ):
								return 'y8';
								break;
							case  ($yerok_id  > '230000000' and $yerok_id < '240000000' ):
								return 'y9';
								break;
							case  ($yerok_id  > '250000000' and $yerok_id < '260000000' ):
								return 'y10';
								break;
							default:
								die('HATA!');
								break;
							}
				}


session_start();
if(isset($_SESSION['user_type'])  and isset($_SESSION['user_no']) and isset($_SESSION['mail_adress']))
	{
		if($_SESSION['user_no']==1 and $_SESSION['user_type']==1 )
			{
			if(isset($_POST['y_data']))
				{
				include("kjasafhfjaghl.php");
				$connect=new connect();
				$connect->sql_connect_db();
				$update=0;
				$y_type_id=mysql_escape_string(htmlspecialchars(trim((int)$_POST['y_data'])));
				$sql="SELECT*FROM y_name_list WHERE y_type_id='$y_type_id'";
				$sql=mysql_query($sql);
				if(mysql_num_rows($sql)==1)
					{
					 $res=mysql_fetch_assoc($sql);
					 if($res['is_in_season']==0)
						{
						$update=1;
						}
					
					 if($res['is_in_season']==1)
						{
						$update=0;
						}
						$sql="UPDATE y_name_list SET is_in_season='$update' WHERE y_type_id='$y_type_id'";
						$sql=mysql_query($sql);
						$sql="SELECT*FROM y_name_list WHERE y_type_id='$y_type_id'";
						$sql=mysql_query($sql);
						$res=mysql_fetch_assoc($sql);
						if($res['is_in_season']==1)
							{
							$y_type_id=$res['y_type_id'];
							$table=select_table($y_type_id);
							$sql1="DELETE FROM $table WHERE y_name_id=$y_type_id and s_id IN(SELECT s_id FROM shop WHERE shop_number!=0)";
							$sql1=mysql_query($sql1) or die(mysql_error());
							$sql2="INSERT INTO $table(`y_name`,`s_id`,`place_id`,`y_name_id`,`latitude`,`longitude`,`props`,`orders`)   SELECT `brand_shops_yield_datas`.`y_name`,`shop`.`s_id`,`shop`.`place_number`,`brand_shops_yield_datas`.`y_name_id`,`shop`.`latitude`,`shop`.`longitude`,`brand_shops_yield_datas`.`props`,`brand_shops_yield_datas`.`order` FROM `shop` INNER JOIN `brand_shops_yield_datas` ON `shop`.`shop_number`=`brand_shops_yield_datas`.`brand_shop_id` WHERE `brand_shops_yield_datas`.`y_name_id`=$y_type_id AND `brand_shops_yield_datas`.`in_shop`=1";
							$sql2=mysql_query($sql2) or die(mysql_error());
							$sql3="DELETE FROM shop_y_list  WHERE y_type_id=$y_type_id";
							$sql3=mysql_query($sql3) or die(mysql_error());
							$sql4="INSERT INTO shop_y_list(`shop_id`,`y_type_id`,`y_count`,`orders`)   SELECT `shop`.`s_id`,`brand_shop_yield_list`.`y_type_id`,`brand_shop_yield_list`.`y_count`,`brand_shop_yield_list`.`order` FROM `shop` INNER JOIN `brand_shop_yield_list` ON `shop`.`shop_number`=`brand_shop_yield_list`.`brand_shop_id` WHERE `brand_shop_yield_list`.`in_shop`=1 and `brand_shop_yield_list`.`y_type_id`=$y_type_id";
							$sql4=mysql_query($sql4) or die(mysql_error());
							
								if($sql1 and $sql2 and $sql3 and $sql4){
									echo "{\"problem\":0,\"y_type_id\":".$res['y_type_id'].",\"exp\":\"!\",\"is_season\":1}";
								}else{
								echo "{\"problem\":1,\"y_type_id\":".$res['y_type_id'].",\"exp\":\"Urun ekleme tablo islem hatasi\",\"is_season\":1}";
								}
							
							}elseif($res['is_in_season']==0)
							{
							$table=select_table($y_type_id);
							$sql1="DELETE FROM $table WHERE y_name_id=$y_type_id and  s_id IN(SELECT s_id FROM shop WHERE shop_number!=0)";
							$sql1=mysql_query($sql1) or die(mysql_error());
							$sql3="DELETE FROM shop_y_list  WHERE y_type_id=$y_type_id";
							$sql3=mysql_query($sql3) or die(mysql_error());
							
								if( $sql1 and $sql3 ){
									echo "{\"problem\":0,\"y_type_id\":".$res['y_type_id'].",\"exp\":\"!\",\"is_season\":0}";
								}else{
									echo "{\"problem\":1,\"y_type_id\":".$res['y_type_id'].",\"exp\":\"Urun ekleme tablo islem hatasi\",\"is_season\":1}";
								}
							
							}else{
							echo "{\"problem\":1,\"exp\":\"is_in_season degiskeni 1 veya 0 degil!!!\"}";
							}
					
					}else{
					echo "{\"problem\":1,\"exp\":\"Gonderilen idye ait bir urun kategorisi bulunamadi!\"}";
					}
				}else{
				echo "{\"problem\":1,\"exp\":\"Veri Gonderim Hatasi!\"}";
				}
			}else{
				echo "HATA! Sayfa Bulunamadı...!";
			}
	}else{
		echo "HATA! Sayfa Bulunamadı...!";
	}
?>