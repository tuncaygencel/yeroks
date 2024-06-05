<?php
//magazanin urunleri listelendiginde ozel olarak altta ayrintili sekilde listelenmesini saglayan sayfa
if(!isset($_POST['cat_id']) and !isset($_POST['s_data']))
	{
	die("Veri Göderim Hatası...");
	}

include("kjasafhfjaghl.php");
$connect=new connect();
$connect->sql_connect_db();

$shop_id=mysql_real_escape_string(htmlspecialchars(trim((int)$_POST['s_data'])));
$yerok_id=mysql_real_escape_string(htmlspecialchars(trim((int)$_POST['cat_id'])));


$new_yerok=new yerok_id_handler();

$new_yerok->get_yerok_shop_id($yerok_id,$shop_id);
$new_yerok->yerok_id_control();
$new_yerok->select_table();
$new_yerok->get_props_to_y_show();
$new_yerok->get_props_value_to_y_show();
$new_yerok->get_y_icon();
$new_yerok->get_y_first_images();
$new_yerok->press_yields_for_web();

?>