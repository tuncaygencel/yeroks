<?php
include("kjasafhfjaghl.php");
session_start();
if(isset($_SESSION['user_type'])  and isset($_SESSION['user_no']) and isset($_SESSION['mail_adress']))
	{
	if($_SESSION['user_type']!=2)
		{
		die("Bu İşleme Yetkiniz Bulunmamakta...");
		}
	if(isset($_POST['info0']) and isset($_POST['info1']) and isset($_POST['action']) and isset($_POST['type']))
		{
		$connect=new connect();
		$connect->sql_connect_db(); 
		$info0=mysql_real_escape_string(htmlspecialchars(trim((int)$_POST['info0'])));
		$info1=mysql_real_escape_string(htmlspecialchars(trim((int)$_POST['info1'])));
		$action=mysql_real_escape_string(htmlspecialchars(trim((int)$_POST['action'])));
		$type=mysql_real_escape_string(htmlspecialchars(trim((int)$_POST['type'])));
		$shop_id=$_SESSION['user_no'];
		//info0 yields category id, info1 is order of cat or id of yield, action -1 and +1 up or down, type 0 means category type 1 means yield
			if(is_numeric($info0) and is_numeric($info1) and ($action==-1 or $action==1) and ($type==0 or $type==1))
				{
				$yield_order=new yield_order();
					 switch(true)
						{
						case ($action==1 and $type == 0):
						$yield_order->set_variables($shop_id,0,0,$info1);
						$yield_order->set_to_up_category();
						break;
						case ($action==-1 and $type == 0):
						$yield_order->set_variables($shop_id,0,0,$info1);
						$yield_order->set_to_down_category();
						break;
						case ($action==1 and $type == 1):$connect=new connect();
						$yield_order->set_variables($shop_id,$info0,$info1,0);
						$yield_order->set_to_up_yield();
						break;
						case ($action==-1 and $type == 1):
						$yield_order->set_variables($shop_id,$info0,$info1,0);
						$yield_order->set_to_down_yield();
						break;
						default: echo 'İstenen İşlem Tespit Edilemedi...';
						break;
						}
				}else
				{
				echo "Veri Eşleşme Hatası...";
				}	
		}else
		{
		echo "Veri Gönderim Hatası...";
		}
	}else
	{
	echo "Lütfen Giriş Yapınız..."; 
	}


?>