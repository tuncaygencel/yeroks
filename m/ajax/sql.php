<?php
class connect
	{
	
			private $host_name="";
			private $user="";
			private $passwd="";
			private $database="";
			private $c_ip;
			private $c_id;
			private $user_id;
			private $u_type;
			private $time_now;
			private $c_scanner;
			private $bd; 
			
		public	function sql_connect_db()
			{
			$this->bd=mysql_connect($this->host_name,$this->user,$this->passwd) or die("Bir hata oluştu!");
			mysql_select_db($this->database,$this->bd) or die("Bir hata oluştu!");  
			mysql_query("SET NAMES utf8");
			mysql_query("SET CHARACTER SET utf8");
			mysql_query("SET COLLATION_CONNECTION='utf8_general_ci'");  
			return $this->bd;
			}
		public function sql_connect_close()
			{
			mysql_close($this->bd);
			}
			
			private $_u_cook_mail;
			private $_u_cook_data;
			
		////////start connect_data sets
		public function getip()
			{ 
				if(getenv("HTTP_CLIENT_IP"))
				{
				$ip = getenv("HTTP_CLIENT_IP"); 
				} elseif(getenv("HTTP_X_FORWARDED_FOR")) 
				{ 
					$ip = getenv("HTTP_X_FORWARDED_FOR"); 
					if (strstr($ip, ',')) 
						{ 
					$tmp = explode (',', $ip); 
					$ip = trim($tmp[0]); 
						} 
				} else 
				{
				$ip = getenv("REMOTE_ADDR"); 
				}
				$this->c_ip=$ip; 
			}
		
		public function set_user_id()
			{
			if(isset($_SESSION['user_no']))
						{
						$this->user_id=$_SESSION['user_no'];
						}else
						{
						$this->user_id=0;
						}
			}
		public function set_user_type()
			{
			if(isset($_SESSION['user_no']))
						{
						$this->u_type=$_SESSION['user_type'];
						}else
						{
						$this->u_type=0;
						}
			}
		public function set_connect_data($page_number)
			{
				if(isset($_SESSION['current_page']) and $_SESSION['current_page']!=$page_number and $_SESSION['page_in_count']<50)
					{
					$this->time_now=date("Y-m-d H:i:s");
					$this->c_scanner=$_SERVER [ "HTTP_USER_AGENT" ];
					if($this->c_id==0)
						{
						$sql="INSERT INTO connect_type(connect_user_id,connect_type,connect_s_or_u,connect_time,connect_ip,connected_scanner) VALUES('$this->user_id','$page_number:$this->time_now','$this->u_type','$this->time_now','$this->c_ip','$this->c_scanner')"; 
						$sql=mysql_query($sql) or die(mysql_error());
						$_SESSION['connect_id']=mysql_insert_id();
						$_SESSION['page_in_count']=$_SESSION['page_in_count']+1;
						}elseif($this->c_id>0)
						{
						$sql="UPDATE connect_type SET connect_user_id='$this->user_id' , connect_s_or_u='$this->u_type',connect_type=Concat(connect_type,',$page_number:$this->time_now') WHERE connect_id='$this->c_id'";
						$sql=mysql_query($sql) or die(mysql_error());
						$_SESSION['page_in_count']=$_SESSION['page_in_count']+1;
						}
					
					}
			}
			//user_ıd ıp devıce
			public function set_current_ses($page_number)
			{
				$_SESSION['current_page']=$page_number;
			}
			
			public function session_set()
			{
			if(!isset($_SESSION['current_page']))
				{
					$_SESSION['current_page']=0;
				}
			if(!isset($_SESSION['page_in_count']))
				{
					$_SESSION['page_in_count']=0;
				}
			if(isset($_SESSION['user_no']))
				{
					$_SESSION['current_page']=10;
				}
			}
			
			public function connect_set_packet($page_number)
			{
			self::session_set();
			self::getip();
			self::set_session_id();
			self::set_user_id();
			self::set_user_type();
			self::set_connect_data($page_number);
			self::set_current_ses($page_number);
			}
			
			public function get_scanner_data()
			{
			$this->c_scanner=$_SERVER [ "HTTP_USER_AGENT" ];
			}
			
			public function set_session_id()
			{
				$this->c_id=session_id();
			}
			
			public function enter_active($page_id,$active_id,$y_type_id,$shop_id,$place_id)
			{
			self::set_session_id();
			self::set_user_id();
			self::getip();
			self::get_scanner_data();
			$sql="INSERT INTO connect_user_active(session_id,user_id,active_id,y_type_id,shop_id,place_id,page_id,time,ip,device_data) 
			VALUES('$this->c_id','$this->user_id','$active_id','$y_type_id','$shop_id','$place_id','$page_id',NOW(),'$this->c_ip','$this->c_scanner')";
			$sql=mysql_query($sql) or die(mysql_error());
			}
			/////////finish connect_datas sets	
			///search data enter functions start
			
			
			public function enter_search_data($place_id,$latitude,$longitude,$search_main_id,$search_type,$page_id,$search_source,$search_page)
			{
			self::set_session_id();
			self::set_user_id();
			self::getip();
			self::get_scanner_data();
			$sql="INSERT INTO connect_user_search_data(session_id,user_id,place_id,latitude,longitude,search_main_id,search_type,page_id,search_source,search_page,time,ip,device_data) 
			VALUES('$this->c_id','$this->user_id','$place_id','$latitude','$longitude','$search_main_id','$search_type','$page_id','$search_source','$search_page',NOW(),'$this->c_ip','$this->c_scanner')";
			$sql=mysql_query($sql) or die(mysql_error());
			}
			//////search data enter functions finish
		public function connect_close()
			{
				mysql_close();
			}
	};


	class insert_new_u_db
		{
		  private $u_full_name;
		  private $u_mail;
		  private $u_password;
		
		public function control_u_mail($mail)
			{
				$this -> u_mail = $mail; 
				$sql="SELECT COUNT(*) FROM user WHERE  u_mail='$this->u_mail' ";
				$res=mysql_query($sql);
				$res=mysql_fetch_array($res);
				$count=$res[0];
				return $count;
			}
		
		
		public function u_insert_new_user($fullname,$mail,$password,$type) 
			{
				$this -> u_full_name = $fullname;
				$this -> u_mail = $mail;
				$this -> u_password = sha1($password);
				$sql="INSERT INTO user(u_full_name,u_mail,u_password,join_time,system_type) VALUES('$this->u_full_name ','$this->u_mail ','$this->u_password',NOW(),'$type')"; 
				$res=mysql_query($sql);
				if($res)
				{
					echo "{\"result\":1,\"explain\":\"Kaydınız başarıyla gerçekleştirildi...\"}";
				}else
				{
					echo "{\"result\":0,\"explain\":\"Bir hata oluştu...\"}";
				}
			
			}
		};

class log_in
		{
			private $mail_adress;
			private $password;
			private $user_type;
			private $error_type;
			private $remember;
			private $login_status;
			private $name;
			private $u_or_s_id;
			private $_u_cook_mail;
			private $_u_cook_data;
			
			public	function is_login()
			{
				if(isset($_SESSION['user_type'])  and isset($_SESSION['user_no']) and isset($_SESSION['mail_adress']) and isset($_SESSION['user_full_name']))
					{
					return true;
					}elseif(isset($_COOKIE['mail']) && isset($_COOKIE['info']))
					{
						$this->_u_cook_mail= $_COOKIE['mail'];
						$this->_u_cook_data = $_COOKIE['info'];
						
						$conn_scanner_md5=md5($_SERVER [ "HTTP_USER_AGENT" ]);
						$last_ip=$_SERVER['REMOTE_ADDR'];
						
						$remember_data=$conn_scanner_md5.$this->_u_cook_data.$last_ip;
						$sql="SELECT*FROM user WHERE u_mail='$this->_u_cook_mail' AND u_cook_data='$this->_u_cook_data' ";
						$ress=mysql_query($sql) or die(mysql_error());
						$exists=mysql_num_rows($ress);
						if($exists==1)
							{
							$res=mysql_fetch_assoc($ress);
							$_SESSION['user_no']=$res['u_id'];
							$_SESSION['user_full_name']=$res['u_full_name'];
							$_SESSION['user_type']=1;
							$_SESSION['mail_adress']=$res['u_mail'];
							return true;
							};
					}else{
					return false;
					}
			}
			
			public function login_data_set($mail,$pass,$user_type)
				{
				 $this -> mail_adress = $mail;
				 $this -> password = $pass;
				 $this -> user_type = $user_type;
				}

			private function is_login_data_exist()
				{

				if( $this->mail_adress == "" )
					{
						$this -> error_type = '3';
						return	'3';
					}elseif($this->password=="")
					{
						$this -> error_type = '4';
						return '4';
					}else
					{
						return '5';
					}
				}
		
			public function error_type_press()
				{
					switch($this->error_type)
					{
						case '3':
						echo "E-Postanızı Girmediniz...";
							;
							break;
						case '4':
						echo "Şifrenizi girmediniz...";
							;
							break;
						case '6':
						echo "Bu E-posta adresine ait bir hesap bulunamadı.";
						break;
						case '7':
						echo "Yanlış şifre girdiniz...";
						break;
					}
				}
			
			public function error_type_return()
				{
					switch($this->error_type)
					{
						case '3':
						return "E-Postanızı Girmediniz...";
							;
							break;
						case '4':
						return "Şifrenizi girmediniz...";
							;
							break;
						case '6':
						return "Bu E-posta adresine ait bir hesap bulunamadı.";
						break;
						case '7':
						return "Yanlış şifre girdiniz...";
						break;
					}
				}
			private function control_data_login()
				{
					$this->password = sha1($this->password);
					
					if($this->user_type=='1')
						{
							$sql="SELECT u_id,u_full_name,u_mail,u_password FROM user WHERE u_mail='$this->mail_adress'";
						}else
						{
							$sql="SELECT s_id,s_name,s_mail,s_password,is_remember FROM shop WHERE s_mail='$this->mail_adress' ";
						}
					
					$sql=mysql_query($sql) or die(mysql_error());
				if(mysql_num_rows($sql)>0)
					{
					$res=mysql_fetch_assoc($sql);
					if($this->user_type=='1')
						{
						$pass=$res['u_password'];
						}else
						{
						$pass=$res['s_password'];
						}
					
					
					if($pass==$this->password)
						{
						
							if($this->user_type==1)
								{
								$this->u_or_s_id=$res['u_id'];
								$this->name=$res['u_full_name'];
								}else
								{
								$this->u_or_s_id=$res['s_id'];
								$this->name=$res['s_name'];
								}
						$this->login_status=1;
						
						}else
						{
						$this->login_status=0;
						$this->error_type=7;
						}
					
					}else
					{
						$this->login_status=0;
						$this->error_type=6;
					}
				}
			private function create_session()
				{
							$_SESSION['user_no']=$this->u_or_s_id;
							$_SESSION['user_type']= $this -> user_type;
							$_SESSION['user_full_name']=$this->name;
							$_SESSION['mail_adress']=$this->mail_adress;
							return true;
				}
		
		public function set_remember_data($remember)
				{
						$this -> remember = $remember;
						
						if(isset($_SESSION['user_no']) and $this->remember=='0' or $this->remember=='1')
								{
								$user_no=$_SESSION['user_no'];
								if($this->remember=='1')
									{
									$conn_scanner_md5=md5($_SERVER [ "HTTP_USER_AGENT" ]);
									$last_ip=$_SERVER['REMOTE_ADDR'];
									$number1= sha1(rand(1000,32767));
									$info1=sha1($this->mail_adress);
									$info_cook_data=$number1.$info1;
									$database_cook_data=$conn_scanner_md5.$info_cook_data.$last_ip;
										if( Setcookie("mail", $this->mail_adress, time()+60*60*24*100, "/") and Setcookie("info", $database_cook_data, time()+60*60*24*100, "/") )
											{
											$sql="UPDATE user SET u_cook_data='$database_cook_data' , is_remember='1' WHERE u_id='$user_no'";
											$res=mysql_query($sql);	
											}
									}elseif($this->remember=='0')
									{
											$sql=$sql="UPDATE user SET is_remember='0' WHERE u_id='$user_no'";
											$res=mysql_query($sql);	
									}
								}
				}

		public function login_func($mail,$pass,$user_type)
				{
					self::login_data_set($mail,$pass,$user_type);
					if(self::is_login_data_exist()==5)
						{
						self::control_data_login();
						if(	$this->login_status==1)
							{
								if(self::create_session())
									{
									self::set_remember_data(1);
									return true;
									}else
									{
									return false;
									}
							}else
							{
							return false;
							}
						}else
						{
						return false;
						}
				}

				
		};
		
		
		class check_type_get_values
			{
			
				 public $mail;
				 public $type;
				 public $user_id;
				 public $name;
				 public $latitude;
				 public $longitude;
				 
			function sent_values($mail,$type,$user_id)
						{
						$this->mail=$mail;
						$this->type=$type;
						$this->user_id=$user_id;
						}
			
			function check_and_get()
				{
					if($this->type=='1')
						{
						$sql="SELECT u_id, u_full_name, u_mail FROM user WHERE u_id='$this->user_id=$this->user_id' and u_mail='$this->mail'";
						$sql=mysql_query($sql) or die(mysql_error());
							if(mysql_num_rows($sql)=='1')
								{
								$res=mysql_fetch_assoc($sql);
								$this->name=$res['u_full_name'];
								return '1';
								}else
								{
								return false;
								}

						}elseif($this->type=='2')
						{
						
							$sql="SELECT s_id, s_name, s_mail,latitude,longitude FROM shop WHERE s_id='$this->user_id' and s_mail='$this->mail'";
							$sql=mysql_query($sql) or die(mysql_error());
							if(mysql_num_rows($sql)=='1')
								{
								$res=mysql_fetch_assoc($sql);
								$this->latitude=$res['latitude'];
								$this->longitude=$res['longitude'];
								$this->name=$res['s_name'];
								return '2';
								}else
								{
								return false;
								}
						}else
						{
						session_destroy();
						echo '<meta http-equiv="refresh" content=0;URL=error_page/error.php>';
						}
				}
			};
		
		
		class check_functions
				{
				 private $mail;
				 private $pass;
				 private $sha1_pass;
				 private $user_id;
				 
					function sent_mail($mail)
						{
						$this->mail=$mail;
						}
					function sent_pass($pass)
						{
						$this->pass=$pass;
						}
					function sent_sha1_pass($pass)
						{
						$this->sha1_pass=sha1($pass);
						}
					function sent_user_id($id)
						{
						$this->user_id=$id;
						}
				
					function check_user_mail_pass()
						{
							$sql="SELECT u_mail,u_password FROM user WHERE u_mail='$this->mail'";
							$sql=mysql_query($sql) or die(mysql_error());
							$res=mysql_fetch_assoc($sql);
							if($res['u_password']==$this->sha1_pass)
								{
								return true;
								}else
								{
								return false; 
								}
						}
					function check_user_id_pass()
						{
							$sql="SELECT u_id,u_password FROM user WHERE u_id='$this->user_id'";
							$sql=mysql_query($sql) or die(mysql_error());
							$res=mysql_fetch_assoc($sql);
							if($res['u_password']==$this->sha1_pass)
								{
								return true;
								}else
								{
								return false; 
								}
						}
				
				};
		
		class change_user_info
				{
				private $user_id;
				private $user_mail;
				private $pass;
				private $user_sha1_pass;
				private $user_name;
				
				function set_user_id($id)
					{
					$this->user_id=$id;
					}
				function set_user_mail($mail)
					{
					$this->user_mail=$mail;
					}
				function set_pass($pass)
					{
					$this->pass=$pass;
					}
				function set_sha1_pass()
					{
					$this->user_sha1_pass=sha1($this->pass);
					}
				function set_user_name($name)
					{
					$this->user_name=$name;
					}
				
				
				function change_name()
					{
						if(strlen($this->user_name)< 4)
							{
							echo "<b>-</b>   Lütfen İsminizi 3 Karakterden Daha Fazla Olacak Şekilde Giriniz...</br>";
							}else
							{
							$sql="UPDATE user  SET u_full_name='$this->user_name'  WHERE u_id='$this->user_id'";
								$res=mysql_query($sql) or die(mysql_error());
								if($res)
									{
										echo "<b>-</b>   İsim Bilginiz Değiştirildi...</br>";
									}
							
							}
					}
				
					function change_pass()
					{
						if(strlen($this->pass)< 5)
							{
							echo "<b>-</b>   Lütfen Şifrenizi 5 Karakterden Fazla Olacak Şekilde Giriniz...</br>";
							}else
							{
							$sql="UPDATE user  SET u_password='$this->user_sha1_pass'  WHERE u_id='$this->user_id'";
								$res=mysql_query($sql) or die(mysql_error());
								if($res)
									{
										echo "<b>-</b>   Şifreniz Değiştirildi...</br>";
									}
							
							}
					}
				
					function change_mail()
					{
						if(!self::check_mail_real())
							{
							echo "<b>-</b>   Lütfen Geçerli Bir E-Posta Giriniz...";
							}elseif(!self::check_mail_in_database())
							{
							echo "<b>-</b>   Girdiğiniz E-Postayı Kullanamazsınız.Lütfen Başka Bir E-Posta ile Deneyiniz...";
							}else
							{
							self::change_mail_sql();
							}
					
					
					}
				
					function change_mail_sql()
					{
					$sql="UPDATE user  SET u_mail='$this->user_mail'  WHERE u_id='$this->user_id'";
					$res=mysql_query($sql) or die(mysql_error());
								if($res)
									{
										echo "<b>-</b>   E-Postanız Değiştirildi...</br>";
									}
					}
					function check_mail_real()
					{
						if (!preg_match('#^[a-z0-9.!\#$%&\'*+-/=?^_`{|}~]+@([0-9.]+|([^\s\'"<>]+\.+[a-z]{2,6}))$#si',$this->user_mail))
							{ 
								return false;
							}else
							{
								return true;
							}
					}
					
					function check_mail_in_database()
					{
					$sql="SELECT u_mail,u_id FROM user WHERE u_mail='$this->user_mail' and u_id!=$this->user_id";
					$sql=mysql_query($sql) or die(mysql_error());
						if(mysql_num_rows($sql)>0)
							{
							return false;
							}else
							{
							return true;
							}
					
					}
				
				};
		
		
		
		
		
		class search_funtions
				{
					private $yield_type_id;
					private $property;
					private $property_value;
					private $is_add_more=Array();
					
					public function data_get($yield)
					{
					$this->yield_type_id=$yield;
					}
					
					public function get_property()
					{
					$sql="SELECT y_prop_q.p_id, y_prop_q.p_q_to_user, y_prop_value_contact.y_type_id,y_prop_value_contact.y_p_id
					FROM y_prop_value_contact 
						INNER JOIN y_prop_q ON y_prop_q.p_id=y_prop_value_contact.y_p_id
						WHERE y_prop_value_contact.y_type_id=$this->yield_type_id and ask_to_people='1' GROUP BY y_prop_value_contact.y_p_id";
						
					$this->property=mysql_query($sql) or die(mysql_error());
						if(mysql_num_rows($this->property)>0)
							{
							return true;
							}else
							{
							return false;
							}
					}
					
					public function get_property_value()
					{
					$sql="SELECT y_prop_value_contact.y_type_id,y_prop_value_contact.y_p_id,y_prop_value_contact.y_v_id,y_prop_value.v_id,y_prop_value.v_name_user
						FROM y_prop_value_contact
						INNER JOIN y_prop_value ON y_prop_value.v_id=y_prop_value_contact.y_v_id
						WHERE y_prop_value_contact.y_type_id=$this->yield_type_id";
						
					$this->property_value=mysql_query($sql) or die(mysql_error());
					}
					
					public function press_property()
					{
					
					$prop_counter=0;
					
					while($res1=mysql_fetch_assoc($this->property))
						{
						
						$y_p_id=$res1['y_p_id'];
						echo "<div id=\"prop\">".$res1['p_q_to_user'].":";
						echo "<select name=\"prop_".$prop_counter."\" id=\"prop_".$prop_counter."\" >";                     
						echo "<option value=\"0\">Seçiniz...</option>";
						while($res2=mysql_fetch_assoc($this->property_value))
							{
								if($y_p_id==$res2['y_p_id'])
								{
								echo "<option value=\"".$res2['v_id']."\">".$res2['v_name_user']."</option>";
								}		
							}
							
							mysql_data_seek($this->property_value,0);
						echo "</select>";
						echo "<input type=\"hidden\" name=\"prop_".$prop_counter."_data\" id=\"prop_".$prop_counter."_data\" value=".$res1['p_id']." />";
						echo "</div>";
						$prop_counter=$prop_counter+1;
						}
						echo "<input type=\"hidden\" name=\"prop_counter\" id=\"prop_counter\" value=".$prop_counter." />";
					}
				
				//////////////////////////////////////////////////////////////////////
				public function get_property_for_new_yield()
					{
					$sql="SELECT y_prop_q.p_id, y_prop_q.p_q_to_shop, y_prop_value_contact.y_type_id,y_prop_value_contact.y_p_id
					FROM y_prop_value_contact 
						INNER JOIN y_prop_q ON y_prop_q.p_id=y_prop_value_contact.y_p_id
						WHERE y_prop_value_contact.y_type_id=$this->yield_type_id and ask_to_shop='1' GROUP BY y_prop_value_contact.y_p_id";
						
					$this->property=mysql_query($sql) or die(mysql_error());
					return true;
					}
					
					public function get_is_add_more()
					{
						//delete for new is_add_more data not to upload datas on old datas
						foreach ($this->is_add_more as $i => $value) {
								unset($this->is_add_more[$i]);
							}
							
					$sql="SELECT*FROM y_add_value_more WHERE y_type_id='$this->yield_type_id'";
					$sql=mysql_query($sql) or die(mysql_error());
						while($res=mysql_fetch_assoc($sql))
							{
								$this->is_add_more[$res['y_p_id']]=$res['is_add_more'];
							}
					
					}
					
					public function get_property_value_for_new_yield()
					{
					$sql="SELECT y_prop_value_contact.y_type_id,y_prop_value_contact.y_p_id,y_prop_value_contact.y_v_id,y_prop_value.v_id,y_prop_value.v_name_shop
						FROM y_prop_value_contact
						INNER JOIN y_prop_value ON y_prop_value.v_id=y_prop_value_contact.y_v_id
						WHERE y_prop_value_contact.y_type_id=$this->yield_type_id";
						
					$this->property_value=mysql_query($sql) or die(mysql_error());
					}
					
					public function press_name_input()
					{
					echo "<div id=\"prop_set_area\">";
					echo "<div id=\"new_y_prop\">Ürün İsmini Giriniz:</div>";
					echo "<input type=\"text\" onkeydown=\"new_yield_typing()\" onkeyup=\"new_yield_typing()\" id=\"new_yield_name_input\" name=\"new_yield_name_input\" />";
					echo "</div>";
					}
					public function press_property_for_new_yield()
					{
					
					$prop_counter=0;
					$s_prop_counter=0;
					while($res1=mysql_fetch_assoc($this->property))
						{
						
						$y_p_id=$res1['y_p_id'];
						echo "<div id=\"prop_set_area\">";
								echo "<div id=\"new_y_prop\">".$res1['p_q_to_shop']."</div>";
						if($this->is_add_more[$y_p_id]==0)
						{
								
								echo "<select class=\"new_y_values\" onchange=\"onchange_option(".$prop_counter.")\" id=\"prop_".$prop_counter."\">";   
								echo "<option class=\"value\"  value=\"0\">Seçiniz</option>";						
								while($res2=mysql_fetch_assoc($this->property_value))
									{
										if($y_p_id==$res2['y_p_id'])
										{
										echo "<option class=\"value\"  value=\"".$res2['v_id']."\">".$res2['v_name_shop']."</option>";
										}		
									}
								echo "</select>";
								echo "<input type=\"hidden\" name=\"prop_".$prop_counter."_data\" id=\"prop_".$prop_counter."_data\" value=".$res1['p_id']." />";
								$prop_counter=$prop_counter+1;
						}else
						{
							$value_counter=0;
							while($res2=mysql_fetch_assoc($this->property_value))
									{
										if($y_p_id==$res2['y_p_id'])
										{
										echo "<div class=\"p_v_more\" onclick=\"onchange_div(".$res1['p_id'].",'".$res2['v_name_shop']."',".$res2['v_id'].")\" id=\"d_".$res1['p_id']."_prop_".$res2['v_id']."\">".$res2['v_name_shop']."<img height=40 src=\"img_files/okay1.png\"/></div>";
										echo "<input type=\"hidden\" name=\"p_".$res1['p_id']."_v_more_".$res2['v_id']."\"  id=\"p_".$res1['p_id']."_v_more_".$res2['v_id']."\" class=\"p_".$res1['p_id']."_v_m_".$value_counter."\" value=0 /> ";
										$value_counter=$value_counter+1;
										}	
									}
									
								echo "<input type=\"hidden\" name=\"s_prop_".$s_prop_counter."_value_counter\" id=\"s_prop_".$s_prop_counter."_value_counter\" value=".$value_counter." />";		
								echo "<input type=\"hidden\" name=\"s_prop_".$s_prop_counter."_data\" id=\"s_prop_".$s_prop_counter."_data\" value=".$res1['p_id']." />";
								$s_prop_counter=$s_prop_counter+1;
						}
						
						echo "</div>";
						mysql_data_seek($this->property_value,0);
						
						}
						
						echo "<input type=\"hidden\" name=\"yield_type_data\" id=\"yield_type_data\" value=".$this->yield_type_id." />";
						echo "<input type=\"hidden\" name=\"prop_counter\" id=\"prop_counter\" value=".$prop_counter." />";
						echo "<input type=\"hidden\" name=\"s_prop_counter\" id=\"s_prop_counter\" value=".$s_prop_counter."  />";
						echo "<div Onclick=\"new_yield_step_one()\" id=\"new_yield_enter_buton\">Kaydet</div>";
						
					}
				
				//////////////////////////////////////////////////////////////////////
				
				};
				
				
				
				
		class user_info
		{
			public $user_id;
			public $user_name;
			public $user_latitude;
			public $user_longitude;
			
		
		public function get_shop_info($shop_id)
			{
			$sql="SELECT*FROM shop WHERE s_id='$shop_id'";
			$sql=mysql_query($sql) or die(mysql_error());
				if(mysql_num_rows($sql)==1)
					{
					$res=mysql_fetch_assoc($sql);
					$this->user_name=$res['s_name'];
					$this->user_latitude=$res['latitude'];
					$this->user_longitude=$res['longitude'];
					return true;
					}else
					{
					return false;
					}
			}
		
		public function get_user_info($shop_id)
			{
			$sql="SELECT*FROM user WHERE u_id='$shop_id'";
			$sql=mysql_query($sql) or die(mysql_error());
				if(mysql_num_rows($sql)==1)
					{
					$res=mysql_fetch_assoc($sql);
					$this->user_name=$res['u_full_name'];
					return true;
					}else
					{
					return false;
					}
			}
		
		
		};
		
		
		class yerok_id_handler
		{
			public $yerok_id; 
			public $yerok_right_id;
			public $yerok_left_id;
			public $yerok_child_or_father;
			public $icon_name;
			public $props_asking=Array();
			public $props_data=Array();
			public $props_data_last=Array();
			public $props_last=Array();
			public $props_y_id=Array();
			private $prop_sql='';
			public $shop_id;
			private $table_name='y1';
			public $props_correct=Array();
			public $props_data_correct=Array();
			public $y_first_image=Array();
			//new_yield_values_sql
			private $n_y_v_sql;
			
			///prop_press_arrays
			public $prop_text=Array();
			public $value_text=Array();
			public $show_y_name=Array();
			public $show_y_prop=Array();
			public $y_data_ids=Array();
			public $shop_data=Array();
			
			public function get_yerok_shop_id($id,$shop) 
				{
				$this->yerok_id=$id;
				$this->shop_id=$shop;
				}
				
			public function yerok_id_control()
				{
					if($this->yerok_id==null or $this->yerok_id==0)
						{
					$this->yerok_id=0;
						}
				}
			
			public function select_table()
				{
				
					switch ($this->yerok_id) 
							{
							case  ($this->yerok_id  >= '1' and $this->yerok_id < '19000000' ):
								$this->table_name='y1';
								break;
							case  ($this->yerok_id  >= '20000000' and $this->yerok_id < '30000000' ):
								$this->table_name='y2';
								break;
							case  ($this->yerok_id  >= '40000000' and $this->yerok_id < '50000000' ):
								$this->table_name='y3';
								break;
							case  ($this->yerok_id  >= '60000000' and $this->yerok_id < '80000000' ):
								$this->table_name='y4';
								break;
							case  ($this->yerok_id  >= '90000000' and $this->yerok_id < '120000000' ):
								$this->table_name='y5';
								break;
							case  ($this->yerok_id  >= '130000000' and $this->yerok_id < '150000000' ):
								$this->table_name='y6';
								break;
							case  ($this->yerok_id  >= '170000000' and $this->yerok_id < '190000000' ):
								$this->table_name='y7';
								break;
							default:
								die('HATA!');
								break;
							}
				}
				
			public function search_right_left_id()
				{
				$sql="SELECT*FROM y_name_list Where y_type_id='$this->yerok_id' ";
				$sql=mysql_query($sql);
				$res=mysql_fetch_assoc($sql);
				$this->yerok_child_or_father=$res['is_y'];
				$this->yerok_left_id=$res['child_left_id'];
				$this->yerok_right_id=$res['child_right_id'];
				$this->icon_name=$res['icon_name'];
				}
				
				//create firstly new array that is not include 0 or nonint value than proses in a loop
				//shop yield insert json prop turn to arrays of prop and value of prop 
			public function to_ready_arrays($json_prop)
				{
				$i=0;
				$select_char = array('.',',',':');
				$delete_char = array('','','');
				$json_prop=ltrim($json_prop,",{");
				$json_prop=rtrim($json_prop,",}");
						//urunun props textini parcalama
							$dizi=explode(",",$json_prop);
							//urunun props sayisi
							if($json_prop!=null)
								{
								$size1=sizeof($dizi);
								}else
								{
								$size1=0;
								}
								
							for($ii = 0; $ii<$size1; $ii++)
								{
								$aa=explode(":",$dizi[$ii]);
								$prop = str_replace($select_char,$delete_char,$aa['0']);
								$prop_value = str_replace($select_char,$delete_char,$aa['1']);
									if(is_numeric($prop) and is_numeric($prop_value))
										{
										//soruid
										$this->props_last[]=$prop_value;
										//degerid
										$this->props_data_last[]=$prop;
										}
								$i=$i+1;
								if($i>50){ break; } // max prop loop number is 50;										
								}
				/*$i=0;
				$json_prop=json_decode($json_prop,TRUE);
				foreach($json_prop['data'] as $id => $data)
					{
					if( is_int($data['prop']) and is_int($data['value']))
						{
						$this->props_data_last[]=$data['prop'];
						$this->props_last[]=$data['value'];
						}
						$i=$i+1;
						if($i>50){ break; } // max prop loop number is 50;
					}
					*/
				}
				
				
			public function control_array_in_database()
				{
					$count=0;
					$prop_size=sizeof($this->props_last);
					for($i = 0; $i<$prop_size; $i++)
					{
					$sql="SELECT*FROM y_prop_value_contact WHERE y_type_id='$this->yerok_id' and y_p_id='$this->props_last[$i]' and y_v_id='$this->props_data_last[$i]'";
					$sql=mysql_query($sql) or die(mysql_error());
						if(mysql_num_rows($sql)!=1)
							{
							$this->props_correct[$count]=$this->props_last[$i];
							$this->props__data_correct[$count]=$this->props_data_last[$i];
							$count=$count+1;
							}
							
					unset($this->props_las[$i]);
					unset($this->props_data_last[$i]);		
					}
				}
			public function get_new_yield_values_sql()
				{
				$prop_size=sizeof($this->props_correct);
				$this->n_y_v_sql="{";
					for($i = 0; $i<$prop_size; $i++)
					{
						if($i==0)
							{
							$this->n_y_v_sql=$this->n_y_v_sql.$this->props__data_correct[$i].":".$this->props_correct[$i];
							}else
							{
							$this->n_y_v_sql=$this->n_y_v_sql.",".$this->props__data_correct[$i].":".$this->props_correct[$i];
							}
					}
				$this->n_y_v_sql=$this->n_y_v_sql."}"; 
				}
			public function enter_new_yield_in_database($yerok_name,$latitude,$longitude)
				{
				self::get_new_yield_values_sql();
				$sql="INSERT INTO ".$this->table_name."(y_name,s_id,y_name_id,latitude,longitude,props) VALUES('$yerok_name','$this->shop_id','$this->yerok_id','$latitude','$longitude','$this->n_y_v_sql')";
				$sql=mysql_query($sql) or die(mysql_error());
					if($sql)
						{
						echo "Yeni Ürün girildi...";
						}else
						{
						echo "Giriş Sırasında Hata Oluştu!";
						}
				
				}
			
			public function get_props_to_y_show()
				{
				$prop_values=Array();
				$props_y_id=Array();
				$select_char = array('.',',',':');
				$delete_char = array('','','');
				$sql="SELECT y_id,y_name,s_id,y_name_id,props FROM ".$this->table_name." WHERE s_id='$this->shop_id' and y_name_id='$this->yerok_id'";
				
				$sql=mysql_query($sql) or die(mysql_error());
					while($res=mysql_fetch_assoc($sql))
						{
						$prop_values[$res['y_id']]=$res['props'];
						$this->show_y_name[$res['y_id']]=$res['y_name'];
						}
				
				//urun sayisi kadar for dongusu
				foreach($prop_values as $key => $value)
					{
					$prop_values[$key]=ltrim($prop_values[$key],",{");
					$prop_values[$key]=rtrim($prop_values[$key],",}");
						//$i. urunun props textini parcalama
							$dizi=explode(",",$prop_values[$key]);
							//$i. urunun props sayisi
							if($prop_values[$key]!=null)
								{
								$size1=sizeof($dizi);
								}else
								{
								$size1=0;
								}
								
							for($ii = 0; $ii<$size1; $ii++)
								{
								$aa=explode(":",$dizi[$ii]);
								$prop = str_replace($select_char,$delete_char,$aa['0']);
								$prop_value = str_replace($select_char,$delete_char,$aa['1']);
									if(is_numeric($prop) and is_numeric($prop_value))
										{
										//soruid
										$this->props_last[]=$prop;
										//degerid
										$this->props_data_last[]=$prop_value;
								
										$this->y_data_ids[$key][$prop][$prop_value]=$prop_value;

										}			
								}
					}	
				}
			
			public function get_props_value_to_y_show()
				{
						$props_value_ids = join(', ', $this->props_data_last);
						$props_ids=join(', ', $this->props_last);
					
					if($props_value_ids!=null)
					{
					$sql="SELECT*FROM y_prop_value WHERE v_id IN ($props_value_ids)";
					$sql=mysql_query($sql) or die(mysql_error());
					while($res=mysql_fetch_assoc($sql))
						{
						$this->value_text[ $res['v_id'] ]=$res['value_text'];
						}
					}
					
					if($props_ids!=null)
					{
					$sql="SELECT*FROM y_prop_q WHERE p_id IN ($props_ids)";
					$sql=mysql_query($sql) or die(mysql_error());
					while($res=mysql_fetch_assoc($sql))
						{
						$this->prop_text[ $res['p_id'] ]=$res['prop_text'];
						}
					}	
				}
				
			public function press_yields_from_list()
				{
						foreach ($this->show_y_name as $y_id => $y_name)
							{
								echo "<div id=\"show_y_special\" class=\"show_y_special_".$y_id."_".$this->yerok_id."\">";
								echo "<div class=\"yield_set\" id=\"yield_set_".$y_id."_".$this->yerok_id."\" > <div class=\"b_yield_set_delete\" id=\"yield_set_delete_".$y_id."_".$this->yerok_id."\" onclick=\"yield_set_delete(".$y_id.",".$this->yerok_id.")\" >Sil</div></div>";
								echo "<div id=\"y_special_name\">".$y_name."</div>";
									if (array_key_exists($y_id, $this->y_data_ids)) 
									{
										echo "<table id=\"s_y_list\" align=center><tbody>";
											foreach ( $this->y_data_ids[$y_id] as $q_id => $value_id)
												{
												echo  "<tr><td>".$this->prop_text[$q_id].":</td><td>";
													foreach($value_id as $v_id =>$vv_id)
														{
														echo "<div id=\"prop_vis\">".$this->value_text[$v_id]."</div>";
														}
												echo "</td></tr>";
												}
										echo "</tbody></table>";
									}else
									{
									echo "Özellik Bulunamadı...";
									}
								echo "</div>";
							}		
				}
			public function get_props_to_y_show_from_search($y_id)
				{
				$prop_values=Array();
				$props_y_id=Array();
				$select_char = array('.',',',':');
				$delete_char = array('','','');
				$sql="SELECT ".$this->table_name.".y_id,".$this->table_name.".y_name,".$this->table_name.".s_id,".$this->table_name.".props,shop.s_name,shop.latitude,shop.longitude FROM ".$this->table_name." LEFT JOIN shop ON shop.s_id=".$this->table_name.".s_id WHERE y_id='$y_id' and y_name_id='$this->yerok_id'";
				
				$sql=mysql_query($sql) or die(mysql_error());
					while($res=mysql_fetch_assoc($sql))
						{
						$prop_values[$res['y_id']]=$res['props'];
						$this->show_y_name[$res['y_id']]=$res['y_name'];
						$this->shop_data[$res['y_id']][0]=$res['s_id'];
						$this->shop_data[$res['y_id']][1]=$res['s_name'];
						$this->shop_data[$res['y_id']][2]=$res['latitude'];
						$this->shop_data[$res['y_id']][3]=$res['longitude'];
						}
				
				//urun sayisi kadar for dongusu
				foreach($prop_values as $key => $value)
					{
					$prop_values[$key]=ltrim($prop_values[$key],",{");
					$prop_values[$key]=rtrim($prop_values[$key],",}");
						//$i. urunun props textini parcalama
							$dizi=explode(",",$prop_values[$key]);
							//$i. urunun props sayisi
							if($prop_values[$key]!=null)
								{
								$size1=sizeof($dizi);
								}else
								{
								$size1=0;
								}
								
							for($ii = 0; $ii<$size1; $ii++)
								{
								$aa=explode(":",$dizi[$ii]);
								$prop = str_replace($select_char,$delete_char,$aa['0']);
								$prop_value = str_replace($select_char,$delete_char,$aa['1']);
									if(is_numeric($prop) and is_numeric($prop_value))
										{
										//soruid
										$this->props_last[]=$prop;
										//degerid
										$this->props_data_last[]=$prop_value;
								
										$this->y_data_ids[$key][$prop][$prop_value]=$prop_value;

										}			
								}
					}	
				}
			public function get_y_icon()
				{
				$sql="SELECT icon_name FROM y_name_list WHERE y_type_id='$this->yerok_id'";
				$sql=mysql_query($sql) or die(mysql_error());
				$res=mysql_fetch_assoc($sql);
				$this->y_icon=$res['icon_name'].".png";
				}
			public function get_y_images($y_id)
				{
				$sql="SELECT y_id,y_type_id,img_way FROM y_image  WHERE y_type_id='$this->yerok_id' and y_id='$y_id' ";
				$sql=mysql_query($sql) or die(mysql_error());
				//$this->y_first_image[]=null;
				while($res=mysql_fetch_assoc($sql))
					{
						$this->y_first_image[]=$res['img_way'];
					}
				}
			public function press_yield_from_search($y_id,$y_type_id)
				{
				$this->yerok_id=$y_type_id;
				self::select_table();
				self::get_props_to_y_show_from_search($y_id);
				self::get_props_value_to_y_show();
				self::get_y_images($y_id);
				echo "<div id=\"lightbox_exit\" onclick=\"hidebackground();\" ><img src=\"image/close.png\"></div>";
				echo "<div id=\"y_special_light_box\">";
				$number_of_image=count($this->y_first_image);
				if($number_of_image==0)
					{
					self::get_y_icon();
					echo "<div id=\"main_icon_big\" style=\"background-image:url('../y_img_small/".$this->y_icon."')\" ></div>";
					}else
					{
					echo "<div id=\"main_image_big\">";
					echo "<img src=\"../y_img_big/".$this->y_first_image[0]."\" />";
					echo "</div>";
					echo "<div id=\"small_images_area\">";
					echo "<div id=\"y_small_image\">";
					if(isset($this->y_first_image[0])){ echo "<img onclick=\"load_big_y_image('../y_img_big/".$this->y_first_image[0]."')\" src=\"../www.yeroks.com/y_img_big/".$this->y_first_image[0]."\" height=80 />";}
					echo "</div>";
					echo "<div id=\"y_small_image\">";
					if(isset($this->y_first_image[1])){ echo "<img onclick=\"load_big_y_image('../y_img_big/".$this->y_first_image[1]."')\" src=\"../www.yeroks.com/y_img_big/".$this->y_first_image[1]."\" height=80 />";}
					echo "</div>";
					echo "<div id=\"y_small_image\">";
					if(isset($this->y_first_image[2])){ echo "<img onclick=\"load_big_y_image('../y_img_big/".$this->y_first_image[2]."')\" src=\"../www.yeroks.com/y_img_big/".$this->y_first_image[2]."\" height=80 />";}
					echo "</div>";
					echo "</div>";
					}

				echo "<div id=\"y_special_props\">";
				foreach ($this->show_y_name as $y_id => $y_name)
							{
								echo "<div id=\"show_y_special\" class=\"show_y_special_".$y_id."_".$this->yerok_id."\">";
								$yield_name=$y_name;
									if (array_key_exists($y_id, $this->y_data_ids)) 
									{
										echo "<table id=\"s_y_list\" align=center><tbody>";
											foreach ( $this->y_data_ids[$y_id] as $q_id => $value_id)
												{
												echo  "<tr><td>".$this->prop_text[$q_id].":</td><td>";
													foreach($value_id as $v_id =>$vv_id)
														{
														echo "<div id=\"prop_vis\">".$this->value_text[$v_id]."</div>";
														}
												echo "</td></tr>";
												}
										echo "</tbody></table>";
									}else
									{
									echo "Özellik Bulunamadı...";
									}
								echo "</div>";
							}
				echo "<div id=\"y_name_light\">".$this->show_y_name[$y_id]."</div>";
				echo "</div>";
				echo "<div id=\"shop_name_light\">-".$this->shop_data[$y_id][1]."</div>";
				echo "<div id=\"bottom_area\"><div id=\"get_direction\" onclick=\"calcRoute( ".$this->shop_data[$y_id][2].",".$this->shop_data[$y_id][3].",".$this->shop_data[$y_id][0].")\">Yol Tarifi</div><div id=\"see_on_map\" onclick=\"see_on_map(".$this->shop_data[$y_id][2].",".$this->shop_data[$y_id][3].")\">Haritada Gör</div><div onclick=\"add_fav(".$y_id.",".$y_type_id.")\" id=\"fav_".$y_id."_".$y_type_id."\" class=\"add_fav\"><img class=\"cycle_loading\" src=\"image/cycle_loading.gif\" height=\"20\"><img class=\"add_icon\" src=\"image/add_fav.png\" height=\"20\"><img class=\"added_icon\" src=\"image/added_fav.png\" height=\"20\"></div>";
				echo "</div>";
				
				}
				
			public function enter_y_type_in_database()
				{
				$sql="INSERT INTO shop_y_list(shop_id, y_type_id,y_count) VALUES('$this->shop_id','$this->yerok_id','1' )
				ON DUPLICATE KEY UPDATE y_count=(y_count+1)";
				$sql=mysql_query($sql) or die(mysql_error());
				}
			public function to_ready_arrays_search($props_json)
				{
				$select_char = array('.',',',':');
				$delete_char = array('','','');
				//props_data_array have prop_ids!
				$props_json=ltrim($props_json,",{");
				$props_json=rtrim($props_json,",}");
						//$i. urunun props textini parcalama
							$dizi=explode(",",$props_json);
							//$i. urunun props sayisi
							if($props_json!=null)
								{
								$size1=sizeof($dizi);
								}else
								{
								$size1=0;
								}
								
							for($ii = 0; $ii<$size1; $ii++)
								{
								$aa=explode(":",$dizi[$ii]);
									if(isset($aa['0']) and isset($aa['1']))
										{
										$prop = str_replace($select_char,$delete_char,$aa['0']);
										$prop_value = str_replace($select_char,$delete_char,$aa['1']);
										}else{
										$prop=0;
										$prop_value=0;
										}
								if(is_numeric($prop) and is_numeric($prop_value))
										{
											if($prop!=0 and $prop_value!=0)
												{
													//soruid
													$this->props_last[]=$prop_value;
													//degerid
													$this->props_data_last[]=$prop;
												}
										}			
								}

				}

			public function props_prepare($props_json)
				{
			
				self::to_ready_arrays_search($props_json);
				
				$prop_size=sizeof($this->props_last);
				if($prop_size==1)
					{
						$this->prop_sql="'%".$this->props_data_last[0].":".$this->props_last[0]."%'";
					}else
					{
				
						for($i = 0; $i<$prop_size; $i++)
							{
								if($i!=($prop_size-1))
									{
										$this->prop_sql=$this->prop_sql."'%".$this->props_data_last[$i].":".$this->props_last[$i]."%' and props LIKE ";
									}else
									{
										$this->prop_sql=$this->prop_sql."'%".$this->props_data_last[$i].":".$this->props_last[$i]."%'";
									}
							}
					}
					
					if($prop_size>0)
						{
						$this->prop_sql="and props LIKE ".$this->prop_sql."";
						}
				}
				
			public function yeroks_search($lat,$long,$page_no)
				{
					
					$result_number=20;
				if($page_no!=null)
					{
					$up_no=$result_number*$page_no;
					$down_no=$up_no-$result_number;
					$next_page=$page_no+1;
					$prev_page=$page_no-1;
					}else
					{
					$down_no=0;
					$up_no=$result_number;
					}
					$ii=$down_no;
				
					if($this->yerok_child_or_father==0)
						{				
						$sql="SELECT ".$this->table_name.".y_id, ".$this->table_name.".y_name, ".$this->table_name.".y_name_id,".$this->table_name.".latitude,".$this->table_name.".longitude,( 6371 * acos( cos( radians($lat) ) * cos( radians( ".$this->table_name.".latitude ) ) * cos( radians( ".$this->table_name.".longitude ) - radians($long) ) + sin
( radians($lat) ) * sin( radians( ".$this->table_name.".latitude ) ) ) ) AS distance,".$this->table_name.".s_id,".$this->table_name.".place_id,shop.s_id,shop.s_name,place.id,place.name,y_name_list.icon_name,y_image.img_way FROM ".$this->table_name." LEFT JOIN shop ON shop.s_id=".$this->table_name.".s_id LEFT JOIN y_name_list ON y_name_list.y_type_id=".$this->table_name.".y_name_id LEFT JOIN place ON ".$this->table_name.".place_id=place.id LEFT JOIN y_image ON ".$this->table_name.".y_id=y_image.y_id and ".$this->table_name.".y_name_id=y_image.y_type_id and y_image.no=1   WHERE (y_name_id <'$this->yerok_right_id' and y_name_id > '$this->yerok_left_id') ".$this->prop_sql." GROUP BY ".$this->table_name.".s_id HAVING distance < 30  ORDER BY distance  LIMIT $down_no,$result_number";
		
						}else
						{
							if($this->yerok_id==0)
								{
								$sql="SELECT ".$this->table_name.".y_id, ".$this->table_name.".y_name, ".$this->table_name.".y_name_id,".$this->table_name.".latitude,".$this->table_name.".longitude,( 6371 * acos( cos( radians($lat) ) * cos( radians( ".$this->table_name.".latitude ) ) * cos( radians( ".$this->table_name.".longitude ) - radians($long) ) + sin
( radians($lat) ) * sin( radians( ".$this->table_name.".latitude ) ) ) ) AS distance,".$this->table_name.".s_id,".$this->table_name.".place_id,shop.s_id,shop.s_name,place.id,place.name,y_name_list.icon_name,y_image.img_way FROM ".$this->table_name." LEFT JOIN shop ON shop.s_id=".$this->table_name.".s_id LEFT JOIN y_name_list ON y_name_list.y_type_id=".$this->table_name.".y_name_id LEFT JOIN place ON ".$this->table_name.".place_id=place.id LEFT JOIN y_image ON ".$this->table_name.".y_id=y_image.y_id and ".$this->table_name.".y_name_id=y_image.y_type_id and y_image.no=1   WHERE ".$this->prop_sql."  GROUP BY ".$this->table_name.".s_id HAVING distance < 30  ORDER BY distance LIMIT $down_no,$result_number";
								}else
								{
						$sql="SELECT ".$this->table_name.".y_id, ".$this->table_name.".y_name, ".$this->table_name.".y_name_id, ".$this->table_name.".latitude,".$this->table_name.".longitude,( 6371 * acos( cos( radians($lat) ) * cos( radians( ".$this->table_name.".latitude ) ) * cos( radians( ".$this->table_name.".longitude ) - radians($long) ) + sin
( radians($lat) ) * sin( radians( ".$this->table_name.".latitude ) ) ) ) AS distance,".$this->table_name.".s_id,".$this->table_name.".place_id,shop.s_id,shop.s_name,place.id,place.name,y_name_list.icon_name,y_image.img_way FROM ".$this->table_name." LEFT JOIN shop ON shop.s_id=".$this->table_name.".s_id LEFT JOIN y_name_list ON y_name_list.y_type_id=".$this->table_name.".y_name_id LEFT JOIN place ON ".$this->table_name.".place_id=place.id LEFT JOIN y_image ON ".$this->table_name.".y_id=y_image.y_id and ".$this->table_name.".y_name_id=y_image.y_type_id and y_image.no=1   WHERE y_name_id ='$this->yerok_id' ".$this->prop_sql."  GROUP BY ".$this->table_name.".s_id  HAVING distance < 30 ORDER BY distance  LIMIT $down_no,$result_number";
								}						
						
						}
					$sql=mysql_query($sql) or die(mysql_error());
					$count=mysql_num_rows($sql);
					if($count==0)
						{
						if($page_no==1 and $this->yerok_id==0)
							{
								echo "{\"press\":0,\"is_f_p\":1,\"text\":\"Ürün Seçerek Arama Yapabilirsin...\"}";
							}elseif($page_no==1)
							{
								echo "{\"press\":0,\"is_f_p\":1,\"text\":\"Çevrenizde Bu Ürün Bulunamadı...\"}";
							}else
							{
								echo "{\"press\":0,\"is_f_p\":1,\"text\":\"</br>Bütün Ürünler Yüklendi...\"}";
							}
						}else
						{
						$i=0;
						echo "{\"press\":1,\"is_f_p\":0,\"text\":\"\",\"results\":[";
							while($res=mysql_fetch_assoc($sql))
							{
							$i=$i+1;
							$ii=$ii+1;
							$distance =$res['distance'];
							if($distance<1)
							{
							$distance=$distance*1000;
							$dis_json="<b>".round($distance,0)."</b> m";
							}else
							{
							$dis_json="<b>".round($distance,2)."</b> km";
							}
							if($res['place_id']!=0)
							{
							$place_name=$res['name'];
							}else
							{
							$place_name="";
							}
							if($res['img_way']!=''){
							echo "{
							\"y_id\":".$res['y_id'].",
							\"y_name\":\"".$res['y_name']."\",
							\"image\":\"".$res['img_way']."\",
							\"s_id\":".$res['s_id'].",
							\"s_name\":\"".$res['s_name']."\",
							\"lati\":".$res['latitude'].",
							\"longi\":".$res['longitude'].",
							\"distance\":\"".$dis_json."\",
							\"place\":".$res['place_id'].",
							\"type_id\":".$res['y_name_id'].",
							\"place_name\":\"".$place_name."\",
							\"i_n\":".$ii."
							}";
							}else
							{
							echo "{
							\"y_id\":".$res['y_id'].",
							\"y_name\":\"".$res['y_name']."\",
							\"image\":\"".$res['icon_name'].".png\",
							\"s_id\":".$res['s_id'].",
							\"s_name\":\"".$res['s_name']."\",
							\"lati\":".$res['latitude'].",
							\"longi\":".$res['longitude'].",
							\"distance\":\"".$dis_json."\",
							\"place\":".$res['place_id'].",
							\"type_id\":".$res['y_name_id'].",
							\"place_name\":\"".$place_name."\",
							\"i_n\":".$ii."
							}";
							}
							if($i!=$count)
								{
								echo ",";
								}
							}
							echo "]}";
						}
				}
				
				
						
				public function yeroks_search_in_place($lat,$long,$page_no,$position)
				{
					$result_number=20;
				if($page_no!=null)
					{
					$up_no=$result_number*$page_no;
					$down_no=$up_no-$result_number;
					$next_page=$page_no+1;
					$prev_page=$page_no-1;
					}else
					{
					$down_no=0;
					$up_no=$result_number;
					}
					$ii=$down_no;
				
					if($this->yerok_child_or_father==0)
						{
							$sql="SELECT ".$this->table_name.".y_id,".$this->table_name.".y_name_id, ".$this->table_name.".y_name,".$this->table_name.".latitude,".$this->table_name.".longitude,
".$this->table_name.".s_id,".$this->table_name.".place_id,shop.s_id,shop.s_name,shop.floor,place.id,place.name,y_name_list.icon_name,y_image.img_way  FROM ".$this->table_name." INNER JOIN (place,shop) ON (".$this->table_name.".place_id=place.id and shop.s_id=".$this->table_name.".s_id)  LEFT JOIN y_name_list ON y_name_list.y_type_id=".$this->table_name.".y_name_id LEFT JOIN y_image ON ".$this->table_name.".y_id=y_image.y_id and ".$this->table_name.".y_name_id=y_image.y_type_id and y_image.no=1  WHERE (y_name_id <'$this->yerok_right_id' and y_name_id > '$this->yerok_left_id') and place_id='$position' ".$this->prop_sql." GROUP BY ".$this->table_name.".y_name_id  LIMIT $down_no,$result_number";
								
						}else
						{
						if($this->yerok_id==0)
								{
								$sql="SELECT ".$this->table_name.".y_id, ".$this->table_name.".y_name,".$this->table_name.".latitude,".$this->table_name.".longitude,".$this->table_name.".s_id,".$this->table_name.".place_id,shop.s_id,shop.s_name,shop.floor,place.id,place.name,y_name_list.icon_name,y_image.img_way  
								FROM ".$this->table_name." INNER JOIN (place,shop) ON (".$this->table_name.".place_id=place.id and shop.s_id=".$this->table_name.".s_id)  LEFT JOIN y_name_list ON y_name_list.y_type_id=".$this->table_name.".y_name_id LEFT JOIN y_image ON ".$this->table_name.".y_id=y_image.y_id and ".$this->table_name.".y_name_id=y_image.y_type_id and y_image.no=1   WHERE ".$this->table_name."place_id='$position' ".$this->prop_sql."  GROUP BY ".$this->table_name.".s_id LIMIT
$down_no,$result_number";
								}else
								{
						$sql="SELECT ".$this->table_name.".y_id, ".$this->table_name.".y_name_id, ".$this->table_name.".y_name,".$this->table_name.".latitude,".$this->table_name.".longitude,
".$this->table_name.".s_id,".$this->table_name.".place_id,shop.s_id,shop.s_name,shop.floor,place.id,place.name,y_name_list.icon_name,y_image.img_way  FROM ".$this->table_name." INNER JOIN (place,shop) ON (".$this->table_name.".place_id=place.id and shop.s_id=".$this->table_name.".s_id)  LEFT JOIN y_name_list ON y_name_list.y_type_id=".$this->table_name.".y_name_id LEFT JOIN y_image ON ".$this->table_name.".y_id=y_image.y_id and ".$this->table_name.".y_name_id=y_image.y_type_id and y_image.no=1  WHERE y_name_id ='$this->yerok_id' and place_id='$position' ".$this->prop_sql."  GROUP BY ".$this->table_name.".s_id  LIMIT
$down_no,$result_number";
								}
						}
					
					$sql=mysql_query($sql) or die(mysql_error());
					$count=mysql_num_rows($sql);
					if($count==0)
						{
						if($page_no==1 and $this->yerok_id==0)
							{
								echo "{\"press\":0,\"is_f_p\":1,\"text\":\"Ürün Seçerek Arama Yapabilirsin...\"}";
							}elseif($page_no==1)
							{
								echo "{\"press\":0,\"is_f_p\":1,\"text\":\"Burada Bu Ürün Bulunamadı...\"}";
							}else
							{
								echo "{\"press\":0,\"is_f_p\":1,\"text\":\"</br>Bütün Ürünler Yüklendi...\"}";
							}
						}else
						{
						$i=0;
						echo "{\"press\":1,\"is_f_p\":0,\"text\":\"\",\"results\":[";
							while($res=mysql_fetch_assoc($sql))
							{
								$i=$i+1;
								$ii=$ii+1;
								$floor_text="<b>".$res['floor']."</b> . Katta ";
							
								if($res['img_way']!=''){
								
									echo "{
									\"y_id\":".$res['y_id'].",
									\"y_name\":\"".$res['y_name']."\",
									\"image\":\"".$res['img_way']."\",
									\"s_id\":".$res['s_id'].",
									\"s_name\":\"".$res['s_name']."\",
									\"floor\":\"".$floor_text."\",
									\"type_id\":".$res['y_name_id'].",
									\"i_n\":".$ii."
									}";
									}else{
									echo "{
									\"y_id\":".$res['y_id'].",
									\"y_name\":\"".$res['y_name']."\",
									\"image\":\"".$res['icon_name'].".png\",
									\"s_id\":".$res['s_id'].",
									\"s_name\":\"".$res['s_name']."\",
									\"floor\":\"".$floor_text."\",
									\"type_id\":".$res['y_name_id'].",
									\"i_n\":".$ii."
									}";
									}
									if($i!=$count)
										{
										echo ",";
										}
								}
						echo "]}";	
						}
				}
			public function yeroks_search_on_map($lat,$long)
				{
					$sql="SELECT ".$this->table_name.".latitude,".$this->table_name.".longitude,( 6371 * acos( cos( radians($lat) ) * cos( radians( ".$this->table_name.".latitude ) ) * cos( radians( ".$this->table_name.".longitude ) - radians($long) ) + sin
( radians($lat) ) * sin( radians( ".$this->table_name.".latitude ) ) ) ) AS distance,".$this->table_name.".s_id,".$this->table_name.".place_id,shop.s_id,shop.s_name,place.id,place.name FROM ".$this->table_name." LEFT JOIN shop ON shop.s_id=".$this->table_name.".s_id LEFT JOIN place ON ".$this->table_name.".place_id=place.id  WHERE (y_name_id <'$this->yerok_right_id' and y_name_id > '$this->yerok_left_id') GROUP BY ".$this->table_name.".s_id HAVING distance < 30  ORDER BY distance  LIMIT 0,20";
						
					$sql=mysql_query($sql) or die(mysql_error());
					$count=mysql_num_rows($sql);
					if($count==0)
						{
						echo "{\"press\":0,\"explain\":\"Malesef Herhangi Bir Sonuç Bulunamadı...\"}";
						}else
						{
						$i=0;
						echo "{\"press\":1,\"explain\":\"\",\"results\":[";
							while($res=mysql_fetch_assoc($sql))
							{
							$i=$i+1;
							$distance =$res['distance'];
							if($distance<1)
							{
							$distance=$distance*1000;
							$dis_json="<b>".round($distance,0)."</b> m";
							}else
							{
							$dis_json="<b>".round($distance,2)."</b> km";
							}
							if($res['place_id']!=0)
							{
							$place_name=$res['name'];
							}else
							{
							$place_name="";
							}
							
							echo "{
							\"s_id\":".$res['s_id'].",
							\"s_name\":\"".$res['s_name']."\",
							\"lati\":".$res['latitude'].",
							\"longi\":".$res['longitude'].",
							\"distance\":\"".$dis_json."\",
							\"place\":".$res['place_id'].",
							\"place_name\":\"".$place_name."\"
							}";
							if($i!=$count)
								{
								echo ",";
								}
							}
							echo "]}";
						}
				}
				
				function press_all_s_product()
				{
				$sql="SELECT y_name,y_name_id,y_id FROM ".$this->table_name." WHERE y_name_id='$this->yerok_id' and s_id='$this->shop_id' ";
				$sql=mysql_query($sql) or die(mysql_error());
				if(mysql_num_rows($sql)==0)
					{
					echo "Herhangi Bir ürün Bulunamadı...";
					}else
					{
					while($res=mysql_fetch_assoc($sql))
						{
						echo "<div id=\"y_spe\"  onclick=\"show_product_props(".$res['y_id'].",".$res['y_name_id'].")\" >".$res['y_name']."</div>";
						}
					}
				
				}
		};
		class select_t
			{
			public function select_table($yerok_id)
				{
				
					switch ($yerok_id) 
							{
							case  ($yerok_id  >= '1' and $yerok_id < '19000000' ):
								return 'y1';
								break;
							case  ($yerok_id  >= '20000000' and $yerok_id < '30000000' ):
								return 'y2';
								break;
							case  ($yerok_id  >= '40000000' and $yerok_id < '50000000' ):
								return 'y3';
								break;
							case  ($yerok_id  >= '60000000' and $yerok_id < '80000000' ):
								return 'y4';
								break;
							case  ($yerok_id  >= '90000000' and $yerok_id < '120000000' ):
								return 'y5';
								break;
							case  ($yerok_id  >= '130000000' and $yerok_id < '150000000' ):
								return 'y6';
								break;
							case  ($yerok_id  >= '170000000' and $yerok_id < '190000000' ):
								return 'y7';
								break;
							default:
								die('HATA!');
								break;
							}
				}
			}
		class favorite
			{
				public $fav_id;
				public $type;
				private $user_id;
				public $problem;
				
				function get_values($id,$type,$user)
					{
					$this->fav_id=$id;
					$this->type=$type;
					$this->user_id=$user;
					}
			
				function fav_s_operation()
					{
						$sql="SELECT id,s_id FROM shopping_fav_list WHERE user_id='$this->user_id' and s_id='$this->fav_id'";
						$sql=mysql_query($sql) or die(mysql_error());
						if(mysql_num_rows($sql)>0)
							{
							$sql="DELETE FROM shopping_fav_list WHERE user_id=$this->user_id and s_id=$this->fav_id";
							if(mysql_query($sql))
											{
											echo "{\"result\":1,\"fav_status\":0,\"explain\":\"Mağaza Favorilerden Çıkarıldı...\"}";
											}else
											{
											echo "{\"result\":0,\"explain\":\"Bir Hata Oluştu...\"}";
											}
							}else
							{						
							$sql="SELECT s_id FROM shop WHERE s_id='$this->fav_id'";
							$sql=mysql_query($sql) or die(mysql_error());
								if(mysql_num_rows($sql)==1)
									{
									$res=mysql_fetch_assoc($sql);
									$s_id=$res['s_id'];
									$sql="INSERT INTO shopping_fav_list(user_id,s_id,type,add_time) VALUES('$this->user_id','$s_id','$this->type',NOW()) ";
										if(mysql_query($sql))
											{
											echo "{\"result\":1,\"fav_status\":1,\"explain\":\"Favori Listesine Eklendi...\"}";
											}else
											{
											echo "{\"result\":0,\"explain\":\"Bir Hata Oluştu...\"}";
											}
									}else
									{
									die("{\"result\":0,\"explain\":\"Bir Hata Oluştu...\"}");
									}
								
							}
					
					
					}
				function fav_p_operation()
					{
						$sql="SELECT id,p_id FROM shopping_fav_list WHERE user_id='$this->user_id' and p_id='$this->fav_id'";
						$sql=mysql_query($sql) or die(mysql_error());
						if(mysql_num_rows($sql)>0)
							{
							$sql="DELETE FROM shopping_fav_list WHERE user_id=$this->user_id and p_id=$this->fav_id";
							if(mysql_query($sql))
											{
											echo "{\"result\":1,\"fav_status\":0,\"explain\":\"AVM Favorilerden Çıkarıldı...\"}";
											}else
											{
											echo "{\"result\":0,\"explain\":\"Bir Hata Oluştu...\"}";
											}
							}else
							{					
							$sql="SELECT id FROM place WHERE id='$this->fav_id'";
							$sql=mysql_query($sql) or die(mysql_error());
								if(mysql_num_rows($sql)==1)
									{
									$res=mysql_fetch_assoc($sql);
									$p_id=$res['id'];
									$sql="INSERT INTO shopping_fav_list(user_id,p_id,type,add_time) VALUES('$this->user_id','$p_id','$this->type',NOW()) ";
										if(mysql_query($sql))
											{
											echo "{\"result\":1,\"fav_status\":1,\"explain\":\"Favori Listesine Eklendi...\"}";
											}else
											{
											echo "{\"result\":0,\"explain\":\"Bir Hata Oluştu...\"}";
											}
									}else
									{
									die("{\"result\":0,\"explain\":\"Bir Hata Oluştu...\"}");
									}
								
							}
					
					
					}
					function fav_control($fav_id,$type,$user_id)
					{
						if($type==2){
						$sql="SELECT id,s_id FROM shopping_fav_list WHERE user_id='$user_id' and s_id='$fav_id' and type='2' ";
						}else{
						$sql="SELECT id,p_id FROM shopping_fav_list WHERE user_id='$user_id' and p_id='$fav_id' and type='3' ";
						}
						$sql=mysql_query($sql);
						if(mysql_num_rows($sql)>0)
						{
						return true;
						}else{
						return false;
						}
					}
					
					function fav_y_operation($user_id,$y_type_id,$y_id)
					{
						$sql="SELECT id,user_id,y_type_id,y_id FROM shopping_fav_list WHERE user_id='$user_id' and y_type_id='$y_type_id' and y_id='$y_id'";
						$sql=mysql_query($sql) or die(mysql_error());
						if(mysql_num_rows($sql)>0)
							{
							$res=mysql_fetch_assoc($sql);
							$id=$res['id'];
							$sql="DELETE FROM shopping_fav_list WHERE user_id=$user_id and id=$id";
							if(mysql_query($sql))
											{
											echo "{\"result\":1,\"fav_status\":0,\"explain\":\"Ürün Alışveriş Listesinden Çıkarıldı...\"}";
											}else
											{
											echo "{\"result\":0,\"explain\":\"Bir Hata Oluştu...\"}";
											}
							}else
							{	
							$table_name=table_select::select_table($y_type_id);							
							$sql="SELECT y_id,s_id,y_name,y_name_id FROM $table_name WHERE y_id='$y_id' and y_name_id='$y_type_id'";
							$sql=mysql_query($sql) or die(mysql_error());
								if(mysql_num_rows($sql)==1)
									{
									$res=mysql_fetch_assoc($sql);
									$y_id1=$res['y_id'];
									$s_id1=$res['s_id'];
								    $y_name1=$res['y_name'];
									$y_type_id1=$res['y_name_id'];
									$sql="INSERT INTO shopping_fav_list(user_id,y_type_id,y_id,s_id,y_name,type,add_time)VALUES('$user_id','$y_type_id1','$y_id1','$s_id1','$y_name1','4',NOW()) ";
										if(mysql_query($sql))
											{
											echo "{\"result\":1,\"fav_status\":1,\"explain\":\"Ürün Alışveriş Listesine Eklendi...\"}";
											}else
											{
											echo "{\"result\":0,\"explain\":\"Bir Hata Oluştu...\"}";
											}
									}else
									{
									die("{\"result\":0,\"explain\":\"Bir Hata Oluştu...\"}");
									}
								
							}
					
					
					}
			}
		class table_select
		{
			public function select_table($yerok_id)
				{
					switch($yerok_id)
							{
							case  ($yerok_id  >= '1' and $yerok_id < '19000000' ):
								return 'y1';break;
							case  ($yerok_id  >= '20000000' and $yerok_id < '30000000' ):
								return 'y2';break;
							case  ($yerok_id  >= '40000000' and $yerok_id < '50000000' ):
								return 'y3';break;
							case  ($yerok_id  >= '60000000' and $yerok_id < '80000000' ):
								return 'y4';break;
							case  ($yerok_id  >= '90000000' and $yerok_id < '120000000' ):
								return 'y5';break;
							case  ($yerok_id  >= '130000000' and $yerok_id < '150000000' ):
								return 'y6';break;
							case  ($yerok_id  >= '170000000' and $yerok_id < '190000000' ):
								return 'y7';break;
							case  ($yerok_id  >= '200000000' and $yerok_id < '220000000' ):
								return 'y8';break;
							case  ($yerok_id  >= '230000000' and $yerok_id < '240000000' ):
								return 'y9';break;
							case  ($yerok_id  >= '250000000' and $yerok_id < '260000000' ):
								return 'y10';break;
							default:
								die('HATA!');break;
							}
				}
		
		}
		class show_yield
			{
				function show($shop_id)
					{
						$sql="SELECT shop_y_list.id,shop_y_list.shop_id,shop_y_list.y_type_id,shop_y_list.y_count,y_name_list.y_name 
FROM shop_y_list INNER JOIN y_name_list ON shop_y_list.y_type_id=y_name_list.y_type_id WHERE shop_y_list.shop_id=$shop_id";
						
						$sql=mysql_query($sql) or die(mysql_error());
						if(mysql_num_rows($sql)>0)
							{
							while($res=mysql_fetch_assoc($sql))
								{
								?>
								<div class="shops_yield" Onclick="get_shop_category_special(<?php echo $shop_id.",".$res['y_type_id']; ?>)" >
								<div class="yield_name">
								<?php
								echo $res['y_name']."</br>";
								?>
								</div>
								<div class="yield_count" id="yield_count_<?php  echo $res['y_type_id']; ?>" >
								<?php
								echo $res['y_count']." Çeşit";
								?>
								</div>
								
								</div>
								<div class="y_special_show" id="<?php echo "y-".$shop_id."-".$res['y_type_id']; ?>">
								</div>
								<?php
								}
							}else
							{
							echo "Kayıtlı Ürününüz Bulunmamakta...";
							}
					
					
					}
			
			
			
			
			
			};
		
		class yield_set
			{
			private $y_id;
			private $shop_id;
			private $table_name='y1';
			private $y_type_id;
			private $y_count;
			private $status;
			private $text;
			
			
			public function select_table()
				{
				
					switch ($this->y_type_id) 
							{
							case  ($this->y_type_id  > '1' and $this->y_type_id < '19000000' ):
								$this->table_name='y1';
								break;
							case  ($this->y_type_id  > '20000000' and $this->y_type_id < '30000000' ):
								$this->table_name='y2';
								break;
							case  ($this->y_type_id  > '40000000' and $this->y_type_id < '50000000' ):
								$this->table_name='y3';
								break;
							case  ($this->y_type_id  > '60000000' and $this->y_type_id < '80000000' ):
								$this->table_name='y4';
								break;
							case  ($this->y_type_id  > '90000000' and $this->y_type_id < '120000000' ):
								$this->table_name='y5';
								break;
							case  ($this->y_type_id  > '130000000' and $this->y_type_id < '150000000' ):
								$this->table_name='y6';
								break;
							case  ($this->y_type_id  > '170000000' and $this->y_type_id < '190000000' ):
								$this->table_name='y7';
								break;
							default:
								$this->status=0;
								$this->text="HATA!";
								self::json_echo();
								break;
							}
				}
				function get_y_id($y_id)
					{
						$this->y_id=$y_id;
					}
				function get_shop_id($shop_id)
					{
						$this->shop_id=$shop_id;
					}
				function get_y_type_id($y_type_id)
					{
					$this->y_type_id=$y_type_id;
					}
				function control_y_id()
					{
						//status 1 operation is succesful status 0 false;
						if($this->y_id<0 or $this->y_id>190000000 or $this->y_id==null)
							{
							$this->status=0;
							$this->text="İşlem Sırasında Hata Oluştu!...";
							self::json_echo();
							}
							
					}
				function control_yield_own()
					{
					$sql="SELECT y_id,s_id,y_name_id FROM ".$this->table_name." WHERE y_id='$this->y_id' ";
					$sql=mysql_query($sql) or die(mysql_error());
					$res=mysql_fetch_assoc($sql);
						if(mysql_num_rows($sql)!=0 and $res['y_name_id']==$this->y_type_id and $res['s_id']==$this->shop_id)
							{
							return true;
							}else{
							$this->status=0;
							$this->text="İşlem Sırasında Hata Oluştu!...";
							self::json_echo();
							return false;
							}
					}
				
				function delete_yield()
					{
					$sql="DELETE FROM ".$this->table_name." WHERE y_id='$this->y_id'";
					$sql=mysql_query($sql) or die(mysql_error());
						if($sql)
							{
							$this->status=1;
							$this->text="Ürün Silindi...";
							return true;
							}else
							{
							$this->status=0;
							$this->text="İşlem Sırasında Hata Oluştu!...";
							self::json_echo();
							return false;
							}
					}
				function y_category_count_set()
					{
					$counter = mysql_query("SELECT COUNT(y_id) as id FROM ".$this->table_name." WHERE  y_name_id='$this->y_type_id' and s_id='$this->shop_id'");
					$num = mysql_fetch_array($counter);
					$this->y_count = $num["id"];
						if($this->y_count==0)
							{
							//yield count is zero and yield count have to delete from shop_y_list table
							$sql="DELETE FROM shop_y_list WHERE shop_id='$this->shop_id' and y_type_id='$this->y_type_id'";
							$sql=mysql_query($sql) or die(mysql_error());
							}else
							{
							$sql="UPDATE shop_y_list SET y_count='$this->y_count' WHERE shop_id='$this->shop_id' and y_type_id='$this->y_type_id'";
							$sql=mysql_query($sql) or die(mysql_error());
							}
					
					}
				function json_echo()
					{
						if($this->status==0)
							{
							die("{\"status\":0,\"text\":\"".$this->text."\",\"count\":\"?\"}");
							}else
							{
							echo "{\"status\":1,\"text\":\"".$this->text."\",\"count\":".$this->y_count."}";
							}
					}
			
			
			};
		
		
				
		class place
		{
		
		 function search_place($lat,$long)
			{
			$sql="SELECT id,name,latitude,longitude,floor_max,floor_min,is_check ,( 6371 * acos( cos( radians($lat) ) * cos( radians( latitude ) ) * cos( radians( longitude ) - radians($long) ) + sin( radians($lat) ) * sin( radians( latitude ) ) ) ) AS distance FROM place HAVING distance < 3  ORDER BY distance ASC";
			$sql=mysql_query($sql) or die(mysql_error());
				if(mysql_num_rows($sql)>0)
				{
				while($res=mysql_fetch_assoc($sql))		
					{
					echo "<div id=\"place\">".$res['name']."<div id=\"p_select_b\" onclick=\"jump_to_place(".$res['id'].",'".$res['name']."')\">Buradayım</div></div>";
					}
				}else
				{
				echo "Size Yakın Bir AVM Bulunamadı...";
				}
			}
		};
		
		
		class product_list
			{
				var $y_back_father_id=0;
				var $category_way="";
				var $exp_products="";
				var $back_buttons_show=1;
				
				public function get_category_way($cate_father_id)
					{
					if($cate_father_id==0){$this->back_buttons_show=0;}else{$this->back_buttons_show=1;}
					$sql="SELECT y_type_id,y_name FROM y_name_list WHERE child_left_id<$cate_father_id and child_right_id>$cate_father_id or y_type_id=$cate_father_id";
					$sql=mysql_query($sql) or die(mysql_error());
					$count=mysql_num_rows($sql);
					$this->category_way='[';
					$i=0;
					while($res=mysql_fetch_assoc($sql))
						{
						$i=$i+1;
						$this->category_way=$this->category_way."{\"type_no\":".$res['y_type_id'].",\"c_name\":\"".$res['y_name']."\"}";
						if($i!=$count){$this->category_way=$this->category_way.","; }
						}
						$this->category_way=$this->category_way."]";
					}
				public function find_get_back_id($cate_father_id)
					{
					if($cate_father_id!=0){
					$sql1="SELECT fathers_id FROM y_name_list WHERE y_type_id=$cate_father_id";
					$sql1=mysql_query($sql1) or die(mysql_error());
						if(mysql_num_rows($sql1)==1){ 
							$res=mysql_fetch_assoc($sql1);
							$this->y_back_father_id=$res['fathers_id'];
							}
						}
					}
				public function get_exp_product($cate_father_id)
					{
					$sql="SELECT y_type_id, y_name,icon_name FROM y_name_list WHERE y_type_id >=(SELECT child_left_id FROM y_name_list WHERE y_type_id=$cate_father_id) and y_type_id <=(SELECT child_right_id FROM y_name_list WHERE y_type_id=$cate_father_id) and is_y=1 ORDER BY RAND() LIMIT 0,12";
					$sql=mysql_query($sql) or die(mysql_error());
					$count=mysql_num_rows($sql);
					if($count==0)
						{
						$this->exp_products="[]";
						//echo "Bu Kategoride Bir Ürün Bulunmamakta...
						}else{
								$i=0;
								$this->exp_products=$this->exp_products."[";
								while($res=mysql_fetch_assoc($sql))
								{
									$i=$i+1;
									$this->exp_products=$this->exp_products."{
									\"y_type_id\":".$res['y_type_id'].",
									\"y_name\":\"".$res['y_name']."\",
									\"icon_name\":".$res['icon_name']."
									}";
									if($i!=$count)
									{
									$this->exp_products=$this->exp_products.",";
									}
			
								}
							$this->exp_products=$this->exp_products."]";
						}
					}
				public function press_category($cate_father_id)
					{
					self::find_get_back_id($cate_father_id);
					self::get_category_way($cate_father_id);
					self::get_exp_product($cate_father_id);
					$sql1="SELECT y_type_id, y_name,icon_name,is_y FROM y_name_list WHERE fathers_id=$cate_father_id";
					$sql1=mysql_query($sql1) or die(mysql_error());
					$count=mysql_num_rows($sql1);
					if($count==0)
						{
						echo "{\"result\":0,\"y_back_id\":".$this->y_back_father_id.",\"category_way\":".$this->category_way."}";
						//echo "Bu Kategoride Bir Ürün Bulunmamakta...
						}else{
								$i=0;
								echo "{\"result\":1,\"is_show_back_button\":".$this->back_buttons_show.",\"y_back_id\":".$this->y_back_father_id.",\"category_way\":".$this->category_way.",\"products\":".$this->exp_products.",\"results\":[";
								while($res=mysql_fetch_assoc($sql1))
								{
									$i=$i+1;
									echo "{
									\"y_type_id\":".$res['y_type_id'].",
									\"y_name\":\"".$res['y_name']."\",
									\"icon_name\":\"".$res['icon_name']."\",
									\"is_y\":\"".$res['is_y']."\"
									}";
									if($i!=$count)
									{
									echo ",";
									}
			
								}
							echo "]}";
						}
					
					}
				
				
			
			
			
			
			}
					
		
		

			







?>
