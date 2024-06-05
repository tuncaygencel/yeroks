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
		
		
		public function u_insert_new_user($fullname,$mail,$password) 
			{
				$this -> u_full_name = $fullname;
				$this -> u_mail = $mail;
				$this -> u_password = sha1($password);
				$sql="INSERT INTO user(u_full_name,u_mail,u_password) VALUES('$this->u_full_name ','$this->u_mail ','$this->u_password')"; 
				$res=mysql_query($sql);
				if($res)
				{
					echo "Kaydınız Başarıyla gerçekleştirildi...";
				}else
				{
					echo "Bir hata oluştu...";
				}
			
			}
		};
class insert_new_s_db
		{
		  private $s_full_name;
		  private $s_mail;
		  private $s_password;
		
		public function control_s_mail($mail)
			{
				$this -> s_mail = $mail; 
				$sql="SELECT COUNT(*) FROM shop WHERE  s_mail='$this->s_mail' ";
				$res=mysql_query($sql);
				$res=mysql_fetch_array($res);
				$count=$res[0];
				return $count;
			}
		
		public function s_insert_new_user($fullname,$mail,$password,$shop_type) 
			{
				$this -> s_full_name = $fullname;
				$this -> s_mail = $mail;
				$this -> s_password = sha1($password);
				$sql="INSERT INTO shop(s_name,s_mail,s_password,shop_type,is_register,join_time) VALUES('$this->s_full_name ','$this->s_mail ','$this->s_password','$shop_type','1',NOW())"; 
				$res=mysql_query($sql);
				if($res)
				{
					echo "{\"result\":1,\"explain\":\"Kaydınız Başarıyla Gerçekleştirildi...\"}";
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
			
		public	function is_login($user_type)
			{
				if(isset($_SESSION['user_type'])  and isset($_SESSION['user_no']) and isset($_SESSION['mail_adress']) and isset($_SESSION['user_full_name']))
					{
						if($_SESSION['user_type']==$user_type){
							return true;
							}else{
							return false;
							}
					}elseif(isset($_COOKIE['mail']) && isset($_COOKIE['info']))
					{
						$this->_u_cook_mail= $_COOKIE['mail'];
						$this->_u_cook_data = $_COOKIE['info'];
						
						$conn_scanner_md5=md5($_SERVER [ "HTTP_USER_AGENT" ]);
						$last_ip=$_SERVER['REMOTE_ADDR'];
						
						$remember_data=$conn_scanner_md5.$this->_u_cook_data.$last_ip;
						if($user_type==1){
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
							}else{
							return false;
							}
							}else{
						$sql="SELECT*FROM shop WHERE s_mail='$this->_u_cook_mail' AND s_cook_data='$this->_u_cook_data' ";
						$ress=mysql_query($sql) or die(mysql_error());
						$exists=mysql_num_rows($ress);
						if($exists==1)
							{
							$res=mysql_fetch_assoc($ress);
							$_SESSION['user_no']=$res['s_id'];
							$_SESSION['user_full_name']=$res['s_name'];
							$_SESSION['user_type']=2;
							$_SESSION['mail_adress']=$res['s_mail'];	
							}else{
							return false;
							}
							}
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

			private function is_insert_data()
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
							$_SESSION['login']=1;
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
										if( Setcookie("mail", $this->mail_adress, time()+60*60*24*100, "/") and Setcookie("info", $info_cook_data, time()+60*60*24*100, "/") )
											{
											$sql="UPDATE user_shop SET u_s_cook_data='$database_cook_data' , is_remember='1' WHERE u_s_id='$user_no'";
											$res=mysql_query($sql);	
											}
									}elseif($this->remember=='0')
									{
											$sql=$sql="UPDATE user_shop SET is_remember='0' WHERE u_s_id='$user_no'";
											$res=mysql_query($sql);	
									}
								}
				}

		public function login_func($mail,$pass,$user_type)
				{
					self::login_data_set($mail,$pass,$user_type);
					if(self::is_insert_data()==5)
						{
						self::control_data_login();
						if(	$this->login_status==1)
							{
								if(self::create_session())
									{
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
		public function create_form_data()
				{
				$_SESSION['f']=sha1(time().rand(0,32000));
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
				 public $p_img_way=Array();
				 public $adress;
				 public $place_id;
				 public $place_name;
				 public $place_number;
				 public $s_floor;
				 public $tel_number;
				 public $text_id;
				 
			function sent_values($mail,$type,$user_id)
						{
						$this->mail=$mail;
						$this->type=$type;
						$this->user_id=$user_id;
						}
			function get_profile_image()
				{
				if($this->user_id!=0 or $this->user_id!=null )
					{
						$sql="SELECT img_way FROM s_image WHERE shop_id='$this->user_id=$this->user_id' ORDER BY no";
						$sql=mysql_query($sql) or die(mysql_error());
							if(mysql_num_rows($sql)>0)
								{
								$i=1;
								while($res=mysql_fetch_assoc($sql)){
								$this->p_img_way[$i]=$res['img_way'];
								$i=$i+1;
								}
								}
					}else{
					return false;
					}
				}
			function get_shop_adress()
				{
				$sql="SELECT adress_text, tel_number FROM shop_adress WHERE s_id='$this->user_id'";
				$sql=mysql_query($sql) or die(mysql_error());
				if(mysql_num_rows($sql)==1)
					{
					$res=mysql_fetch_assoc($sql);
					$this->adress=$res['adress_text'];
					$this->tel_number=$res['tel_number'];
					}
				if($this->adress=="")
					{
					$sql1="SELECT place.address FROM place WHERE id=(SELECT shop.place_number FROM shop WHERE shop.s_id='$this->user_id')";
					$sql1=mysql_query($sql1) or die(mysql_error());
						if(mysql_num_rows($sql1)==1)
							{
							$res1=mysql_fetch_assoc($sql1);
							$this->adress=$res1['address'];
							}
					}
				}
			function check_and_get()
				{
					if($this->type=='1')
						{
						$sql="SELECT u_id, u_full_name, u_mail FROM user WHERE u_id='$this->user_id'";
						$sql=mysql_query($sql) or die(mysql_error());
							if(mysql_num_rows($sql)=='1')
								{
								$res=mysql_fetch_assoc($sql);
								$this->name=$res['u_full_name'];
								return 1;
								}else
								{
								return false;
								}

						}elseif($this->type=='2')
						{
						
							$sql="SELECT shop.s_id, shop.s_name, shop.s_mail,shop.latitude,shop.longitude,shop.place_number,shop.floor,place.name,place.text_id FROM shop LEFT JOIN place ON place.id=shop.place_number WHERE s_id='$this->user_id'";
							$sql=mysql_query($sql) or die(mysql_error());
							if(mysql_num_rows($sql)==1)
								{
								$res=mysql_fetch_assoc($sql);
								$this->latitude=$res['latitude'];
								$this->longitude=$res['longitude'];
								$this->name=$res['s_name'];
								$this->place_name=$res['name'];
								$this->place_number=$res['place_number'];
								$this->s_floor=$res['floor'];
								$this->text_id=$res['text_id'];
								return 2;
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
 				 private $user_type=0;

					function sent_user_type($type)
						{
						$this->user_type=$type;
						} 
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
				
					function check_id_pass()
						{
							if($this->user_type==1)
							{
								 if(self::check_user_id_pass()){ return true;}else{return false;}  
							}elseif($this->user_type==2){
								 if(self::check_shop_id_pass()){ return true;}else{return false;}	
							}else{
								return false;
							}
						}
					function check_mail_pass()
						{
							 if($this->user_type==1)
                                                        {
                                                                 if(self::check_user_mail_pass()){ return true;}else{return false;}  
                                                        }elseif($this->user_type==2){
                                                                if(self::check_shop_mail_pass()){ return true;}else{return false;}                         
                                                        }else{
                                                        	return false;
                                                        }
						}
					function check_shop_mail_pass()
						{
						$sql="SELECT s_mail,s_password FROM shop WHERE s_mail='$this->mail'";
						 $sql=mysql_query($sql) or die(mysql_error());
                                                        $res=mysql_fetch_assoc($sql);
                                                        if($res['s_password']==$this->sha1_pass)
                                                                {
                                                                return true;
                                                                }else
                                                                {
                                                                return false;
                                                                }
						}
					function check_shop_id_pass()
						{
						$sql="SELECT s_id,s_password FROM shop WHERE s_id='$this->user_id'";
                                                        $sql=mysql_query($sql) or die(mysql_error());
                                                        $res=mysql_fetch_assoc($sql);
                                                        if($res['s_password']==$this->sha1_pass)
                                                                {
                                                                return true;
                                                                }else
                                                                {
                                                                return false;
                                                                }
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
							$sql="UPDATE shop  SET s_name='$this->user_name'  WHERE s_id='$this->user_id' LIMIT 1";
								$res=mysql_query($sql) or die(mysql_error());
								if($res)
									{
										echo "<b>-</b>   İsim Bilginiz Değiştirildi...</br>";
										$_SESSION['user_full_name']=$this->user_name;
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
							$sql="UPDATE shop  SET s_password='$this->user_sha1_pass'  WHERE s_id='$this->user_id' LIMIT 1";
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
					$sql="UPDATE shop  SET s_mail='$this->user_mail'  WHERE s_id='$this->user_id' LIMIT 1";
					$res=mysql_query($sql) or die(mysql_error());
								if($res)
									{
										echo "<b>-</b>   E-Postanız Değiştirildi...</br>";
										$_SESSION['mail_adress']=$this->user_mail;
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
					$sql="SELECT s_mail,s_id FROM shop WHERE s_mail='$this->user_mail' and s_id!=$this->user_id";
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
		class change_pass
				{
				
				private $user_type;
				private $user_mail;
				private $user_id;
				private $user_name;
				private $change_pass_data;
				
				public function set_datas($user_mail,$user_type)
						{
						if($user_mail!=null and $user_type!=null)
							{
							$this->user_type=$user_type;
							$this->user_mail=$user_mail;
							}else
							{
							return false;
							}
						}
						
				public function control_datas()
						{
						if($this->user_type==1)
							{
							$sql="SELECT u_id,u_mail,u_full_name FROM user WHERE u_mail='$this->user_mail' LIMIT 1";
							}elseif($this->user_type==2)
							{
							$sql="SELECT s_id,s_mail,s_name FROM shop WHERE s_mail='$this->user_mail' LIMIT 1";
							}else{
							die("Bir Hata Oluştu...");
							}
						$sql=mysql_query($sql) or die('Bir Hata Oluştu...');
						if(mysql_num_rows($sql)=='1')
							{
								$res=mysql_fetch_assoc($sql);
								if($this->user_type==1)
								{
								$user_id=$res['u_id'];
								$this->user_name=$res['u_full_name'];
								}elseif($this->user_type==2){
								$user_id=$res['s_id'];
								$this->user_name=$res['s_name'];
								}else{
								$user_id=0;
								}
								
								if($this->user_id=$user_id )
								{
								return true;
								}else
								{
								return false;
								}
							}else
							{
							return false;
							}
						}
						
				public function control_active_pass_data($type)
						{
						$current_time=time();
						$referance_time=$current_time-1800;
						$sql="SELECT*FROM change_pass_data WHERE user_id='$this->user_id' and type='$type' and sent_time_to_mail>$referance_time";
						$sql=mysql_query($sql) or die('Hata oluştu...');
							if(mysql_num_rows($sql)>'0')
								{
								return true;
								}else
								{
								return false;
								}
						}
						
				public function send_to_mail_datas($type)
						{
						$current_time=time();
						$this->change_pass_data=sha1($current_time.$this->user_id.$this->user_mail);
						if(self::mail_sent())
							{
							$sql="INSERT INTO change_pass_data(user_id,type,change_pass_data,sent_time_to_mail) VALUES('$this->user_id','$type','$this->change_pass_data','$current_time')"; 
								if(mysql_query($sql))
									{
									return true;
									}else
									{
									return false;
									}
							}else
							{
							return false;
							}
						}
						
				private function mail_sent()
						{
						$phpmailler=new PHPMailer();
						$phpmailler->IsHTML(true);
						$phpmailler->IsSMTP();                                   // send via SMTP
						$phpmailler->Host     = ""; // SMTP servers
						$phpmailler->SMTPAuth = true;     // turn on SMTP authentication
						$phpmailler->Username = "";  // SMTP username
						$phpmailler->Password = ""; // SMTP password

						$phpmailler->From     = ""; // smtp kullanıcı adınız ile aynı olmalı
						$phpmailler->Fromname = "";
						$phpmailler->AddAddress($this->user_mail,$this->user_name);
						$phpmailler->Subject  = "Yeroks Şifre Değişimi";
						$phpmailler->Body     =  "<div style=\"width:100%;height:auto;padding:20px 0px 5px 0px;margin-bottom:20px;background-color:rgb(60,60,60);text-align:center;\"><img src=\"https://www.yeroks.com/img_files/yeroks_logo.png\" height=50 /></div><div style=\"font-size:24px;font-family:Arial;width:100%;padding:30px 0px 15px 0px; \"><center>Yeroks'dan Merhaba</center></br><center>Şifreni <a href=\"www.yeroks.com/change_pass_from_out.php?data=".$this->change_pass_data."\" >Buraya Tıklayarak</a> değiştirebilirsin...</center></div>";

						if($phpmailler->Send())
							{
							return true;
							}else
							{
							return false;
							}
						}
					public function  shop_mail_sent($shop_name,$shop_id,$shop_mail)
						{
						$phpmailler=new PHPMailer();
						$phpmailler->IsHTML(true);
						$phpmailler->IsSMTP();                                   // send via SMTP
						$phpmailler->Host     = ""; // SMTP servers
						$phpmailler->SMTPAuth = true;     // turn on SMTP authentication
						$phpmailler->Username = "";  // SMTP username
						$phpmailler->Password = ""; // SMTP password

						$phpmailler->From     = ""; // smtp kullanıcı adınız ile aynı olmalı
						$phpmailler->Fromname = "";
						$phpmailler->AddAddress($shop_mail,$shop_name);
						$phpmailler->Subject  = "Yeroks'a Hoşgeldiniz...";
						$phpmailler->Body     =  "<div style=\"width:100%;height:auto;padding:20px 0px 5px 0px;margin-bottom:20px;background-color:rgb(60,60,60);text-align:center;\">
<img src=\"https://www.yeroks.com/img_files/yeroks_logo.png\" height=50 >
</div>
<div style=\"font-size:17px;font-family:Arial;width:100%;padding:30px 0px 15px 0px;color:black;line-height:1.3; \"> 
 &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;Sayın <b> $shop_name </b> Mağazasının Yetkilisi, Yeroks'a Hoşgeldiniz...<br><br>
&#8226; &nbsp; Mağaza hesabınızda Yeroks'ta bulunan bütün ürün çeşitleri için reyon oluşturabilirsiniz. Oluşturduğunuz her reyona 99 çeşit ürün ekleme hakkınız bulunmaktadır. <br>
&#8226; &nbsp; Bize herhangi bir soru, sorun, görüş ve öneriniz için shop@yeroks.com e-posta adresimizden ulaşabilirsiniz. Lütfen bize ulaşmak için kullanacağınız e-posta adresinizin 
Yeroks'ta kayıtlı olan <b> $shop_mail </b> e-posta adresi olmasına dikkat ediniz.  <br><br>
 &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; İyi çalışmalar, Bol kazançlar dileriz...<br><br>
<center><b>Yeroks İletişim Takımı</b></center>
</div>";

						if($phpmailler->Send())
							{
							echo "mail gonderildi...";
							return true;
							}else
							{
							echo "Bir sorun olustu...";
							return false;
							}
						}
				};
		class pass_change_form_not_login
				{
					private $user_id;
					private $data;
					private $change_pass_id;
					
					public function set_data($data)
						{
						$this->data=$data;
						}
						
					public function data_control()
						{
						$sql="SELECT*FROM change_pass_data WHERE change_pass_data='$this->data' ";
						$sql=mysql_query($sql) or die("HATA!");
						if(mysql_num_rows($sql)==1)
							{
								$res=mysql_fetch_assoc($sql);
								if(self::get_time_diff($res['sent_time_to_mail'])<1500)
									{
									$this->change_pass_id=$res['id'];
									$this->user_id=$res['user_id'];
									return true;
									}else
									{
									return false;
									}
							}else
							{
								return false;
							}
						}
				
					private function get_time_diff($sent_time)
						{
						$current_time=time();
						$time_diff=$current_time-$sent_time;
						return $time_diff;
						}
						
					public function set_pass($password,$type)
						{ 
							if($type==1)
							{
							$sql="UPDATE user SET u_password='$password' WHERE u_id=' $this->user_id ' LIMIT 1";
							}elseif($type==2){
							$sql="UPDATE shop SET s_password='$password' WHERE s_id=' $this->user_id ' LIMIT 1";
							}else{
							return false;
							}
						
						if(mysql_query($sql) or die(mysql_error()))
							{
							return true;
							}else
							{
							return false;
							}
						}
					public function set_change_pass_table()
						{
						$sql="DELETE FROM change_pass_data WHERE id='$this->change_pass_id' ";
						if(mysql_query($sql))
							{
							return true;
							}else
							{
							return false;
							}
						
						}

				};
		
		
		
		class search_funtions
				{
					private $yield_type_id;
					private $property;
					private $property_value;
					private $is_add_more=Array();
					private $y_id;
					private $prop_id=Array();
					private $prop_value_id=Array();
					private $price;
					private $orders;
					private $y_name='';
					private $currency;
					private $extra_prop=Array();
					private	$extra_value=Array();
					private $about;
					
					public function data_get($yield_type,$y_id)
					{
					$this->yield_type_id=$yield_type;
					$this->y_id=$y_id;
					}
					
					public function control_max_y_number($shop_id,$y_type_id)
					{
					$sql="SELECT y_count FROM shop_y_list WHERE shop_id='$shop_id' and y_type_id=$y_type_id";
					$sql=mysql_query($sql) or die(mysql_error());
						if(mysql_num_rows($sql)==0)
							{
							return true;
							}else{
								$res=mysql_fetch_assoc($sql);
								if($res['y_count']<99)
									{
									return true;
									}else{
									return false;
									}
							}
					}
					
					
					
					public function get_yield_props($shop_id)
					{
					$table=yield_order::select_table($this->yield_type_id);
					$sql="SELECT y_name,props,price,currency,orders FROM $table WHERE y_id=$this->y_id and y_name_id=$this->yield_type_id and s_id=$shop_id";
					$sql=mysql_query($sql) or die(mysql_error());
					if(mysql_num_rows($sql)==0)
						{
						die("Böyle Bir Ürün Bulunamadı...");
						$this->yield_type_id=0;
						$this->y_id=0;
						}
					$res=mysql_fetch_assoc($sql);
					$this->price=$res['price'];
					$this->y_name=$res['y_name'];
					$this->currency=$res['currency'];
					$props=$res['props'];
					$this->orders=$res['orders'];
					self::get_prop_array($res['props']);
					}
					private function get_prop_array($json_prop)
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
										$this->prop_value_id[]=$prop_value;
										//degerid
										$this->prop_id[]=$prop;
										}
								$i=$i+1;
								if($i>50){ break; } // max prop loop number is 50;										
								}
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
					
					public function get_plus_props()
					{
					$sql="SELECT id,y_id, prop, value FROM y_extra_props WHERE y_type_id='$this->yield_type_id' and y_id='$this->y_id' ";
					$sql=mysql_query($sql) or die(mysql_error());
					$i=0;
					while($res=mysql_fetch_assoc($sql))
						{
						$this->extra_prop[$res['y_id']][$res['id']]=$res['prop'];
						$this->extra_value[$res['y_id']][$res['id']]=$res['value'];
						$i=$i+1;
						}
					}
					
					public function get_about()
					{
					$sql="SELECT y_explain FROM y_explain_text WHERE y_type_id='$this->yield_type_id' and y_id='$this->y_id' ";
					$sql=mysql_query($sql) or die(mysql_error());
					if(mysql_num_rows($sql)>0){
					$res=mysql_fetch_assoc($sql);
					$this->about=$res['y_explain'];
					}else{
					$this->about='';
					}
					
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
					echo "<input type=\"text\" value=\"".$this->y_name."\" onkeydown=\"new_yield_typing()\" onkeyup=\"new_yield_typing()\" id=\"new_yield_name_input\" name=\"new_yield_name_input\" />";
					echo "</div>";
					}
					public function press_property_for_new_yield()
					{
					
					$prop_counter=0;
					$s_prop_counter=0;
					echo "<form name=\"props\" id=\"props_list\" style=\"display: inline;\">";
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
						echo "<div id=\"prop_set_area\">";
						echo "<div id=\"new_y_prop\">Ürün Fiyatı Nedir?:</div>";
						echo "<input type=\"text\" name=\"price\" id=\"y_price_input\" placeholder=\"2.33 vb\"  /><select id=\"price_type_input\"><option value=1>TL</option>><option value=2>Dolar</option>><option value=3>Euro</option></select>";
						echo "</div>";
						echo "</form>";
						echo "<form name=\"plus_prop\" id=\"plus_prop\">";
						echo "<div id=\"plus_prop_area\">";
						echo "<div id=\"plus_prop_open\" onclick=\"open_plus_prop();\">Yeni Özellik Girişi Aç</div><p>(Kendiniz İstediğiniz Ürün Özelliğini Girebilirsiniz)</p>";
						echo "<div id=\"plus_prop_open_area\"></div>";
						echo "</div>";
						echo "</form>";
						echo "<div id=\"product_explain_area\"><textarea placeholder=\"Ürün Açıklaması Girebilirsin...\" id=\"product_explain\" name=\"product_explain\" cols=\"40\" rows=\"5\"></textarea></div>";
						echo "<form name=\"y_images\" id=\"y_images\" style=\"display: inline;\">";
						echo "<div id=\"prop_set_area\">";
						echo "<div id=\"new_y_prop\">Ürün Resmi Ekle:</div>";
						echo "<div id=\"img_show\"></div>";
						echo "<div class=\"file_area\"><input id=\"y_image_1\" onchange=\"control_file('y_image_1')\" type=\"file\" name=\"y_image[]\"/></div><div class=\"file_area\"><input id=\"y_image_2\" onchange=\"control_file('y_image_2')\" type=\"file\" name=\"y_image[]\"/></div><div class=\"file_area\"><input id=\"y_image_3\" onchange=\"control_file('y_image_3')\" type=\"file\" name=\"y_image[]\"/></div>";
						echo "</div>";
						echo "</form>";
						echo "<div Onclick=\"new_yield_step_one()\" id=\"new_yield_enter_buton\">Kaydet</div>";
						
						
					}
				
				//////////////////////////////////////////////////////////////////////
				//$this->props_last[]=$prop_value;
				//$this->props_data_last[]=$prop;
				public function press_property_for_set_yield()
					{
					
					$prop_counter=0;
					$s_prop_counter=0;
					echo "<form name=\"props\" id=\"props_list\" style=\"display: inline;\">";
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
											if(in_array( $res2['v_id'],$this->prop_value_id))
											{
												echo "<option selected class=\"value\"  value=\"".$res2['v_id']."\">".$res2['v_name_shop']."</option>";
											}else{
												echo "<option class=\"value\"  value=\"".$res2['v_id']."\">".$res2['v_name_shop']."</option>";
											}
										
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
											if(in_array( $res2['v_id'],$this->prop_value_id))
											{
												echo "<div class=\"p_v_more\" onclick=\"onchange_div(".$res1['p_id'].",'".$res2['v_name_shop']."',".$res2['v_id'].")\" id=\"d_".$res1['p_id']."_prop_".$res2['v_id']."\">".$res2['v_name_shop']."<img height=40 style=\"display:block;\" src=\"img_files/okay1.png\"/></div>";
												echo "<input type=\"hidden\" name=\"p_".$res1['p_id']."_v_more_".$res2['v_id']."\"  id=\"p_".$res1['p_id']."_v_more_".$res2['v_id']."\" class=\"p_".$res1['p_id']."_v_m_".$value_counter."\" value=\"".$res2['v_id']."\" /> ";
												$value_counter=$value_counter+1;
											}else{
												echo "<div class=\"p_v_more\" onclick=\"onchange_div(".$res1['p_id'].",'".$res2['v_name_shop']."',".$res2['v_id'].")\" id=\"d_".$res1['p_id']."_prop_".$res2['v_id']."\">".$res2['v_name_shop']."<img height=40 src=\"img_files/okay1.png\"/></div>";
												echo "<input type=\"hidden\" name=\"p_".$res1['p_id']."_v_more_".$res2['v_id']."\"  id=\"p_".$res1['p_id']."_v_more_".$res2['v_id']."\" class=\"p_".$res1['p_id']."_v_m_".$value_counter."\" value=0 /> ";
												$value_counter=$value_counter+1;
											}
										}	
									}
									
								echo "<input type=\"hidden\" name=\"s_prop_".$s_prop_counter."_value_counter\" id=\"s_prop_".$s_prop_counter."_value_counter\" value=".$value_counter." />";		
								echo "<input type=\"hidden\" name=\"s_prop_".$s_prop_counter."_data\" id=\"s_prop_".$s_prop_counter."_data\" value=".$res1['p_id']." />";
								$s_prop_counter=$s_prop_counter+1;
						}
						
						echo "</div>";
						mysql_data_seek($this->property_value,0);
						}
						echo "<input type=\"hidden\" name=\"yield_name_data\" id=\"yield_name_data\" value='".$this->y_name."' />";
						echo "<input type=\"hidden\" name=\"yield_type_data\" id=\"yield_type_data\" value=".$this->yield_type_id." />";
						echo "<input type=\"hidden\" name=\"yield_data\" id=\"yield_data\" value=".$this->y_id." />";
						echo "<input type=\"hidden\" name=\"prop_counter\" id=\"prop_counter\" value=".$prop_counter." />";
						echo "<input type=\"hidden\" name=\"s_prop_counter\" id=\"s_prop_counter\" value=".$s_prop_counter."  />";
						echo "<div id=\"prop_set_area\">";
						echo "<div id=\"new_y_prop\">Ürün Fiyatı Nedir?:</div>";
						echo "<input type=\"text\" value=\"".$this->price."\" name=\"price\" id=\"y_price_input\" placeholder=\"2.33 vb\"  /><select id=\"price_type_input\"><option value=1>TL</option>><option value=2>Dolar</option>><option value=3>Euro</option></select>";
						echo "</div>";
						echo "</form>";
						echo "<form name=\"plus_prop\" id=\"plus_prop\">";
						echo "<div id=\"plus_prop_area\">";
						echo "<div id=\"plus_prop_open\" onclick=\"open_plus_prop();\">Yeni Özellik Girişi Aç</div><p>(Kendiniz İstediğiniz Ürün Özelliğini Girebilirsiniz)</p>";
						$i=0;
						if (array_key_exists($this->y_id,  $this->extra_prop)) 
									{
										foreach ( $this->extra_prop[$this->y_id] as $p_id => $p_name)
											{
												echo "<div class=\"plus_prop_input_area\" id=\"plus_prop_e_".$p_id."\">
												<input name=\"plus_prop_name[]\" class=\"plus_prop_name\" type=\"text\" placeholder=\"Özellik İsmi\" value=\"".$this->extra_prop[$this->y_id][$p_id]."\"/>.
												<input name=\"plus_prop_value[]\" class=\"plus_prop_value\" type=\"text\" placeholder=\"Özellik Değeri\" value=\"".$this->extra_value[$this->y_id][$p_id]."\">
												<div id=\"delete_plus_prop\" onclick=\"$('#plus_prop_e_".$p_id."').remove();reduce_opening_counter();\">Sil!</div>
												</div>";
												$i=$i+1;
											}
									
									}
						echo "<script type=\"text/javascript\">opening_counter=".$i.";plus_prop_counter=".$i.";</script>";
						echo "<div id=\"plus_prop_open_area\"></div>";
						echo "</div>";
						echo "</form>";
						self::press_images_for_set_yield();
						echo "<div id=\"product_explain_area\"><textarea placeholder=\"Ürün Açıklaması Girebilirsin...\" id=\"product_explain\" name=\"product_explain\" cols=\"40\" rows=\"5\">".$this->about."</textarea></div>";
						echo "<div id=\"buttons_area\"><div Onclick=\"yield_set_one()\" id=\"yield_set_button\">Kaydet</div><div Onclick=\"hidebackground()\" id=\"yield_set_give_up_button\">İptal</div></div>";
						
						
					}
					function press_images_for_set_yield()
					{
					$sql="SELECT img_id,no,img_way FROM y_image WHERE y_id=$this->y_id and y_type_id=$this->yield_type_id ORDER BY no ASC";
					$sql=mysql_query($sql) or die(mysql_error());
					$i=1;
					echo "<form name=\"y_images\" id=\"y_images\" style=\"display: inline;\">";
					echo "<div id=\"prop_set_area\">";
					echo "<div id=\"new_y_prop\">Resimleri Düzenle:</div>";
					while($res=mysql_fetch_assoc($sql))
						{
						echo "<div id=\"img_set_area\"><div id=\"img_set\"><img src=\"y_img_small/".$res['img_way']."\"/></div>";
						echo "<div id=\"file_area_set\"><input id=\"y_image_".$i."\" onchange=\"control_file('y_image_".$i."')\" type=\"file\" name=\"y_image_".$i."\"/></div>";
						echo "<div id=\"img_delete_box\">Sil:<input type=\"checkbox\" id=\"delete_".$i."\"></div>"; 
						echo "</div>";
						$i=$i+1;
						}
					for( ;$i <= 3; $i++)
						{
						echo "<div id=\"img_set_area\"><div id=\"img_set\"></div>";
						echo "<div id=\"file_area_set\"><input id=\"y_image_".$i."\" onchange=\"control_file('y_image_".$i."')\" type=\"file\" name=\"y_image_".$i."\"/></div>";
						echo "</div>";
						}
					
					echo "</div>";
					echo "</form>";
					
					}
						
				
				};
				
				
				
				
		class user_info
		{
			public $user_id;
			public $user_name;
			public $user_latitude;
			public $user_longitude;
			public $user_floor;
			public $user_address;
			public $shopping_center_name;
			public $shopping_center_id;
			
		
		public function get_shop_info($shop_id)
			{
			$sql="SELECT shop.s_name,shop.latitude, shop.longitude, shop.floor, shop.place_number,place.name,place.address
			FROM shop LEFT JOIN place ON place.id=shop.place_number WHERE s_id='$shop_id'";
			$sql=mysql_query($sql) or die(mysql_error());
				if(mysql_num_rows($sql)==1)
					{
					$res=mysql_fetch_assoc($sql);
					$this->user_name=$res['s_name'];
					$this->user_latitude=$res['latitude'];
					$this->user_longitude=$res['longitude'];
					$this->user_floor=$res['floor'];
					$this->shopping_center_id=$res['place_number'];
					$this->shopping_center_name=$res['name'];
						if($res['place_number']!=0){
						$this->user_address=$res['address'];
						}else{
						$sql="SELECT adress_text FROM shop_adress WHERE s_id=$shop_id";
						$sql=mysql_query($sql) or die(mysql_error());
						$res1=mysql_fetch_assoc($sql);
						$this->user_address=$res1['adress_text'];
						}
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
		
		class yerok_id_handler
		{
			public $yerok_id;//y_type_id 
			public $inserted_y_id=0;//inserted product id
			public $yerok_right_id;
			public $yerok_left_id;
			public $yerok_child_or_father; 
			public $y_ids=Array();//y_ids of  selected products...
			public $y_icon=1;
			public $props_asking=Array();
			public $props_data=Array();
			public $props_data_last=Array();
			public $props_last=Array();
			public $props_y_id=Array();
			public $y_first_image=Array();
			private $prop_sql='';
			public $shop_id;
			private $table_name='y1';
			public $props_correct=Array();
			public $props_data_correct=Array();
			//new_yield_values_sql
			private $n_y_v_sql;
			
			///prop_press_arrays
			public $prop_text=Array();
			public $value_text=Array();
			public $show_y_name=Array();
			public $show_y_prop=Array();
			public $y_data_ids=Array();
			public $price=Array();
			public $order=Array();
			public $extra_prop=Array();
			public $extra_value=Array();
			
			
			//price values
			public $price_val; //exact price value
			public $pr_min_val;//price min value
			public $pr_max_val; //price max value
			public $prop_min_val; // props min price value
			public $prop_max_val; // props max price value
			public $pr_v_id;// price value id
			public $exact_min_pr_val;
			public $exact_max_pr_val;
			public $exact_price;
			
			
			
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
								$table_select=new table_select();
								$this->table_name=$table_select->select_table($this->yerok_id);
				}
			
			public function show_c_name(){
			$sql="SELECT*FROM y_name_list Where y_type_id='$this->yerok_id' ";
			$sql=mysql_query($sql);
			$res=mysql_fetch_assoc($sql);
			return $res['y_name'];
			}
			public function search_right_left_id()
				{
				$sql="SELECT*FROM y_name_list Where y_type_id='$this->yerok_id' ";
				$sql=mysql_query($sql);
				$res=mysql_fetch_assoc($sql);
				$this->yerok_child_or_father=$res['is_y'];
				$this->yerok_left_id=$res['child_left_id'];
				$this->yerok_right_id=$res['child_right_id'];
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
					$sql="SELECT*FROM y_prop_value_contact WHERE y_type_id='$this->yerok_id' and y_p_id='".$this->props_data_last[$i]."' and y_v_id='".$this->props_last[$i]."'";
					$sql=mysql_query($sql) or die(mysql_error());
						if(mysql_num_rows($sql) > 0)
							{
							$this->props_correct[$count]=$this->props_last[$i];
							$this->props_data_correct[$count]=$this->props_data_last[$i];							
							$count=$count+1;
							}
					unset($this->props_last[$i]);
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
							$this->n_y_v_sql=$this->n_y_v_sql.$this->props_data_correct[$i].":".$this->props_correct[$i];
							}else
							{
							$this->n_y_v_sql=$this->n_y_v_sql.",".$this->props_data_correct[$i].":".$this->props_correct[$i];
							}
					}
				$this->n_y_v_sql=$this->n_y_v_sql."}"; 
				}
			public function enter_new_yield_in_database($yerok_name,$latitude,$longitude,$currency,$order)
				{
				self::get_new_yield_values_sql();
				if($currency!=1 and $currency!=2 and $currency!=3)
					{
						$currency=1; 
					}	
				if($this->exact_price==0)
					{
						$currency=0;
					}
				$sql="INSERT INTO ".$this->table_name."(y_name,s_id,y_name_id,latitude,longitude,props,min_price,price,max_price,currency,orders) VALUES('$yerok_name','$this->shop_id','$this->yerok_id','$latitude','$longitude','$this->n_y_v_sql','$this->exact_min_pr_val','$this->exact_price','$this->exact_max_pr_val','$currency','$order')";
				$sql=mysql_query($sql) or die(mysql_error());
					if($sql)
						{
						echo "Yeni Ürün girildi...<br><br><div id=\"show_yield\">".$yerok_name."</div>";
						$this->inserted_y_id=mysql_insert_id();
						}else
						{
						echo "Giriş Sırasında Hata Oluştu!";
						}
				
				}
			public function set_yield_in_database($y_id,$s_id,$yerok_name,$currency)
				{
				self::get_new_yield_values_sql();
				if($currency!=1 or $currency!=2 or $currency!=3)
					{
						$currency=1; 
					}
				if($this->exact_price==0)
					{
						$currency=0;
					}
				$sql="UPDATE ".$this->table_name." SET y_name='$yerok_name' , props='$this->n_y_v_sql' , min_price='$this->exact_min_pr_val' , price='$this->exact_price' , max_price='$this->exact_max_pr_val' , currency='$currency' WHERE y_id='$y_id' and s_id='$s_id' LIMIT 1";
				if(mysql_query($sql))
					{
					echo "Ürün Düzenlendi...<br><br><div id=\"show_yield\">".$yerok_name."</div>";
					return true;
					}else{
					echo "Giriş Sırasında Hata Oluştu!";
					return false;
					}
				}
			public function get_props_to_one_y_show($y_id)
				{
				$prop_values=Array();
				$props_y_id=Array();
				$select_char = array('.',',',':');
				$delete_char = array('','','');
				$sql="SELECT y_id,y_name,s_id,y_name_id,props,price,currency,".$this->table_name.".orders FROM ".$this->table_name." WHERE y_name_id='$this->yerok_id' and y_id='$y_id' ORDER BY ".$this->table_name.".orders DESC ";
				$this->y_ids[]=null;
				$sql=mysql_query($sql) or die(mysql_error());
				if(mysql_num_rows($sql)==0){
				return false;
				}else{
				$res=mysql_fetch_assoc($sql);
						$prop_values[$res['y_id']]=$res['props'];
						$this->order[$res['y_id']]=$res['orders'];
						$this->show_y_name[$res['y_id']]=$res['y_name'];
						$this->y_ids[]=$res['y_id'];
						$this->price[$res['y_id']][0]=$res['price'];
						$this->price[$res['y_id']][1]=$res['currency'];
						$this->shop_id=$res['s_id'];
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
					$sql="SELECT y_id, prop, value FROM y_extra_props WHERE y_type_id='$this->yerok_id' and y_id=$y_id";
					$sql=mysql_query($sql) or die(mysql_error());
					$i=0;
					while($res=mysql_fetch_assoc($sql))
						{
						$y_id=$res['y_id'];
						$this->extra_prop[$y_id][$i]=$res['prop'];
						$this->extra_value[$y_id][$i]=$res['value'];
						$i=$i+1;
						}					
					return true;
				}
			public function get_exp_data($y_id)
				{
				$sql="SELECT y_explain FROM y_explain_text WHERE y_type_id='$this->yerok_id' and y_id=$y_id";
				$sql=mysql_query($sql) or die(mysql_error());
					if(mysql_num_rows($sql)==1){ 
						$res=mysql_fetch_assoc($sql);
						return $res['y_explain'];
					}else{
						return "";
					}
				}
			public function get_yields_datas()
				{
				$sql="SELECT y_id,y_name,s_id,y_name_id,props,price,currency,".$this->table_name.".orders FROM ".$this->table_name." WHERE s_id='$this->shop_id' and y_name_id='$this->yerok_id' ORDER BY ".$this->table_name.".orders DESC ";
				$this->y_ids[]=null;
				$sql=mysql_query($sql) or die(mysql_error());
				if(mysql_num_rows($sql)==0){ 
				return false;
				}else{
					while($res=mysql_fetch_assoc($sql))
						{
						$this->order[$res['y_id']]=$res['orders'];
						$this->show_y_name[$res['y_id']]=$res['y_name'];
						$this->y_ids[]=$res['y_id'];
						$this->price[$res['y_id']][0]=$res['price'];
						$this->price[$res['y_id']][1]=$res['currency'];
						}
						return true;
				}
				}
			public function get_props_to_y_show()
				{
				$prop_values=Array();
				$props_y_id=Array();
				$select_char = array('.',',',':');
				$delete_char = array('','','');
				$sql="SELECT y_id,y_name,s_id,y_name_id,props,price,currency,".$this->table_name.".orders FROM ".$this->table_name." WHERE s_id='$this->shop_id' and y_name_id='$this->yerok_id' ORDER BY ".$this->table_name.".orders DESC ";
				$this->y_ids[]=null;
				$sql=mysql_query($sql) or die(mysql_error());
				if(mysql_num_rows($sql)==0){ 
				return false;
				}else{
					while($res=mysql_fetch_assoc($sql))
						{
						$prop_values[$res['y_id']]=$res['props'];
						$this->order[$res['y_id']]=$res['orders'];
						$this->show_y_name[$res['y_id']]=$res['y_name'];
						$this->y_ids[]=$res['y_id'];
						$this->price[$res['y_id']][0]=$res['price'];
						$this->price[$res['y_id']][1]=$res['currency'];
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
					return true;
					}
				}
			public function get_y_icon()
				{
				$sql="SELECT icon_name FROM y_name_list WHERE y_type_id='$this->yerok_id'";
				$sql=mysql_query($sql) or die(mysql_error());
				$res=mysql_fetch_assoc($sql);
				$this->y_icon=$res['icon_name'].".png";
				}
			public function get_y_first_images()
				{
				$y_ids = join(', ', $this->y_ids);
				$y_ids=ltrim($y_ids,",");
				$y_ids=rtrim($y_ids,",");
				$sql="SELECT y_id,y_type_id,img_way,no FROM y_image  WHERE y_type_id='$this->yerok_id' and y_id IN ($y_ids) ";
				$sql=mysql_query($sql) or die(mysql_error());
				//$this->y_first_image[]=null;
				while($res=mysql_fetch_assoc($sql))
					{
						$this->y_first_image[$res['y_id']][$res['no']]=$res['img_way'];
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
					$y_ids = join(', ', $this->y_ids);
					$y_ids=ltrim($y_ids,",");
					$y_ids=rtrim($y_ids,",");
					$sql="SELECT y_id, prop, value FROM y_extra_props WHERE y_type_id='$this->yerok_id' and y_id IN ($y_ids)";
					$sql=mysql_query($sql) or die(mysql_error());
					$i=0;
					while($res=mysql_fetch_assoc($sql))
						{
						$y_id=$res['y_id'];
						$this->extra_prop[$y_id][$i]=$res['prop'];
						$this->extra_value[$y_id][$i]=$res['value'];
						$i=$i+1;
						}
				}
				
				
			public function press_yields_from_list()
				{
						foreach ($this->show_y_name as $y_id => $y_name)
							{
								echo "<div id=\"show_y_special\" class=\"show_y_special_".$y_id."_".$this->yerok_id."\">";
																echo "<div class=\"yield_set\" id=\"yield_set_".$y_id."_".$this->yerok_id."\" ><div class=\"yield_set_change\" id=\"yield_set_change_".$y_id."_".$this->yerok_id."\" onclick=\"yield_set_change(".$y_id.",".$this->yerok_id.");\">Düzenle</div> <div class=\"b_yield_set_delete\" id=\"yield_set_delete_".$y_id."_".$this->yerok_id."\" onclick=\"yield_set_delete(".$y_id.",".$this->yerok_id.")\" >Sil</div></div>";

								echo "<div id=\"y_s_image\">";
								if(array_key_exists( $y_id, $this->y_first_image))
										{
										if (array_key_exists( 1, $this->y_first_image[$y_id])) 
											{
											echo "<div id=\"y_s_image_big\" style=\"background-image:url('y_img_big/".$this->y_first_image[$y_id][1]."')\" ></div>";
											}else{
											echo "<div id=\"y_s_image_big\"><img src=\"https://www.yeroks.com/y_img_big/".$this->y_icon."\" height=70 /></div>";
											}
										if (array_key_exists( 2, $this->y_first_image[$y_id])) 
											{
											echo "<div id=\"y_s_image_small\" style=\"background-image:url('y_img_big/".$this->y_first_image[$y_id][2]."')\" ></div>";
											}else{
											echo "<div id=\"y_s_image_small\"><img src=\"https://www.yeroks.com/y_img_big/".$this->y_icon."\" height=70 /></div>";
											}	
										if (array_key_exists( 3, $this->y_first_image[$y_id])) 
											{
											echo "<div id=\"y_s_image_small\" style=\"background-image:url('y_img_big/".$this->y_first_image[$y_id][3]."')\" ></div>";
											}else{
											echo "<div id=\"y_s_image_small\"><img src=\"https://www.yeroks.com/y_img_big/".$this->y_icon."\" height=70 /></div>";
											}	
											
										}else{
										echo "<div id=\"y_s_image_big\"><img src=\"https://www.yeroks.com/y_img_big/".$this->y_icon."\" height=70 /></div>";
										echo "<div id=\"y_s_image_small\"><img src=\"https://www.yeroks.com/y_img_big/".$this->y_icon."\" height=70 /></div>";
										echo "<div id=\"y_s_image_small\"><img src=\"https://www.yeroks.com/y_img_big/".$this->y_icon."\" height=70 /></div>";
										}
								echo "</div>";
								echo "<div id=\"y_special_area\">";
								echo "<div id=\"y_special_name\">".$y_name."</div>";
									echo "<table id=\"s_y_list\" align=center><tbody>";
									/*
									if (array_key_exists($y_id, $this->y_data_ids)) 
									{
											foreach ( $this->y_data_ids[$y_id] as $q_id => $value_id)
												{
												echo  "<tr><td>".$this->prop_text[$q_id].":</td><td>";
													foreach($value_id as $v_id =>$vv_id)
														{
														echo "<div id=\"prop_vis\">".$this->value_text[$v_id]."</div>";
														}
												echo "</td></tr>";
												}
									if (array_key_exists($y_id,  $this->extra_prop)) 
									{
											foreach ( $this->extra_prop[$y_id] as $p_id => $p_name)
												{
												echo  "<tr><td>".$this->extra_prop[$y_id][$p_id].":</td><td>";
												echo "<div id=\"prop_vis\">".$this->extra_value[$y_id][$p_id]."</div>";
												echo "</td></tr>";
												}
									}	
									}else
									{
									echo "<div>Özellik Bulunamadı...</div>";
									}	
*/									
										if($this->price[$y_id][0]!=0){
										echo  "<tr><td>Fiyat:</td><td><div id=\"prop_vis\">".$this->price[$y_id][0];
										if($this->price[$y_id][1]==1){ echo " TL</div></td></tr>";}
										if($this->price[$y_id][1]==2){ echo " Dolar</div></td></tr>";}
										if($this->price[$y_id][1]==3){ echo " Euro</div></td></tr>";}
										}
										echo "</tbody></table>";
									
								?>
								</div>
								<?php	
								echo "</div>";
							}		
				}
			public function press_yields_for_web()
				{
						foreach ($this->show_y_name as $y_id => $y_name)
							{
								echo "<div id=\"show_y_special\" class=\"show_y_special_".$y_id."_".$this->yerok_id."\">";
								echo "<div id=\"show_lb_icon\" onclick=\"show_pr_lb(".$this->yerok_id.",".$y_id.")\"></div>";
								?>
								<?php
								//echo "<div id=\"y_image\"><img src=\"http://www.yeroks.com/y_img_small/".$this->y_first_image[$y_id][1]."\" height=100 /><img src=\"http://www.yeroks.com/y_img_small/".$this->y_first_image[$y_id][2]."\" height=100 /><img src=\"http://www.yeroks.com/y_img_small/".$this->y_first_image[$y_id][3]."\" height=100 /></div>";
								echo "<div id=\"s_y_image\"";
								if(array_key_exists( $y_id, $this->y_first_image))
										{
										if (array_key_exists( 1, $this->y_first_image[$y_id])) 
											{
											echo "style=\"background-image:url('y_img_big/".$this->y_first_image[$y_id][1]."')\" >";
											}else{
											echo "><img src=\"https://www.yeroks.com/y_img_big/".$this->y_icon."\" height=70 />";
											}
										}else{
										echo "><img src=\"https://www.yeroks.com/y_img_big/".$this->y_icon."\" height=70 />";
										}
								echo "</div>";
								echo "<div id=\"y_special_area\">";
								echo "<div id=\"y_special_name\">".$y_name."</div>";
								
										echo "<table id=\"s_y_list\" align=center><tbody>";
											/*
											foreach ( $this->y_data_ids[$y_id] as $q_id => $value_id)
												{
												echo  "<tr><td>".$this->prop_text[$q_id].":</td><td>";
													foreach($value_id as $v_id =>$vv_id)
														{
														echo "<div id=\"prop_vis\">".$this->value_text[$v_id]."</div>";
														}
												echo "</td></tr>";
												}
												*/
										if($this->price[$y_id][0]!=0){
										echo  "<tr><td></td><td><div id=\"prop_vis\">".$this->price[$y_id][0];
										if($this->price[$y_id][1]==1){ echo " TL</div></td></tr>";}
										if($this->price[$y_id][1]==2){ echo " Dolar</div></td></tr>";}
										if($this->price[$y_id][1]==3){ echo " Euro</div></td></tr>";}
										}
										echo "</tbody></table>";
								?>
								</div>
								<?php	
								echo "</div>";
							}		
				}
			public function enter_y_type_in_database($order)
				{
				$sql="INSERT INTO shop_y_list(shop_id, y_type_id,y_count,orders) VALUES('$this->shop_id','$this->yerok_id','1','$order' )
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
								$prop = str_replace($select_char,$delete_char,$aa['0']);
								$prop_value = str_replace($select_char,$delete_char,$aa['1']);
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
				
				
					if($this->yerok_child_or_father==1)
						{
							
								if($this->yerok_id==0)
								{
								$sql="SELECT ".$this->table_name.".y_id, ".$this->table_name.".y_name,".$this->table_name.".latitude,".$this->table_name.".longitude,( 6371 * acos( cos( radians($lat) ) * cos( radians( ".$this->table_name.".latitude ) ) * cos( radians( ".$this->table_name.".longitude ) - radians($long) ) + sin
( radians($lat) ) * sin( radians( ".$this->table_name.".latitude ) ) ) ) AS distance,".$this->table_name.".s_id,".$this->table_name.".place_id,shop.s_id,shop.s_name,place.id,place.name FROM ".$this->table_name." LEFT JOIN (place,shop) ON (".$this->table_name.".place_id=place.id and shop.s_id=".$this->table_name.".s_id)   WHERE ".$this->prop_sql."  GROUP BY ".$this->table_name.".s_id HAVING distance < 30  ORDER BY distance LIMIT
$down_no,$up_no";
								}else
								{
						$sql="SELECT ".$this->table_name.".y_id, ".$this->table_name.".y_name,".$this->table_name.".latitude,".$this->table_name.".longitude,( 6371 * acos( cos( radians($lat) ) * cos( radians( ".$this->table_name.".latitude ) ) * cos( radians( ".$this->table_name.".longitude ) - radians($long) ) + sin
( radians($lat) ) * sin( radians( ".$this->table_name.".latitude ) ) ) ) AS distance,".$this->table_name.".s_id,".$this->table_name.".place_id,shop.s_id,shop.s_name,place.id,place.name FROM ".$this->table_name." LEFT JOIN shop ON shop.s_id=".$this->table_name.".s_id LEFT JOIN place ON ".$this->table_name.".place_id=place.id   WHERE y_name_id ='$this->yerok_id' ".$this->prop_sql."  GROUP BY ".$this->table_name.".s_id  HAVING distance < 30 ORDER BY distance  LIMIT
$down_no,$up_no";
								}
						}else
						{
								
						$sql="SELECT ".$this->table_name.".y_id, ".$this->table_name.".y_name,".$this->table_name.".latitude,".$this->table_name.".longitude,( 6371 * acos( cos( radians($lat) ) * cos( radians( ".$this->table_name.".latitude ) ) * cos( radians( ".$this->table_name.".longitude ) - radians($long) ) + sin
( radians($lat) ) * sin( radians( ".$this->table_name.".latitude ) ) ) ) AS distance,".$this->table_name.".s_id,".$this->table_name.".place_id,shop.s_id,shop.s_name,place.id,place.name FROM ".$this->table_name." LEFT JOIN (place,shop) ON (".$this->table_name.".place_id=place.id and shop.s_id=".$this->table_name.".s_id)  WHERE (y_name_id <'$this->yerok_right_id' and y_name_id > '$this->yerok_left_id') ".$this->prop_sql." GROUP BY ".$this->table_name.".s_id HAVING distance < 30  ORDER BY distance   LIMIT
$down_no,$up_no";
						}
					$sql=mysql_query($sql) or die(mysql_error());
					if(mysql_num_rows($sql)==0)
						{
						if($page_no==1 and $this->yerok_id==0)
							{
								echo "<p>Ürün Seçerek Arama Yapabilirsin...</p>";
								echo "<script type=\"text/javascript\">  is_this_final_page=true;</script>";
							}elseif($page_no==1)
							{
								echo "<p>Çevrenizde bu ürün bulunamadı...</p>";
								echo "<script type=\"text/javascript\">  is_this_final_page=true;</script>";
								
							}else
							{
								echo "<p>Bütün Ürünler Yüklendi...</p>";
								echo "<script type=\"text/javascript\">  is_this_final_page=true;</script>";
							}
						}else
						{
							while($res=mysql_fetch_assoc($sql))
							{
							?>
							<div id="yield_m_area">
							<div id="s_y_name">
							<?php
							echo $res['y_name'];
							?>
							</div>							
							<div id="top_area">	
							<div id="shop_icon">
							<img src="img_files/shop.png" height=40 />
							</div>
							<div id="shop_name">
							<a href="#" onclick="shops_product( <?php echo $res['s_id'] ?>);" > <?php echo $res['s_name']; ?> </a>
							</div>
							</div>
							<div id="bottom_area">
							<div id="get_direction" onclick="calcRoute( <?php echo $res['latitude'].",".$res['longitude'];  ?> )" >
							<img src="img_files/go_shop.png" />
							</div>
							
							
							<div id="distance">
							<script>
							marker_enter(<?php echo $res['latitude'].",".$res['longitude'].",'".$res['s_name']."',".$res['s_id'].""; ?>);
							
							</script>
							
							 
							
							<?php
							$distance =( 6371000 * acos((cos(deg2rad($lat)) ) * (cos(deg2rad($res['latitude']))) * (cos(deg2rad($res['longitude']) - deg2rad($long)) )+ ((sin(deg2rad($lat))) * (sin(deg2rad($res['latitude']))))) );
							if($res['place_id']!=0)
							{
							echo $res['name']."<br>";
							}
							if($distance<1000)
							{
							echo "<b>".round($distance,0)."</b> m";
							}else
							{
							$distance=$distance/1000;
							echo "<b>".round($distance,2)."</b> km";
							}
							?>
							</div>
							</div>
							
							</div>
							<?php
							
							}
				
						}
				
				}
				
				public function ready_price_max_min_values($price)
				{
				$this->price_val=$price;
				$sql="SELECT y_prop_value.v_id,y_prop_value.min_value,y_prop_value.max_value FROM y_prop_value_contact INNER JOIN y_prop_value ON y_prop_value.v_id=y_prop_value_contact.y_v_id WHERE y_prop_value_contact.y_type_id=$this->yerok_id and y_prop_value_contact.y_p_id=102 and y_prop_value.min_value <= $price and y_prop_value.max_value >= $price";
				$sql=mysql_query($sql) or die(mysql_error());
				if(mysql_num_rows($sql)>0)
					{
					$res=mysql_fetch_assoc($sql);
					$this->pr_min_val=$res['min_value'];
					$this->pr_max_val=$res['max_value'];
					$this->pr_v_id=$res['v_id'];
					}else
					{
					$this->pr_min_val=0;
					$this->pr_max_val=0;
					$this->pr_v_id=0;
					}
				}
				public function ready_array_max_min_values()
				{
					$v_ids='';
					$this->prop_min_val=0;
					$this->prop_max_val=0;
					$i=0;
					$size=sizeof($this->props_data_correct);
					for($ii = 0; $ii<$size; $ii++)
						{
							if($this->props_data_correct[$ii]==102)
								{
								$v_ids=$v_ids.", ".$this->props_correct[$ii];
								$i=$i+1;
								}
						}
						
					if($i==0){return false;}
						
					$i=0;
					$v_ids=ltrim($v_ids,",");
					$v_ids=rtrim($v_ids,",");
					$sql="SELECT v_id,min_value,max_value FROM y_prop_value WHERE v_id IN( $v_ids )";
					$sql=mysql_query($sql) or die(mysql_error());
					if(mysql_num_rows($sql)==0){return false;}
					
					while($res=mysql_fetch_assoc($sql))
						{
							if($i==0)
								{
								$this->prop_max_val=$res['max_value'];
								$this->prop_min_val=$res['min_value'];
								}else
								{
									if($this->prop_max_val < $res['max_value'])
										{
										$this->prop_max_val=$res['max_value'];
										}
									if($this->prop_min_val > $res['min_value'])
										{
										$this->prop_min_val=$res['min_value'];
										}
								}
								$i=$i+1;
						}
					return true;
				}
			public function get_price_to_exact()
				{
				$this->exact_min_pr_val=$this->prop_min_val;
				$this->exact_max_pr_val=$this->prop_max_val;
				$this->exact_price=0;
				}
			public function check_prices()
				{
					if($this->price_val > $this->prop_max_val or $this->price_val < $this->prop_min_val)
						{
						
							$this->exact_min_pr_val=$this->pr_min_val;
							$this->exact_max_pr_val=$this->pr_max_val;
							$this->exact_price=$this->price_val;
							if($this->pr_v_id!=0)
							{
							self::change_price_props();
							$this->props_data_correct[]=102;
							$this->props_correct[]=$this->pr_v_id;
							}
						}else
						{
						$this->exact_min_pr_val=$this->prop_min_val;
						$this->exact_max_pr_val=$this->prop_max_val;
						$this->exact_price=$this->price_val;
						}
				}
				
			public function change_price_props()
				{
					$size=sizeof($this->props_data_correct);
					for($ii = 0; $ii<$size; $ii++)
						{
							if($this->props_data_correct[$ii]==102)
								{
									unset($this->props_correct[$ii]);
									unset($this->props_data_correct[$ii]);
								}
						}
					$this->props_correct = array_values($this->props_correct);
					$this->props_data_correct=array_values($this->props_data_correct);
				}
				
				
				
		};
		
		class place
			{
			
			public $place_id=0;
			public $name;
			public $adress;
			public $min_floor;
			public $max_floor;
			public $latitude;
			public $longitude;
			public $place_img=Array();
			
				function is_there_place($text_name)
					{
					$sql="SELECT id FROM place WHERE text_id='$text_name'";
					$sql=mysql_query($sql) or die(mysql_error());
					if(mysql_num_rows($sql)>0){
					$res=mysql_fetch_assoc($sql);
					$this->place_id=$res['id'];
					return true;
					}else{
					return false;
					}
					}
				function get_place_id($place_id)
					{
						if( filter_var($place_id, FILTER_VALIDATE_INT) and 0<$place_id)
							{
								$this->place_id=$place_id;
								return true;
							}else{
								return false;
							}
					}
			
				function get_info_of_place()
					{
					$sql="SELECT name, latitude, longitude, floor_max, floor_min, address FROM place WHERE id=$this->place_id";
					$sql=mysql_query($sql) or die(mysql_error());
						if(mysql_num_rows($sql)>0)
							{
							$res=mysql_fetch_assoc($sql);
								$this->name=$res['name'];
								$this->address=$res['address'];
								$this->min_floor=$res['floor_min'];
								$this->max_floor=$res['floor_max'];
								$this->latitude=$res['latitude'];
								$this->longitude=$res['longitude'];
								return true;
							}else{
							return false;
							}
					}
				
				function get_place_img()
					{
					$sql="SELECT img_way FROM p_image Where p_id=$this->place_id ORDER BY id";
					$sql=mysql_query($sql) or die(mysql_error());
						if(mysql_num_rows($sql)>0)
							{
							$i=0;
								while($res=mysql_fetch_assoc($sql))
									{
									$i=$i+1;
									$this->place_img[$i]=$res['img_way'];
									}
							}
					}
				function get_shops_of_place()
					{
					$sql="SELECT s_id, s_name,floor,shop_type FROM shop Where place_number=$this->place_id ORDER BY floor,s_name";
					$sql=mysql_query($sql) or die(mysql_error());
						if(mysql_num_rows($sql)>0)
							{
								$floor=$this->min_floor;
								echo "<div id=\"floor_num\">".$floor.". Kat</div>";
								while($res=mysql_fetch_assoc($sql))
									{
									if($floor!=$res['floor'])
										{
										$floor=$res['floor'];
										echo "<div id=\"floor_num\">".$floor.". Kat</div>";
										}
									echo "<a href=\"https://www.yeroks.com/".$res['s_id']."/".text::set_text($res['s_name'])."\" id=\"p_shop\"><div id=\"p_shop_ic\"></div>".$res['s_name']."</a>";
									}
							}else{
							return false;
							//finding no shop;
							}
					
					}
			
			
			}
		class text
			{
			
			function set_text($text)
				{
				$text = trim($text);
				$search = array("Ç","ç","Ğ","ğ","ı","İ","Ö","ö","Ş","ş","Ü","ü"," ","’","'","&",".","(",")","/",'\'');
				$replace = array("C","c","G","g","i","I","O","o","S","s","U","u","-","-","-","-","","-","","-",'-');
				return str_replace($search,$replace,$text);
				}
				
			}
		class show_yield
			{
			
				function show($shop_id)
					{
						$sql="SELECT shop_y_list.id,shop_y_list.shop_id,shop_y_list.y_type_id,shop_y_list.y_count,shop_y_list.orders,y_name_list.y_name 
FROM shop_y_list INNER JOIN y_name_list ON shop_y_list.y_type_id=y_name_list.y_type_id WHERE shop_y_list.shop_id=$shop_id ORDER BY shop_y_list.orders DESC";
						
						$sql=mysql_query($sql) or die(mysql_error());
						if(mysql_num_rows($sql)>0)
							{
							while($res=mysql_fetch_assoc($sql))
								{
								?>
								<div class="yield_area" id="c-<?php  echo $res['orders'];?>" Onclick="get_shop_category_special(<?php echo $res['y_type_id']; ?>)">
								<div id="y_image">
								<div class="yield_count" id="yield_count_<?php  echo $res['y_type_id']; ?>" >
								<?php
								echo $res['y_count'];
								?>
								</div>
								<img src="https://www.yeroks.com/y_img_small/<?php echo $res['icon_name']; ?>.png" height="70">
								</div>
								<div class="yield_name">
								<?php
								echo $res['y_name']."</br>";
								?>
								</div>
								</div>
								<?php
								}
							}else
							{
							echo "<br>Kayıtlı Ürününüz Bulunmamakta...";
							}
					}
					
					
					function show_for_shop($shop_id)
					{	
						$sql="SELECT shop_y_list.id,shop_y_list.shop_id,shop_y_list.y_type_id,shop_y_list.y_count,shop_y_list.orders,y_name_list.y_name,y_name_list.icon_name 
FROM shop_y_list INNER JOIN y_name_list ON shop_y_list.y_type_id=y_name_list.y_type_id WHERE shop_y_list.shop_id=$shop_id ORDER BY shop_y_list.orders DESC";
						
						$sql=mysql_query($sql) or die(mysql_error());
						if(mysql_num_rows($sql)>0)
							{
							while($res=mysql_fetch_assoc($sql))
								{
								?>
								<div class="yield_area" id="c-<?php  echo $res['y_type_id'];?>">
								
								<div id="y_image">
								<div class="yield_count" id="yield_count_<?php  echo $res['y_type_id']; ?>" >
								<?php
								echo $res['y_count'];
								?>
								</div>
								<img src="https://www.yeroks.com/y_img_small/<?php echo $res['icon_name']; ?>.png" height="100">
								</div>
								<div class="yield_name">
								<?php
								echo $res['y_name']."</br>";
								?>
								</div>
								<div class="yield_set">
								<div class="yield_set_change" id="yield_set_change_<?php  echo $res['y_type_id'];?>" onclick="go_to_yield_page(<?php  echo $res['y_type_id'];?>);">Reyona Ürün Ekle</div>
								<div class="b_yield_set_delete" id="yield_set_delete_<?php  echo $res['y_type_id'];?>" onclick="cat_delete_step_one(<?php  echo $res['y_type_id'].",".$res['y_count'];?>)">Reyonu Sil</div>
								</div>
								</div>
								<?php
								}
							}else
							{
							echo "<br>Bu Mağazanın Kayıtlı Ürünü Bulunmamakta...";
							}
					}
					function show_for_web($shop_id,$shop_name)
					{
						$sql="SELECT shop_y_list.id,shop_y_list.shop_id,shop_y_list.y_type_id,shop_y_list.y_count,shop_y_list.orders,y_name_list.y_name,y_name_list.icon_name 
FROM shop_y_list INNER JOIN y_name_list ON shop_y_list.y_type_id=y_name_list.y_type_id WHERE shop_y_list.shop_id=$shop_id ORDER BY shop_y_list.orders DESC";
						
						$sql=mysql_query($sql) or die(mysql_error());
						if(mysql_num_rows($sql)>0)
							{
							while($res=mysql_fetch_assoc($sql))
								{
								?>
								<a class="yield_area" id="c-<?php  echo $res['orders'];?>" href="https://www.yeroks.com/<?php echo $res['shop_id']."/".$res['y_type_id']."/".text::set_text($shop_name)."/".text::set_text($res['y_name']);?>">
								<div id="y_image">
								<div class="yield_count" id="yield_count_<?php  echo $res['y_type_id']; ?>" >
								<?php
								echo $res['y_count'];
								?>
								</div>
								<img src="https://www.yeroks.com/y_img_small/<?php echo $res['icon_name']; ?>.png" height="100">
								</div>
								<div class="yield_name">
								<?php
								echo $res['y_name']."</br>";
								?>
								</div>								
								<div class="y_special_show" id="<?php echo "y-".$res['y_type_id']; ?>">
								</div>
								</a>
								<?php
								}
							}else
							{
							echo "<br>Bu Mağazanın Kayıtlı Ürünü Bulunmamakta...";
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
					$this->table_name=table_select::select_table($this->y_type_id);
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
							$sql="UPDATE shop_y_list SET y_count='$this->y_count' WHERE shop_id='$this->shop_id' and y_type_id='$this->y_type_id'";
							$sql=mysql_query($sql) or die(mysql_error());
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
		
		
		class yield_order
			{
				
				public $shop_id;
				public $cat_id;
				public $y_id;
				public $order;
				public $c_order;
				public $y_order;
				
				function set_variables($shop_id,$cat_id,$y_id,$order)
					{
					$this->shop_id=$shop_id;
					$this->cat_id=$cat_id;
					$this->y_id=$y_id;
					$this->order=$order;
					}
				function calculate_next_order()
					{
					$sql="SELECT y_count,orders FROM shop_y_list WHERE shop_id='$this->shop_id' and y_type_id='$this->cat_id'";
					$sql=mysql_query($sql) or die(mysql_error());
						if(mysql_num_rows($sql) > 0)
							{
							$res=mysql_fetch_assoc($sql);
							$this->c_order=$res['orders'];
							$this->y_order=$res['orders']+($res['y_count']+1)*0.01;
							}else
							{
							$sql1="SELECT orders FROM shop_y_list WHERE shop_id='$this->shop_id' ORDER BY orders DESC";
							$sql1=mysql_query($sql1) or die(mysql_error());
								if(mysql_num_rows($sql1) > 0)
									{
									$res1=mysql_fetch_assoc($sql1);
									$this->c_order=$res1['orders']+1;
									$this->y_order=$res1['orders']+1.01;
									}else
									{
									$this->c_order=1;
									$this->y_order=1.01;
									}
							
							}
					
					}
					//butun shop categori kismindaki order lari tekrar sirali sekilde eski numara sirasina gore(32,45 ile atlasa bile 32,33 seklinde) ile siralandirir.
				function set_order_category()
					{
					$sql1="SET @i=0";
					$sql1=mysql_query($sql1) or die(mysql_error());	
					$sql2="UPDATE  `shop_y_list` SET  `orders` = @i := @i +1 WHERE  `shop_id` =$this->shop_id ORDER BY  `orders` ASC";
					$sql2=mysql_query($sql2) or die(mysql_error());	
					return $sql2;
					}
				
				function set_to_up_category()
					{
					$uporder=$this->order+1;
					$c_id=0;//orders category id
					$up_c_id=0;//order+1's category id
					
					$sql="SELECT `id`,`orders`,`y_count`,`y_type_id` FROM `shop_y_list` WHERE `shop_id`=$this->shop_id and (`orders`=$this->order or `orders`=$uporder)";
					$sql=mysql_query($sql) or die(mysql_error());
					while($res=mysql_fetch_assoc($sql))
						{
							if($res['orders']==$this->order)
								{
								$c_id=$res['id'];
								$sql1="UPDATE `shop_y_list` SET `orders`=(`orders`+1) WHERE `shop_id`=$this->shop_id and `id`=$c_id";
								$sql1=mysql_query($sql1) or die(mysql_error());
								}elseif($res['orders']==$uporder)
								{
								$up_c_id=$res['id'];
								$sql2="UPDATE `shop_y_list` SET `orders`=(`orders`-1) WHERE  `shop_id`=$this->shop_id and `id`=$up_c_id";
								$sql2=mysql_query($sql2) or die(mysql_error());
								}
						}
					self::set_order_category();
					
					$sql3="SELECT `orders`,`y_type_id` FROM `shop_y_list` WHERE `shop_id`=$this->shop_id and (`id`=$c_id or `id`=$up_c_id)";
					$sql3=mysql_query($sql3) or die(mysql_error());
					while($res3=mysql_fetch_assoc($sql3))
						{
						self::set_order_yield(self::select_table($res3['y_type_id']),$res3['y_type_id'],$res3['orders']);
						}
					return $sql1;
					}
			function set_to_down_category()
					{
					$downorder=$this->order-1;
					$c_id=0;//orders category id
					$down_c_id=0;//order+1's category id
			
					$sql="SELECT `id`,`orders`,`y_count`,`y_type_id` FROM `shop_y_list` WHERE `shop_id`=$this->shop_id and (`orders`=$this->order or `orders`=$downorder)";
					$sql=mysql_query($sql) or die(mysql_error());
					while($res=mysql_fetch_assoc($sql))
						{
							if($res['orders']==$this->order)
								{
								$c_id=$res['id'];
								$sql1="UPDATE  `shop_y_list` SET  `orders` =(`orders`-1) WHERE  `shop_id` =$this->shop_id and `id`=$c_id ";
								$sql1=mysql_query($sql1) or die(mysql_error());
								}elseif($res['orders']==$downorder)
								{
								$down_c_id=$res['id'];
								$sql2="UPDATE  `shop_y_list` SET  `orders` =(`orders`+1) WHERE  `shop_id` =$this->shop_id and `id`=$down_c_id";
								$sql2=mysql_query($sql2) or die(mysql_error());
								}
						}
					self::set_order_category();
					
					$sql3="SELECT `orders`,`y_type_id` FROM `shop_y_list` WHERE `shop_id`=$this->shop_id and (`id`=$c_id or `id`=$down_c_id)";
					$sql3=mysql_query($sql3) or die(mysql_error());
					while($res3=mysql_fetch_assoc($sql3))
						{
						self::set_order_yield(self::select_table($res3['y_type_id']),$res3['y_type_id'],$res3['orders']);
						}
					return $sql1;
					}
			function calculate_sum_for_cat()
					{
					$sql="SELECT SUM(`y_count`) as total FROM `shop_y_list` WHERE `shop_id`=$this->shop_id and `orders`<$this->order ";
					$sql=mysql_query($sql) or die(mysql_error());
					$res=mysql_fetch_assoc($sql);
					if(is_numeric($res['total']))
						{
						return $res['total'];
						}else
						{
						return 0;
						}
					}
			function get_start_for_yield()
					{
					$sql="SELECT orders FROM `shop_y_list` WHERE `shop_id`=$this->shop_id and `y_type_id`=$this->cat_id ";
					$sql=mysql_query($sql) or die(mysql_error());
					$res=mysql_fetch_assoc($sql);
					return $res['orders'];
					}
			function set_order_yield($table,$y_type_id,$start)
					{
					$sql1="SET @i=$start";
					$sql1=mysql_query($sql1) or die(mysql_error());	
					$sql2="UPDATE  `$table` SET  `orders` = @i := @i +0.01 WHERE  `s_id` =$this->shop_id and `y_name_id`=$y_type_id ORDER BY  `orders` ASC";
					$sql2=mysql_query($sql2) or die(mysql_error());	
					return $sql2;
					}
			public function select_table($yerok_id)
				{
					return table_select::select_table($yerok_id);
				}
				
			function set_to_up_yield()
					{
					$table=self::select_table($this->cat_id);
					$sql="SELECT `y_id` FROM `$table` WHERE `orders`=((SELECT `orders` FROM `$table` WHERE `s_id`=$this->shop_id and `y_id`=$this->y_id)+0.01) and `s_id`=$this->shop_id";
					$sql=mysql_query($sql) or die(mysql_error());
					if(mysql_num_rows($sql)!=0)
						{
						$res=mysql_fetch_assoc($sql);
						$up_id=$res['y_id'];
						$sql1="UPDATE `$table` SET `orders`=CASE y_id WHEN $this->y_id THEN (`orders`+0.01) WHEN $up_id THEN (`orders`-0.01) END WHERE `s_id`=$this->shop_id and ( `y_id`=$this->y_id or `y_id`=$up_id ) ";
						$sql1=mysql_query($sql1) or die(mysql_error());
						}else{
						$sql1=false;
						}
					
					$start=self::get_start_for_yield();
					self::set_order_yield($table,$this->cat_id,$start);
					return $sql1;
					}	
			function set_to_down_yield()
					{
					$table=self::select_table($this->cat_id);
					$sql="SELECT `y_id` FROM `$table` WHERE `orders`=((SELECT `orders` FROM `$table` WHERE `s_id`=$this->shop_id and `y_id`=$this->y_id)-0.01) and `s_id`=$this->shop_id";
					$sql=mysql_query($sql) or die(mysql_error());
					if(mysql_num_rows($sql)!=0)
						{
						$res=mysql_fetch_assoc($sql);
						$down_id=$res['y_id'];
						$sql1="UPDATE `$table` SET `orders`=CASE y_id WHEN $this->y_id THEN (`orders`-0.01) WHEN $down_id THEN (`orders`+0.01) END WHERE `s_id`=$this->shop_id and ( `y_id`=$this->y_id or `y_id`=$down_id ) ";
						$sql1=mysql_query($sql1) or die(mysql_error());
						}else{
						$sql1=false;
						}
					$start=self::get_start_for_yield();
					self::set_order_yield($table,$this->cat_id,$start);
					return $sql1;
					}	
				
			}
		
		class shop_map_set
			{
				public $is_set_okay=0;
				public $place_id=0;
				public $latitude=0;
				public $longitude=0;
				public $s_floor=0;
				public $problem="";
				public $adress="-";
				public $tel_number="-";
				
				function is_pos_okay($s_id)
					{
					$sql="SELECT s_id,latitude,longitude,place_number FROM shop WHERE s_id='$s_id'";
					$sql=mysql_query($sql) or die(mysql_error());
					if(mysql_num_rows($sql)==0)
						{
						return false;
						}else
						{
						$res=mysql_fetch_assoc($sql);
						if ($res['latitude']!=0)
							{
							$this->is_set_okay=1;
							$this->place_id=$res['place_number'];
							$this->latitude=$res['latitude'];
							$this->longitude=$res['longitude'];
							}
						}
					}
					
				function get_adress_tel($shop_id)
					{
					$sql="SELECT place_number, floor FROM shop WHERE s_id='$shop_id'";
					$sql=mysql_query($sql) or die(mysql_error());
					if(mysql_num_rows($sql)==0)
					{
					die("Bir Hata Oluştu...");
					}
					$res=mysql_fetch_assoc($sql);
					$place_number=$res['place_number'];
					if($place_number!=0)
					{
					$sql1="SELECT place.name, place.address,shop_adress.tel_number FROM place,shop_adress WHERE place.id='$place_number' and shop_adress.s_id='$shop_id'";
					}else{
					$sql1="SELECT adress_text,tel_number FROM shop_adress WHERE s_id='$shop_id'";
					}
					
					$sql1=mysql_query($sql1) or die(mysql_error());
						if(mysql_num_rows($sql1)>0)
							{
								if($place_number!=0){
										$res1=mysql_fetch_assoc($sql1);
										$this->adress=$res1['name']." ".$res['floor'].".Kat ";
										if($res1['tel_number']==""){
										$this->tel_number=" -";
										}else{
										$this->tel_number=$res1['tel_number'];
										}
									}else{
									$res1=mysql_fetch_assoc($sql1);
									$this->adress=$res1['adress_text'];
									if($res1['tel_number']==""){
										$this->tel_number=" -";
										}else{
										$this->tel_number=$res1['tel_number'];
										}
									}
							return true;
							}else{
							return false;
							}
					}
					
				function is_there_adress_data($shop_id)
					{
					$sql="SELECT adress_text FROM shop_adress WHERE s_id='$shop_id'";
					$sql=mysql_query($sql) or die(mysql_error());
						if(mysql_num_rows($sql)>0)
							{
							$this->problem='';
							return true;
							}else{
							$this->problem="Bir Hata Oluştu.Lütfen Öncelikle Adres Bilgilerinizi Doldurunuz...";
							return false;
							}
					}
				function floor_check($floor)
					{
						if($floor=="")
						{
						$this->problem="Bir Problemle Karşılaştık.Lütfen Sayfayı Yenileyiniz...";
						return false;
						}elseif($floor==100)
						{
						$this->problem="Lütfen Kat Seçiniz...";
						return false;
						}elseif($floor>9 or $floor<-9)
						{
						$this->problem="Bir Problemle Karşılaştık.Lütfen Sayfayı Yenileyiniz...";
						return false;
						}else
						{
						$this->s_floor=$floor;
						return true;
						}
					
					}
				function s_center_check($shop_center_id)
					{
					$sql="SELECT id,latitude,longitude FROM place WHERE id='$shop_center_id'";
					$sql=mysql_query($sql) or die(mysql_error());
					if(mysql_num_rows($sql)==1)
						{
						$res=mysql_fetch_assoc($sql);
						$this->latitude=$res['latitude'];
						$this->longitude=$res['longitude'];
						$this->place_id=$res['id'];
						return true;
						}else
						{
						$this->problem="Bir Problemle Karşılaştık.Lütfen Sayfayı Yenileyiniz...";
						return false;
						}
					}
				
				function save_in_s_center($shop_id)
					{
					$sql="UPDATE shop SET latitude='$this->latitude', longitude='$this->longitude',floor='$this->s_floor', place_number='$this->place_id' WHERE s_id='$shop_id' LIMIT 1";
					$sql=mysql_query($sql) or die(mysql_error());
					if($sql)
						{
						self::set_yields_pos($shop_id,$this->latitude,$this->longitude,$this->place_id);
						return true;
						}else
						{
						return false;
						}
					
					}
				function check_map_data($loc_lat,$loc_long,$zoom)
					{
						if($loc_lat=="" or $loc_lat=="" or $loc_long=="" or $loc_long=="")
							{
							$this->problem="Bir Hata İle Karşılaşıldı. Lütfen Sayfayı Yenileyiniz...";
							return false;
							}
						if($loc_lat>90 or $loc_lat<-90 or $loc_long>180 or $loc_long<-180)
							{
							$this->problem="Lütfen Konumu Doğru Seçtiğinizden Emin Olunuz...";
							return false;
							}
						if($zoom<15)
							{
							$this->problem="Lütfen Haritada Mağazanızın Konumunu Hassas Şekilde Girecek Kadar Yaklaştırarak Kayıt Yapınız...";
							return false;
							}
						if($zoom>25)
							{
							$this->problem="Bir Hata İle Karşılaşıldı. Lütfen Sayfayı Yenileyiniz...";
							return false;
							}
							return true;
					}
				function street_map_data_save($loc_lat,$loc_long,$zoom,$shop_id)
					{
					$sql="UPDATE shop SET latitude='$loc_lat', longitude='$loc_long', place_number='0' WHERE s_id='$shop_id' LIMIT 1";
					$sql=mysql_query($sql) or die(mysql_error());
					if($sql)
						{
						self::set_yields_pos($shop_id,$loc_lat,$loc_long,0);
						return true;
						}else
						{
						$this->problem="Bir Hata İle Karşılaşıldı...";
						return false;
						}
					}
				function set_yields_pos($shop_id,$lati,$longi,$place_id)
					{
						$sql1="UPDATE y1 SET latitude='$lati', longitude='$longi', place_id='$place_id' WHERE s_id='$shop_id' ";
						$sql2="UPDATE y2 SET latitude='$lati', longitude='$longi', place_id='$place_id' WHERE s_id='$shop_id' ";
						$sql3="UPDATE y3 SET latitude='$lati', longitude='$longi', place_id='$place_id' WHERE s_id='$shop_id' ";
						$sql4="UPDATE y4 SET latitude='$lati', longitude='$longi', place_id='$place_id' WHERE s_id='$shop_id' ";
						$sql5="UPDATE y5 SET latitude='$lati', longitude='$longi', place_id='$place_id' WHERE s_id='$shop_id' ";
						$sql6="UPDATE y6 SET latitude='$lati', longitude='$longi', place_id='$place_id' WHERE s_id='$shop_id' ";
						$sql7="UPDATE y7 SET latitude='$lati', longitude='$longi', place_id='$place_id' WHERE s_id='$shop_id' ";
						//update yields location
						
						$sql_res1=mysql_query($sql1) or die(mysql_error());
						$sql_res2=mysql_query($sql2) or die(mysql_error());
						$sql_res3=mysql_query($sql3) or die(mysql_error());
						$sql_res4=mysql_query($sql4) or die(mysql_error());
						$sql_res5=mysql_query($sql5) or die(mysql_error());
						$sql_res6=mysql_query($sql6) or die(mysql_error());
						$sql_res7=mysql_query($sql7) or die(mysql_error());	
						
						if($sql_res1 and $sql_res2 and $sql_res3 and $sql_res4 and $sql_res5 and $sql_res6 and $sql_res7)
							{
								return true;
							}else{
								return false;
							}
					}
			}
		
		
		class gps_request
			{
				public $shop_id;
				public $req_data;
				function check_gps_data($data)
					{
					$sql="SELECT*FROM gps_request WHERE remember_id='$data' and is_in_process=1";
					$sql=mysql_query($sql) or die(mysql_error());
						if(mysql_num_rows($sql)=='1')
							{
							$res=mysql_fetch_assoc($sql);
							$this->shop_id=$res['shop_id'];
							$this->req_data=$res['remember_id'];
							return true;
							}else
							{
							return false;
							}
					
					}
				function is_there_active_request($shop_id)
					{
					$sql="SELECT*FROM gps_request WHERE shop_id='$shop_id' and is_in_process=1";
					$sql=mysql_query($sql) or die(mysql_error());
					if(mysql_num_rows($sql)>'0')
							{
							return true;
							}else
							{
							return false;
							}
					}
				
				function create_session()
					{
						$sql="SELECT s_id,s_mail,s_name FROM shop WHERE s_id='$this->shop_id'";
						$sql=mysql_query($sql) or die(mysql_error());
						if(mysql_num_rows($sql)=='1')
							{
							$res=mysql_fetch_assoc($sql);
							$_SESSION['user_no']=$res['s_id'];
							$_SESSION['user_type']= 2;
							$_SESSION['s_full_name']=$res['s_name'];
							$_SESSION['mail_adress']=$res['s_mail'];
							$_SESSION['login']=0;
							$_SESSION['req_data']=$this->req_data;
							}else
							{
							return false;
							}
					
					}
				function destroy_session()
					{
					session_destroy();
					}
				function save_loc($marker_lat,$marker_long,$optimum_lat,$optimum_long,$optimum_accuracy,$shop_id,$req_data)
					{
						$sql="UPDATE shop SET latitude='$marker_lat', longitude='$marker_long' WHERE s_id='$shop_id' LIMIT 1";
						//shop_location update
						$sql1="UPDATE y1 SET latitude='$optimum_lat', longitude='$optimum_long' WHERE s_id='$shop_id' ";
						$sql2="UPDATE y2 SET latitude='$optimum_lat', longitude='$optimum_long' WHERE s_id='$shop_id' ";
						$sql3="UPDATE y3 SET latitude='$optimum_lat', longitude='$optimum_long' WHERE s_id='$shop_id' ";
						$sql4="UPDATE y4 SET latitude='$optimum_lat', longitude='$optimum_long' WHERE s_id='$shop_id' ";
						$sql5="UPDATE y5 SET latitude='$optimum_lat', longitude='$optimum_long' WHERE s_id='$shop_id' ";
						$sql6="UPDATE y6 SET latitude='$optimum_lat', longitude='$optimum_long' WHERE s_id='$shop_id' ";
						$sql7="UPDATE y7 SET latitude='$optimum_lat', longitude='$optimum_long' WHERE s_id='$shop_id' ";
						//update yields location
						$sqll="UPDATE gps_request SET is_in_process='0' WHERE shop_id='$shop_id' and remember_id='$req_data' LIMIT 1";
						
						$sql_res1=mysql_query($sql) or die(mysql_error());
						$sql_res2=mysql_query($sql1) or die(mysql_error());
						$sql_res3=mysql_query($sql2) or die(mysql_error());
						$sql_res4=mysql_query($sql3) or die(mysql_error());
						$sql_res5=mysql_query($sql4) or die(mysql_error());
						$sql_res6=mysql_query($sql5) or die(mysql_error());
						$sql_res7=mysql_query($sql6) or die(mysql_error());
						$sql_res8=mysql_query($sql7) or die(mysql_error());
						$sql_res9=mysql_query($sqll) or die(mysql_error());
						
						
						if($sql_res1 and $sql_res2 and $sql_res3 and $sql_res4 and $sql_res5 and $sql_res6 and $sql_res7 and $sql_res8 and $sql_res9)
							{
							$sql="INSERT INTO gps_request_result(req_id,shop_id,marker_lat,marker_long,optimum_lat,optimum_long,optimum_accuracy,time) VALUES('$req_data','$shop_id','$marker_lat','$marker_long','$optimum_lat','$optimum_long','$optimum_accuracy',now())";
							mysql_query($sql);
							self::destroy_session();
							return true;
							}else
							{
							return false;
							}
					}
			
			}
			
	
class image_handler
{
	private $way;
	private $name;
	private $type;
	private $internal_image;
	private $new_width;
	private $new_height;
	private $size;
	public function initialize($inp_way,$inp_name,$inp_type)
	{
		$this->way=$inp_way;
		$this->name=$inp_name;
		$this->type=$inp_type;
		$im_size = getimagesize($this->way);
		$this->new_width=$im_size[0];
		$this->new_height=$im_size[1];
	}
	public function is_valid_type()
	{
		if($this->type=="image/jpeg" or $this->type=="image/jpg"
			or $this->type=="image/gif" or $this->type=="image/png")
			{
				return true;
			}
			else
			{
				return false;
			};
	}

	public function get_type_string()
	{
		if($this->type=="image/jpeg" or $this->type=="image/jpg")
		{
			return "jpg";
		}
		elseif($this->type=="image/gif")
		{
			return "gif";
		}
		else
		{
			return "png";
		};
	}
	public function import_image()
	{
		if($this->type=="image/jpeg" or $this->type=="image/jpg")
		{
			$this->internal_image=ImageCreateFromJPEG($this->way);
		}
		elseif($this->type=="image/gif")
		{
			$this->internal_image=ImageCreateFromGIF($this->way);
		}
		else
		{
			$this->internal_image=ImageCreateFromPNG($this->way);
		};
	}
	public function resize($max_width,$max_height)
	{
		$width=$this->new_width;
		$height=$this->new_height;
		if($width>$max_width)
		{	$scale_ratio=$max_width/$width;$width=$max_width;$height=ceil($height*$scale_ratio);};
		if($height>$max_height)
		{	$scale_ratio=$max_height/$height;$height=$max_height;$width=ceil($width*$scale_ratio);};
		$str_width=$width .'';
		$str_height=$height .'';
		$newimage=imagecreatetruecolor($width ,$height);
		imageCopyResized($newimage,$this->internal_image,0,0,0,0,
			$width,$height,$this->new_width,$this->new_height);
		$this->internal_image=$newimage;	
		$this->new_width=$width;
		$this->new_height=$height;		
	}
	
	public function save_image_to($path)
	{
		$res=ImageJpeg($this->internal_image,$path);
		if($res!=1)
		{
			echo "Image Upload ERROR!";
			die("");
		};
		chmod($path,0644);
	}
	public function insert_image_to_database($shop_id,$image_way,$no)
		{
			$insertpicture="INSERT INTO s_image(shop_id,img_way,no) VALUES('$shop_id','$image_way','$no')"; 	
			$imageinsert=mysql_query($insertpicture) or die(mysql_error());
		}
	public function insert_y_image_to_database( $y_id,$y_type_id,$no,$rand_image_name)
		{
			$insertpicture="INSERT INTO y_image(y_id,y_type_id,no,img_way) VALUES('$y_id','$y_type_id','$no','$rand_image_name')"; 	
			$imageinsert=mysql_query($insertpicture) or die(mysql_error());
		
		}
	public function update_y_image_to_database( $y_id,$y_type_id,$no,$rand_image_name)
		{
					$sqlpicture="INSERT INTO y_image(y_id,img_way,y_type_id,no) VALUES('$y_id','$rand_image_name','$y_type_id','$no')"; 
					$sqlpicture=mysql_query($sqlpicture) or die(mysql_error());
					$sql1="SET @i=0";
					$sql1=mysql_query($sql1) or die(mysql_error());	
					$sql2="UPDATE  `y_image` SET  `no` = @i := @i +1 WHERE  y_id='$y_id' and y_type_id='$y_type_id' ORDER BY  `no` ASC";
					$sql2=mysql_query($sql2) or die(mysql_error());
		
		
		
		}
	public function delete_y_image_in_databese($y_id,$y_type_id,$no)
		{
		$sql="SELECT img_way FROM y_image WHERE y_id='$y_id' and y_type_id='$y_type_id' and no='$no' LIMIT 1";
		$sql=mysql_query($sql) or die(mysql_error());
			if(mysql_num_rows($sql)>0)
				{
				$i=1;
				while($res=mysql_fetch_assoc($sql))
					{
					$img_way=$res['img_way'];
					$updatepicture="DELETE FROM y_image WHERE y_id='$y_id' and y_type_id='$y_type_id' and no='$no' LIMIT 1"; 	
					$imageinsert=mysql_query($updatepicture) or die(mysql_error());
					self::delete_file("../y_img_small/".$img_way);
					self::delete_file("../y_img_big/".$img_way);
					if($i>10){break;}
					$i=$i+1;
					}
				}
		}
	public function set_order_images($y_id,$y_type_id)
		{
					$sql1="SET @i=0";
					$sql1=mysql_query($sql1) or die(mysql_error());	
					$sql2="UPDATE  `y_image` SET  `no` = @i := @i +1 WHERE  y_id='$y_id' and y_type_id='$y_type_id' ORDER BY  `no` ASC";
					$sql2=mysql_query($sql2) or die(mysql_error());
		}
	public function get_random_image_name($extension)
		{
	$month=date("n");
	$year=date("Y");
	$day=date("j");
	$hour=date('H');
	$minute=date('i');
	$second=date('s');
	$daterandominformation=rand(1000,32000)."_".$month.$year.$day."_".
	$hour.$minute.$second."_".rand(1000,32000).".".$extension;
	return $daterandominformation;
		}
		
	public function delete_file($way)
		{
			if (file_exists($way)) 
				{
				unlink($way);
				}
		}
	public function get_image_to_delete($shop_id,$no)
		{
		$sql="SELECT*FROM s_image WHERE shop_id='$shop_id' and no='$no' ";
		$sql=mysql_query($sql) or die(mysql_error());
			if(mysql_num_rows($sql)>0)
				{
					while($res=mysql_fetch_assoc($sql))
						{
						self::delete_file("../s_profile_img_big/".$res['img_way']);
						self::delete_file("../s_profile_img_small/".$res['img_way']);
						}
				}
		return true;
		}
	public function delete_image_in_database($shop_id,$no)
		{
			$insertpicture="DELETE FROM s_image WHERE shop_id='$shop_id' and no='$no'"; 	
			$imageinsert=mysql_query($insertpicture) or die(mysql_error());
			return true;
		}
};
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
							
							echo "{\"result\":1,\"fav_status\":1,\"explain\":\"Favori Listesine Eklendi...\"}";
							}else
							{						
							$sql="SELECT p_id FROM place WHERE id='$this->fav_id'";
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
							$sql="SELECT y_id,s_id,y_type_id FROM $table_name WHERE y_id='$y_id' and y_name_id='$y_type_id'";
							$sql=mysql_query($sql) or die(mysql_error());
								if(mysql_num_rows($sql)==1)
									{
									$res=mysql_fetch_assoc($sql);
									$y_id1=$res['y_id'];
									$s_id1=$res['s_id'];
								    $y_name1=$res['y_name'];
									$y_type_id1=$res['y_type_id'];
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
					function fav_control($fav_id,$type,$user_id,$y_type_id)
					{
						if($type==2){
						$sql="SELECT id,s_id FROM shopping_fav_list WHERE user_id='$user_id' and s_id='$fav_id' and type='2' ";
						}elseif($type==3){
						$sql="SELECT id,p_id FROM shopping_fav_list WHERE user_id='$user_id' and p_id='$fav_id' and type='3' ";
						}elseif($type==4){
						$sql="SELECT id,p_id FROM shopping_fav_list WHERE user_id='$user_id' and y_type_id='$y_type_id' and y_id='$fav_id' and type='4' ";
						}
						$sql=mysql_query($sql);
						if(mysql_num_rows($sql)>0)
						{
						return true;
						}else{
						return false;
						}
					}
			};

////////////////////////////////////////////////////
// PHPMailer - PHP email class
//
// Class for sending email using either
// sendmail, PHP mail(), or SMTP.  Methods are
// based upon the standard AspEmail(tm) classes.
//
// Copyright (C) 2001 - 2003  Brent R. Matzelle
//
// License: LGPL, see LICENSE
////////////////////////////////////////////////////

/**
 * PHPMailer - PHP email transport class
 * @package PHPMailer
 * @author Brent R. Matzelle
 * @copyright 2001 - 2003 Brent R. Matzelle
 */
class PHPMailer
{
    /////////////////////////////////////////////////
    // PUBLIC VARIABLES
    /////////////////////////////////////////////////

    /**
     * Email priority (1 = High, 3 = Normal, 5 = low).
     * @var int
     */
    var $Priority          = 3;

    /**
     * Sets the CharSet of the message.
     * @var string
     */
    var $CharSet           = "utf8";

    /**
     * Sets the Content-type of the message.
     * @var string
     */
    var $ContentType        = "text/html";

    /**
     * Sets the Encoding of the message. Options for this are "8bit",
     * "7bit", "binary", "base64", and "quoted-printable".
     * @var string
     */
    var $Encoding          = "8bit";

    /**
     * Holds the most recent mailer error message.
     * @var string
     */
    var $ErrorInfo         = "";

    /**
     * Sets the From email address for the message.
     * @var string
     */
    var $From               = "root@localhost";

    /**
     * Sets the From name of the message.
     * @var string
     */
    var $FromName           = "";

    /**
     * Sets the Sender email (Return-Path) of the message.  If not empty,
     * will be sent via -f to sendmail or as 'MAIL FROM' in smtp mode.
     * @var string
     */
    var $Sender            = "";

    /**
     * Sets the Subject of the message.
     * @var string
     */
    var $Subject           = "";

    /**
     * Sets the Body of the message.  This can be either an HTML or text body.
     * If HTML then run IsHTML(true).
     * @var string
     */
    var $Body               = "";

    /**
     * Sets the text-only body of the message.  This automatically sets the
     * email to multipart/alternative.  This body can be read by mail
     * clients that do not have HTML email capability such as mutt. Clients
     * that can read HTML will view the normal Body.
     * @var string
     */
    var $AltBody           = "";

    /**
     * Sets word wrapping on the body of the message to a given number of 
     * characters.
     * @var int
     */
    var $WordWrap          = 0;

    /**
     * Method to send mail: ("mail", "sendmail", or "smtp").
     * @var string
     */
    var $Mailer            = "mail";

    /**
     * Sets the path of the sendmail program.
     * @var string
     */
    var $Sendmail          = "/var/qmail/bin/sendmail";
    
    /**
     * Path to PHPMailer plugins.  This is now only useful if the SMTP class 
     * is in a different directory than the PHP include path.  
     * @var string
     */
    var $PluginDir         = "";

    /**
     *  Holds PHPMailer version.
     *  @var string
     */
    var $Version           = "1.72";

    /**
     * Sets the email address that a reading confirmation will be sent.
     * @var string
     */
    var $ConfirmReadingTo  = "";

    /**
     *  Sets the hostname to use in Message-Id and Received headers
     *  and as default HELO string. If empty, the value returned
     *  by SERVER_NAME is used or 'localhost.localdomain'.
     *  @var string
     */
    var $Hostname          = "";

    /////////////////////////////////////////////////
    // SMTP VARIABLES
    /////////////////////////////////////////////////

    /**
     *  Sets the SMTP hosts.  All hosts must be separated by a
     *  semicolon.  You can also specify a different port
     *  for each host by using this format: [hostname:port]
     *  (e.g. "smtp1.example.com:25;smtp2.example.com").
     *  Hosts will be tried in order.
     *  @var string
     */
    var $Host        = "localhost";

    /**
     *  Sets the default SMTP server port.
     *  @var int
     */
    var $Port        = 25;

    /**
     *  Sets the SMTP HELO of the message (Default is $Hostname).
     *  @var string
     */
    var $Helo        = "";

    /**
     *  Sets SMTP authentication. Utilizes the Username and Password variables.
     *  @var bool
     */
    var $SMTPAuth     = true;

    /**
     *  Sets SMTP username.
     *  @var string
     */
    var $Username     = "";

    /**
     *  Sets SMTP password.
     *  @var string
     */
    var $Password     = "";

    /**
     *  Sets the SMTP server timeout in seconds. This function will not 
     *  work with the win32 version.
     *  @var int
     */
    var $Timeout      = 10;

    /**
     *  Sets SMTP class debugging on or off.
     *  @var bool
     */
    var $SMTPDebug    = false;

    /**
     * Prevents the SMTP connection from being closed after each mail 
     * sending.  If this is set to true then to close the connection 
     * requires an explicit call to SmtpClose(). 
     * @var bool
     */
    var $SMTPKeepAlive = false;

    /**#@+
     * @access private
     */
    var $smtp            = NULL;
    var $to              = array();
    var $cc              = array();
    var $bcc             = array();
    var $ReplyTo         = array();
    var $attachment      = array();
    var $CustomHeader    = array();
    var $message_type    = "";
    var $boundary        = array();
    var $language        = array();
    var $error_count     = 0;
    var $LE              = "\n";
    /**#@-*/
    
    /////////////////////////////////////////////////
    // VARIABLE METHODS
    /////////////////////////////////////////////////

    /**
     * Sets message type to HTML.  
     * @param bool $bool
     * @return void
     */
    function IsHTML($bool) {
        if($bool == true)
            $this->ContentType = "text/html";
        else
            $this->ContentType = "text/plain";
    }

    /**
     * Sets Mailer to send message using SMTP.
     * @return void
     */
    function IsSMTP() {
        $this->Mailer = "smtp";
    }

    /**
     * Sets Mailer to send message using PHP mail() function.
     * @return void
     */
    function IsMail() {
        $this->Mailer = "mail";
    }

    /**
     * Sets Mailer to send message using the $Sendmail program.
     * @return void
     */
    function IsSendmail() {
        $this->Mailer = "sendmail";
    }

    /**
     * Sets Mailer to send message using the qmail MTA. 
     * @return void
     */
    function IsQmail() {
        $this->Sendmail = "/var/qmail/bin/sendmail";
        $this->Mailer = "sendmail";
    }


    /////////////////////////////////////////////////
    // RECIPIENT METHODS
    /////////////////////////////////////////////////

    /**
     * Adds a "To" address.  
     * @param string $address
     * @param string $name
     * @return void
     */
    function AddAddress($address, $name = "") {
        $cur = count($this->to);
        $this->to[$cur][0] = trim($address);
        $this->to[$cur][1] = $name;
    }

    /**
     * Adds a "Cc" address. Note: this function works
     * with the SMTP mailer on win32, not with the "mail"
     * mailer.  
     * @param string $address
     * @param string $name
     * @return void
    */
    function AddCC($address, $name = "") {
        $cur = count($this->cc);
        $this->cc[$cur][0] = trim($address);
        $this->cc[$cur][1] = $name;
    }

    /**
     * Adds a "Bcc" address. Note: this function works
     * with the SMTP mailer on win32, not with the "mail"
     * mailer.  
     * @param string $address
     * @param string $name
     * @return void
     */
    function AddBCC($address, $name = "") {
        $cur = count($this->bcc);
        $this->bcc[$cur][0] = trim($address);
        $this->bcc[$cur][1] = $name;
    }

    /**
     * Adds a "Reply-to" address.  
     * @param string $address
     * @param string $name
     * @return void
     */
    function AddReplyTo($address, $name = "") {
        $cur = count($this->ReplyTo);
        $this->ReplyTo[$cur][0] = trim($address);
        $this->ReplyTo[$cur][1] = $name;
    }


    /////////////////////////////////////////////////
    // MAIL SENDING METHODS
    /////////////////////////////////////////////////

    /**
     * Creates message and assigns Mailer. If the message is
     * not sent successfully then it returns false.  Use the ErrorInfo
     * variable to view description of the error.  
     * @return bool
     */
    function Send() {
        $header = "";
        $body = "";
        $result = true;

        if((count($this->to) + count($this->cc) + count($this->bcc)) < 1)
        {
            $this->SetError($this->Lang("provide_address"));
            return false;
        }

        // Set whether the message is multipart/alternative
        if(!empty($this->AltBody))
            $this->ContentType = "multipart/alternative";

        $this->error_count = 0; // reset errors
        $this->SetMessageType();
        $header .= $this->CreateHeader();
        $body = $this->CreateBody();

        if($body == "") { return false; }

        // Choose the mailer
        switch($this->Mailer)
        {
            case "sendmail":
                $result = $this->SendmailSend($header, $body);
                break;
            case "mail":
                $result = $this->MailSend($header, $body);
                break;
            case "smtp":
                $result = $this->SmtpSend($header, $body);
                break;
            default:
            $this->SetError($this->Mailer . $this->Lang("mailer_not_supported"));
                $result = false;
                break;
        }

        return $result;
    }
    
    /**
     * Sends mail using the $Sendmail program.  
     * @access private
     * @return bool
     */
    function SendmailSend($header, $body) {
        if ($this->Sender != "")
            $sendmail = sprintf("%s -oi -f %s -t", $this->Sendmail, $this->Sender);
        else
            $sendmail = sprintf("%s -oi -t", $this->Sendmail);

        if(!@$mail = popen($sendmail, "w"))
        {
            $this->SetError($this->Lang("execute") . $this->Sendmail);
            return false;
        }

        fputs($mail, $header);
        fputs($mail, $body);
        
        $result = pclose($mail) >> 8 & 0xFF;
        if($result != 0)
        {
            $this->SetError($this->Lang("execute") . $this->Sendmail);
            return false;
        }

        return true;
    }

    /**
     * Sends mail using the PHP mail() function.  
     * @access private
     * @return bool
     */
    function MailSend($header, $body) {
        $to = "";
        for($i = 0; $i < count($this->to); $i++)
        {
            if($i != 0) { $to .= ", "; }
            $to .= $this->to[$i][0];
        }

        if ($this->Sender != "" && strlen(ini_get("safe_mode"))< 1)
        {
            $old_from = ini_get("sendmail_from");
            ini_set("sendmail_from", $this->Sender);
            $params = sprintf("-oi -f %s", $this->Sender);
            $rt = @mail($to, $this->EncodeHeader($this->Subject), $body, 
                        $header, $params);
        }
        else
            $rt = @mail($to, $this->EncodeHeader($this->Subject), $body, $header);

        if (isset($old_from))
            ini_set("sendmail_from", $old_from);

        if(!$rt)
        {
            $this->SetError($this->Lang("instantiate"));
            return false;
        }

        return true;
    }

    /**
     * Sends mail via SMTP using PhpSMTP (Author:
     * Chris Ryan).  Returns bool.  Returns false if there is a
     * bad MAIL FROM, RCPT, or DATA input.
     * @access private
     * @return bool
     */
    function SmtpSend($header, $body) {
        include_once($this->PluginDir . "class.smtp.php");
        $error = "";
        $bad_rcpt = array();

        if(!$this->SmtpConnect())
            return false;

        $smtp_from = ($this->Sender == "") ? $this->From : $this->Sender;
        if(!$this->smtp->Mail($smtp_from))
        {
            $error = $this->Lang("from_failed") . $smtp_from;
            $this->SetError($error);
            $this->smtp->Reset();
            return false;
        }

        // Attempt to send attach all recipients
        for($i = 0; $i < count($this->to); $i++)
        {
            if(!$this->smtp->Recipient($this->to[$i][0]))
                $bad_rcpt[] = $this->to[$i][0];
        }
        for($i = 0; $i < count($this->cc); $i++)
        {
            if(!$this->smtp->Recipient($this->cc[$i][0]))
                $bad_rcpt[] = $this->cc[$i][0];
        }
        for($i = 0; $i < count($this->bcc); $i++)
        {
            if(!$this->smtp->Recipient($this->bcc[$i][0]))
                $bad_rcpt[] = $this->bcc[$i][0];
        }

        if(count($bad_rcpt) > 0) // Create error message
        {
            for($i = 0; $i < count($bad_rcpt); $i++)
            {
                if($i != 0) { $error .= ", "; }
                $error .= $bad_rcpt[$i];
            }
            $error = $this->Lang("recipients_failed") . $error;
            $this->SetError($error);
            $this->smtp->Reset();
            return false;
        }

        if(!$this->smtp->Data($header . $body))
        {
            $this->SetError($this->Lang("data_not_accepted"));
            $this->smtp->Reset();
            return false;
        }
        if($this->SMTPKeepAlive == true)
            $this->smtp->Reset();
        else
            $this->SmtpClose();

        return true;
    }

    /**
     * Initiates a connection to an SMTP server.  Returns false if the 
     * operation failed.
     * @access private
     * @return bool
     */
    function SmtpConnect() {
        if($this->smtp == NULL) { $this->smtp = new SMTP(); }

        $this->smtp->do_debug = $this->SMTPDebug;
        $hosts = explode(";", $this->Host);
        $index = 0;
        $connection = ($this->smtp->Connected()); 

        // Retry while there is no connection
        while($index < count($hosts) && $connection == false)
        {
            if(strstr($hosts[$index], ":"))
                list($host, $port) = explode(":", $hosts[$index]);
            else
            {
                $host = $hosts[$index];
                $port = $this->Port;
            }

            if($this->smtp->Connect($host, $port, $this->Timeout))
            {
                if ($this->Helo != '')
                    $this->smtp->Hello($this->Helo);
                else
                    $this->smtp->Hello($this->ServerHostname());
        
                if($this->SMTPAuth)
                {
                    if(!$this->smtp->Authenticate($this->Username, 
                                                  $this->Password))
                    {
                        $this->SetError($this->Lang("authenticate"));
                        $this->smtp->Reset();
                        $connection = false;
                    }
                }
                $connection = true;
            }
            $index++;
        }
        if(!$connection)
            $this->SetError($this->Lang("connect_host"));

        return $connection;
    }

    /**
     * Closes the active SMTP session if one exists.
     * @return void
     */
    function SmtpClose() {
        if($this->smtp != NULL)
        {
            if($this->smtp->Connected())
            {
                $this->smtp->Quit();
                $this->smtp->Close();
            }
        }
    }

    /**
     * Sets the language for all class error messages.  Returns false 
     * if it cannot load the language file.  The default language type
     * is English.
     * @param string $lang_type Type of language (e.g. Portuguese: "br")
     * @param string $lang_path Path to the language file directory
     * @access public
     * @return bool
     */
    function SetLanguage($lang_type, $lang_path = "language/") {
        if(file_exists($lang_path.'phpmailer.lang-'.$lang_type.'.php'))
            include($lang_path.'phpmailer.lang-'.$lang_type.'.php');
        else if(file_exists($lang_path.'phpmailer.lang-en.php'))
            include($lang_path.'phpmailer.lang-en.php');
        else
        {
            $this->SetError("Could not load language file");
            return false;
        }
        $this->language = $PHPMAILER_LANG;
    
        return true;
    }

    /////////////////////////////////////////////////
    // MESSAGE CREATION METHODS
    /////////////////////////////////////////////////

    /**
     * Creates recipient headers.  
     * @access private
     * @return string
     */
    function AddrAppend($type, $addr) {
        $addr_str = $type . ": ";
        $addr_str .= $this->AddrFormat($addr[0]);
        if(count($addr) > 1)
        {
            for($i = 1; $i < count($addr); $i++)
                $addr_str .= ", " . $this->AddrFormat($addr[$i]);
        }
        $addr_str .= $this->LE;

        return $addr_str;
    }
    
    /**
     * Formats an address correctly. 
     * @access private
     * @return string
     */
    function AddrFormat($addr) {
        if(empty($addr[1]))
            $formatted = $addr[0];
        else
        {
            $formatted = $this->EncodeHeader($addr[1], 'phrase') . " <" . 
                         $addr[0] . ">";
        }

        return $formatted;
    }

    /**
     * Wraps message for use with mailers that do not
     * automatically perform wrapping and for quoted-printable.
     * Original written by philippe.  
     * @access private
     * @return string
     */
    function WrapText($message, $length, $qp_mode = false) {
        $soft_break = ($qp_mode) ? sprintf(" =%s", $this->LE) : $this->LE;

        $message = $this->FixEOL($message);
        if (substr($message, -1) == $this->LE)
            $message = substr($message, 0, -1);

        $line = explode($this->LE, $message);
        $message = "";
        for ($i=0 ;$i < count($line); $i++)
        {
          $line_part = explode(" ", $line[$i]);
          $buf = "";
          for ($e = 0; $e<count($line_part); $e++)
          {
              $word = $line_part[$e];
              if ($qp_mode and (strlen($word) > $length))
              {
                $space_left = $length - strlen($buf) - 1;
                if ($e != 0)
                {
                    if ($space_left > 20)
                    {
                        $len = $space_left;
                        if (substr($word, $len - 1, 1) == "=")
                          $len--;
                        elseif (substr($word, $len - 2, 1) == "=")
                          $len -= 2;
                        $part = substr($word, 0, $len);
                        $word = substr($word, $len);
                        $buf .= " " . $part;
                        $message .= $buf . sprintf("=%s", $this->LE);
                    }
                    else
                    {
                        $message .= $buf . $soft_break;
                    }
                    $buf = "";
                }
                while (strlen($word) > 0)
                {
                    $len = $length;
                    if (substr($word, $len - 1, 1) == "=")
                        $len--;
                    elseif (substr($word, $len - 2, 1) == "=")
                        $len -= 2;
                    $part = substr($word, 0, $len);
                    $word = substr($word, $len);

                    if (strlen($word) > 0)
                        $message .= $part . sprintf("=%s", $this->LE);
                    else
                        $buf = $part;
                }
              }
              else
              {
                $buf_o = $buf;
                $buf .= ($e == 0) ? $word : (" " . $word); 

                if (strlen($buf) > $length and $buf_o != "")
                {
                    $message .= $buf_o . $soft_break;
                    $buf = $word;
                }
              }
          }
          $message .= $buf . $this->LE;
        }

        return $message;
    }
    
    /**
     * Set the body wrapping.
     * @access private
     * @return void
     */
    function SetWordWrap() {
        if($this->WordWrap < 1)
            return;
            
        switch($this->message_type)
        {
           case "alt":
              // fall through
           case "alt_attachment":
              $this->AltBody = $this->WrapText($this->AltBody, $this->WordWrap);
              break;
           default:
              $this->Body = $this->WrapText($this->Body, $this->WordWrap);
              break;
        }
    }

    /**
     * Assembles message header.  
     * @access private
     * @return string
     */
    function CreateHeader() {
        $result = "";
        
        // Set the boundaries
        $uniq_id = md5(uniqid(time()));
        $this->boundary[1] = "b1_" . $uniq_id;
        $this->boundary[2] = "b2_" . $uniq_id;

        $result .= $this->HeaderLine("Date", $this->RFCDate());
        if($this->Sender == "")
            $result .= $this->HeaderLine("Return-Path", trim($this->From));
        else
            $result .= $this->HeaderLine("Return-Path", trim($this->Sender));
        
        // To be created automatically by mail()
        if($this->Mailer != "mail")
        {
            if(count($this->to) > 0)
                $result .= $this->AddrAppend("To", $this->to);
            else if (count($this->cc) == 0)
                $result .= $this->HeaderLine("To", "undisclosed-recipients:;");
            if(count($this->cc) > 0)
                $result .= $this->AddrAppend("Cc", $this->cc);
        }

        $from = array();
        $from[0][0] = trim($this->From);
        $from[0][1] = $this->FromName;
        $result .= $this->AddrAppend("From", $from); 

        // sendmail and mail() extract Bcc from the header before sending
        if((($this->Mailer == "sendmail") || ($this->Mailer == "mail")) && (count($this->bcc) > 0))
            $result .= $this->AddrAppend("Bcc", $this->bcc);

        if(count($this->ReplyTo) > 0)
            $result .= $this->AddrAppend("Reply-to", $this->ReplyTo);

        // mail() sets the subject itself
        if($this->Mailer != "mail")
            $result .= $this->HeaderLine("Subject", $this->EncodeHeader(trim($this->Subject)));

        $result .= sprintf("Message-ID: <%s@%s>%s", $uniq_id, $this->ServerHostname(), $this->LE);
        $result .= $this->HeaderLine("X-Priority", $this->Priority);
        $result .= $this->HeaderLine("X-Mailer", "PHPMailer [version " . $this->Version . "]");
        
        if($this->ConfirmReadingTo != "")
        {
            $result .= $this->HeaderLine("Disposition-Notification-To", 
                       "<" . trim($this->ConfirmReadingTo) . ">");
        }

        // Add custom headers
        for($index = 0; $index < count($this->CustomHeader); $index++)
        {
            $result .= $this->HeaderLine(trim($this->CustomHeader[$index][0]), 
                       $this->EncodeHeader(trim($this->CustomHeader[$index][1])));
        }
        $result .= $this->HeaderLine("MIME-Version", "1.0");

        switch($this->message_type)
        {
            case "plain":
                $result .= $this->HeaderLine("Content-Transfer-Encoding", $this->Encoding);
                $result .= sprintf("Content-Type: %s; charset=\"%s\"",
                                    $this->ContentType, $this->CharSet);
                break;
            case "attachments":
                // fall through
            case "alt_attachments":
                if($this->InlineImageExists())
                {
                    $result .= sprintf("Content-Type: %s;%s\ttype=\"text/html\";%s\tboundary=\"%s\"%s", 
                                    "multipart/related", $this->LE, $this->LE, 
                                    $this->boundary[1], $this->LE);
                }
                else
                {
                    $result .= $this->HeaderLine("Content-Type", "multipart/mixed;");
                    $result .= $this->TextLine("\tboundary=\"" . $this->boundary[1] . '"');
                }
                break;
            case "alt":
                $result .= $this->HeaderLine("Content-Type", "multipart/alternative;");
                $result .= $this->TextLine("\tboundary=\"" . $this->boundary[1] . '"');
                break;
        }

        if($this->Mailer != "mail")
            $result .= $this->LE.$this->LE;

        return $result;
    }

    /**
     * Assembles the message body.  Returns an empty string on failure.
     * @access private
     * @return string
     */
    function CreateBody() {
        $result = "";

        $this->SetWordWrap();

        switch($this->message_type)
        {
            case "alt":
                $result .= $this->GetBoundary($this->boundary[1], "", 
                                              "text/plain", "");
                $result .= $this->EncodeString($this->AltBody, $this->Encoding);
                $result .= $this->LE.$this->LE;
                $result .= $this->GetBoundary($this->boundary[1], "", 
                                              "text/html", "");
                
                $result .= $this->EncodeString($this->Body, $this->Encoding);
                $result .= $this->LE.$this->LE;
    
                $result .= $this->EndBoundary($this->boundary[1]);
                break;
            case "plain":
                $result .= $this->EncodeString($this->Body, $this->Encoding);
                break;
            case "attachments":
                $result .= $this->GetBoundary($this->boundary[1], "", "", "");
                $result .= $this->EncodeString($this->Body, $this->Encoding);
                $result .= $this->LE;
     
                $result .= $this->AttachAll();
                break;
            case "alt_attachments":
                $result .= sprintf("--%s%s", $this->boundary[1], $this->LE);
                $result .= sprintf("Content-Type: %s;%s" .
                                   "\tboundary=\"%s\"%s",
                                   "multipart/alternative", $this->LE, 
                                   $this->boundary[2], $this->LE.$this->LE);
    
                // Create text body
                $result .= $this->GetBoundary($this->boundary[2], "", 
                                              "text/plain", "") . $this->LE;

                $result .= $this->EncodeString($this->AltBody, $this->Encoding);
                $result .= $this->LE.$this->LE;
    
                // Create the HTML body
                $result .= $this->GetBoundary($this->boundary[2], "", 
                                              "text/html", "") . $this->LE;
    
                $result .= $this->EncodeString($this->Body, $this->Encoding);
                $result .= $this->LE.$this->LE;

                $result .= $this->EndBoundary($this->boundary[2]);
                
                $result .= $this->AttachAll();
                break;
        }
        if($this->IsError())
            $result = "";

        return $result;
    }

    /**
     * Returns the start of a message boundary.
     * @access private
     */
    function GetBoundary($boundary, $charSet, $contentType, $encoding) {
        $result = "";
        if($charSet == "") { $charSet = $this->CharSet; }
        if($contentType == "") { $contentType = $this->ContentType; }
        if($encoding == "") { $encoding = $this->Encoding; }

        $result .= $this->TextLine("--" . $boundary);
        $result .= sprintf("Content-Type: %s; charset = \"%s\"", 
                            $contentType, $charSet);
        $result .= $this->LE;
        $result .= $this->HeaderLine("Content-Transfer-Encoding", $encoding);
        $result .= $this->LE;
       
        return $result;
    }
    
    /**
     * Returns the end of a message boundary.
     * @access private
     */
    function EndBoundary($boundary) {
        return $this->LE . "--" . $boundary . "--" . $this->LE; 
    }
    
    /**
     * Sets the message type.
     * @access private
     * @return void
     */
    function SetMessageType() {
        if(count($this->attachment) < 1 && strlen($this->AltBody) < 1)
            $this->message_type = "plain";
        else
        {
            if(count($this->attachment) > 0)
                $this->message_type = "attachments";
            if(strlen($this->AltBody) > 0 && count($this->attachment) < 1)
                $this->message_type = "alt";
            if(strlen($this->AltBody) > 0 && count($this->attachment) > 0)
                $this->message_type = "alt_attachments";
        }
    }

    /**
     * Returns a formatted header line.
     * @access private
     * @return string
     */
    function HeaderLine($name, $value) {
        return $name . ": " . $value . $this->LE;
    }

    /**
     * Returns a formatted mail line.
     * @access private
     * @return string
     */
    function TextLine($value) {
        return $value . $this->LE;
    }

    /////////////////////////////////////////////////
    // ATTACHMENT METHODS
    /////////////////////////////////////////////////

    /**
     * Adds an attachment from a path on the filesystem.
     * Returns false if the file could not be found
     * or accessed.
     * @param string $path Path to the attachment.
     * @param string $name Overrides the attachment name.
     * @param string $encoding File encoding (see $Encoding).
     * @param string $type File extension (MIME) type.
     * @return bool
     */
    function AddAttachment($path, $name = "", $encoding = "base64", 
                           $type = "application/octet-stream") {
        if(!@is_file($path))
        {
            $this->SetError($this->Lang("file_access") . $path);
            return false;
        }

        $filename = basename($path);
        if($name == "")
            $name = $filename;

        $cur = count($this->attachment);
        $this->attachment[$cur][0] = $path;
        $this->attachment[$cur][1] = $filename;
        $this->attachment[$cur][2] = $name;
        $this->attachment[$cur][3] = $encoding;
        $this->attachment[$cur][4] = $type;
        $this->attachment[$cur][5] = false; // isStringAttachment
        $this->attachment[$cur][6] = "attachment";
        $this->attachment[$cur][7] = 0;

        return true;
    }

    /**
     * Attaches all fs, string, and binary attachments to the message.
     * Returns an empty string on failure.
     * @access private
     * @return string
     */
    function AttachAll() {
        // Return text of body
        $mime = array();

        // Add all attachments
        for($i = 0; $i < count($this->attachment); $i++)
        {
            // Check for string attachment
            $bString = $this->attachment[$i][5];
            if ($bString)
                $string = $this->attachment[$i][0];
            else
                $path = $this->attachment[$i][0];

            $filename    = $this->attachment[$i][1];
            $name        = $this->attachment[$i][2];
            $encoding    = $this->attachment[$i][3];
            $type        = $this->attachment[$i][4];
            $disposition = $this->attachment[$i][6];
            $cid         = $this->attachment[$i][7];
            
            $mime[] = sprintf("--%s%s", $this->boundary[1], $this->LE);
            $mime[] = sprintf("Content-Type: %s; name=\"%s\"%s", $type, $name, $this->LE);
            $mime[] = sprintf("Content-Transfer-Encoding: %s%s", $encoding, $this->LE);

            if($disposition == "inline")
                $mime[] = sprintf("Content-ID: <%s>%s", $cid, $this->LE);

            $mime[] = sprintf("Content-Disposition: %s; filename=\"%s\"%s", 
                              $disposition, $name, $this->LE.$this->LE);

            // Encode as string attachment
            if($bString)
            {
                $mime[] = $this->EncodeString($string, $encoding);
                if($this->IsError()) { return ""; }
                $mime[] = $this->LE.$this->LE;
            }
            else
            {
                $mime[] = $this->EncodeFile($path, $encoding);                
                if($this->IsError()) { return ""; }
                $mime[] = $this->LE.$this->LE;
            }
        }

        $mime[] = sprintf("--%s--%s", $this->boundary[1], $this->LE);

        return join("", $mime);
    }
    
    /**
     * Encodes attachment in requested format.  Returns an
     * empty string on failure.
     * @access private
     * @return string
     */
    function EncodeFile ($path, $encoding = "base64") {
        if(!@$fd = fopen($path, "rb"))
        {
            $this->SetError($this->Lang("file_open") . $path);
            return "";
        }
        $file_buffer = fread($fd, filesize($path));
        $file_buffer = $this->EncodeString($file_buffer, $encoding);
        fclose($fd);

        return $file_buffer;
    }

    /**
     * Encodes string to requested format. Returns an
     * empty string on failure.
     * @access private
     * @return string
     */
    function EncodeString ($str, $encoding = "base64") {
        $encoded = "";
        switch(strtolower($encoding)) {
          case "base64":
              // chunk_split is found in PHP >= 3.0.6
              $encoded = chunk_split(base64_encode($str), 76, $this->LE);
              break;
          case "7bit":
          case "8bit":
              $encoded = $this->FixEOL($str);
              if (substr($encoded, -(strlen($this->LE))) != $this->LE)
                $encoded .= $this->LE;
              break;
          case "binary":
              $encoded = $str;
              break;
          case "quoted-printable":
              $encoded = $this->EncodeQP($str);
              break;
          default:
              $this->SetError($this->Lang("encoding") . $encoding);
              break;
        }
        return $encoded;
    }

    /**
     * Encode a header string to best of Q, B, quoted or none.  
     * @access private
     * @return string
     */
    function EncodeHeader ($str, $position = 'text') {
      $x = 0;
      
      switch (strtolower($position)) {
        case 'phrase':
          if (!preg_match('/[\200-\377]/', $str)) {
            // Can't use addslashes as we don't know what value has magic_quotes_sybase.
            $encoded = addcslashes($str, "\0..\37\177\\\"");

            if (($str == $encoded) && !preg_match('/[^A-Za-z0-9!#$%&\'*+\/=?^_`{|}~ -]/', $str))
              return ($encoded);
            else
              return ("\"$encoded\"");
          }
          $x = preg_match_all('/[^\040\041\043-\133\135-\176]/', $str, $matches);
          break;
        case 'comment':
          $x = preg_match_all('/[()"]/', $str, $matches);
          // Fall-through
        case 'text':
        default:
          $x += preg_match_all('/[\000-\010\013\014\016-\037\177-\377]/', $str, $matches);
          break;
      }

      if ($x == 0)
        return ($str);

      $maxlen = 75 - 7 - strlen($this->CharSet);
      // Try to select the encoding which should produce the shortest output
      if (strlen($str)/3 < $x) {
        $encoding = 'B';
        $encoded = base64_encode($str);
        $maxlen -= $maxlen % 4;
        $encoded = trim(chunk_split($encoded, $maxlen, "\n"));
      } else {
        $encoding = 'Q';
        $encoded = $this->EncodeQ($str, $position);
        $encoded = $this->WrapText($encoded, $maxlen, true);
        $encoded = str_replace("=".$this->LE, "\n", trim($encoded));
      }

      $encoded = preg_replace('/^(.*)$/m', " =?".$this->CharSet."?$encoding?\\1?=", $encoded);
      $encoded = trim(str_replace("\n", $this->LE, $encoded));
      
      return $encoded;
    }
    
    /**
     * Encode string to quoted-printable.  
     * @access private
     * @return string
     */
    function EncodeQP ($str) {
        $encoded = $this->FixEOL($str);
        if (substr($encoded, -(strlen($this->LE))) != $this->LE)
            $encoded .= $this->LE;

        // Replace every high ascii, control and = characters
        $encoded = preg_replace('/([\000-\010\013\014\016-\037\075\177-\377])/e',
                  "'='.sprintf('%02X', ord('\\1'))", $encoded);
        // Replace every spaces and tabs when it's the last character on a line
        $encoded = preg_replace("/([\011\040])".$this->LE."/e",
                  "'='.sprintf('%02X', ord('\\1')).'".$this->LE."'", $encoded);

        // Maximum line length of 76 characters before CRLF (74 + space + '=')
        $encoded = $this->WrapText($encoded, 74, true);

        return $encoded;
    }

    /**
     * Encode string to q encoding.  
     * @access private
     * @return string
     */
    function EncodeQ ($str, $position = "text") {
        // There should not be any EOL in the string
        $encoded = preg_replace("[\r\n]", "", $str);

        switch (strtolower($position)) {
          case "phrase":
            $encoded = preg_replace("/([^A-Za-z0-9!*+\/ -])/e", "'='.sprintf('%02X', ord('\\1'))", $encoded);
            break;
          case "comment":
            $encoded = preg_replace("/([\(\)\"])/e", "'='.sprintf('%02X', ord('\\1'))", $encoded);
          case "text":
          default:
            // Replace every high ascii, control =, ? and _ characters
            $encoded = preg_replace('/([\000-\011\013\014\016-\037\075\077\137\177-\377])/e',
                  "'='.sprintf('%02X', ord('\\1'))", $encoded);
            break;
        }
        
        // Replace every spaces to _ (more readable than =20)
        $encoded = str_replace(" ", "_", $encoded);

        return $encoded;
    }

    /**
     * Adds a string or binary attachment (non-filesystem) to the list.
     * This method can be used to attach ascii or binary data,
     * such as a BLOB record from a database.
     * @param string $string String attachment data.
     * @param string $filename Name of the attachment.
     * @param string $encoding File encoding (see $Encoding).
     * @param string $type File extension (MIME) type.
     * @return void
     */
    function AddStringAttachment($string, $filename, $encoding = "base64", 
                                 $type = "application/octet-stream") {
        // Append to $attachment array
        $cur = count($this->attachment);
        $this->attachment[$cur][0] = $string;
        $this->attachment[$cur][1] = $filename;
        $this->attachment[$cur][2] = $filename;
        $this->attachment[$cur][3] = $encoding;
        $this->attachment[$cur][4] = $type;
        $this->attachment[$cur][5] = true; // isString
        $this->attachment[$cur][6] = "attachment";
        $this->attachment[$cur][7] = 0;
    }
    
    /**
     * Adds an embedded attachment.  This can include images, sounds, and 
     * just about any other document.  Make sure to set the $type to an 
     * image type.  For JPEG images use "image/jpeg" and for GIF images 
     * use "image/gif".
     * @param string $path Path to the attachment.
     * @param string $cid Content ID of the attachment.  Use this to identify 
     *        the Id for accessing the image in an HTML form.
     * @param string $name Overrides the attachment name.
     * @param string $encoding File encoding (see $Encoding).
     * @param string $type File extension (MIME) type.  
     * @return bool
     */
    function AddEmbeddedImage($path, $cid, $name = "", $encoding = "base64", 
                              $type = "application/octet-stream") {
    
        if(!@is_file($path))
        {
            $this->SetError($this->Lang("file_access") . $path);
            return false;
        }

        $filename = basename($path);
        if($name == "")
            $name = $filename;

        // Append to $attachment array
        $cur = count($this->attachment);
        $this->attachment[$cur][0] = $path;
        $this->attachment[$cur][1] = $filename;
        $this->attachment[$cur][2] = $name;
        $this->attachment[$cur][3] = $encoding;
        $this->attachment[$cur][4] = $type;
        $this->attachment[$cur][5] = false; // isStringAttachment
        $this->attachment[$cur][6] = "inline";
        $this->attachment[$cur][7] = $cid;
    
        return true;
    }
    
    /**
     * Returns true if an inline attachment is present.
     * @access private
     * @return bool
     */
    function InlineImageExists() {
        $result = false;
        for($i = 0; $i < count($this->attachment); $i++)
        {
            if($this->attachment[$i][6] == "inline")
            {
                $result = true;
                break;
            }
        }
        
        return $result;
    }

    /////////////////////////////////////////////////
    // MESSAGE RESET METHODS
    /////////////////////////////////////////////////

    /**
     * Clears all recipients assigned in the TO array.  Returns void.
     * @return void
     */
    function ClearAddresses() {
        $this->to = array();
    }

    /**
     * Clears all recipients assigned in the CC array.  Returns void.
     * @return void
     */
    function ClearCCs() {
        $this->cc = array();
    }

    /**
     * Clears all recipients assigned in the BCC array.  Returns void.
     * @return void
     */
    function ClearBCCs() {
        $this->bcc = array();
    }

    /**
     * Clears all recipients assigned in the ReplyTo array.  Returns void.
     * @return void
     */
    function ClearReplyTos() {
        $this->ReplyTo = array();
    }

    /**
     * Clears all recipients assigned in the TO, CC and BCC
     * array.  Returns void.
     * @return void
     */
    function ClearAllRecipients() {
        $this->to = array();
        $this->cc = array();
        $this->bcc = array();
    }

    /**
     * Clears all previously set filesystem, string, and binary
     * attachments.  Returns void.
     * @return void
     */
    function ClearAttachments() {
        $this->attachment = array();
    }

    /**
     * Clears all custom headers.  Returns void.
     * @return void
     */
    function ClearCustomHeaders() {
        $this->CustomHeader = array();
    }


    /////////////////////////////////////////////////
    // MISCELLANEOUS METHODS
    /////////////////////////////////////////////////

    /**
     * Adds the error message to the error container.
     * Returns void.
     * @access private
     * @return void
     */
    function SetError($msg) {
        $this->error_count++;
        $this->ErrorInfo = $msg;
    }

    /**
     * Returns the proper RFC 822 formatted date. 
     * @access private
     * @return string
     */
    function RFCDate() {
        $tz = date("Z");
        $tzs = ($tz < 0) ? "-" : "+";
        $tz = abs($tz);
        $tz = ($tz/3600)*100 + ($tz%3600)/60;
        $result = sprintf("%s %s%04d", date("D, j M Y H:i:s"), $tzs, $tz);

        return $result;
    }
    
    /**
     * Returns the appropriate server variable.  Should work with both 
     * PHP 4.1.0+ as well as older versions.  Returns an empty string 
     * if nothing is found.
     * @access private
     * @return mixed
     */
    function ServerVar($varName) {
        global $HTTP_SERVER_VARS;
        global $HTTP_ENV_VARS;

        if(!isset($_SERVER))
        {
            $_SERVER = $HTTP_SERVER_VARS;
            if(!isset($_SERVER["REMOTE_ADDR"]))
                $_SERVER = $HTTP_ENV_VARS; // must be Apache
        }
        
        if(isset($_SERVER[$varName]))
            return $_SERVER[$varName];
        else
            return "";
    }

    /**
     * Returns the server hostname or 'localhost.localdomain' if unknown.
     * @access private
     * @return string
     */
    function ServerHostname() {
        if ($this->Hostname != "")
            $result = $this->Hostname;
        elseif ($this->ServerVar('SERVER_NAME') != "")
            $result = $this->ServerVar('SERVER_NAME');
        else
            $result = "localhost.localdomain";

        return $result;
    }

    /**
     * Returns a message in the appropriate language.
     * @access private
     * @return string
     */
    function Lang($key) {
        if(count($this->language) < 1)
            $this->SetLanguage("en"); // set the default language
    
        if(isset($this->language[$key]))
            return $this->language[$key];
        else
            return "Language string failed to load: " . $key;
    }
    
    /**
     * Returns true if an error occurred.
     * @return bool
     */
    function IsError() {
        return ($this->error_count > 0);
    }

    /**
     * Changes every end of line from CR or LF to CRLF.  
     * @access private
     * @return string
     */
    function FixEOL($str) {
        $str = str_replace("\r\n", "\n", $str);
        $str = str_replace("\r", "\n", $str);
        $str = str_replace("\n", $this->LE, $str);
        return $str;
    }

    /**
     * Adds a custom header. 
     * @return void
     */
    function AddCustomHeader($custom_header) {
        $this->CustomHeader[] = explode(":", $custom_header, 2);
    }
}
	

?>
