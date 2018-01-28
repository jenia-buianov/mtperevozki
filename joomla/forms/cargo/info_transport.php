<?php

if ($_GET) {
 if (isset($_GET["id"])) {
		$language = isset($_GET['language'])?(string)$_GET['language']:"ru";
		$id = stripslashes($_GET["id"]);
		
		include ($_SERVER['DOCUMENT_ROOT'].'/db_connect/db_read.php');
		
		$query="SELECT m.*, a.country_name_$language as export_title, a.alpha3 as export_code, b.country_name_$language as import_title, b.alpha3 as import_code,"
					."c.city_name_$language as export_city_title,d.city_name_$language as import_city_title,"
					."e.transport_type_$language as type_title, f.cargo_type_$language as name_title, g.cargo_volume_$language as volume_title"
					." FROM movers_order as m LEFT JOIN country as a ON a.id_country=m.export LEFT JOIN country as b ON b.id_country=m.import"
					." LEFT JOIN city as c ON c.id_city=m.export_city LEFT JOIN city as d ON d.id_city=m.import_city"
					." LEFT JOIN transport_type as e ON e.id=m.type LEFT JOIN cargo_type as f ON f.id=m.name LEFT JOIN cargo_volume as g ON g.id=m.volume WHERE m.id='".$id."' LIMIT 1";

		$result = mysql_query($query);
		
		$title = "<title>Перевозка грузов, транспортные услуги, международные грузоперевозки.</title>";

		$styles = "<style>
				#table_header {
				color: #FFF;
				font: 14px;
				font-weight:bold;
				padding: 2px;
				}

				#table_header a {
				color: #CEFFCE;
				font: 14px;
				font-weight:bold;
				font-style:normal;
				}

				#table_header a:hover {
				color: #CEFFCE;
				font: 14px;
				font-weight:bold;
				font-style:underline;
				}

				#type_cargo {
				font-size: 12px;
				color:#666;
				}

				.cargo_table tr td {
				border: solid 1px #fff !important;
				border-collapse:collapse !important;
				padding: 0px 3px 0px 3px !important;
				font-size:14px !important;
				}

				#cargo-today{
				background: #CEFFCE;
				}

				#cargo-today:hover{
				background: #6F6;
				}

				#cargo{
				background: #BDF;
				}

				#cargo:hover{
				background: #6F6;
				}
			</style>";
			
			$header_logo = "<br>
			<div align='center' style='width:100%;'>
				<div><a target='_blank' href='index.php'><img src='http://www.moldovatruck.md/templates/ja_mesolite/images/logo.png' style='border: none;'></a></div>
				<br>
				<div><strong>Moldova Truck - Сайт транспортных экспедиторских услуг</strong></div>
				<br><br>
			";

		if (mysql_num_rows($result) == 1) {

			$row = mysql_fetch_array($result);

			if ($row["by_admin"] == "-1") {

				$fio = $row["face"];
				$phone = $row["phone"];
				$email = $row["email"];
				$skype = $row["skype"];
				$icq = $row["icq"];
				$docs = $row["documents"];
				$is_admin = 1;
				$b_color = "#ace1af";
			} else {
				if ($row['by_admin'] == 0) {
					$id_contact = 1;
				} else {
					$id_contact = $row['by_admin'];
				}

				$res = mysql_query("SELECT * FROM movers_contact WHERE id='$id_contact'");

				if ($res and mysql_num_rows($res) == 1) {

					$res = mysql_fetch_array($res);
					$fio = $res['face'];
					$phone = $res['phone'];
					$email = $res['email'];
					$skype = $res['skype'];
					$icq = $res['icq'];
				} else {
					/*
					$fio = "Александр Маламудман";
					$phone = "+373 69107853";
					$email = "real@riatec.md";
					$skype ="movers";
					$icq = "";
					*/
					$fio = $row["face"];
					$phone = $row["phone"];
					$email = $row["email"];
					$skype = $row["skype"];
					$icq = $row["icq"];
				}
				$is_admin = 0;
			}
			if ($row["by_admin"] == "-1") $b_color = "#ace1af";
				else $b_color="#CEFFCE";
				
				if ($row['export_title']) $export = $row['export_title']; else $export = $row['export'];
				if ($row['export_city_title']) $export_city = $row['export_city_title']; else $export_city = $row['export_city'];
				
				if ($row['import_title']) $import = $row['import_title']; else $import = $row['import'];
				if ($row['import_city_title']) $import_city = $row['import_city_title']; else $import_city = $row['import_city'];
				
				if ($row['free']) $date = $row['free']; elseif ($row['date_from'] && $row['date_to']) $date = $row['date_from']."-".$row['date_to']; else $date = $row['order_date'];
				if ($row['type_title']) $type = $row['type_title']; else $type = $row['type'];
				if ($row['volume_title']) $volume = $row['volume_title']; else $volume = $row['volume'];
				
				$title = "<title>Транспорт по направлению ".$export." - ".$import;
				if (isset($date)) $title.=", свободен с ".$date.".";
				if (isset($type)) $title.=", тип транспорта ".$type.".";
				$title.="</title>";
				
				$table.=
				"<div style='text-align:left;'><strong>&nbsp;Иформация о транспорте:</strong></div>
				<table border='1' class='cargo_table' style='width:100%;'>

					<tr>
					<td style='color: #FFF;font: 14px;font-weight:bold;padding:2px; width:150px;' bgcolor='#1076a8'>Место погрузки:</td>
					<td class='cargo_today' style='background: $b_color;'>".$export." <span id='type_cargo'>".$export_city."</span></td>
					</tr>

					<tr>
					<td style='color: #FFF;font: 14px;font-weight:bold;padding:2px; width:150px;' bgcolor='#1076a8'>Место разгрузки:</td>
					<td class='cargo_today' style='background: $b_color;'>".$import." <span id='type_cargo'>".$import_city."</span></td>
					</tr>

					<tr>
					<td style='color: #FFF;font: 14px;font-weight:bold;padding:2px; width:150px;' bgcolor='#1076a8'>Свободен с:</td>
					<td class='cargo_today' style='background: $b_color;'>".$date."</td>
					</tr>

					<tr>
					<td style='color: #FFF;font: 14px;font-weight:bold;padding:2px; width:150px;' bgcolor='#1076a8'> Тип транспотра:</td>
					<td class='cargo_today' style='background: $b_color;'>".$type."</td>
					</tr>


					<tr>
					<td style='color: #FFF;font: 14px;font-weight:bold;padding:2px; width:150px;' bgcolor='#1076a8'>Объём, грузоподьёмность:</td>
					<td class='cargo_today' style='background: $b_color;'>".$volume."</td>
					</tr>";
				$table.= "</table>

				<br><div style='text-align:left;'><strong>&nbsp;Контактная информация:</strong></div>
				<table border='1' class='cargo_table' style='width:100%;'>

					<tr>
					<td style='color: #FFF;font: 14px;font-weight:bold;padding:2px; width:150px;' bgcolor='#1076a8'>Контактное лицо:</td>
					<td class='cargo_today' style='background:$b_color;'>$fio</td>
					</tr>

					<tr>
					<td style='color: #FFF;font: 14px;font-weight:bold;padding:2px; width:150px;' bgcolor='#1076a8'>Контактный телефон:</td>
					<td class='cargo_today' style='background: $b_color;'>$phone</td>
					</tr>

					<tr>
					<td style='color: #FFF;font: 14px;font-weight:bold;padding:2px; width:150px;' bgcolor='#1076a8'>E-mail:</td>
					<td class='cargo_today' style='background: $b_color;'>$email</td>
					</tr>

					<tr>
					<td style='color: #FFF;font: 14px;font-weight:bold;padding:2px; width:150px;' bgcolor='#1076a8'>Skype:</td>
					<td class='cargo_today' style='background: $b_color;'>$skype</td>
					</tr>

					<tr>
					<td style='color: #FFF;font: 14px;font-weight:bold;padding:2px; width:150px;' bgcolor='#1076a8'>ICQ:</td>
					<td class='cargo_today' style='background: $b_color;'>$icq</td>
					</tr>
				</table>";

		} else {
			$table .="<center><h2>Ошибка, груз не найден!</h2></center>";
		}

		$message="<html>
				  <head>".$title.$styles."</head>
				  <body>".$header_logo.$table."
				  </body>
				  </html>";

		echo $message;

	}
}

?>



