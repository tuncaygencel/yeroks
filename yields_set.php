<?php
session_start();
if(isset($_SESSION['user_type'])  and isset($_SESSION['user_no']) and isset($_SESSION['mail_adress']))
	{
		if($_SESSION['user_no']==1 and $_SESSION['user_type']==1 )
			{
			include("ajax/kjasafhfjaghl.php");
			$connect=new connect();
			$connect->sql_connect_db();
				?>
				<html>
				<head>
				<meta name = "viewport" content="width=320, user-scalable=0" />
				<meta http-equiv="Content-Type" content="text/HTML"; charset="utf-8" />
				<style>
				.yield_red,.yield_green,.yield_black{
				width:294px;
				height:auto;
				margin-left:auto;
				margin-right:auto;
				margin-top:3px;
				padding:3px;
				border:1px solid rgb(240,240,240);
				color:black;
				font-size:20px;
				font-family:arial;
				}
				.yield_red{
				background-color:rgb(255, 0, 0);
				}
				.yield_green{
				background-color:rgb(0, 255, 111);
				}
				.yield_black{
				background-color:rgb(0, 0, 0);
				}
				#ch{
				width:30px;
				height:20px;
				background-color:black;
				color:white;
				float:right;
				padding:3px;
				cursor:pointer;
				}
				#order_form{
				margin-left: auto;
				margin-right: auto;
				margin-top:10px;
				padding: 3px;
				color: black;
				font-size: 14px;
				width: 300px;
				}
				#order_button{
				margin-left: auto;
				margin-right: auto;
				margin-top: 10px;
				padding: 4px;
				color: white;
				box-sizing: border-box;
				font-size: 18px;
				font-family: Arial;
				text-align: center;
				width: 300px;
				cursor: pointer;
				background-color: #7D0001;
				margin-bottom: 10px;
				}
				#reset1_result,#reset2_result{
				margin-left: auto;
				margin-right: auto;
				width: 300px;
				color:black;
				margin-bottom: 100px;
				}
				#count{
				padding:4px;
				margin:auto;
				color:black;
				font-size:20px;
				font-family:Arial;
				}
				#limit_area{
				margin:20px auto 20px auto;
				}
				#black_display{
				width:100%;
				height:100%;
				position:fixed;
				background-color:black;
				opacity:0.5;
				display:none;
				margin:0;
				}
				</style>
				<script type="text/javascript" src="scripts/jquery-1.11.1.min.js"></script>
				<script type="text/javascript">
					var level=1;
					var limit=5000;
					var second_level=0;
					function change(y_data)
							{
								$("#black_display").css("display","block");
								$.ajax({
									type:'POST',
									url:'ajax/change_in_season.php',
									dataType: 'json',				
									data:'&y_data='+y_data,
									success: function(data) {
											if(data.problem==0)
											{
												if(data.is_season==1){
												$("#type-"+data.y_type_id).css("background-color","rgb(0, 255, 111)");
												}else if(data.is_season==0){
												$("#type-"+data.y_type_id).css("background-color","rgb(255, 0, 0)");	
												}else{
												alert("Ciddi Sorun!season islem yapilmasina ragmen istenilen degerde degil!");
												}
											}else
											{
												alert(data.explain);
											}
											$("#black_display").css("display","none");
										}
									});
									
				
							}
							//order tablo secimidir,
							//limit kac adet link tek istekte islenecegidir,
							//level ayni tablo isteginde kacinci istekte bulunuldugudur,
							///
							function reset_site_map()
							{
									$('#reset2_result').html("<img src=\"img_files/line_style_loading.gif\"/>");	
									var order=document.getElementById('order_form2').value;
								$.ajax({
									type:'POST',
									url:'ajax/reset_site_map.php',
									dataType: 'json',
									data:'&order='+order+'&level='+level+'&limit='+limit+'&second_level='+second_level,
									success: function(data) {
									$('#reset2_result').html(data);
								
											if(data.error==1)
											{
											level=1;
											second_level=0;
											alert(data.exp);
											$('#reset2_result').html("Veriler sifirlandi!...");
											}else if(data.error==0)
											{
												if(data.next_level==-1)
												{
												level=1;
												second_level=0;
												$('#reset2_result').html("Site Haritasi Tamamiyle Olusturuldu-"+data.exp+", limit_degeri="+data.limit);
												}else{
												level=data.next_level;
												second_level=data.second_level;
												$('#reset2_result').html("Site Haritasinin olusturulmasina devam etmek icin devam ediniz. Sitemap Olusturma Asamasi="+data.next_level+", limit_degeri="+data.limit+", second_level="+second_level);
												}
											}else{
											alert("ajax dosyasinda ciddi yazili hatasi!!!!!");
											}
										
									}
									});
							}
							function set_limit()
							{
							limit=document.getElementById('limit_number').value;
							$('#limit_area').html("belirlenen limit sayisi="+limit);
							}
					</script>
				</head>
				<body>
				<div id="black_display" > </div>
				[Kırmızı-0:Geçersiz; Yeşil-1:Kullanımda]
				<?php
				$sql="SELECT y_type_id,y_name,is_y,is_in_season FROM y_name_list WHERE is_y=1";
				$sql=mysql_query($sql) or die(mysql_error());
				
				while($res=mysql_fetch_assoc($sql))
					{
						if($res['is_in_season']==0)
							{
							echo "<div id=\"type-".$res['y_type_id']."\" class=\"yield_red\">".$res['y_name']."<div id=\"ch\" onclick=\"change(".$res['y_type_id'].")\">CH</div></div>";
							}elseif($res['is_in_season']==1){
							echo "<div id=\"type-".$res['y_type_id']."\" class=\"yield_green\">".$res['y_name']."<div id=\"ch\" onclick=\"change(".$res['y_type_id'].")\">CH</div></div>";
							}else{
							echo "<div id=\"type-".$res['y_type_id']."\" class=\"yield_black\">".$res['y_name']."<div id=\"ch\" onclick=\"change(".$res['y_type_id'].")\">CH</div></div>";
							}
					}
				?>
				<div id="limit_area">
				<select id="limit_number" onchange="set_limit()" >
				<option value=0>Kademe Sec</option>
				<option value=1000>1000 lik </option>
				<option value=2000>2000 lik </option>
				<option value=3000>3000 lik </option>
				<option value=4000>4000 lik </option>
				<option value=5000>5000 lik </option>
				<option value=6000>6000 lik </option>
				<option value=7000>7000 lik </option>
				<option value=8000>8000 lik </option>
				<option value=9000>9000 lik </option>
				<option value=10000>10000 lik </option>
				<option value=20000>20000 lik </option>
				<option value=30000>30000 lik </option>
				</select>
				</div>
				<?php
				$sql1="SELECT
						(SELECT COUNT(*) FROM y1 ) as count1, 
						(SELECT COUNT(*) FROM y2 ) as count2,
						(SELECT COUNT(*) FROM y3 ) as count3,
						(SELECT COUNT(*) FROM y4 ) as count4,
						(SELECT COUNT(*) FROM y5 ) as count5,
						(SELECT COUNT(*) FROM y6 ) as count6,
						(SELECT COUNT(*) FROM y7 ) as count7,
						(SELECT COUNT(*) FROM y8 ) as count8,
						(SELECT COUNT(*) FROM y9 ) as count9,
						(SELECT COUNT(*) FROM y10 ) as count10,
						(SELECT COUNT(*) FROM shop ) as counts ";
						$sql1=mysql_query($sql1) or die(mysql_error());
						$res1=mysql_fetch_assoc($sql1);
				echo "<div id=\"count\">[y1=".$res1['count1']."]-["."y2=".$res1['count2']."]-["."y3=".$res1['count3']."]-["."y4=".$res1['count4']."]-["."y5=".$res1['count5']."]-["."y6=".$res1['count6']."]-["."y7=".$res1['count7']."]-[y8=".$res1['count8']."]-[y9=".$res1['count9']."]-[y10=".$res1['count10']."]-["."shop=".$res1['counts']."]</div>";
				?>
				<select id="order_form2">
				<option value=-1>Komut Seç</option>
				<option value=1>1.(Yiyecek vb.)  Sitemapı Yenile</option>
				<option value=2>2.(Giyim vb.)  Sitemapı Yenile</option>
				<option value=3>3.(Kozmetik-Kişisel Bakım vb.) Sitemapı Yenile</option>
				<option value=4>4.(Elektronik vb.) Sitemapı Yenile</option>
				<option value=5>5.(Ev Gereçleri vb.) Sitemapı Yenile</option>
				<option value=6>6.(Ev Dekorasyon-Mobilya vb.) Sitemapı Yenile</option>
				<option value=7>7.(Kitap-Dergi vb.)Sitemapı Yenile</option>
				<option value=8>8. Market Urunleri</option>
				<option value=9>9. Spor Outdoor</option>
				<option value=10>10. Oyuncak ve Eglence Urunleri</option>
				<option value=11>Shop Sitemapı Yenile</option>
				</select>
				<div id="order_button" onclick="reset_site_map()">
				Komutu Gerceklestir!
				</div>
				<div id="reset2_result">
				</div>
				</body>
				</html>
				<?php
			}else{
			include("error_page/error.php");
			}
	}else{
	include("error_page/error.php");
	}
?>