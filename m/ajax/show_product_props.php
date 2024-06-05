<?php
include("sql.php");
$connect=new connect();
$connect->sql_connect_db();

if( !isset($_POST['id']) or !isset($_POST['type']) ){  
die("");
}
$y_id=mysql_real_escape_string(htmlspecialchars(trim((int)$_POST['id'])));
$y_type_id=mysql_real_escape_string(htmlspecialchars(trim((int)$_POST['type'])));


$yerok=new yerok_id_handler();
$yerok->press_yield_from_search($y_id,$y_type_id);





?>