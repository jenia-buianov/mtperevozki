<?php
	include_once($_SERVER['DOCUMENT_ROOT']."/forms/lib/unified/loader.php");
	
	$builder = Unified::GetInstance();
	
	var_dump($builder);
?>

<form method="post" action="/index.php/ru/component/mtpform/">

	<fieldset>
		<div class="legend">Пункт загрузки</div>
		<p>
			<label>Страна загрузки</label>
			
			<select type="select" name="order[export]" class="inputbox" data-action="update-city" data-rel="export_city" >
				<option value="">Укажите страну загрузки</option>
				<?=$countries?>
			</select>
		</p>
		
		<p>
			<label>Город загрузки</label>
			
			<select type="select" name="order[export_city]" class="inputbox" disabled="disabled">
				<option value="">Укажите город загрузки</option>
				<option value="0">Все города</option>
			</select>
		</p>
		
	</fieldset>
	
	<fieldset>
		<div class="legend">Пункт назначения</div>
		<p>
			<label>Страна разгрузки</label>
			
			<select type="select" name="order[import]" class="inputbox" data-action="update-city" data-rel="import_city" >
				<option value="">Укажите страну разгрузки</option>
				<?=$countries?>
			</select>
		</p>
		
		<p>
			<label>Город разгрузки</label>
			
			<select type="select" name="order[import_city]" class="inputbox" disabled="disabled">
				<option value="">Укажите город разгрузки</option>
				<option value="0">Все города</option>
			</select>
		</p>
		
	</fieldset>
	
	<fieldset>
		<div class="legend">Груз</div>

		<p>
			<label>Тип транспорта</label>
			
			<select type="select" name="order[type]" class="inputbox">
				<option value="">Укажите тип транспорта</option>
				<?=$transport_types?>
			</select>
		</p>
		
		<p>
			<label>Дата погрузки</label>
			<input readonly="true" type="text" name="order[date_export]" id="date_export" class="inputbox" /><input name="calendar" type="button" onClick="return showCalendar('date_export', 'dd-mm-y');" tabindex="105" value="..." style="width: 20px"/>
		</p>
		
		<p>
			<label>Наименование груза</label>
			
			<select type="select" name="order[name]" class="inputbox">
				<option value="">Укажите тип груза</option>
				<?=$cargo_types?>
			</select>
		</p>
		
		<p>
			<label>Объём груза, вес груза</label>
			
			<select type="select" name="order[volume]" class="inputbox">
				<option value="">Укажите тип груза</option>
				<?=$cargo_volumes?>
			</select>
		</p>
		
		<div>Дополнительная информация</div>
		<textarea name="order[comment]"></textarea>
		
	</fieldset>
	
	<fieldset>
		<div class="legend">Контактная информация</div>
		<p>
			<label>ФИО *</label>
			<input type="text" value="" name="name" class="inputbox">
		</p>
		<p>
			<label>Телефон *</label>
			<input type="text" value="" name="phone" class="inputbox">
		</p>
		<p>
			<label>e-mail *</label>
			<input type="text" value="" name="email" class="inputbox">
		</p>
	</fieldset>
	
	<div><input type="submit" value="Отправить" class="button"></div>
	
</form>