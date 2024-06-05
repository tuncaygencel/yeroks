<?php
session_start();
include("../ajax/kjasafhfjaghl.php");
$no=1;
if(isset($_SESSION['user_type'])  and isset($_SESSION['user_no']) and isset($_SESSION['mail_adress']))
	{
	$connect=new connect();
	$connect->sql_connect_db();
	$check_type=new check_type_get_values();	
	$check_type->sent_values($_SESSION['mail_adress'],$_SESSION['user_type'],$_SESSION['user_no']);
	
	if($check_type->check_and_get()=='2')
		{
			
		if(isset($_POST) and $_SERVER['REQUEST_METHOD'] == "POST")
			{
				$json="{\"press\":1,\"explain\":\"\",\"results\":[";
				foreach($_FILES['image']['error'] as $key => $error) 
				{
					if($error == UPLOAD_ERR_OK) 
					{
						$imageway=$_FILES['image']['tmp_name'][$key];
						$imagename=$_FILES['image']['name'][$key];
						$type=$_FILES['image']['type'][$key];
						$size=$_FILES['image']['size'][$key];
						$m_image=new image_handler();
						$m_image->initialize($imageway,$imagename,$type);
						if($m_image->is_valid_type()==false)
						{
							if($no!=1)
								{
								$json=$json.",{\"no\":".$no.",\"problem\":1,\"press\":1,\"explain\":\"-Yalnızca JPEG GIF veya PNG formatında Resim Yükleyebilirsiniz...\"}";
								}else
								{
								$json=$json."{\"no\":".$no.",\"problem\":1,\"press\":1,\"explain\":\"-Yalnızca JPEG GIF veya PNG formatında Resim Yükleyebilirsiniz...\"}";
								}
						}elseif(($size/1024/1024)>3)
						{
								if($no!=1)
								{
								$json=$json.",{\"no\":".$no.",\"problem\":1,\"press\":1,\"explain\":\"-Lütfen 3MB'dan Daha Küçük Resim Yükleyiniz...\"}";
								}else
								{
								$json=$json."{\"no\":".$no.",\"problem\":1,\"press\":1,\"explain\":\"-Lütfen 3MB'dan Daha Küçük Resim Yükleyiniz...\"}";
								}
						}else
						{
							$rand_image_name=$m_image->get_random_image_name("jpg");//We want to save every image in jpg format
							$m_image->import_image();
							$m_image->resize(800,525);
							$m_image->save_image_to("../s_profile_img_big/".$rand_image_name);
							$m_image->resize(250,150);
							$m_image->save_image_to("../s_profile_img_small/".$rand_image_name);
							$m_image->get_image_to_delete($_SESSION['user_no'],$no);
							$m_image->delete_image_in_database($_SESSION['user_no'],$no);							
							$m_image->insert_image_to_database( $_SESSION['user_no'],$rand_image_name,$no);
							if($no!=1)
								{
								$json=$json.",{\"no\":".$no.",\"problem\":0,\"press\":1,\"explain\":\"\",\"image_name\":\"".$rand_image_name."\"}";
								}else
								{
								$json=$json."{\"no\":".$no.",\"problem\":0,\"press\":1,\"explain\":\"\",\"image_name\":\"".$rand_image_name."\"}";
								}
						};
					}else
					{
						if($no!=1)
								{
								$json=$json.",{\"no\":".$no.",\"problem\":0,\"press\":0,\"explain\":\"\",\"image_name\":\"\"}";
								}else
								{
								$json=$json."{\"no\":".$no.",\"problem\":0,\"press\":0,\"explain\":\"\",\"image_name\":\"\"}";
								}
					}
					$no=$no+1;
				};
				$json=$json."]}";
				echo $json;
			}else
			{
			echo "{\"press\":0,\"explain\":\"Bir Sorun Oluştu...\"}";
			}
			
	
	
		}else
		{
		echo "{\"press\":0,\"explain\":\"Lütfen Giriş Yapınız\"}";
		}
	}else
	{
	echo "{\"press\":0,\"explain\":\"Lütfen Giriş Yapınız\"}";
	}




?>