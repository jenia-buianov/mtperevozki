<style type="text/css">
.countries {
	background-color:#CCC;
	border: 1px solid #FFF;
	padding-left: 10px;
	
}
.contry{
	border: 1px solid #FFF;
}
.country:hover {
	border:2px solid #000;
}
.transport_from {
	background-color:#0CF;
	border: 1px solid #FFF;
}
.transport_to {
	background:#09C;
	border: 1px solid #FFF;
}
.cargo_to {
	background:#3F9;
	border: 1px solid #FFF;
}
.cargo_from {
	background:#80FF80;
	border: 1px solid #FFF;
}
</style>
<body>
<table width="100%" border="0" cellpadding="0" cellspacing="0" style="border-collapse:collapse;">
  <tr>
    <td rowspan="2" align="center" valign="middle" class="countries">—трана</td>
    <td colspan="2" align="center" bgcolor="#00FFFF" style="border: 1px solid #FFF;">“ранспорт</td>
    <td colspan="2" align="center" bgcolor="#2DFF71" style="border: 1px solid #FFF;">√рузы</td>
  </tr>
  <tr>
    <td width="100" align="center" class="transport_from">из страны</td>
    <td width="100" align="center" class="transport_to">в страну</td>
    <td width="100" align="center" class="cargo_from">из страны</td>
    <td width="100" align="center" class="cargo_to">в страну</td>
  </tr>


<?
include ($_SERVER['DOCUMENT_ROOT'].'/db_connect/db_read.php');

$country_result = mysql_query("SELECT id_country, country_name_ru  FROM  country  ORDER BY  country.country_name_ru ASC");

while ($country = mysql_fetch_array($country_result)) {
	$cargo_export_num = mysql_num_rows(mysql_query("SELECT id  FROM  movers_cargo  WHERE  export LIKE  '%".mysql_real_escape_string($country['country_name_ru'])."%'"));
	$cargo_import_num = mysql_num_rows(mysql_query("SELECT id  FROM  movers_cargo  WHERE  import LIKE  '%".mysql_real_escape_string($country['country_name_ru'])."%'"));
	$transport_export_num = mysql_num_rows(mysql_query("SELECT id  FROM  movers_order  WHERE  export ='".mysql_real_escape_string($country['id_country'])."'"));
	$transport_import_num = mysql_num_rows(mysql_query("SELECT id  FROM  movers_order  WHERE  import ='".mysql_real_escape_string($country['id_country'])."'"));
	/*добавление урл дл€ показа груза\транспорта выборочно по стране */
	/*TODO добавить ссылки на модули показа транспорта и груза*/
	////////////////////////
	if($cargo_export_num > 0){
		$cargo_export = "<a href=\"http://".$_SERVER['SERVER_NAME']."/index.php?option=com_content&task=view&id=7&Itemid=17&type=export&country=".$country['id_country']."\"> ".$cargo_export_num." </a>";
	}else{
		$cargo_export = $cargo_export_num;
	}
	///////////////////////
	if($cargo_import_num > 0){
		$cargo_import = "<a href=\"http://".$_SERVER['SERVER_NAME']."/index.php?option=com_content&task=view&id=7&Itemid=17&type=import&country=".$country['id_country']."\"> ".$cargo_import_num." </a>";
	}else{
		$cargo_import = $cargo_import_num;
	}
	//////////////////////
	if($transport_export_num > 0){
		$transport_export = "<a href=\"http://".$_SERVER['SERVER_NAME']."/index.php?option=com_content&task=view&id=9&Itemid=22&export=".$country['id_country']."\"> ".$transport_export_num." </a>";
	}else{
		$transport_export = $transport_export_num;
	}
	/////////////////////
	if($transport_import_num > 0){
		$transport_import = "<a href=\"http://".$_SERVER['SERVER_NAME']."/index.php?option=com_content&task=view&id=9&Itemid=22&import=".$country['id_country']."\"> ".$transport_import_num." </a>";
	}else{
		$transport_import = $transport_import_num;
	}
	//echo "\n<br>".$country['country_name_ru']." cargo import-".$cargo_import." export-".$cargo_export." :::::: transport import-".$transport_import." export-".$transport_export."\n<br>";
	if ($cargo_export_num!=0 || $cargo_import_num!=0 || $transport_export_num!=0 || $transport_import_num!=0){
		echo"
	  <tr class=\"country\">
		<td class=\"countries\">".$country['country_name_ru']."</td>
		<td width=\"100\" align=\"center\" class=\"transport_from\">".$transport_export."</td>
		<td width=\"100\" align=\"center\" class=\"transport_to\">".$transport_import."</td>
		<td width=\"100\" align=\"center\" class=\"cargo_from\">".$cargo_export."</td>
		<td width=\"100\" align=\"center\" class=\"cargo_to\">".$cargo_import."</td>
	  </tr>
		";
	}
}
echo "</table>";
//echo "\n<br>TEST Hello world!\n<br>";
?>