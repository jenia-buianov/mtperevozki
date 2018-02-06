<?php

include (dirname(__FILE__).'/../system/configuration.php');
include (dirname(__FILE__).'/../system/functions.php');
include (dirname(__FILE__).'/../db_connect/db_read.php');
include (dirname(__FILE__).'/../utils.php');

$LANG =$langActivity['lang'];

$mosConfig_lang = $langActivity['lang'];
function Navigation($page,$pages) {
   $n='';
	global $home_page;
    if($pages>1){
        $n.='<div class="pages" style="text-align:center;margin-top:1em">';
        if($pages <= 9) {
        $start = 1;
           $end = $pages;
      }
        else {
           if(($page - 4) < 1) {
                $start = 1;
                $end = 9;
          }
           elseif(($page + 4) > $pages) {
               $end = $pages;
               $start = $pages - 9;
          }
            else {
               $start = ($page - 4);
                $end = ($page +4);
          }
        }
        if ($page>10) $n.='<a href="'.HOME.LANG.'/plain_cargo.php?export='.$_GET['export'].'&import='.$_GET['import'].'&page=1"  class="btn btn-link" style="margin-right:0.5em;">1</a> ...';
       for($i = $start; $i <= $end; $i++){
            $n.='<a href="'.HOME.LANG.'/plain_cargo.php?export='.$_GET['export'].'&import='.$_GET['import'].(($i != 1) ? '&page='.$i : '').'"'.(($page == $i) ? ' class="btn btn-primary" ' : ' class="btn btn-link"').'   style="margin-right:0.5em;">'.$i.'</a>';
        }
        if($end < $pages) {
           if($end != ($pages - 1)) $n.='...';
            $n.='<a href="'.HOME.LANG.'/plain_cargo.php?export='.$_GET['export'].'&import='.$_GET['import'].'&page='.$pages.'" class="btn btn-link" style="margin-left:0.5em;">'.$pages.'</a>';
        }
        $n.='</div>';
    }
return $n;
}



	if (!empty($_GET['page'])) $start = (htmlspecialchars($_GET['page'],ENT_QUOTES)-1) * 50; 
	$query = "SELECT * FROM `movers_cargo`";
	$AutoID = '43';
	$query.=" WHERE `type` IN (".$AutoID.") AND `hidden`='0' ";
	if (!empty($_POST['country_from'])) $query.=" AND `export`='".htmlspecialchars($_POST['country_from'],ENT_QUOTES)."'";
	if (!empty($_POST['country_to'])) $query.=" AND `import`='".htmlspecialchars($_POST['country_to'],ENT_QUOTES)."'";
	
	if (!empty($_GET['export'])) {$export = htmlspecialchars($_GET['export'],ENT_QUOTES);}
	if (!empty($_GET['import'])) {$import = htmlspecialchars($_GET['import'],ENT_QUOTES);}
	

	if (!empty($_GET['export'])) $query.=" AND `export`='".htmlspecialchars($_GET['export'],ENT_QUOTES)."'";
	if (!empty($_GET['import'])&&!empty($_GET['export'])) $query.=" AND `import`='".htmlspecialchars($_GET['import'],ENT_QUOTES)."'";
	if (!empty($_GET['import'])&&empty($_GET['export'])) $query.=" AND `import`='".htmlspecialchars($_GET['import'],ENT_QUOTES)."'";
	
	if (!empty($_GET['day'])) $dateF = htmlspecialchars($_GET['day'],ENT_QUOTES);
	if (!empty($_GET['month'])) $dateF.= '-'.htmlspecialchars($_GET['month'],ENT_QUOTES);
	if (!empty($_GET['year'])) $dateF.= '-'.htmlspecialchars($_GET['year'],ENT_QUOTES);	

	
	if (!empty($dateF)) $query.=" AND `date`='$dateF'";
	$query.=" ORDER BY `id` DESC";
	$askQueyr = $query;
	
	$CPages = mysqli_query($db,$askQueyr);
	$PagesCount=intval((mysqli_num_rows($CPages) - 1) / 50) + 1;
	if(!is_numeric($_GET['page'])) $_GET['page'] = 1;
	if (!empty($start)) $query.=" LIMIT ".$start.",50";
	else $query.=' LIMIT 50';
	if (LANG=='ru')$title = '<br><b>Всего в вашем направлении перевозки ';
	if (LANG=='ro')$title = '<br><b>În direcția voastră ';
	if (LANG=='en')$title = '<br><b>Total in your direction ';
	

	if(!empty($export))

	{

		$getCountryExport = mysqli_query($db,"SELECT `country_name_$mosConfig_lang` FROM `country` WHERE `id_country`='$export'");

		$countryExport = mysqli_fetch_array($getCountryExport);

		if (LANG=='ru') $title.='из '.CountryFrom($countryExport['country_name_'.$mosConfig_lang]);

		if (LANG=='ro') $title.='din '.CountryFrom($countryExport['country_name_'.$mosConfig_lang]);

		if (LANG=='en') $title.='from '.CountryFrom($countryExport['country_name_'.$mosConfig_lang]);

	}

	if(!empty($import))

	{

		$getCountryImport = mysqli_query($db,"SELECT `country_name_$mosConfig_lang` FROM `country` WHERE `id_country`='$import'");

		$countryImport = mysqli_fetch_array($getCountryImport);

		if(LANG=='ru')$title.=' в '.CountryTo($countryImport['country_name_'.$mosConfig_lang]);

		if(LANG=='ro')$title.=' în '.CountryTo($countryImport['country_name_'.$mosConfig_lang]);

		if(LANG=='en')$title.=' to '.CountryTo($countryImport['country_name_'.$mosConfig_lang]);

	}

	

	$result = mysqli_query($db,$query);
	if(mysqli_num_rows($result)==0) echo mysqli_error($db);
	if ((!empty($export)||!empty($import))&&LANG=='ru') echo $title.' найдено грузов '.mysqli_num_rows($CPages).' заявок</b>';

	if ((!empty($export)||!empty($import))&&LANG=='ro') echo $title.' au fost găsite '.mysqli_num_rows($CPages).' oferte cu camine</b>';

	if ((!empty($export)||!empty($import))&&LANG=='en') echo $title.' found '.mysqli_num_rows($CPages).' cargo offers</b>';

	if (mysqli_num_rows($result)>0)

	{

		

		if (LANG=='ru') $t1 = 'Место погрузки';

		if (LANG=='ro') $t1 = 'Locul de încărcare';

		if (LANG=='en') $t1 = 'Loading palce';

		

		if (LANG=='ru') $t2 = 'Место разгрузки';

		if (LANG=='ro') $t2 = 'Locul de descărcare';

		if (LANG=='en') $t2 = 'Unloading place';

		

		

		if (LANG=='ru') $t3 = 'Дата погрузки';

		if (LANG=='ro') $t3 = 'Data încarcării';

		if (LANG=='en') $t3 = 'Loading date';

		

		

		if (LANG=='ru') $t4 = 'Тип транспорта';
		if (LANG=='ro') $t4 = 'Tipul transportului';
		if (LANG=='en') $t4 = 'Transport type';

		

		if (LANG=='ru') $t5 = 'Тип груза';

		if (LANG=='ro') $t5 = 'Tipul marfei';

		if (LANG=='en') $t5 = 'Cargo type';

		

		if (LANG=='ru') $t6 = 'Объем/вес';

		if (LANG=='ro') $t6 = 'Volum';

		if (LANG=='en') $t6 = 'Volume';

		

		if (LANG=='ru') $t7 = 'Заказчик';

		if (LANG=='ro') $t7 = 'Client';

		if (LANG=='en') $t7 = 'Client';

		

		if (LANG=='ru') $more = 'Подробнее';

		if (LANG=='ro') $more = 'Detalii';

		if (LANG=='en') $more = 'Information';

		

		if (LANG=='ru') $onpage= 'На странице';

		if (LANG=='ro') $onpage= 'Pe pagină';

		if (LANG=='en') $onpage= 'On page';

		

		if (LANG=='ru') $page_= 'Страница';

		if (LANG=='ro') $page_= 'Pagină';

		if (LANG=='en') $page_= 'Page';

		

		if (LANG=='ru') $from_= 'из';

		if (LANG=='ro') $from_= 'din';

		if (LANG=='en') $from_= 'from';

		echo '<div style="float:right;margin-top:1em">'.$page_.' '.$_GET['page'].' '.$from_.' '.$PagesCount.' </div>';
		echo Navigation(htmlspecialchars($_GET['page'],ENT_QUOTES),$PagesCount);
		echo'</div>';
	
		echo '

		

		<table cellpadding="0" cellspacing="0" border="0" id="table" class="sortable" style="margin-top:2em">

		<thead>

			<tr>

				<th style="width:15%"><h3>'.$t1.'</h3></th>

				<th style="width:15%"><h3>'.$t2.'</h3></th>

				<th style="width:15%"><h3>'.$t3.'</h3></th>

				<th style="width:20%"><h3>'.$t4.'</h3></th>

				<th style="width:15%"><h3>'.$t5.'</h3></th>

				<th style="width:10%"><h3>'.$t6.'</h3></th>

				<th><h3>'.$t7.'</h3></th>

			</tr>

		</thead>

		';

		$cargo = mysqli_fetch_array($result);

		do

		{

			if ($cargo["by_admin"] == "-1") {



				$fio = $cargo["face"];

				$phone = '';

					$explodePhone = explode(';',$cargo["phone"]);

					for($k=0;$k<count($explodePhone);$k++)

					{

						if ($explodePhone[$k][1]=='+') $phone.=substr($explodePhone[$k],1).'<br>';

						else $phone.=$explodePhone[$k].'<br>';

					}

					$phone = substr($phone,0,strlen($phone)-4);

				$email = $cargo["email"];

				$skype = $cargo["skype"];

				$icq = $cargo["icq"];

				$docs = $cargo["documents"];

				

			} else {

				if ($cargo['by_admin'] == 0) {

					$id_contact = 1;

				} else {

					$id_contact = $cargo['by_admin'];

				}



				$res = mysqli_query($db,"SELECT * FROM movers_contact WHERE id='$id_contact'");



				if ($res and mysqli_num_rows($res) == 1) {



					$res = mysqli_fetch_array($res);

					$fio = $res['face'];

					$phone = '';

					$explodePhone = explode(';', $cargo["phone"]);

					for($k=0;$k<count($explodePhone);$k++)

					{

						if ($explodePhone[$k][1]=='+') $phone.=substr($explodePhone[$k],1).'<br>';

						else $phone.=$explodePhone[$k].'<br>';

					}

					$phone = substr($phone,0,strlen($phone)-4);

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

					$fio = $cargo["face"];

					$phone = '';

					$explodePhone = explode(';',$cargo["phone"]);

					for($k=0;$k<count($explodePhone);$k++)

					{

						if ($explodePhone[$k][1]=='+') $phone.=substr($explodePhone[$k],1,strlen($explodePhone[$k])).'<br>';

						else $phone.=$explodePhone[$k].'<br>';

					}

					$phone = substr($phone,0,strlen($phone)-4);

					$email = $cargo["email"];

					$skype = $cargo["skype"];

					$icq = $cargo["icq"];

				}

			}

			$getCountryImport = mysqli_query($db,"SELECT `country_name_$mosConfig_lang` FROM `country` WHERE `id_country`='$cargo[import]'");
			$countryImport = mysqli_fetch_array($getCountryImport);
			$getCountryExport = mysqli_query($db,"SELECT `country_name_$mosConfig_lang` FROM `country` WHERE `id_country`='$cargo[export]'");
			$countryExport = mysqli_fetch_array($getCountryExport);



			if(!empty($cargo['import_city'])) {
				if(is_numeric($cargo['import_city'])&&$cargo['import_city']>0)
				{
					$getCityImport = mysqli_query($db,"SELECT `city_name_$mosConfig_lang` FROM `city` WHERE `id_city`='$cargo[import_city]'");
					$cityImport = mysqli_fetch_array($getCityImport);
					$import_city = $cityImport['city_name_'.$mosConfig_lang];
				}
				else $import_city = $cargo['import_city'];
			}else $import_city='';

			if(!empty($cargo['export_city'])) {
				if(is_numeric($cargo['export_city'])&&$cargo['export_city']>0)
				{
					$getCityExport = mysqli_query($db,"SELECT `city_name_$mosConfig_lang` FROM `city` WHERE `id_city`='$cargo[export_city]'");
					$cityExport = mysqli_fetch_array($getCityExport);
					$export_city = $cityExport['city_name_'.$mosConfig_lang];
				}
				else $export_city = $cargo['export_city'];
			}else $export_city = '';



			$getTransportType = mysqli_query($db,"SELECT `transport_type_$mosConfig_lang` FROM `transport_type` WHERE `id`='$cargo[type]'");

			$TransportType = mysqli_fetch_array($getTransportType);

			$getVolume = mysqli_query($db,"SELECT `cargo_volume_$mosConfig_lang` FROM `cargo_volume` WHERE `id`='$cargo[volume]'");

			$Volume = mysqli_fetch_array($getVolume);



			$getGruz = mysqli_query($db,"SELECT `cargo_type_$mosConfig_lang` FROM `cargo_type` WHERE `id`='$cargo[name]'");

			$Gruz = mysqli_fetch_array($getGruz);



			echo '<tr>

			<td style="width:15%">'.$countryExport['country_name_'.$mosConfig_lang].' <font style="color:#666"><b>'.$export_city.'</b></font></td>
			<td style="width:15%">'.$countryImport['country_name_'.$mosConfig_lang].' <font style="color:#666"><b>'.$import_city.'</b></font></td>

			<td  style="width:15%">'.$cargo['date'].'</td>

			<td  style="width:20%">'.$TransportType['transport_type_'.$mosConfig_lang];
			if (!empty($Conteiner['container_type_'.$mosConfig_lang])) echo '<br>'.$Conteiner['container_type_'.$mosConfig_lang];
			echo'</td>

			<td style="width:15%">'.$Gruz['cargo_type_'.$mosConfig_lang].'</td>

			<td style="text-align:center;width:10%">'.$Volume['cargo_volume_'.$mosConfig_lang].'</td>

			<td>'.$fio.'<br>'.$phone.'<br><a href="'.HOME.'cargo/plain_info.php?language='.LANG.'&id='.$cargo['id'].'" ';?>onclick='return popup(this, "Popup")'<?php echo' target="_blank">'.$more.'</a></td>

			</tr>';

		}

		while($cargo = mysqli_fetch_array($result));

		echo '</table>	';

		echo '<div style="float:right;margin-top:1em">'.$page_.' '.$_GET['page'].' '.$from_.' '.$PagesCount.' </div>';
		echo Navigation(htmlspecialchars($_GET['page'],ENT_QUOTES),$PagesCount);
		echo'</div>';

	}else echo 'Ничего не найдено';


?>


<script>


$(function () {

		pos = $('#table thead').offset().top;
		left = $('#table thead').offset().left;
		getTd = $('#table tbody tr:eq(0)').html().split('<td');
		countTR = count($('#table tbody').html().split('<tr'))-2;

				

		for(k=0;k<count(getTd);k++)

		$('#table thead th:eq('+k+')').css('width',($('#table tbody tr:eq(0) td:eq('+k+')').width()+11)+'px');
		
		if(parseInt($('html').width())<750)
		{
			if(parseInt($('html').width())<481)$('#table thead th:eq(2)').css('display','none');
			$('#table thead th:eq(3)').css('display','none');
			$('#table thead th:eq(4)').css('display','none');
			$('#table thead th:eq(5)').css('display','none');
			
			for(k=0;k<count($('#table tbody').html().split('tr'));k++)
			{
							
				if(parseInt($('html').width())<481)$('#table tbody tr td:eq('+(2+((count(getTd)-1)*k))+')').css('display','none');
				$('#table tbody tr td:eq('+(3+((count(getTd)-1)*k))+')').css('display','none');
				$('#table tbody tr td:eq('+(4+((count(getTd)-1)*k))+')').css('display','none');
				$('#table tbody tr td:eq('+(5+((count(getTd)-1)*k))+')').css('display','none');
			}
			$('#table thead th:eq(6)').css('width',($('#table tbody tr:eq(0) td:eq(6)').width()+11)+'px');
		}
		

		$(window).scroll(function () {
			
			if(parseInt($('html').width())<750)
			{
				if(parseInt($('html').width())<481) $('#table thead th:eq(2)').css('display','none');
				else  $('#table thead th:eq(2)').css('display','table-cell');
				$('#table thead th:eq(3)').css('display','none');
				$('#table thead th:eq(4)').css('display','none');
				$('#table thead th:eq(5)').css('display','none');
				
				for(k=0;k<count($('#table tbody').html().split('tr'));k++)
				{
								
					if(parseInt($('html').width())<481) $('#table tbody tr td:eq('+(2+((count(getTd)-1)*k))+')').css('display','none');
					else $('#table tbody tr td:eq('+(2+((count(getTd)-1)*k))+')').css('display','table-cell');
					$('#table tbody tr td:eq('+(3+((count(getTd)-1)*k))+')').css('display','none');
					$('#table tbody tr td:eq('+(4+((count(getTd)-1)*k))+')').css('display','none');
					$('#table tbody tr td:eq('+(5+((count(getTd)-1)*k))+')').css('display','none');
				}
				$('#table thead th:eq(6)').css('width',($('#table tbody tr:eq(0) td:eq(6)').width()+11)+'px');
			}

			if ($(this).scrollTop() > pos&&$(this).scrollTop()<$('#table tbody tr:eq('+countTR+')').offset().top) {

				

				$('#table thead').css('background-color','#2c3e50');

				$('#table thead').css('position','fixed');
				$('#table thead ').css('left',left+'px');
				$('#table thead ').css('top','60px');


			} else {

				$('#table thead').css('position','relative');
				$('#table thead ').css('left','0px');
				$('#table thead ').css('top','0px');

			}

		});


	});

	
</script>