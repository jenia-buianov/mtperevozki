<style type="text/css">
#a {
					width:100%;
					display:block;
					border:none;
				}
				
				#a tr td:nth-child(1)
				{
					background-color:#2c3e50;
					padding:5px;
					text-align:left;
					color:#fff;
					font-size:1em;
					width:200px;
					border:none;
					border-bottom:1px solid #dce6ee;
				}
				#a tr td:nth-child(2), #a tr td:nth-child(3), #a tr td:nth-child(4), #a tr td:nth-child(5)
				{
					background-color:#fff;
					padding:5px;
					text-align:center;
					color:#2c3e50;
					font-size:1em;
					border-bottom:1px solid #dce6ee;
					width:340px;
					font-weight:bold;
				}
				#a tr td:nth-child(2), #a tr td:nth-child(4)
				{
					background-color:#ecf2f6;
					
				}
				#a tr:hover td
				{
					background-color:#dce6ee;
					color:#000;
				}
</style>
<body>
<?php
if (LANG=='ru')
{
?>
<table width="100%" id="a">
  <tr>
    <td rowspan="2" align="center" valign="middle" style="background-color:#2c3e50;color:#fff"><b>Страна</b></td>
    <td colspan="2" align="center" style="background-color:#2c3e50;color:#fff">Транспорт</td>
    <td colspan="2" align="center" style="background-color:#2c3e50;color:#fff">Грузы</td>
  </tr>
  <tr>
    <td width="100" align="center" style="background-color:#2c3e50;color:#fff"><b>из страны</b></td>
    <td width="100" align="center" style="background-color:#2c3e50;color:#fff">в страну</td>
    <td width="100" align="center" style="background-color:#2c3e50;color:#fff">из страны</td>
    <td width="100" align="center" style="background-color:#2c3e50;color:#fff" >в страну</td>
  </tr>
<? }
if (LANG=='ro')
{
 ?>
 <table width="100%" id="a">
  <tr>
    <td rowspan="2" align="center" valign="middle" style="background-color:#2c3e50;color:#fff"><b>Țară</b></td>
    <td colspan="2" align="center" style="background-color:#2c3e50;color:#fff">Transport</td>
    <td colspan="2" align="center" style="background-color:#2c3e50;color:#fff">Marfă</td>
  </tr>
  <tr>
    <td width="100" align="center" style="background-color:#2c3e50;color:#fff"><b>din țară</b></td>
    <td width="100" align="center" style="background-color:#2c3e50;color:#fff">în țară</td>
    <td width="100" align="center" style="background-color:#2c3e50;color:#fff">din țară</td>
    <td width="100" align="center" style="background-color:#2c3e50;color:#fff" >în țară</td>
  </tr>
<? }

if(LANG=='en')
{
?>
 <table width="100%" id="a">
  <tr>
    <td rowspan="2" align="center" valign="middle" style="background-color:#2c3e50;color:#fff"><b>Country</b></td>
    <td colspan="2" align="center" style="background-color:#2c3e50;color:#fff">Transport</td>
    <td colspan="2" align="center" style="background-color:#2c3e50;color:#fff">Cargo</td>
  </tr>
  <tr>
    <td width="100" align="center" style="background-color:#2c3e50;color:#fff"><b>from country</b></td>
    <td width="100" align="center" style="background-color:#2c3e50;color:#fff">to crounry</td>
    <td width="100" align="center" style="background-color:#2c3e50;color:#fff">from country</td>
    <td width="100" align="center" style="background-color:#2c3e50;color:#fff" >to country</td>
  </tr>

<?
}
include (dirname(__FILE__).'/../db_connect/db_read.php');

$country_result = mysqli_query($db,"SELECT id_country, country_name_".LANG."  FROM  country  ORDER BY  country.country_name_ru ASC");

while ($country = mysqli_fetch_array($country_result)) {
	$cargo_export_num = mysqli_num_rows(mysqli_query($db,"SELECT id  FROM  movers_cargo  WHERE  export='".mysqli_real_escape_string($db,$country['id_country'])."'"));
	$cargo_import_num = mysqli_num_rows(mysqli_query($db,"SELECT id  FROM  movers_cargo  WHERE  import='".mysqli_real_escape_string($db,$country['id_country'])."'"));
	$transport_export_num = mysqli_num_rows(mysqli_query($db,"SELECT id  FROM  movers_order  WHERE  export ='".mysqli_real_escape_string($db,$country['id_country'])."'"));
	$transport_import_num = mysqli_num_rows(mysqli_query($db,"SELECT id  FROM  movers_order  WHERE  import ='".mysqli_real_escape_string($db,$country['id_country'])."'"));
	/*добавление урл для показа груза\транспорта выборочно по стране */
	/*TODO добавить ссылки на модули показа транспорта и груза*/
	////////////////////////
	if($cargo_export_num > 0){
		$cargo_export = "".$cargo_export_num." ";
	}else{
		$cargo_export = $cargo_export_num;
	}
	///////////////////////
	if($cargo_import_num > 0){
		$cargo_import = " ".$cargo_import_num." ";
	}else{
		$cargo_import = $cargo_import_num;
	}
	//////////////////////
	if($transport_export_num > 0){
		$transport_export = " ".$transport_export_num." ";
	}else{
		$transport_export = $transport_export_num;
	}
	/////////////////////
	if($transport_import_num > 0){
		$transport_import = " ".$transport_import_num." ";
	}else{
		$transport_import = $transport_import_num;
	}
	//echo "\n<br>".$country['country_name_ru']." cargo import-".$cargo_import." export-".$cargo_export." :::::: transport import-".$transport_import." export-".$transport_export."\n<br>";
	if ($cargo_export_num!=0 || $cargo_import_num!=0 || $transport_export_num!=0 || $transport_import_num!=0){
		echo"
	  <tr>
		<td >".$country['country_name_'.LANG]."</td>
		<td>".$transport_export."</td>
		<td>".$transport_import."</td>
		<td>".$cargo_export."</td>
		<td>".$cargo_import."</td>
	  </tr>
		";
	}
}
echo "</table>";
//echo "\n<br>TEST Hello world!\n<br>";
?>