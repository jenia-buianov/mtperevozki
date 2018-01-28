<?php

$language = _LANGUAGE;

include ($_SERVER['DOCUMENT_ROOT'].'/db_connect/db_read.php');

//country list
$query = "SELECT country_group, id_country, country_name_$language as country_name FROM country ORDER BY country_group ASC, country_name_$language ASC";
$table = mysql_query($query);
$countries = "";
if (mysql_num_rows($table) > 0)
{
	$data= mysql_fetch_array($table);
	$country_group = $data['country_group'];
	$countries.="<optgroup label=''>";
	$countries.= "<option value='".$data['id_country']."'>".$data['country_name']."</option>";
	while ($data = mysql_fetch_array($table))
	{
		if ($data['country_group'] != $country_group)
		{
			$countries.="</optgroup>";
			$country_group = $data['country_group'];
			$countries.="<optgroup label='___________________________'>";
		}
		$countries.="<option value='".$data['id_country']."'>".$data['country_name']."</option>";
	}
	$countries.="</optgroup>";
}
//country list
$split = " AND split$suffix=1";
if ($suffix == "")  $split1 = " OR split_seafreight = 1";
else { $split1 = ""; $split = ""; }
//Transport type list
$query = "SELECT id as data_id, transport_type_$language as data_title,  transport_type_group as data_group FROM  transport_type$suffix WHERE transport_type_hidden != 1 $split $split1 ORDER BY transport_type_group ASC, `order` ASC, transport_type_$language ASC";
$table = mysql_query($query);
$transport_types = "";

if (mysql_num_rows($table) > 0)
{
	$data= mysql_fetch_array($table);
	$data_group = $data['data_group'];
	$transport_types.="<optgroup label=''>";
	$transport_types.= "<option value='".$data['data_id']."'>".$data['data_title']."</option>";
	
	while ($data = mysql_fetch_array($table))
	{
		if ($data['data_group'] != $data_group)
		{
			$transport_types.="</optgroup>";
			$data_group = $data['data_group'];
			$transport_types.="<optgroup label='___________________________'>";
		}
		$transport_types.= "<option value='".$data['data_id']."'>".$data['data_title']."</option>";
	}
	$transport_types.="</optgroup>";
}
//Transport type list

//Cargo type list
$query = "SELECT id as data_id, cargo_type_$language as data_title, cargo_type_group as data_group  FROM  cargo_type$suffix WHERE cargo_type_hidden != 1 ORDER BY cargo_type_group ASC, `order` ASC, cargo_type_$language ASC";
$table = mysql_query($query);
$cargo_types = "";
if (mysql_num_rows($table) > 0)
{
	$data= mysql_fetch_array($table);
	$data_group = $data['data_group'];
	$cargo_types.="<optgroup label=''>";
	$cargo_types.= "<option value='".$data['data_id']."'>".$data['data_title']."</option>";
	
	while ($data = mysql_fetch_array($table))
	{
		if ($data['data_group'] != $data_group)
		{
			$cargo_types.="</optgroup>";
			$data_group = $data['data_group'];
			$cargo_types.="<optgroup label=''>";
		}
		$cargo_types.= "<option value='".$data['data_id']."'>".$data['data_title']."</option>";
	}
	$cargo_types.="</optgroup>";
}
//Cargo type list

//Volume type list
$query = "SELECT id as data_id, cargo_volume_$language as data_title, cargo_volume_group as data_group  FROM  cargo_volume$suffix WHERE cargo_volume_hidden != 1 $split ORDER BY id ASC, cargo_volume_group ASC, `order` ASC, cargo_volume_$language ASC";
$table = mysql_query($query);
$cargo_volumes = "";
if (mysql_num_rows($table) > 0)
{
	$data= mysql_fetch_array($table);
	$data_group = $data['data_group'];
	$cargo_volumes.="<optgroup label=''>";
	$cargo_volumes.= "<option value='".$data['data_id']."'>".$data['data_title']."</option>";
	
	while ($data = mysql_fetch_array($table))
	{
		if ($data['data_group'] != $data_group)
		{
			$cargo_volumes.="</optgroup>";
			$data_group = $data['data_group'];
			$cargo_volumes.="<optgroup label=''>";
		}
		$cargo_volumes.= "<option value='".$data['data_id']."'>".$data['data_title']."</option>";
	}
	$cargo_volumes.="</optgroup>";
}
//Volume type list
//$dbo = mysql_connect('localhost', 'mweb72', 'ytsAF3aA');
//$db_select = mysql_select_db('usr_mweb72_1', $dbo);

?>