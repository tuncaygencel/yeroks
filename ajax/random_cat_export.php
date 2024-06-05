<?php
include("kjasafhfjaghl.php");
$connect=new connect();
$connect->sql_connect_db();

if(!isset($_POST['cat_num']) or !isset($_POST['type']) or !isset($_POST['place']) ){ die("Veri Hatasi!"); }

$cat_number=mysql_real_escape_string(htmlspecialchars(trim($_POST['cat_num'])));
$type=mysql_real_escape_string(htmlspecialchars(trim($_POST['type'])));
$place_id=mysql_real_escape_string(htmlspecialchars(trim($_POST['place'])));
$down_num=0;
$up_num=0;

switch ( $cat_number ) {
	case 0:
		$down_num=0;
		$up_num=260000000;
		break;
    case 1:
		$down_num=0;
		$up_num=260000000;
		break;
	case 2:
        $down_num=2;
		$up_num=19000000;
        break;
    case 3:
        $down_num=20000000;
		$up_num=30000000;
        break;
	case 4:
        $down_num=40000000;
		$up_num=50000000;
        break;
    case 5:
        $down_num=60000000;
		$up_num=80000000;
        break;
	case 6:
        $down_num=90000000;
		$up_num=120000000;
        break;
	case 7:
        $down_num=130000000;
		$up_num=150000000;
        break;
	case 8:
        $down_num=170000000;
		$up_num=190000000;
        break;
	case 9:
        $down_num=200000000;
		$up_num=220000000;
        break;
	case 10:
        $down_num=230000000;
		$up_num=240000000;
        break;
	case 11:
        $down_num=250000000;
		$up_num=260000000;
        break;
	case 30:
        $down_num=10;
		$up_num=10000000;
        break;
	case 31:
        $down_num=13000000;
		$up_num=14000000;
        break;
	case 32:
        $down_num=11000000;
		$up_num=12000000;
        break;
	case 40:
        $down_num=24000000;
		$up_num=25000000;
        break;
	case 41:
        $down_num=22000000;
		$up_num=23000000;
        break;
	case 42:
        $down_num=20000010;
		$up_num=21000000;
        break;
    default:
        die("Veri Hatasi(1)!"); ;
}

$limit=100;
if($type==2 or $type==3){
$limit=20;
}
///type=1 c_place  3 m_place
///type=0 c_index  2 m_index
if($type==0 or $type==2){
$sql="SELECT DISTINCT y_type_id,icon_name,y_name FROM y_name_list WHERE y_type_id>$down_num and y_type_id<$up_num and  is_y=1 and icon_name!=0 GROUP BY y_type_id ORDER BY RAND() LIMIT 0,$limit";
$sql=mysql_query($sql) or die(mysql_error());
while($res=mysql_fetch_assoc($sql))
	{
	echo "<div id=\"yield_show\" onclick=\"change_value('".$res['y_name']."',".$res['y_type_id'].",".$res['icon_name'].",2)\"><img src=\"y_img_small/".$res['icon_name'].".png\" height=40 title=\"".$res['y_name']."\" id=\"e_y_icon\" /><div id=\"yield_show_text\">".$res['y_name']."</div></div>";
	}
}else{
$sql="SELECT shop_y_list.y_type_id, y_name_list.y_name,y_name_list.icon_name FROM shop_y_list LEFT JOIN
  y_name_list ON shop_y_list.y_type_id=y_name_list.y_type_id WHERE   
  shop_y_list.shop_id IN( SELECT shop.s_id FROM shop WHERE shop.place_number=$place_id ) and shop_y_list.y_type_id>$down_num and shop_y_list.y_type_id<$up_num GROUP BY y_name_list.y_type_id ORDER BY RAND() LIMIT 0, $limit ";
$sql=mysql_query($sql) or die(mysql_error());
while($res=mysql_fetch_assoc($sql))
	{
	?>
	<div id="yield_show" onclick="change_value('<?php echo $res['y_name']."',".$res['y_type_id'].",".$res['icon_name'].",2"; ?>)">
	<img src="y_img_small/<?php echo $res['icon_name'].".png";?>" height="40" title="<?php echo $res['y_name']?>" id="e_y_icon">
	<div id="yield_show_text"><?php echo $res['y_name']?></div></div> 
	<?php
	}
}




?>




