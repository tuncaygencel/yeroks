<?php
include("sql.php");
$connect=new connect();
$connect->sql_connect_db();
session_start();
	if(isset($_POST['type']))
		{
		$type=mysql_real_escape_string(htmlspecialchars(trim((int)$_POST['type'])));
		}else
		{
		$type=0;
		}
if(isset($_SESSION['user_no']))
	{
	$user_id=$_SESSION['user_no'];
	if($type==0)
	  {
	$sql="SELECT shopping_fav_list.id,shopping_fav_list.user_id,shopping_fav_list.type,
	shop.s_id,shop.s_name,place.id as p_id,place.name FROM shopping_fav_list 
	LEFT JOIN shop ON shop.s_id=shopping_fav_list.s_id 
	LEFT JOIN place ON place.id=shopping_fav_list.p_id
	WHERE shopping_fav_list.user_id='$user_id' and (shopping_fav_list.type=2 or shopping_fav_list.type=3) ORDER BY shopping_fav_list.add_time DESC";
	$sql=mysql_query($sql) or die(mysql_error());
	if(mysql_num_rows($sql)>0)
		{
			while($res=mysql_fetch_assoc($sql))
				{
					if($res['type']==2){
					echo "<div id=\"r_shop_main\"><div id=\"r_shop_icon\"></div><div id=\"r_shop_text\" onclick=\"window.open('../shop.php?shop_no=".$res['s_id']."');\">".$res['s_name']."</div></div>";
					}else{
					echo "<div id=\"exp_p_main\">
					<div id=\"exp_p_place_icon\"></div>
					<div id=\"exp_p_avm_main\"  onclick=\"window.open('../place.php?place_no=".$res['p_id']."');\" >".$res['name']."</div>
					</div>";
					}
				}
		}else
		{
		echo "Herhangi Bir Favori Bulunamadı...";
		}
	  }elseif($type==1)
	  {
	  $sql="SELECT shopping_fav_list.id,shopping_fav_list.y_name,shopping_fav_list.y_id,shopping_fav_list.y_type_id FROM shopping_fav_list WHERE user_id='$user_id' and type=1 ORDER BY add_time DESC";
		$sql=mysql_query($sql) or die(mysql_error());
		if(mysql_num_rows($sql)>0)
			{
				while($res=mysql_fetch_assoc($sql))
				{
					echo "<div id=\"shopping_y_".$res['id']."\" class=\"shopping_y\"><li>".$res['y_name']."</li><img onclick=\"open_y_del(".$res['id'].")\" src=\"image/waste.png\" height=20 /></div>";
				}
			}else
			{
				echo "<p>Herhangi Bir Ürün Bulunamadı...</p>";
			}
	  }elseif($type==2)
	  {
	   $sql="SELECT shopping_fav_list.id,shopping_fav_list.y_name,shopping_fav_list.y_id,shopping_fav_list.y_type_id,shopping_fav_list.s_id,y_image.img_way,shop.s_name 
	   FROM shopping_fav_list 
	   LEFT JOIN y_image ON shopping_fav_list.y_id=y_image.y_id and shopping_fav_list.y_type_id=y_image.y_type_id and y_image.no=1
	   LEFT JOIN shop ON shopping_fav_list.s_id=shop.s_id
	   WHERE user_id='$user_id' and type=4 ORDER BY add_time DESC";
		$sql=mysql_query($sql) or die(mysql_error());
		if(mysql_num_rows($sql)>0)
			{
				while($res=mysql_fetch_assoc($sql))
				{
				$img='';
				if($res['img_way']==''){ $img='product_icon2.png'; }else{ $img=$res['img_way']; }
				echo "
				<div id=\"show_y_special\" class=\"show_y_special_".$res['y_id']."_".$res['y_type_id']."\">
				<div id=\"show_lb_icon\" onclick=\"show_pr_lb(".$res['y_type_id'].",".$res['y_id'].",".$res['s_id'].")\"></div>
				<div id=\"s_y_image\" style=\"background-image:url('../y_img_small/".$img."')\"></div>
				<div id=\"y_special_area\">
				<div id=\"y_special_name\">".$res['y_name']."</div>
				<div onclick=\"window.open('../shop.php?shop_no=".$res['s_id']."');\" id=\"y_special_shop_name\">".$res['s_name']."</div>
				</div>
				</div>";
				
				}
			}else
			{
				echo "<p>Herhangi Bir Ürün Bulunamadı...</p>";
			}
	  }
	}else
	{
	echo "Lütfen Giriş Yapınız...";
	
	}






?>