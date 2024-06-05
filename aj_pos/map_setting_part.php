<div class="shop_type_select">
<div class="shop_type_text">
Mağazanız Hangi Tip?
</div>
<div style="margin: auto;display: inline-block;">
<div id="shop_center_type" onclick="$('.shop_type_select').css('display','none');$('#get_avm_adress_area').css('display','block');">
AVM Mağazası
</div>

<div id="street_type" onclick="get_adress()">
Sokak Mağazası
</div>
</div>
</div>
<!--street_shop_select()--step one----->

<!--- step two --->
<div id="get_adress_area" style="display:none;">
<div class="shop_type_text">
İşyeri Bilgilerinizi Giriniz...
</div>
<form id="adress_form" style="display: inline-block;">
<table>
<tbody>
<tr><td class="adress_text" >İşyeri Adresi<b>(Zorunlu)</b>:</td><td><textarea rows="4" cols="100" name="adress" class="adress_input" id="adress_input" maxlength="400" placeholder="İşyeri Adresinizi Giriniz..." ></textarea></td></tr>
</tbody>
</table>
</form>

<form id="tel_form" style="display: inline-block;">
<table>
<tbody>
<tr><td class="adress_text" >İşyeri Numarası <b>(İsteğe Bağlı):</b></td><td><input type="text" name="tel_number" class="tel_input" id="tel_input" maxlength="80" size="80"  placeholder="No Giriniz..."  /></td></tr>
</tbody>
</table>
</form>
<div id="adress_result">
</div>
<div id="adress_next" onclick="adress_save(1)">
İlerle<img id="right_image" src="img_files/right.png" style="display:block;" height="30"> <img src="img_files/cycle_loading.gif" height=30 />
</div>

</div>

<!----  tel number for AVM shop         ---->
<div id="get_avm_adress_area" style="display:none;">
<div class="shop_type_text">
İşyeri Bilgilerinizi Giriniz...
</div>
<form id="tel_form" style="display: inline-block;">
<table>
<tbody>
<tr><td class="adress_text" >İşyeri Numarası <b>(İsteğe Bağlı):</b></td><td><input type="text" name="tel_number" class="tel_input" id="tel_avm_input" maxlength="80" size="80"  placeholder="No Giriniz..."  /></td></tr>
<tr><td></td><td> <div id="adress_next" style="display:inline-block;" onclick="adress_save(2)">
İleri<img id="right_image" src="img_files/right.png" style="display:block;" height="30"> <img src="img_files/cycle_loading.gif" height=30 />
</div>   </td></tr>
</tbody>
</table>
</form>
</div>

<div id="shop_center_select" style="display:none;">
<div class="shop_type_text">
İlinizi Seçin?
</div>
<div id="select_province">
<select onchange="get_shops_center_to_select()" id="province_option">
<option value=0>İl Seçiniz...</option>
<option value=1>Adana</option>
<option value=2>Adıyaman</option>
<option value=3>Afyon</option>
<option value=4>Ağrı</option>
<option value=5>Amasya</option>
<option value=6>Ankara</option>
<option value=7>Antalya</option>
<option value=8>Artvin</option>
<option value=9>Aydın</option>
<option value=10>Balıkesir</option>
<option value=11>Bilecik</option>
<option value=12>Bingöl</option>
<option value=13>Bitlis</option>
<option value=14>Bolu</option>
<option value=15>Burdur</option>
<option value=16>Bursa</option>
<option value=17>Çanakkale</option>
<option value=18>Çankırı</option>
<option value=19>Çorum</option>
<option value=20>Denizli</option>
<option value=21>Diyarbakır</option>
<option value=22>Edirne</option>
<option value=23>Elazığ</option>
<option value=24>Erzincan</option>
<option value=25>Erzurum</option>
<option value=26>Eskişehir</option>
<option value=27>Gaziantep</option>
<option value=28>Giresun</option>
<option value=29>Gümüşhane</option>
<option value=30>Hakkari</option>
<option value=31>Hatay</option>
<option value=32>Isparta</option>
<option value=33>İçel (Mersin)</option>
<option value=34>İstanbul</option>
<option value=35>İzmir</option>
<option value=36>Kars</option>
<option value=37>Kastamonu</option>
<option value=38>Kayseri</option>
<option value=39>Kırklareli</option>
<option value=40>Kırşehir</option>
<option value=41>Kocaeli</option>
<option value=42>Konya</option>
<option value=43>Kütahya</option>
<option value=44>Malatya</option>
<option value=45>Manisa</option>
<option value=46>Kahramanmaraş</option>
<option value=47>Mardin</option>
<option value=48>Muğla</option>
<option value=49>Muş</option>
<option value=50>Nevşehir</option>
<option value=51>Niğde</option>
<option value=52>Ordu</option>
<option value=53>Rize</option>
<option value=54>Sakarya</option>
<option value=55>Samsun</option>
<option value=56>Siirt</option>
<option value=57>Sinop</option>
<option value=58>Sivas</option>
<option value=59>Tekirdağ</option>
<option value=60>Tokat</option>
<option value=61>Trabzon</option>
<option value=62>Tunceli</option>
<option value=63>Şanlıurfa</option>
<option value=64>Uşak</option>
<option value=65>Van</option>
<option value=66>Yozgat</option>
<option value=67>Zonguldak</option>
<option value=68>Aksaray</option>
<option value=69>Bayburt</option>
<option value=70>Karaman</option>
<option value=71>Kırıkkale</option>
<option value=72>Batman</option>
<option value=73>Şırnak</option>
<option value=74>Bartın</option>
<option value=75>Ardahan</option>
<option value=76>Iğdır</option>
<option value=77>Yalova</option>
<option value=78>Karabük</option>
<option value=79>Kilis</option>
<option value=80>Osmaniye</option>
<option value=81>Düzce</option>
</select>
<input type="hidden" name="selected_shop_center" id="selected_shop_center" value=0 />
<input type="hidden" name="selected_floor" id="selected_floor" value=100 />
</div>
<div id="shop_center_data">
</div>
</div>

<div id="street_shop_select_area" style=" width: 100%;display:none;">
<div id="map_select_title">
<div style="  display: inline-block;">
<img src="img_files/warning.png" height="100" style="float:left;">
Haritada İşyerinizin Konumunu Bulamıyorsanız <b>GPS SİSTEMİ</b>'ni kullanabilirsiniz. Tek İhtiyacınız olan internete bağlanabilen bir akıllı telefon...
</div>
<div style="display:inline-block;  margin: 5px auto 10px auto;">
<a href="pos_gps_request.php" target="_blank" rel="external" id="gps_button">GPS SİSTEMİNE GİT</a>
</div>
</div>
<div style="display:inline-block;width:100%;" >
 <div style="float:left;width:200px;margin-top:10px;margin-left:20px;display:none;" id="pos-title">
   GPS Sistemi<br>
   <p>(Akıllı Telefonun GPS Sistemi İle Konum Kaydedilir)</p>
  </div>
  <div id="pos-title">
  Mağaza Konum Kayıt İşlemi<br>
  <p>(Haritada Adres Girilerek Yaklaşık Mağaza Konumu Bulunur ve Mağaza Simgesi Görüntü Üzerinde Sürüklenerek Tam Konum Kaydedilir)</p>
  </div>
</div>
 <input id="pac-input" class="controls" type="text" placeholder="İşyerinizin Adresini Giriniz...">
<div id="big_map_area">
</div>
<div id="map_loc_save" onclick="map_loc_save()">Haritadaki Mağaza Konumunu Kaydet<img src="img_files/cycle_loading.gif" height="24" style="position:relative;float:right;margin-right: 10px;display:none;background-color: rgb(18, 125, 157);margin-left:-30px;"></div>
<div id="map_loc_result">
</div>
</div>


