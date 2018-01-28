<?php
mb_internal_encoding("UTF-8");

$entry = 0;
include ($_SERVER['DOCUMENT_ROOT'].'/db_connect/db_read.php');
header("Content-type: text/html; charset=windows-1251");
header('Cache-Control: no-cache');

if (isset($_GET['value']))
	$country = mysql_real_escape_string($_GET['value']);
	else $country = 0;

if (isset($_GET['amp;language'])) 
	$language = mysql_real_escape_string($_GET['amp;language']);
	else $language = "ru";
	
if (isset($country)) {
	$res = mysql_query("SELECT * FROM country WHERE id_country='".$country."'") or die(mysql_error());
	if (mysql_num_rows($res) == 1 ) {
		$row = mysql_fetch_array($res);
		$query = "SELECT id_city, city_name_".$language." as city_name FROM city WHERE id_country = '".$country."' ORDER BY city_name_".$language." ASC";
		$res = mysql_query($query) or die(mysql_error());
		if (mysql_num_rows($res) > 0) {
			$entry="____________________________|";
			while ($row = mysql_fetch_array($res)) {
					$entry .= $row['city_name'].'^'.$row['id_city'].',';
			}
			$entry = rtrim($entry, ',|');

		} else {
			$entry = 0;
		}
	} else {
			$entry = 0;
	}

} else {
	$entry = 0;
}

//echo $language;
echo $entry;

?>

