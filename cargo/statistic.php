
    <?php $arrayID = array(9,15,43,44);

	 

	 $sqlCount1 = mysqli_query($CONNECTION,"SELECT `id` FROM `movers_cargo` WHERE `type`='9'");

	 $sqlCount2 = mysqli_query($CONNECTION,"SELECT `id` FROM `movers_cargo` WHERE `type`='15'");

	 $sqlCount3 = mysqli_query($CONNECTION,"SELECT `id` FROM `movers_cargo` WHERE `type`='43'");

	 $sqlCount4 = mysqli_query($CONNECTION,"SELECT `id` FROM `movers_cargo` WHERE `type`='44'");

	 $sqlCount5 = mysqli_query($CONNECTION,"SELECT `id` FROM `movers_cargo_post`");

	  ?>

     

       <div class="page5 add-track-page">



			<div class="cnt-tbl half">

				<div class="cnt-title">Статистика транспорта и грузов</div>

				<div class="cnt-cell-wr">

					<div class="cnt-left-cell">

						<div class="cnt-count" id="c1" data-show="1"><?php echo mysqli_num_rows($sqlCount1);?></div>

						<div class="cnt-count-underline"></div>

						<div class="cnt-count-descr">Автоперевозки</div>

					</div>



					<div class="cnt-left-cell">

						<div class="cnt-count" id="c2" data-show="1"><?php echo mysqli_num_rows($sqlCount2);?></div>

						<div class="cnt-count-underline"></div>

						<div class="cnt-count-descr">Морские перевозки</div>

					</div>

                    <div class="cnt-left-cell">

						<div class="cnt-count" id="c3" data-show="1"><?php echo mysqli_num_rows($sqlCount3);?></div>

						<div class="cnt-count-underline"></div>

						<div class="cnt-count-descr">Железнодорожные перевозки</div>

					</div>



				</div>

			</div>



			<div class="cnt-tbl">

				<div class="cnt-title">&nbsp;</div>

				<div class="cnt-cell-wr">

					<div class="cnt-left-cell">

						<div class="cnt-count" id="c4" data-show="1"><?php echo mysqli_num_rows($sqlCount4);?></div>

						<div class="cnt-count-underline"></div>

						<div class="cnt-count-descr">Авиа доставка грузов</div>

					</div>



					<div class="cnt-left-cell">

						<div class="cnt-count" id="c5" data-to="1580" data-show="1"><?php echo mysqli_num_rows($sqlCount5);?></div>

						<div class="cnt-count-underline"></div>

						<div class="cnt-count-descr">Доставка пасылок</div>

					</div>

                    

                    <div class="cnt-left-cell">

						<div class="cnt-count" data-to="c6" data-show="1">0</div>

						<div class="cnt-count-underline"></div>

						<div class="cnt-count-descr">Пассажирские перевозки</div>

					</div>



				</div>

			</div>
   
<table width="100%"><tr><td width="50%" align="center" style="padding-top:2em"><button href="<?php echo $home_url.'ru/razmestit-gruz-dobavit-gruz-zakazat-perevozku-nayti-transport.php';?>" class="btn btn-info" href="<?php echo $home_url; ?>ru/razmestit-gruz-dobavit-gruz-zakazat-perevozku-nayti-transport.php" type="button">Добавить груз</button></td><td width="50%" align="center" style="padding-top:2em">

<button href="<?php echo $home_url.'ru/razmestit-transport-dobavit-svobodnyiy-transport-nayti-gruz.php';?>" class="btn btn-info" type="button">Добавить транпорт</button></td></tr></table>

		</div>