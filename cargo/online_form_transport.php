<?

/* Защита */

$mosConfig_lang = LANG;

function is_valid_data($data) {
	if (isset($data) and $data != "0" and $data != "") { return true;
	} else {return false;}
}

function is_valid_telefone($city, $code, $number) {
	$city = str_replace("-", "", $city);
	$code = str_replace("-", "", $code);
	$number = str_replace("-", "", $number);

	$join = "+".$city."-".$code."-".$number;

	return (preg_match('/^[+]\d{1,3}-\d{1,4}-\d{1,15}/', $join));
}

function isValidEmail ($email, $strict)
{
 if ( !strict ) $email = preg_replace('/^\s+|\s+$/g', '', $email);
 return (preg_match('/^([a-z0-9_\-]+\.)*[a-z0-9_\-]+@([a-z0-9][a-z0-9\-]*[a-z0-9]\.)+[a-z]{2,4}$/i', $email));
}

$err_message = "";

if ($_POST) {
	$explode = explode('-',$_POST['date_export']);
	$day = $explode[0];
	$month = $explode[1];
	$year = $explode[2];

	if (is_valid_data($_POST['import_country']) and is_valid_data($_POST['export_country']) and is_valid_data($day)and is_valid_data($month)and is_valid_data($year) and mb_strlen($day,'UTF-8')==2 and mb_strlen($month,'UTF-8')==2  and mb_strlen($year,'UTF-8')==4 and is_valid_data($_POST['type_transport']) and is_valid_data($_POST['cargo_type']) and is_valid_data($_POST['cargo_size']) and is_valid_data($_POST['fio']) and isValidEmail($_POST['email'], false) and is_valid_telefone($_POST['telefon_country_code'], $_POST['telefon_city_code'], $_POST['telefon'])) {

		include ($_SERVER['DOCUMENT_ROOT'].'/db_connect/db_write.php');

		$import_country = mysql_real_escape_string($_POST['import_country']);
		$export_country = mysql_real_escape_string($_POST['export_country']);
		$date_export = mysql_real_escape_string($_POST['date_export']);
		$type_transport = mysql_real_escape_string($_POST['type_transport']);
		$cargo_type = mysql_real_escape_string($_POST['cargo_type']);
		$cargo_size = mysql_real_escape_string($_POST['cargo_size']);
		$fio = ucwords(strtolower(mysql_real_escape_string($_POST['fio'])));
		$email = mysql_real_escape_string($_POST['email']);

		$city = str_replace("-", "", $_POST['telefon_country_code']);
		$code = str_replace("-", "", $_POST['telefon_city_code']);
		$number = str_replace("-", "", $_POST['telefon']);

		$telefone_number = mysql_real_escape_string("+".$city."-".$code."-".$number);

		if (is_valid_data($_POST['import_city']) and $_POST['import_city'] != "-1") {
			$import_city = mysql_real_escape_string($_POST['import_city']);
		} elseif (isset($_POST['import_city']) and $_POST['import_city'] == "-1" and is_valid_data($_POST['import_city_type'])) {
			$import_city = mysql_real_escape_string($_POST['import_city_type']);
		} else {
				$import_city = "";
		}

		if (is_valid_data($_POST['export_city']) and $_POST['export_city'] != "-1") {
			$export_city = mysql_real_escape_string($_POST['export_city']);
		} elseif (isset($_POST['export_city']) and $_POST['export_city'] == "-1" and is_valid_data($_POST['export_city_type'])) {
			$export_city = mysql_real_escape_string($_POST['export_city_type']);
		} else {
				$export_city = "";
		}

		if (is_valid_data($_POST['skype'])) $skype = mysql_real_escape_string($_POST['skype']);
		else $skype = "";
		if (is_valid_data($_POST['icq'])) $icq = mysql_real_escape_string($_POST['icq']);
		else $icq = "";
		$date = date("d-m-Y");
		
		$source = "moldovatruck";
		//assign contacts
		$id_contact = 0;
		$query_contacts = mysql_query("SELECT id FROM movers_contact");
		if ($num_rows = mysql_num_rows($query_contacts) and mysql_data_seek($query_contacts, mt_rand(0, $num_rows-1))) {
			$id_contact = mysql_fetch_array($query_contacts);
			$by_admin = $id_contact['id'];
		}

		//$query = "INSERT INTO movers_cargo ('name','export','export_city','import','impot_city','phone','email','skype','icq','face','date','type','order_date','volume') VALUES ('$cargo_type','$export_country','$export_city','$import_country','$import_city','$telefone_number','$email','$skype','$icq','$fio','$date_export','$type_transport','$date','$cargo_size')";

		$res = mysql_query("INSERT INTO movers_cargo (`name`,`export`,`export_city`,`import`,`import_city`,`phone`,`email`,`skype`,`icq`,`face`,`date`,`type`,`order_date`,`volume`,`source`,`by_admin`) VALUES ('$cargo_type','$export_country','$export_city','$import_country','$import_city','$telefone_number','$email','$skype','$icq','$fio','$date_export','$type_transport','$date','$cargo_size','$source','$by_admin') ") or die(mysql_error());

		//echo "Ati introdus urmatoarele date: ",$import_country,",",$import_city,",",$export_country,",",$export_city,",",$date_export,",",$cargo_type,",",$type_transport,",",$cargo_size,",",$fio,",",$telefone_number,",",$email,",",$skype,",",$icq,",",$date;

		$succes_message ="
		<p>Добавленое обьявление можно просмотреть в разделе <a href='http://moldovatruck.md/index.php?option=com_content&task=view&id=7&Itemid=17'>Просмотр груза</a></p>
		";
		header("Location:http://".$_SERVER['SERVER_NAME']."/index.php?option=com_content&id=9&task=view&Itemid=26&import=".$import_country."&export=".$export_country."&day=".$day."&month=".$month."&year=".$year);
		exit;
	} else {
			$succes_message = "";
			$err_message = "<p>Заполните пожалуйста обязательные поля!</p>";
	}
}
?>

<?php if (!isset($succes_message) or $succes_message == "") {

include_once($_SERVER['DOCUMENT_ROOT']."/utils.php");

?>
<script language="JavaScript1.2">
function parse_info(form_user)
{
	var err_message = "";
	var text = "Пожалуйста заполните обязательные поля.<br>";
	var err_message_more = "";
	if (form_user.import_country.value == 0 || form_user.import_country.value == "" || form_user.import_country.value == null) {document.getElementById("select_import").className = "redborder"; err_message += "1";}
	else { document.getElementById("select_import").className = "none";}
	if (form_user.export_country.value == 0 || form_user.export_country.value == "" || form_user.export_country.value == null) {document.getElementById("select_export").className = "redborder"; err_message += "1";}
	else { document.getElementById("select_export").className = "none";}
	if (form_user.date_export.value == 0 || form_user.date_export.value == "" || form_user.date_export.value == null) {document.getElementById("date_span").className = "redborder"; err_message += "1";}
	else {document.getElementById("date_span").className = "none";}
	if (form_user.type_transport.value == 0 || form_user.type_transport.value == "" || form_user.type_transport.value == null) {document.getElementById("select_transport").className = "redborder"; err_message += "1";}
	else {document.getElementById("select_transport").className = "none";}
	if (form_user.cargo_type.value == 0 || form_user.cargo_type.value == "" || form_user.cargo_type.value == null) {document.getElementById("select_cargo").className = "redborder"; err_message += "1";}
	else {document.getElementById("select_cargo").className = "none";}
	if (form_user.cargo_size.value == 0 || form_user.cargo_size.value == "" || form_user.cargo_size.value == null) {document.getElementById("select_cargo_size").className = "redborder"; err_message += "1";}
	else {document.getElementById("select_cargo_size").className = "none";}
	if (form_user.fio.value == 0 || form_user.fio.value == "" || form_user.fio.value == null) {document.getElementById("fio_span").className = "redborder"; err_message +="1";}
	else {document.getElementById("fio_span").className = "none";}
	if (!isValidEmail(form_user.email.value, false) || form_user.email.value == "") {document.getElementById("email_span").className = "redborder"; err_message_more +="Почтовый ящик указан неверно.<br>"; err_message += "1";}
	else {document.getElementById("email_span").className = "none";}
	var country_code = form_user.telefon_country_code.value;
	country_code = country_code.replace(/-/g, "");
	var city_code = form_user.telefon_city_code.value;
	city_code = city_code.replace(/-/g, "");
	var telefon = form_user.telefon.value;
	telefon = telefon.replace(/-/g, "");
	var telefon_number = "+" + country_code + "-" + city_code + "-" + telefon;
	var pattern = /^[+]\d{1,3}-\d{1,4}-\d{1,15}/;
	var ereg = pattern.test(telefon_number);
	if (!ereg) {
	document.getElementById("telefon_country_code_span").className = "redborder";
	document.getElementById("telefon_city_code_span").className = "redborder";
	document.getElementById("telefon_span").className = "redborder";
	err_message_more +="Неправильный формат телефонного номера. <br>(Пример: + код страны - код города - номер абонента)<br>";
	err_message += "1";
	} else {
	document.getElementById("telefon_country_code_span").className = "none";
	document.getElementById("telefon_city_code_span").className = "none";
	document.getElementById("telefon_span").className = "none";
	}
	if (err_message != "") {
		document.getElementById("err_message").innerHTML = text + err_message_more;
		return false;
	} else {
		document.getElementById("err_message").innerHTML = "";
		return true;
	}
}
</script>

<p><span id="err_message" style="color:red"><?php echo $err_message; ?></span>
<p><form action="<?php echo str_replace("&amp;","&",$_SERVER["REQUEST_URI"]); ?>" method="POST" name="forma" onsubmit="return parse_info(this);">
<table class="bold" border="0" width="100%">

<tr>
<td style="padding-bottom:8px;">
<?php if($mosConfig_lang == 'ru') { echo "Тип транспорта"; }
	elseif ($mosConfig_lang == 'ro') { echo "Tipul de transport"; }
	elseif ($mosConfig_lang == 'en') { echo "Transport type"; }
?> <span style="color: red">*</span>
</td>
<td style="padding-bottom:8px;">
<span id="select_transport">
<select type="select" name="type_transport" size="1"  id="type_transport">
<?php if($mosConfig_lang == 'ru') { echo '<option value="">- укажите тип транспорта -</option>';}
		elseif ($mosConfig_lang == 'ro') { echo '<option value="">- indicati tipul transportului -</option>';}
        elseif ($mosConfig_lang == 'en') { echo  '<option value="">- choose transport type -</option>';}
		echo $transport_types;
?>
</select>
</span>
</td>
</tr>

<tr>
<td align="left" style="width:165px; padding-bottom:8px;">
<?php if($mosConfig_lang == 'ru') { echo "Страна загрузки"; }
	elseif ($mosConfig_lang == 'ro') { echo "Tara de incarcare"; }
	elseif ($mosConfig_lang == 'en') { echo "Country of loading"; }
?> <span style="color: red">*</span>
</td>
<td align="left" style="padding-bottom:8px;">
<span id="select_export">
<select type="select" name="export_country" size="1" onchange="updateSelect('export_city', this.value, 'export_city');" id="export_country"   >
<?php if($mosConfig_lang == 'ru') { echo '<option value="">- укажите страну -</option>';}
      elseif($mosConfig_lang == 'ro') { echo '<option value="">- alegeti tara  -</option>';}
      elseif($mosConfig_lang == 'en') { echo '<option value="">- choose the country -</option>';}
	  echo $countries;
?>
</select>
</span>
</td>
</tr>


<tr>
<td align="left" style="padding-bottom:8px;">
<?php if($mosConfig_lang == 'ru') {
	echo 'Город загрузки';}
    elseif($mosConfig_lang == 'ro') { echo'Oras de incarcare';}
    elseif($mosConfig_lang == 'en') { echo'City of Loading';}
?>
<span id="export_city_text"></span>
</td>
<td align="left" style="padding-bottom:8px;">
<select type="select" name="export_city" size="1" id="export_city"   onchange="updateSelect('ignore', this.value, 'export_city');">
<option value="0"><?php if($mosConfig_lang == 'ru') {
	echo '---не имеет значения---';}
    elseif($mosConfig_lang == 'ro') { echo'---oricare---';}
    elseif($mosConfig_lang == 'en') { echo'---all cities---';}
?></option>
</select><span id="export_city_type"></span>
</td>
</tr>

<tr>
<td align="left" style="padding-bottom:8px;">
 <?php if($mosConfig_lang == 'ru') {
	echo 'Страна выгрузки';}
	elseif($mosConfig_lang == 'ro') { echo'Tara de descarcare';}
    elseif($mosConfig_lang == 'en') { echo'Country of unloading';}
 ?> <span style="color: red">*</span>
</td>
<td align="left" style="padding-bottom:8px;">
<span id="select_import">
<select type="select" name="import_country" size="1" id="import_country" onchange="updateSelect('import_city', this.value, 'import_city');"   >
<?php if($mosConfig_lang == 'ru') { echo '<option value="">- укажите страну -</option>';}
      elseif($mosConfig_lang == 'ro') { echo '<option value="">- alegeti tara  -</option>';}
      elseif($mosConfig_lang == 'en') { echo '<option value="">- choose the country -</option>';}
	  echo $countries;
?>
</select>
</span>
</td>
</tr>

<tr>
<td align="left" style="padding-bottom:8px;">
<?php
    if($mosConfig_lang == 'ru') { echo'Город выгрузки';}
    elseif($mosConfig_lang == 'ro') { echo'Oras de descarcare';}
    elseif($mosConfig_lang == 'en') { echo'City of unloading';}
?>
<span id="import_city_text"></span>
</td>
<td align="left" style="padding-bottom:8px;">
<select type="select" name="import_city" size="1" id="import_city"  onchange="updateSelect('ignore', this.value, 'import_city');">
<option value="0"><?php if($mosConfig_lang == 'ru') {
	echo '---не имеет значения---';}
    elseif($mosConfig_lang == 'ro') { echo'---oricare---';}
    elseif($mosConfig_lang == 'en') { echo'---all cities---';}
?></option>
</select><span id="import_city_type"></span>
</td>
</tr>

<tr>
<td style="padding-bottom:8px;">
<?php if($mosConfig_lang == 'ru') { echo "Дата погрузки"; }
								elseif ($mosConfig_lang == 'ro') { echo "Data incarcarii"; }
								elseif ($mosConfig_lang == 'en') { echo "Loading Date"; }
								?> <span style="color: red">*</span>
</td>
<td style="padding-bottom:8px;">
<span id="date_span"><input readonly="true" type="text" name="date_export" size=12 maxlength=19 id="date_export"  style="width: 200px"  /><input name="calendar" type="button" onClick="return showCalendar('date_export', 'dd-mm-y');" tabindex="105" value="..." style="width: 20px"/></span>
</td>
</tr>

<tr>
<td style="padding-bottom:8px;">
<?php if($mosConfig_lang == 'ru') {
	echo "Наименование груза"; }
	elseif ($mosConfig_lang == 'ro') { echo "Denumirea incarcaturii"; }
	elseif ($mosConfig_lang == 'en') { echo "Description of cargo"; }
?> <span style="color: red">*</span>
</td>
<td style="padding-bottom:8px;">
<span id="select_cargo">
<select type="select" name="cargo_type" size="1"   id="cargo_type">
<?php
if($mosConfig_lang == 'ru') { echo "<option value=''>- укажите тип груза -</option>"; }
elseif($mosConfig_lang == 'en') { echo "<option value=''>- chose the cargo type -</option>"; }
elseif ($mosConfig_lang == 'ro') { echo "<option value=''>- indicati tipul de incarcatura -</option>"; }
echo $cargo_types;
?>
</select>
</span>
</td>
</tr>

<tr>
<td style="padding-bottom:8px;">
<?php if($mosConfig_lang == 'ru') { echo "Объём груза, вес груза"; }
		elseif ($mosConfig_lang == 'ro') { echo "Volumul si greutatea incarcaturii"; }
		elseif ($mosConfig_lang == 'en') { echo "Cargo size and weight"; }
?> <span style="color: red">*</span>
</td>
<td style="padding-bottom:8px;">
<span id="select_cargo_size">
<select type="select" name="cargo_size" size="1"    id="cargo_size">
 <?php if($mosConfig_lang == 'ru') { echo '<option value="">- выбрать массу/объём -</option>';}
        elseif ($mosConfig_lang == 'ro') { echo '<option value="">- selectati Volumul/greutatea -</option>';}
        elseif ($mosConfig_lang == 'en') { echo '<option value="">- choose size/weight -</option>';}
		echo $cargo_volumes;
?>
</select>
</span>
</td>
</tr>

<tr>
<td style="padding-bottom:8px;"><?php if($mosConfig_lang == 'ru') { echo "Контактное лицо"; }
	elseif ($mosConfig_lang == 'ro') { echo "Persoana de contact"; }
	elseif ($mosConfig_lang == 'en') { echo "Contact person"; }
?> <span style="color: red">*</span></td>
<td style="padding-bottom:8px;"><span id="fio_span"><input type="text" name="fio" size="25" id="fio"   /></span></td>
</tr>

<tr>
<td style="padding-bottom:8px;"><?php if($mosConfig_lang == 'ru') { echo "Контактный телефон"; }
								elseif ($mosConfig_lang == 'ro') { echo "Telefon de contact"; }
								elseif ($mosConfig_lang == 'en') { echo "Telephone"; }
								?> <span style="color: red">*</span></td>
<td style="padding-bottom:8px;">+ <span id="tel_span"><span id="telefon_country_code_span"><input type="text" name="telefon_country_code" size="1" maxlength="3" id="telefon_country_code" style="width: 25px" /></span>
- <span id="telefon_city_code_span"><input type="text" name="telefon_city_code" size="2" maxlength="4" id="telefon_city_code" style="width: 30px" /></span>
- <span id="telefon_span"><input type="text" name="telefon" size="15" maxlength="15" id="telefon"  style="width: 125px" /></span></span>
</td>

<tr>
<td style="padding-bottom:8px;">E-mail <span style="color: red">*</span></td>
<td style="padding-bottom:8px;"><span id="email_span"><input type="text" name="email" size="25"   /></span></td>
</tr>

<!--<tr>
<td style="padding-bottom:8px;">Skype</td>
<td style="padding-bottom:8px;"><input type="text" name="skype" size="25"   /></td>
</tr>

<tr>
<td style="padding-bottom:10px;">ICQ</td>
<td style="padding-bottom:10px;"><input type="text" name="icq" size="25"   /></td>
</tr>-->

<tr>
<td style="padding-bottom:10px;"><span style="color: red">*</span> - <?php if($mosConfig_lang == 'ru') { echo "обязательные поля"; }
								elseif ($mosConfig_lang == 'ro') { echo "campuri obligatorii"; }
								elseif ($mosConfig_lang == 'en') { echo "required fields"; }
								?></td>
<td style="padding-bottom:10px;">
<input type="submit" name="submit" value="<?php if($mosConfig_lang == 'ru') { echo "Отправить"; }
								elseif ($mosConfig_lang == 'ro') { echo "Expediaza"; }
								elseif ($mosConfig_lang == 'en') { echo "Send"; }
								?>" id="submit" />
</td>
</tr>

</table>
</form>

<?php } else {
	echo $succes_message;
	}
?>