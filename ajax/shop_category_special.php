<?php
session_start();
if(isset($_SESSION['user_type'])  and isset($_SESSION['user_no']) and isset($_SESSION['mail_adress']))
	{
	if($_SESSION['user_type']!=2)
		{
		die("Bu Ýþleme Yetkiniz Bulunmamakta...");
		}
if(!isset($_POST['cat_id']))
{
die("Veri Gönderim Hatasý... Lütfen Sayfayý Yenileyiniz...");
}

//magazanin urunleri listelendiginde ozel olarak altta ayrintili sekilde listelenmesini saglayan sayfa
include("kjasafhfjaghl.php");
$shop_id=$_SESSION['user_no'];
$connect=new connect();
$connect->sql_connect_db();

$yerok_id=mysql_real_escape_string(htmlspecialchars(trim((int)$_POST['cat_id'])));

$new_yerok=new yerok_id_handler();

$new_yerok->get_yerok_shop_id($yerok_id,$shop_id);
$new_yerok->yerok_id_control();
$new_yerok->select_table();
$new_yerok->get_props_to_y_show();
$new_yerok->get_props_value_to_y_show();
$new_yerok->get_y_icon();
$new_yerok->get_y_first_images();
$new_yerok->press_yields_from_list();

	}else
	{
	echo "Lütfen Giriþ Yapýnýz..."; 
	}





?>