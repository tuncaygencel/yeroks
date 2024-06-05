<?php
session_start();
if(isset($_SESSION['user_type'])  and isset($_SESSION['user_no']) and isset($_SESSION['mail_adress']))
	{
	if($_SESSION['user_type']!=2)
		{
		die("Bu İşleme Yetkiniz Bulunmamakta...");
		}
	if(!isset($_POST['data']))
		{
		die("Veri Gönderim Hatası...");
		}
	include("kjasafhfjaghl.php");	
	$shop_id=$_SESSION['user_no'];
	$connect=new connect();
	$connect->sql_connect_db();
	
	$c_id=mysql_real_escape_string(htmlspecialchars(trim((int)$_POST['data'])));
	
	$search_data=new search_funtions();
	$search_data->data_get($c_id,0);

	if($search_data->get_property_for_new_yield())
		{
			if(!$search_data->control_max_y_number($shop_id,$c_id))
				{
				die("<br>Bir Ürün Kategorisi İçin En Fazla 99 Adet Ürün Ekleyebilirsiniz.Eğer Bu Kategoriye Yeni Bir Ürün Eklemek İstiyorsanız Bu Kategorideki Herhangi Bir Ürününüzü Siliniz...");
				}
		$search_data->press_name_input();
		$search_data->get_is_add_more();
		$search_data->get_property_value_for_new_yield();
		$search_data->press_property_for_new_yield();
		}else
		{
		die("HATA!");
		}

	}else
	{
	echo "Lütfen Giriş Yapınız..."; 
	}






?>