<?
$mosConfig_lang = LANG;



if (!defined('FORMS')) {$ANOTHER = 'forma'; $num=0;define('FORMS',$ANOTHER);}

else

{

	$FORMS = FORMS;

	if ($FORMS=='forma') {$ANOTHER='formc';$num=1;}

	else $ANOTHER = 'formb';

}

$err_message = "";



if (!isset($inserted)) $inserted = false;

if (!defined('INSERTED')&&!empty($_POST)&&$_POST['form_name']==$ANOTHER) {



	require_once(dirname(__FILE__).'/../system/functions.php');

	$mosConfig_lang=LANG;

	$explode = explode('-',$_POST['date_export']);

	$day = $explode[0];

	$month = $explode[1];

	$year = $explode[2];

	if (is_valid_data($_POST['transport_type']) and is_valid_data($_POST['export_country']) and is_valid_data($day)and is_valid_data($month)and is_valid_data($year) and mb_strlen($day,'UTF-8')==2 and mb_strlen($month,'UTF-8')==2  and mb_strlen($year,'UTF-8')==4 and is_valid_data($_POST['cargo_size']) and is_valid_data($_POST['fio']) and is_valid_data($_POST['telefon'][0])) {

		

		include (dirname(__FILE__).'/../db_connect/db_write.php');



		$import_country = mysqli_real_escape_string($db,$_POST['import_country']);
		$export_country = mysqli_real_escape_string($db,$_POST['export_country']);
		$date_export = mysqli_real_escape_string($db,$_POST['date_export']);
		$type_transport = mysqli_real_escape_string($db,$_POST['transport_type']);
		$cargo_type = mysqli_real_escape_string($db,$_POST['cargo_type']);
		$company = mysqli_real_escape_string($db,$_POST['company']);
		$fio = ucwords(strtolower(mysqli_real_escape_string($db,$_POST['fio'])));
		$email = mysqli_real_escape_string($db,$_POST['email']);
		$cargo_size =  mysqli_real_escape_string($db,$_POST['cargo_size']);
		$number = str_replace("-", "", $_POST['telefon']);
		for($phones=0;$phones<count($_POST['telefon']);$phones++)

		{

			$telefone_number.=mysqli_real_escape_string($db,"+".$_POST['telefon'][$phones]).'; ';

		}



		$import_city = mysqli_real_escape_string($db,$_POST['import_city']);

		$export_city = mysqli_real_escape_string($db,$_POST['export_city']);

		$date = date("d-m-Y");

		

		$source = "moldovatruck";

		//assign contacts

		$id_contact = 0;

		$query_contacts = mysqli_query($db,"SELECT id FROM movers_contact");

		if ($num_rows = mysqli_num_rows($query_contacts)) {

			$id_contact = mysqli_fetch_array($query_contacts);

			$by_admin = $id_contact['id'];

		}

		

		$res = mysqli_query($db,"INSERT INTO movers_order_post(company,face,phone,email,type,volume,export,export_city,import,import_city,date_from,date_to,order_date,source,by_admin) VALUES ('$company','$fio','$telefone_number','$email','$type_transport','$cargo_size','$import_country','$import_city','$export_country','$export_city','$date_export','$date_export','$date','$source','$by_admin') ") or die(mysqli_error($db));

		

		define('INSERTED',true);



		$inserted = true;

		

		if(LANG=='ru') $LINK = "Вы успешно добавили транспорт для посылок";

		if(LANG=='ro') $LINK = "Dvs ati adaugat cu succes transport pentru parcele ";

		if(LANG=='en') $LINK = "You successfull added post transport ";





		$getCountryExport = mysqli_query($db,"SELECT `country_name_$mosConfig_lang` FROM `country` WHERE `id_country`='$import_country'");

		$countryExport = mysqli_fetch_array($getCountryExport);

		if (LANG=='ru') $LINK.='из '.CountryFrom($countryExport['country_name_'.$mosConfig_lang]);

		if (LANG=='ro') $LINK.='din '.CountryFrom($countryExport['country_name_'.$mosConfig_lang]);

		if (LANG=='en') $LINK.='from '.CountryFrom($countryExport['country_name_'.$mosConfig_lang]);

		$getCountryImport = mysqli_query($db,"SELECT `country_name_$mosConfig_lang` FROM `country` WHERE `id_country`='$export_country'");

		$countryImport = mysqli_fetch_array($getCountryImport);

		if(LANG=='ru')$LINK.=' в '.CountryTo($countryImport['country_name_'.$mosConfig_lang]);

		if(LANG=='ro')$LINK.=' în '.CountryTo($countryImport['country_name_'.$mosConfig_lang]);

		if(LANG=='en')$LINK.=' to '.CountryTo($countryImport['country_name_'.$mosConfig_lang]);

		

		if(LANG=='ru') $LINK.=". <a href='".HOME.LANG."/post_cargo.php?import=".$export_country."&export=".$import_country."' style='color:white'>Просмотреть грузы ";

		if(LANG=='ro') $LINK.=". <a href='".HOME.LANG."/post_cargo.php?import=".$export_country."&export=".$import_country."' style='color:white'>Oferte cu marfa ";

		if(LANG=='en') $LINK.=". <a href='".HOME.LANG."/post_cargo.php?import=".$export_country."&export=".$import_country."' style='color:white'>View cargo ";

		

		if (LANG=='ru') $LINK.='из '.CountryFrom($countryExport['country_name_'.$mosConfig_lang]);

		if (LANG=='ro') $LINK.='din '.CountryFrom($countryExport['country_name_'.$mosConfig_lang]);

		if (LANG=='en') $LINK.='from '.CountryFrom($countryExport['country_name_'.$mosConfig_lang]);

		if(LANG=='ru')$LINK.=' в '.CountryTo($countryImport['country_name_'.$mosConfig_lang]);

		if(LANG=='ro')$LINK.=' în '.CountryTo($countryImport['country_name_'.$mosConfig_lang]);

		if(LANG=='en')$LINK.=' to '.CountryTo($countryImport['country_name_'.$mosConfig_lang]);

		



		//echo "Ati introdus urmatoarele date: ",$import_country,",",$import_city,",",$export_country,",",$export_city,",",$date_export,",",$cargo_type,",",$type_transport,",",$cargo_size,",",$fio,",",$telefone_number,",",$email,",",$skype,",",$icq,",",$date;

	

		echo '<html><head><meta http-equiv="refresh" content="4;URL='.HOME.LANG.'/post_cargo.php?import='.$export_country.'&export='.$import_country.'" /></head></html>';

		$succes_message= "<p class='alert alert-dismissible alert-success' id='".$_POST['form_name']."' style='text-align:center;display:block;margin-bottom:2em;margin-top:70px;'>".$LINK."</a></p>";

		//echo $succes_message;

		

	} else {

			$succes_message ="";

			$err_message = '<p class="alert alert-dismissible alert-danger">Заполните пожалуйста обязательные поля!</p>1= '.$_POST['import_country'].' <br>2= '.$_POST['port_import'].'<br>3= '.$_POST['export_country'].' <br>4= '.$_POST['port_export'].'<br>5= '.$_POST['date_export'].'<br>6= 15<br>7= '.$_POST['company'].'<br>8= '.$_POST['fio'].'<br>9= '.$_POST['email'].'<br>10= '.$_POST['cargo_size'].'<br>11= '.$_POST['cargo_type'];

	}

}

?>

<?php if (!isset($inserted)&&!isset($succes_message) or $succes_message == "") {

			include(dirname(__FILE__)."/../utils.php");

?>

<script>

function parse_info(id)

{

	var err_message = "";

	form_user = $('#'+id);

	var text = '<p class="alert alert-dismissible alert-danger">Пожалуйста заполните обязательные поля.</p>';

	var err_message_more = "";

	

	

	import_county = $('#'+id+' select[name="import_country"]').val();

	export_country = $('#'+id+' select[name="export_country"]').val();

	datetimepicker1 = $('#'+id+' #date_export input').val();

	type_transport = $('#'+id+' select[name="type_transport"]').val();

	cargo_size = $('#'+id+' select[name="cargo_size"]').val();

	fio = $('#'+id+' #fio').val();

	email = $('#'+id+' #email').val();

	telefon_country_code = $('#'+id+' #telefon_country_code').val();

	telefon_city_code = $('#'+id+' #telefon_city_code').val();

	telefon = $('#'+id+' #telefon').val();

	action = $('#'+id).attr('action');

	console.log(import_county);

	console.log(export_country);

	console.log(datetimepicker1);

	console.log(cargo_size);

	console.log(fio);

	console.log(email);

	console.log(telefon_country_code);

	console.log(telefon_city_code);

	console.log(telefon);

	

	if (import_county==''||import_county==0||import_county==null) err_message += "1";

	if (export_country==''||export_country==0||export_country==null) err_message += "1";

	if (datetimepicker1==''||datetimepicker1==0||datetimepicker1==null) err_message += "1";

	if (type_transport==''||type_transport==0||type_transport==null) err_message += "1";

	if (cargo_size==''||cargo_size==0||cargo_size==null) err_message += "1";

	if (fio==''||fio==0||fio==null) err_message += "1";

	if (email=='') err_message += "1";

	else if(!isValidEmail(email, false)) err_message += "1";

	if(telefon_country_code!==''&&telefon_city_code!==''&&telefon)

	{

		country_code = telefon_country_code.replace(/-/g, "");

		city_code = telefon_city_code.replace(/-/g, "");

		telefon = telefon.replace(/-/g, "");

		var telefon_number = "+" + country_code + "-" + city_code + "-" + telefon;

		var pattern = /^[+]\d{1,3}-\d{1,4}-\d{1,15}/;

		var ereg = pattern.test(telefon_number);

	

		if (!ereg) err_message += "1";

	}

	console.log(err_message);

	if (err_message!=="") {

		$('#'+id+' #err_message').html(text);

		//return false;

	} else {

		console.log('here');

		$('#'+id+' #err_message').html("");

		console.log(home_url+'cargo/calculate_cost_of_transportation_cargo.php');

		

		$.post(home_url+action.substr(1,action.length), {import_country:import_country,export_country:export_country,date_export:datetimepicker1,type_transport:type_transport,cargo_type:cargo_type,cargo_size:cargo_size,fio:fio,email:email,telefon_country_code:telefon_country_code,telefon_city_code:telefon_city_code,telefon:telefon},function(data){

			console.log(data);

			//$('#'+id+' #err_message').html(data);

		});

		//console.log('now_here');

		//return true;

	}

}

AnotherFormName = '<? echo $ANOTHER; ?>';

</script>



<p><form action="<?php echo str_replace("&amp;","&",$_SERVER["REQUEST_URI"]).'#'.$ANOTHER; ?>" method="POST" name="<?php echo $ANOTHER;?>" id="<?php echo $ANOTHER;?>" onsubmit=" return sendForm('<?php echo $ANOTHER;?>','type_transport,export_country,import_country,,cargo_size,fio,t0,dt_export','<?php if($mosConfig_lang == 'ru') { echo "Тип транспорта"; }
	elseif ($mosConfig_lang == 'ro') { echo "Tipul de transport"; }
	elseif ($mosConfig_lang == 'en') { echo "Transport type"; }
?>!<?php if($mosConfig_lang == 'ru') { echo "Страна загрузки"; }
	elseif ($mosConfig_lang == 'ro') { echo "Tara de incarcare"; }
	elseif ($mosConfig_lang == 'en') { echo "Country of loading"; }
?>!<?php if($mosConfig_lang == 'ru') {
	echo 'Страна выгрузки';}
	elseif($mosConfig_lang == 'ro') { echo'Tara de descarcare';}
    elseif($mosConfig_lang == 'en') { echo'Country of unloading';}
 ?>!<?php if($mosConfig_lang == 'ru') { echo "Вес, объём груза"; }
		elseif ($mosConfig_lang == 'ro') { echo "Volumul si greutatea incarcaturii"; }
		elseif ($mosConfig_lang == 'en') { echo "Cargo size and weight"; }
?>!<?php if($mosConfig_lang == 'ru') { echo "Контактное лицо"; }
	elseif ($mosConfig_lang == 'ro') { echo "Persoana de contact"; }
	elseif ($mosConfig_lang == 'en') { echo "Contact person"; }
?>!<?php if($mosConfig_lang == 'ru') { echo "Контактный телефон"; }
								elseif ($mosConfig_lang == 'ro') { echo "Telefon de contact"; }
								elseif ($mosConfig_lang == 'en') { echo "Telephone"; }
								?>!<?php if($mosConfig_lang == 'ru') { echo "Дата погрузки"; }
								elseif ($mosConfig_lang == 'ro') { echo "Data incarcarii"; }
								elseif ($mosConfig_lang == 'en') { echo "Loading Date"; }
							?>');">

<p><span id="err_message" style="color:red"><?php echo $err_message; ?></span></p>

<table border="0"  style="min-width:300px; max-width:80%;">
<tr>

<td align="left" style="width:165px; padding-bottom:8px;"><label>

<?php if($mosConfig_lang == 'ru') { echo "Страна загрузки"; }

	elseif ($mosConfig_lang == 'ro') { echo "Tara de incarcare"; }

	elseif ($mosConfig_lang == 'en') { echo "Country of loading"; }

?><span style="color: red">*</span></label>

</td>

<td align="left" style="padding-bottom:8px;">

<select type="select" name="import_country" size="1" onchange="updateSelect('import_city', this.value, 'import_city','<?php echo $ANOTHER;?>');" id="import_country" style="width:100%;padding: 3px 5px;font-size: 1em;" >

<?php if($mosConfig_lang == 'ru') { echo '<option value="">- укажите страну -</option>';}

      elseif($mosConfig_lang == 'ro') { echo '<option value="">- alegeti tara  -</option>';}

      elseif($mosConfig_lang == 'en') { echo '<option value="">- choose the country -</option>';}

	  echo $countries;

?>

</select>



</td>

</tr>





<tr>

<td align="left" style="padding-bottom:8px;"><label for="exampleInputEmail1" style="display:block">

<?php if($mosConfig_lang == 'ru') {

	echo 'Город загрузки';}

    elseif($mosConfig_lang == 'ro') { echo'Oras de incarcare';}

    elseif($mosConfig_lang == 'en') { echo'City of Loading';}

?>
</label>

</td>

<td align="left" style="padding-bottom:8px;">

<select type="select" name="import_city" size="1" id="import_city"   onchange="updateSelect('ignore', this.value, 'import_city','<?php echo $ANOTHER;?>');" style="width:100%;padding: 3px 5px;font-size: 1em;">

<option value="0"><?php if($mosConfig_lang == 'ru') {

	echo '---не имеет значения---';}

    elseif($mosConfig_lang == 'ro') { echo'---oricare---';}

    elseif($mosConfig_lang == 'en') { echo'---all cities---';}

?></option>

</select><span id="import_city_type"></span>

</td>

</tr>


<tr>

<td align="left" style="padding-bottom:8px;"><label for="exampleInputEmail1" style="display:block">

 <?php if($mosConfig_lang == 'ru') {

	echo 'Страна выгрузки';}

	elseif($mosConfig_lang == 'ro') { echo'Tara de descarcare';}

    elseif($mosConfig_lang == 'en') { echo'Country of unloading';}

 ?><span style="color: red">*</span> </label>

</td>

<td align="left" style="padding-bottom:8px;">



<select type="select" name="export_country" size="1" id="export_country" onchange="updateSelect('export_city', this.value, 'export_city','<?php echo $ANOTHER;?>');" style="width:100%;padding: 3px 5px;font-size: 1em;">

<?php if($mosConfig_lang == 'ru') { echo '<option value="">- укажите страну -</option>';}

      elseif($mosConfig_lang == 'ro') { echo '<option value="">- alegeti tara  -</option>';}

      elseif($mosConfig_lang == 'en') { echo '<option value="">- choose the country -</option>';}

	  echo $countries;

?>

</select>

</td>

</tr>



<tr>

<td align="left" style="padding-bottom:8px;"><label for="exampleInputEmail1" style="display:block">

<?php

    if($mosConfig_lang == 'ru') { echo'Город выгрузки';}

    elseif($mosConfig_lang == 'ro') { echo'Oras de descarcare';}

    elseif($mosConfig_lang == 'en') { echo'City of unloading';}

?>

</label>

</td>

<td align="left" style="padding-bottom:8px;">

<select type="select" name="export_city" size="1" id="export_city"  onchange="updateSelect('ignore', this.value, 'export_city','<?php echo $ANOTHER;?>');"  style="width:100%;padding: 3px 5px;font-size: 1em;">

<option value="0"><?php if($mosConfig_lang == 'ru') {

	echo '---не имеет значения---';}

    elseif($mosConfig_lang == 'ro') { echo'---oricare---';}

    elseif($mosConfig_lang == 'en') { echo'---all cities---';}

?></option>

</select><span id="export_city_type"></span>

</td>

</tr>

<tr>

<td align="left" style="padding-bottom:8px;"><label for="exampleInputEmail1" style="display:block">

 <?php if($mosConfig_lang == 'ru') {

	echo 'Тип транспорта';}

	elseif($mosConfig_lang == 'ro') { echo'Tipul transportului';}

    elseif($mosConfig_lang == 'en') { echo'Transport type';}

 ?><span style="color: red">*</span> </label>

</td>

<td align="left" style="padding-bottom:8px;">



<select type="select" name="transport_type" size="1" id="transport_type" style="width:100%;padding: 3px 5px;font-size: 1em;">

<?php if($mosConfig_lang == 'ru') { echo '<option value="">- укажите тип -</option>';}

      elseif($mosConfig_lang == 'ro') { echo '<option value="">- alegeti tipul  -</option>';}

      elseif($mosConfig_lang == 'en') { echo '<option value="">- choose the type -</option>';}

	  echo $transport_types;

?>

</select>

</td>

</tr>

<tr>

<td style="padding-bottom:8px;"><label for="exampleInputEmail1" style="display:block">

Свободен с <span style="color: red">*</span> </label>

</td>

<td style="padding-bottom:8px;">

<span id="date_span">

<div class="form-group">

        <div class='input-group date' id='date_export'>

                    <input type='text' class="form-control" id="dt_export" name="date_export" value="<?php echo $_POST['date_export'] ?>" style="width:100%;padding: 3px 5px;font-size: 1em;height:auto"/>

                    <span class="input-group-addon" style="padding:0px; padding-left:5px; padding-right:5px">

                        <span class="glyphicon glyphicon-calendar"></span>

                    </span>

                </div>

  </div>

</span>

</td>

</tr>







<tr>

<td style="padding-bottom:8px;"><label for="exampleInputEmail1" style="display:block">

<?php if($mosConfig_lang == 'ru') { echo "Объём груза, вес"; }

		elseif ($mosConfig_lang == 'ro') { echo "Volumul si capcitatea"; }

		elseif ($mosConfig_lang == 'en') { echo "Car volume and capacity"; }

?><span style="color: red">*</span></label>

</td>

<td style="padding-bottom:8px;">



<select type="select" name="cargo_size" size="1"  id="cargo_size">

 <?php if($mosConfig_lang == 'ru') { echo '<option value="">- выбрать массу/объём -</option>';}

        elseif ($mosConfig_lang == 'ro') { echo '<option value="">- selectati Volumul/greutatea -</option>';}

        elseif ($mosConfig_lang == 'en') { echo '<option value="">- choose size/weight -</option>';}

		echo $cargo_volumes;

?>

</select>



</td>

</tr>



<tr>

<td style="padding-bottom:8px;"><label for="exampleInputEmail1" style="display:block"><?php if($mosConfig_lang == 'ru') { echo "Компания"; }

	elseif ($mosConfig_lang == 'ro') { echo "Compania"; }

	elseif ($mosConfig_lang == 'en') { echo "Company"; }

?></label></td>

<td style="padding-bottom:8px;"><span id="company_span"><input type="text" value="<?php echo $_POST['company'] ?>" name="company" id="company"  style="height:auto;padding: 3px 5px;font-size: 1em;" /></span></td>

</tr>



<tr>

<td style="padding-bottom:8px;"><label for="exampleInputEmail1" style="display:block"><?php if($mosConfig_lang == 'ru') { echo "Контактное лицо"; }

	elseif ($mosConfig_lang == 'ro') { echo "Persoana de contact"; }

	elseif ($mosConfig_lang == 'en') { echo "Contact person"; }

?><span style="color: red">*</span></label></td>

<td style="padding-bottom:8px;"><span id="fio_span"><input type="text" name="fio" size="25" id="fio"   value="<?php echo $_POST['fio'] ?>" style="height:auto;padding: 3px 5px;font-size: 1em;"/></span></td>

</tr>



<tr>

<td style="padding-bottom:8px;"><label for="exampleInputEmail1" style="display:block"><?php if($mosConfig_lang == 'ru') { echo "Контактный телефон"; }

								elseif ($mosConfig_lang == 'ro') { echo "Telefon de contact"; }

								elseif ($mosConfig_lang == 'en') { echo "Telephone"; }

								?><span style="color: red">*</span> </label></td>

<td style="padding-bottom:8px;"><font id="phones<?php echo $num;?>"><div class="input-group-addon" style="display:inline-block;  padding-left: 5px; height: 27px;  padding-top: 2.5px;">+</div><input type="text" name="telefon[0]" size="15" maxlength="15" id="t0" value="<?php echo $_POST['telefon'][0] ?>" class="form-control"  style="height:auto;padding: 3px 5px;font-size: 1em;display:inline-block;  width: calc(100% - 37px);" placeholder="00000000000" /></font>

<a href="javascript:AddNumbers('<?php echo $num;?>')" id="linkAdd">Добавить еще номер</a> <a href="javascript:DellNumbers('<?php echo $num;?>')" id="linkDell" style="float:right;display:none">Удалить</a>

</td>



<tr>

<td style="padding-bottom:8px;"><label for="exampleInputEmail1" style="display:block">E-mail </label></td>

<td style="padding-bottom:8px;"><span id="email_span"><input type="text" name="email" size="25" value="<?php echo $_POST['email'] ?>" style="padding: 3px 5px;font-size: 1em;height:auto" /></span></td>

</tr>

<!---

<tr>

<td style="padding-bottom:10px;">Примечание</td>

<td style="padding-bottom:10px;"><textarea type="textarea" name="other" class="inputbox" cols="30" rows="5" id="other"   ></textarea></td>

</tr>

--->

<tr>

<td style="padding-bottom:10px;"></td>

<td style="padding-bottom:10px;">

<input type="submit" name="btn" class="btn btn-success" value="<?php if($mosConfig_lang == 'ru') { echo "Отправить"; }

								elseif ($mosConfig_lang == 'ro') { echo "Expediaza"; }

								elseif ($mosConfig_lang == 'en') { echo "Send"; }

								?>" id="submit" style="padding:5px;"/>

</td>

</tr>



</table>



<input type="hidden" value="<? echo $ANOTHER;?>" name="form_name" />

</form>

<script>

$(function () {

                $('#date_export').datetimepicker({

				locale:'ru',

				format: 'DD-MM-YYYY'

				}

				);

				if ($('html').width()<=640) 	$('.bold tr td').css('display','block');

            });

			

			num = [0,0];

	forms = ['forma','formc'];

</script>

<?php } else {

	echo $succes_message;

	}

?>