<?php



if ($_GET) {

 if (isset($_GET["id"])) {

		$language = isset($_GET['language'])?(string)$_GET['language']:"ru";

		$id = stripslashes($_GET["id"]);

		

		include (dirname(__FILE__).'/../db_connect/db_read.php');

		

		$query="SELECT * FROM `movers_order_post` WHERE id='".$id."' LIMIT 1";



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

				}

				$is_admin = 0;

			}

			if ($row["by_admin"] == "-1") $b_color = "#ace1af";

				else $b_color="#CEFFCE";

				

				$getName = mysqli_query($db,"SELECT `cargo_type_$language` AS `n` FROM `cargo_type_post` WHERE `id`='$row[name]'");
				$name = mysqli_fetch_array($getName);
				
				$getVolume = mysqli_query($db,"SELECT `cargo_volume_$language` AS `v` FROM `cargo_volume_post` WHERE `id`='$row[volume]'");
				$volume = mysqli_fetch_array($getVolume);
				
				$getTrasnport = mysqli_query($db,"SELECT `transport_type_$language` AS `t` FROM `transport_type_post` WHERE `id`='$row[type]'");
				$transport = mysqli_fetch_array($getTrasnport);
				
				$exportCountry = mysqli_query($db,"SELECT `country_name_$language` AS `c` FROM `country` WHERE `id_country`=$row[export]");
				$export = mysqli_fetch_array($exportCountry);
			
				
				$importCountry = mysqli_query($db,"SELECT `country_name_$language` AS `c` FROM `country` WHERE `id_country`=$row[import]");
				$import = mysqli_fetch_array($importCountry);
				
				if(!empty($row['export_city']))
				{
					$exportCity = mysqli_query($db,"SELECT `city_name_$language` AS `c` FROM `city` WHERE `id_city`=$row[export_city]");
					$export_city = mysqli_fetch_array($exportCity);
				}
				if(empty($row['export_city']) or $row['export_city']==0) $export_city = ''; else $export_city = $export_city['c'];
				
				if(!empty($row['import_city']))
				{
					$importCity = mysqli_query($db,"SELECT `city_name_$language` AS `c` FROM `city` WHERE `id_city`=$row[import_city]");
					$import_city = mysqli_fetch_array($importCity);
				}
				if(empty($row['import_city']) or $row['import_city']==0) $import_city = ''; else $import_city = $import_city['c'];
			
				

				$title = "<title>Транспорт по направлению ".$export." - ".$import;

				if (isset($date)) $title.=", Свободен с/по ".$date.".";

				if (isset($type)) $title.=", тип транспорта ".$type.".";

				$title.="</title>";

				

				$table.=

				"<div style='text-align:left;'><strong>&nbsp;Иформация о морском транспорте:</strong></div>

				<table style='width:100%;'>



					<tr>

					<td>Место погрузки:</td>

					<td>".$export['c']." <span id='type_cargo'>".$export_city."</span> ";
					if(!empty($export_port)) $table.=" аэропорт $export_port ";
					$table.="</td>

					</tr>



					<tr>

					<td>Место разгрузки:</td>

					<td>".$import['c']." <span id='type_cargo'>".$import_city."</span>";
					if(!empty($import_port)) $table.=" аэропорт $import_port ";
					$table.="</td>

					</tr>



					<tr>

					<td>Свободен с/по:</td>

					<td>".$row['date_from']." - ".$row['date_to']."</td>

					</tr>



					<tr>

					<td> Тип транспотра:</td>

					<td>".$transport['t']."</td>

					</tr>





					<tr>

					<td>Объём, грузоподьёмность:</td>

					<td>".$volume['v']."</td>

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







