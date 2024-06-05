<meta http-equiv="Content-Type" content="text/HTML"; charset="utf-8" />
<?php
//error_reporting(null);
include("kjasafhfjaghl.php");
session_start();
$connect=new connect();
$connect->sql_connect_db();
	
	$user_type=2;
	if($user_type=='1' or $user_type=='2')
		{
			
			$change_mail='';
			if(isset($_POST['pass_change_data']))
				{
				$change_mail=mysql_real_escape_string(htmlspecialchars(trim($_POST['pass_change_data'])));
				}else{
				die("Bir Hata Oluştu...");
				}
				
			if($change_mail!='')
				{
						$send_datas=new change_pass();
						$send_datas->set_datas($change_mail,$user_type);
						if($send_datas->control_datas())
							{
								if($send_datas->control_active_pass_data($user_type))
									{
									echo "<div class=\"change_pass_result\">30 dakika içinde, şifre değiştirme bilgilerini E-postanıza göndermişsiniz.E-postanızdan şifrenizi
										değiştirebilirsiniz.Eğer yeni bir şifre değiştirme bilgisini 
										E-postanıza gönderilmesini istiyorsanız 30 dakika sonra tekrar deneyebilirsiniz</div>";
									}else
									{
									if($send_datas->send_to_mail_datas($user_type))
										{
										echo "<div class=\"change_pass_result\">Şifre değiştirme bilgileri E-postanıza gönderildi.Şifrenizi E-postanıza 
										gönderilen bilgiler aracılığıyla değiştirebilirsiniz.</div>";
										}
									}
							}else
							{
?>
					<form id="pass_change_form">
					<table>
					<tbody>
					<tr>
					<td class="change_pass_info_text" >
					E-Postanızı Giriniz:
					</td>
					<td>
					</td>
					</tr>
					<tr>
					<td>
					<input type="text" name="mail_input_to_change_pass" id="change_pass_input" />
					<input type="hidden" name="change_pass_type" value="<?php echo $user_type;?>"/>
					</td>
					<td>
					<div  onclick="send_datas()" class="mail_sent_button">
					Gönder
					</div>
					</td>
					</tr>
					<tbody>
					</table>
					</form>
					<div class="warning_of_pass_change">
		<?php 		if($user_type=='1'){
						echo "Girdiğiniz Maile Ait Bir Bireysel Kullanıcı Hesabı bulunamadı...";
						}else{
						echo "Girdiğiniz Maile Ait Bir Mağaza Hesabı bulunamadı...";
							}
		?>			</div>
			<?php
					}
				}else
				{
				?>
				<form id="pass_change_form">
					<table>
					<tbody>
					<tr>
					<td class="change_pass_info_text" >
					E-Postanızı Giriniz:
					</td>
					<td>
					</td>
					</tr>
					<tr>
					<td>
					<input type="text" name="mail_input_to_change_pass" id="change_pass_input"/>
					<input type="hidden" name="change_pass_type" value="<?php echo $user_type;?>"/>
					</td>
					<td>
					<div  onclick="send_datas()" class="mail_sent_button">
					Gönder
					</div>
					</td>
					</tr>
					<tbody>
					</table>
					</form>
					<div class="warning_of_pass_change">
					E-Postanızı Girmediniz...
					</div>
				<?php
				}
			}else
			{
			echo "HATA! <meta http-equiv=\"refresh\" content=1;URL=forgetten_password.php?type=user>";
			}
?>