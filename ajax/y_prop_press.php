<?php

include("kjasafhfjaghl.php");
$connect=new connect();
$connect->sql_connect_db();
if(!isset($_POST['y_data'])){ die("");}

$y_id=mysql_real_escape_string(htmlspecialchars(trim((int)$_POST['y_data'])));

$search_data=new search_funtions();
$search_data->data_get($y_id);

	if($search_data->get_property())
		{
		$search_data->get_property_value();
		$search_data->press_property();
		}



?>