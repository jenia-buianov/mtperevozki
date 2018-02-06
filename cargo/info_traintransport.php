<?php



if ($_GET) {

 if (isset($_GET["id"])) {

		$language = isset($_GET['language'])?(string)$_GET['language']:"ru";

		$id = stripslashes($_GET["id"]);

		

		include (dirname(__FILE__).'/../db_connect/db_read.php');

		

		$query="SELECT m.*, a.country_name_$language as export_title, a.alpha3 as export_code, b.country_name_$language as import_title, b.alpha3 as import_code,"

					."c.city_name_$language as export_city_title,d.city_name_$language as import_city_title,"

					."e.transport_type_$language as type_title, f.cargo_type_$language as name_title, g.cargo_volume_$language as volume_title"

					." FROM movers_order as m LEFT JOIN country as a ON a.id_country=m.export LEFT JOIN country as b ON b.id_country=m.import"

					." LEFT JOIN city as c ON c.id_city=m.export_city LEFT JOIN city as d ON d.id_city=m.import_city"

					." LEFT JOIN transport_type as e ON e.id=m.type LEFT JOIN cargo_type as f ON f.id=m.name LEFT JOIN cargo_volume as g ON g.id=m.volume WHERE m.id='".$id."' LIMIT 1";



		$result = mysqli_query($db,$query);

		

		$title = "<title>Перевозка грузов, транспортные услуги, международные грузоперевозки.</title>";



		$styles = "<style>

				table {

					width:100%;

					display:block;

					border:none;

				}

				

				tr td:nth-child(1)

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

				tr td:nth-child(2)

				{

					background-color:#fff;

					padding:5px;

					text-align:left;

					color:#2c3e50;

					font-size:1em;

					border-bottom:1px solid #dce6ee;

					width:340px;

					font-weight:bold;

				}

				#type_cargo

				{

					color:#666;

					font-weight:normal;

				}

			</style>";

			

			$header_logo = "<br>

			<div align='center' style='width:100%;'>

				<div><img src='http://www.moldovatruck.md/templates/ja_mesolite/images/logo.png' style='border: none;'></div>

				<br>

				<div><strong>Moldova Truck - Сайт транспортных экспедиторских услуг</strong></div>

				<br><br>

			";



		if (mysqli_num_rows($result) == 1) {



			$row = mysqli_fetch_array($result);



			if ($row["by_admin"] == "-1") {



				$fio = $row["face"];

				$phone = '';

					$explodePhone = explode(';',$row["phone"]);

					for($k=0;$k<count($explodePhone);$k++)

					{

						if ($explodePhone[$k][1]=='+') $phone.=substr($explodePhone[$k],1,strlen($explodePhone[$k])).'<br>';

						else $phone.=$explodePhone[$k].'<br>';

					}

					$phone = substr($phone,0,strlen($phone)-4);

				$email = $row["email"];

				$company = $row["company"];

				$skype = $row["skype"];

				$icq = $row["icq"];

				$docs = $row["documents"];

				$is_admin = 1;
				$export_port = $row["export_city_port"];
				$import_port = $row["import_city_port"];
				$b_color = "#ace1af";

			} else {

				if ($row['by_admin'] == 0) {

					$id_contact = 1;

				} else {

					$id_contact = $row['by_admin'];

				}



				$res = mysqli_query($db,"SELECT * FROM movers_contact WHERE id='$id_contact'");



				if ($res and mysqli_num_rows($res) == 1) {



					$res = mysqli_fetch_array($res);

					$fio = $res['face'];
					$export_port = $res["export_city_port"];
					$import_port = $res["import_city_port"];
					$phone = '';

					$explodePhone = explode(';',$res["phone"]);

					for($k=0;$k<count($explodePhone);$k++)

					{

						if ($explodePhone[$k][1]=='+') $phone.=substr($explodePhone[$k],1,strlen($explodePhone[$k])).'<br>';

						else $phone.=$explodePhone[$k].'<br>';

					}

					$phone = substr($phone,0,strlen($phone)-4);

					$email = $res['email'];

					$skype = $res['skype'];

					$icq = $res['icq'];

					$company = $row["company"];

				} else {

					/*

					$fio = "Александр Маламудман";

					$phone = "+373 69107853";

					$email = "real@riatec.md";

					$skype ="movers";

					$icq = "";

					*/

					$fio = $row["face"];

					$phone = '';

					$explodePhone = explode(';',$row["phone"]);

					for($k=0;$k<count($explodePhone);$k++)

					{

						if ($explodePhone[$k][1]=='+') $phone.=substr($explodePhone[$k],1,strlen($explodePhone[$k])).'<br>';

						else $phone.=$explodePhone[$k].'<br>';

					}

					$phone = substr($phone,0,strlen($phone)-4);

					$email = $row["email"];

					$skype = $row["skype"];

					$icq = $row["icq"];

					$company = $row["company"];
					$export_port = $row["export_city_port"];
					$import_port = $row["import_city_port"];

				}

				$is_admin = 0;

			}

			if ($row["by_admin"] == "-1") $b_color = "#ace1af";

				else $b_color="#CEFFCE";

				

				if ($row['export_title']) $export = $row['export_title']; else $export = $row['export'];

				if ($row['export_city_title']!==0) $export_city = $row['export_city_title']; else $export_city = $row['export_city'];

				

				if ($row['import_title']) $import = $row['import_title']; else $import = $row['import'];

				if ($row['import_city_title']!==0) $import_city = $row['import_city_title']; else $import_city = $row['import_city'];

				

				if ($row['free']) $date = $row['free']; elseif ($row['date_from'] && $row['date_to']) $date = $row['date_from']."-".$row['date_to']; else $date = $row['order_date'];

				if ($row['type_title']) $type = $row['type_title']; else $type = $row['type'];

				if ($row['volume_title']) $volume = $row['volume_title']; else $volume = $row['volume'];

				

				$title = "<title>Транспорт по направлению ".$export." - ".$import;

				if (isset($date)) $title.=", Свободен с/по ".$date.".";

				if (isset($type)) $title.=", тип транспорта ".$type.".";

				$title.="</title>";
				$query = "SELECT `container_type_$language` AS `container` FROM `container_type_rails` WHERE `container_type_hidden`='0' AND `id`='".$row['container_type']."'";
			$getContainer = mysqli_query($db,$query);
			$Cont = mysqli_fetch_array($getContainer);
				

				$table.=

				"<div style='text-align:left;'><strong>&nbsp;Иформация о морском транспорте:</strong></div>

				<table style='width:100%;'>



					<tr>

					<td>Место погрузки:</td>

					<td>".$export." <span id='type_cargo'>".$export_city."</span> ";
					if(!empty($export_port)) $table.=" станция $export_port ";
					$table.="</td>

					</tr>



					<tr>

					<td>Место разгрузки:</td>

					<td>".$import." <span id='type_cargo'>".$import_city."</span>";
					if(!empty($import_port)) $table.=" станция $import_port ";
					$table.="</td>

					</tr>



					<tr>

					<td>Свободен с/по:</td>

					<td>".$date."</td>

					</tr>
					<tr>

					<td> Тип транспотра:</td>

					<td>".$type."</td>

					</tr>


					<tr>

					<td> Тип контейнера:</td>

					<td>".$Cont['container']."</td>

					</tr>





					<tr>

					<td>Объём, грузоподьёмность:</td>

					<td>".$volume."</td>

					</tr>";

				$table.= "</table>



				<br><div style='text-align:left;'><strong>&nbsp;Контактная информация:</strong></div>

				<table style='width:100%;'>";

				

			if(!empty($company))$table.="		<tr>

					<td>Компания:</td>

					<td>$company</td>

					</tr>";

			$table.="		<tr>

					<td>Контактное лицо:</td>

					<td>$fio</td>

					</tr>



					<tr>

					<td>Контактный телефон:</td>

					<td>$phone</td>

					</tr>



					<tr>

					<td>E-mail:</td>

					<td>$email</td>

					</tr>



					<tr>

					<td>Skype:</td>

					<td>$skype</td>

					</tr>



					<tr>

					<td>ICQ:</td>

					<td>$icq</td>

					</tr>

				</table>";



		} else {

			$table .="<center><h2>Ошибка, груз не найден!</h2></center>";

		}



		$message="<html>

				  <head>".$title.$styles."</head>

				  <body>".$header_logo.$table."

<a href=# onClick='window.print();' style='margin-right:10px;margin-bottm:1em;text-align:center;width:100%;display:block;color:#666'>Печать документа</a>
				  </body>

				  </html>";



		echo $message;



	}

}



?>







