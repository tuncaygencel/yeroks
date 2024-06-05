<?php
session_start();
include("../ajax/kjasafhfjaghl.php");
if(isset($_SESSION['user_type'])  and isset($_SESSION['user_no']) and isset($_SESSION['mail_adress']))
	{
	if($_SESSION['user_type']!=2)
	{
	die("{\"result\":2}");
	}

	$shop_id=$_SESSION['user_no'];
	$shop_mail=$_SESSION['mail_adress'];
	$connect=new connect();
	$connect->sql_connect_db();
	
	$sql="UPDATE gps_request SET is_in_process=0 WHERE shop_id=$shop_id and is_in_process=1";
	$sql=mysql_query($sql) or die(mysql_error());
		if($sql)
			{	
				$rem_id=sha1(time().$shop_id);
				$rem_id=substr($rem_id, 0, 200);
				
				
						$title="Yeroks GPS Sistemi";
                                                $mesaj="<div style=\"width:100%;height:auto;padding:20px 0px 5px 0px;margin-bottom:20px;background-color:rgb(60,60,60);text-align:center;\"><img src=\"http://www.yeroks.com/img_files/yeroks_logo.png\" height=50 /></div><div style=\"font-size:24px;font-family:Arial;width:100%;padding:30px 0px 15px 0px; \"><center>Yeroks'dan Merhaba</center></br><center>GPS Sistemine <a href=\"www.yeroks.com/gps_request_index.php?data=".$rem_id."\" >Buraya Tıklayarak</a> Ulasabilirsin...</center></div>";
                                                $mailtanim = "From: Yeroks <info@yeroks.com> \n";
                                                $mailtanim .= "MIME-Version: 1.0 \n";
                                                $mailtanim .= "Content-type: text/html; charset=utf-8 \r\n";
                                                if(mail($shop_mail,$title,stripslashes($mesaj),$mailtanim))
                                                        {
						
								    $sql1="INSERT INTO gps_request(remember_id,shop_id,time,is_in_process) VALUES('$rem_id','$shop_id',now(),1)";
				                                    $sql1=mysql_query($sql1) or die(mysql_error());
                                		     		        if($sql1)
                                               				 {
                                                				echo "{\"result\":1}";
                                               				 }else{
                                                				echo "{\"result\":0}";
                                                			}
                                                        }else
                                                        {
                                                        echo "{\"result\":0}";
                                                        }
			}else{
			echo "{\"result\":0}";
			}
	
	}
?>
