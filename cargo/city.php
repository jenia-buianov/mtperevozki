<?php

mb_internal_encoding("UTF-8");
$entry = 0;

include (dirname(__FILE__).'/../db_connect/db_read.php');
header('Cache-Control: no-cache');



if (isset($_POST['value']))

	$country = mysqli_real_escape_string($db,$_POST['value']);

	else $country = 0;



if (isset($_POST['language'])) 

	$language = mysqli_real_escape_string($db,$_POST['language']);

	else $language = "ru";

	

if (isset($country)) {

	

		

		$query = "SELECT `id_city`, `city_name_$language` as `city_name` FROM `city` WHERE `id_country` = '".$country."' ORDER BY `city_name_$language` ASC";

		echo $query;

		$res = mysqli_query($db,$query) or die(mysqli_error($db));

		if (mysqli_num_rows($res) > 0) {

			$entry="____________________________|";

			while ($row = mysqli_fetch_array($res)) {

					$entry .= $row['city_name'].'^'.$row['id_city'].',';

			}

			$entry = rtrim($entry, ',|');



		} else {

			$entry = 0;

		}

	



} else {

	$entry = 0;

}



//echo $language;

echo $entry;



?>



