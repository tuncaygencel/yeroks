<?php
session_start();
if(isset($_SESSION['user_type'])  and isset($_SESSION['user_no']) and isset($_SESSION['mail_adress']))
	{
if($_SESSION['user_type']==2){
$shop_id=$_SESSION['user_no'];
include("kjasafhfjaghl.php");
$connect=new connect();
$connect->sql_connect_db();

if(!isset($_POST['y_num'])){ die("{\"error\":1, \"exp\":\"Veri Gonderim Hatasi...\"}"); }
$c_id=mysql_real_escape_string(htmlspecialchars(trim($_POST['y_num'])));
if($c_id==''){ die("{\"error\":1, \"exp\":\"Veri Gonderim Hatasi...\"}");}

///delete images
$table=new yield_order();
$t_name=$table->select_table($c_id);

$sql="SELECT y_image.img_way FROM y_image WHERE y_image.y_type_id='$c_id' and y_image.y_id IN(SELECT $t_name.y_id FROM $t_name WHERE $t_name.s_id=$shop_id and $t_name.y_name_id=$c_id)";
$sql=mysql_query($sql) or die(mysql_error());
			while($res=mysql_fetch_assoc($sql)){
				$way1="../y_img_big/".$res['img_way'];
				if (file_exists($way1)) {unlink($way1);}
				$way2="../y_img_small/".$res['img_way'];
				if (file_exists($way2)) {unlink($way2);}		
			}
$sql1="DELETE FROM y_image WHERE y_image.y_type_id=$c_id and y_image.y_id IN(SELECT $t_name.y_id FROM $t_name WHERE $t_name.s_id=$shop_id and $t_name.y_name_id=$c_id)";
$sql1=mysql_query($sql1) or die(mysql_error());
$sql2="DELETE FROM y_extra_props WHERE y_extra_props.y_type_id=$c_id and y_extra_props.y_id IN(SELECT $t_name.y_id FROM $t_name WHERE $t_name.s_id=$shop_id and $t_name.y_name_id=$c_id)";
$sql2=mysql_query($sql2) or die(mysql_error());
$sql3="DELETE FROM $t_name WHERE s_id=$shop_id and y_name_id=$c_id";
$sql3=mysql_query($sql3) or die(mysql_error());
$sql4="DELETE FROM shop_y_list WHERE shop_id=$shop_id and y_type_id=$c_id";
$sql4=mysql_query($sql4) or die(mysql_error());
	if($sql1 and $sql2 and $sql3 and $sql4){
	echo "{\"error\":0, \"exp\":\"Reyon Tamamıyla Kaldırıldı....\"}";
	}else{
	echo "{\"error\":1, \"exp\":\"Reyon Kaldırılırken Bir Sorun Oluştu....\"}";
	}
	
}
}
?>