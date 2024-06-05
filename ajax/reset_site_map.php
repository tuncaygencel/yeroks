<?php
class sitemap_aperate
	{

	private $min_id=0;
	private $max_id=399999999;
	private $table_number;
	private $sitemap_name_number=-1;
	private $baslangic_etiketi=-1;
	private $bitis_etiketi=-1;
	private $second_level=-1; //sinir degerlerinde birinci satirmi ikinci satirmi kullanilacagini belirler.
	private $level=-1;
	private $next_level=0;
	private $limit;
	
function change($text)
				{
				$text = trim($text);
				$search = array("Ç","ç","Ğ","ğ","ı","İ","Ö","ö","Ş","ş","Ü","ü"," ","’","'","&",".","(",")","/",'\'');
				$replace = array("C","c","G","g","i","I","O","o","S","s","U","u","-","-","-","-","","-","","-",'-');
				return str_replace($search,$replace,$text);
				}
								
				
function set_yield_sitemap()
				{
				
				$sql="SELECT shop_y_list.shop_id, shop_y_list.y_type_id, y_name_list.y_name,shop.s_name FROM shop_y_list 
				INNER JOIN y_name_list ON y_name_list.y_type_id=shop_y_list.y_type_id 
				INNER JOIN shop ON shop.s_id=shop_y_list.shop_id  
				WHERE id >= $this->min_id and id <= $this->max_id Order By id ASC";
				
				$sql=mysql_query($sql) or die(mysql_error());
				if($this->baslangic_etiketi==1){
				$sitemap= '<?xml version="1.0" encoding="UTF-8"?>
							<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';
				}else{
				$sitemap="";
				}
				
				while($res=mysql_fetch_assoc($sql))
							{
							$sitemap=$sitemap.'
							<url>
							<loc>https://www.yeroks.com/'.$res['shop_id'].'/'.$res['y_type_id'].'/'.self::change($res['s_name']).'/'.self::change($res['y_name']).'</loc>
							<changefreq>weekly</changefreq>
							</url>';
							}
					$sql="SELECT MAX(id) as max_id FROM shop_y_list";		
					$sql=mysql_query($sql) or die(mysql_error());
					$res=mysql_fetch_assoc($sql);
					if( (($this->limit*$this->level)>= $res['max_id']) or $this->bitis_etiketi==1 )
					{
					$sitemap=$sitemap."
							</urlset>";
					}
					if( ($this->limit*$this->level)>= $res['max_id'] )
					{
					$this->next_level=-1;
					}
					$file=self::create_sitemap_name();
					if($this->baslangic_etiketi==1){
					$myfile = fopen($file, "w") or die("{\"error\":1,\"exp\":\"Unable to open file!\"}");
					}else{
					$myfile = fopen($file, "a") or die("{\"error\":1,\"exp\":\"Unable to open file!\"}");
					}
						if(fwrite($myfile, $sitemap."\n")){
						echo "{\"error\":0,\"second_level\":".$this->second_level.",\"next_level\":".$this->next_level.",\"limit\":".$this->limit.",\"exp\":\"Site Haritası Yenilendi...\"}";
						}else{
						echo "{\"error\":1,\"exp\":\"Site Haritası Yenilenirken Bir Sorun Oluştu...\"}";
						} 
						fclose($myfile);
				}
function set_shop_sitemap()
				{
				
				$sql="SELECT s_id,s_name FROM shop WHERE s_id >= $this->min_id and s_id <= $this->max_id Order By s_id ASC";
				$sql=mysql_query($sql) or die(mysql_error());
				if($this->baslangic_etiketi==1){
				$sitemap= '<?xml version="1.0" encoding="UTF-8"?>
							<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';
				}else{
				$sitemap="";
				}
				while($res=mysql_fetch_assoc($sql))
							{
							$sitemap=$sitemap.'
							<url>
							<loc>https://www.yeroks.com/'.$res['s_id'].'/'.self::change($res['s_name']).'</loc>
							<changefreq>weekly</changefreq>
							</url>';
							}
					$sql="SELECT MAX(s_id) as max_id FROM shop";	
					$sql=mysql_query($sql) or die(mysql_error());
					$res=mysql_fetch_assoc($sql);
					if( (($this->limit*$this->level)>= $res['max_id']) or $this->bitis_etiketi==1 )
					{
					$sitemap=$sitemap."
							</urlset>";
					}
					if( ($this->limit*$this->level)>= $res['max_id'] )
					{
					$this->next_level=-1;
					}
				$file=self::create_sitemap_name();
				if($this->level==1){
					$myfile = fopen($file, "w") or die("{\"error\":1,\"exp\":\"Unable to open file!\"}");
					}else{
					$myfile = fopen($file, "a") or die("{\"error\":1,\"exp\":\"Unable to open file!\"}");
					}
						if(fwrite($myfile, $sitemap."\n")){
						echo "{\"error\":0,\"second_level\":".$this->second_level.",\"next_level\":".$this->next_level.",\"limit\":".$this->limit.",\"exp\":\"Site Haritası Yenilendi...\"}";
						}else{
						echo "{\"error\":1,\"exp\":\"Site Haritası Yenilenirken Bir Sorun Oluştu...\"}";
						}
						fclose($myfile);
				}		
	function set_table_name()
		{
			switch($this->table_number)
					{
						case 1: return "y1"; break;
						case 2: return "y2"; break;
						case 3: return "y3"; break;
						case 4: return "y4"; break;
						case 5: return "y5"; break;
						case 6: return "y6"; break;
						case 7: return "y7"; break;
						case 8: return "y8"; break;
						case 9: return "y9"; break;
						case 10: return "y10"; break;
						case 11: return "shop"; break;
						default: die("{\"error\":1,\"exp\":\"Gonderilen Komut Degeri  Gecersiz\"}");
					}
		}
	
	function set_operation_datas($table_number,$level,$limit,$second_level)
		{
			$max_id=$level*$limit;
			$min_id=$max_id-$limit+1;
			$this->level=$level;
			$this->table_number=$table_number;
			$this->limit=$limit;
			$sql="SELECT*FROM sitemap_data WHERE table_number='$table_number' and (( max_id >= '$min_id' and min_id <=$min_id ) or ( max_id >= $max_id and min_id <=$max_id ))";
			$sql=mysql_query($sql) or die(mysql_error());
			$result_number=mysql_num_rows($sql);
			if($result_number==0)
				{
					die("{\"error\":1,\"exp\":\"Gonderilen Komut Degeri icin sitemap_data verisi bulunamadi\"}");
				}elseif($result_number==1){
					$res=mysql_fetch_assoc($sql);
					//eger veritabani min_id=$min_id=>	private $baslangic_etiketi=1;
					//eger veritabani max_id=$max_id=>private $bitis_etiketi=1;
					$this->sitemap_name_number=$res['name_number'];
					if($min_id==$res['min_id']){ $this->baslangic_etiketi=1; }else{ $this->baslangic_etiketi=0; }
					if($max_id==$res['max_id']){ $this->bitis_etiketi=1; }else{ $this->bitis_etiketi=0; }
					$this->min_id=$min_id;
					$this->max_id=$max_id;
					$this->table_number=$table_number;
					$this->second_level=0;
					$this->next_level=$level+1;
				}elseif($result_number==2){
						$i=1;
						
						while($res=mysql_fetch_assoc($sql)){
							if( $second_level==0 ){
								if($i==1){ 
								$this->sitemap_name_number=$res['name_number']; 
								$this->min_id=$min_id;
								$this->max_id=$res['max_id'];
								$this->next_level=$level;
								$this->second_level=1;
								}
							}
							
							if( $second_level==1 ){
								if($i==2){
								$this->sitemap_name_number=$res['name_number']; 
								$this->min_id=$res['min_id'];
								$this->max_id=$max_id;
								$this->next_level=$level+1;
								$this->second_level=0;
								}
							}
							$i=$i+1;
						}
						
					if($second_level==0){ $this->baslangic_etiketi=0;$this->bitis_etiketi=1; }
					if($second_level==1){ $this->baslangic_etiketi=1;$this->bitis_etiketi=0; }
					//chaging_id ilk while basamaginin max id'sidir.
				}else{
					die("{\"error\":1,\"exp\":\"Gonderilen Komut Degeri icin sitemap_data verisi 2den fazla donduruldu.Yazilim Hatasi!\"}");
				}
		}
		
	function create_sitemap_name()
		{
		//$this->table_number hangi y1.2.3. tabl oldugunu ve $name_number ise sitemap_data daki name_number degiskenini secer
		$plugin="";
		if($this->sitemap_name_number==1){
		$plugin="_1.xml";
		}elseif($this->sitemap_name_number>1){
		$plugin="_".$this->sitemap_name_number.".xml";
		}else{
		die("{\"error\":1,\"exp\":\"sitemap isim uzantisi olusturulurken hata olustu!\"}");
		}
				switch($this->table_number)
					{
						case 1:
						return "../sitemap_kjcdkmlundlah_1".$plugin;
						break;	
						case 2:
						return "../sitemap_kjcdkmlundlah_2".$plugin;
						break;					
						case 3:
						return "../sitemap_kjcdkmlundlah_3".$plugin;
						break;					
						case 4:
						return "../sitemap_kjcdkmlundlah_4".$plugin;
						break;					
						case 5:
						return "../sitemap_kjcdkmlundlah_5".$plugin;
						break;					
						case 6:
						return "../sitemap_kjcdkmlundlah_6".$plugin;
						break;					
						case 7:
						return "../sitemap_kjcdkmlundlah_7".$plugin;
						break;	
						case 8:
						return "../sitemap_kjcdkmlundlah_8".$plugin;
						break;
						case 9:
						return "../sitemap_kjcdkmlundlah_9".$plugin;
						break;
						case 10:
						return "../sitemap_kjcdkmlundlah_10".$plugin;
						break;
						case 11:
						return "../sitemap_s_kjcdkmlundlah_1".$plugin;
						break;	
						default: echo "{\"error\":1,\"exp\":\"Sitemapname Icin Gonderilen Komut Degeri  Gecersiz\"}";
					}			
		}
	}
	session_start();
	if(isset($_SESSION['user_type'])  and isset($_SESSION['user_no']) and isset($_SESSION['mail_adress']))
		{
		if($_SESSION['user_no']==1 and $_SESSION['user_type']==1 )
			{
			if(isset($_POST['order']) and isset($_POST['level']) and isset($_POST['limit']) and isset($_POST['second_level']))
				{
				include("kjasafhfjaghl.php");
				$connect=new connect();
				$connect->sql_connect_db();
				$order=mysql_escape_string(htmlspecialchars(trim((int)$_POST['order'])));	
				$level=mysql_escape_string(htmlspecialchars(trim((int)$_POST['level'])));
				$limit=mysql_escape_string(htmlspecialchars(trim((int)$_POST['limit'])));
				$second_level=mysql_escape_string(htmlspecialchars(trim((int)$_POST['second_level'])));
				if($limit<0 or $limit>31000){
				die("{\"error\":1,\"exp\":\"limit istenilen aralikta degil!\"}");
				}
				$oper=new sitemap_aperate();
				$oper->set_operation_datas($order,$level,$limit,$second_level);
				
				if($order==11){
					$oper->set_shop_sitemap();
					}else{
					$oper->set_yield_sitemap();
					}
					
				}else{
				echo "{\"error\":1,\"exp\":\"Veri Gonderim Hatasi!\"}";
				}
			}else{
				echo "HATA! Sayfa Bulunamadı...!";
			}
	}else{
		echo "HATA! Sayfa Bulunamadı...!";
	}

?>