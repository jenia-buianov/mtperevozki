		function checkForm(frm, arr, mes)
		{
		  if (!frm || !frm.elements)
		  {
			alert('Форма не определена');
			return false;
		  }
		  el = null;
		  err = '';
		  var j = '';
		  var m = '';
		  for(var i in arr)
		  {
			j = '';
			m = arr[i];
			if (/^([a-zA-Z01-9_]+)\|([a-zA-Z01-9_]+)$/.test(i))
			{
			  i = RegExp.$1;
			  j = RegExp.$2;
			}
			if (frm.elements[i])
				switch(frm.elements[i].type)
				{
				  case 'text':
				  case 'textarea':
				  case 'password':
				  case 'hidden':
				  case 'file':
				  case undefined:
					if ((!/\S/.test(frm.elements[i].value) || frm.elements[i].value == '0' || frm.elements[i].value == 'Укажите дату') && (!j || frm.elements[j].value == '' || frm.elements[j].value == '0' || frm.elements[j].value == 'Укажите дату'))
					{
					  if (i == "order[phone2]" || i == "order[phone3]") continue;
					  if (!el) el = frm.elements[i];
					  err += m + "\n";
					}
					else if ((i == 'order[email]') && (!(/^([a-z0-9_\-]+\.)*[a-z0-9_\-]+@([a-z0-9][a-z0-9\-]*[a-z0-9]\.)+[a-z]{2,4}$/i).test(frm.elements[i].value)))
					{
					  if (!el) el = frm.elements[i];
					  err += m + ' | Правильный формат: email@somethere.org' + '\n';
					}
					break;
				  case 'select-one':
					if ((frm.elements[i].value == '' || frm.elements[i].value == '0') && (!j || frm.elements[j].value == '' || frm.elements[j].value == '0'))
					{
					  if (!el) el = frm.elements[i];
					  err += m + '\n';
					}
					break;
				}

				if (i == 'order[phone]')
				{
					if ( frm.elements['order[phone][country]'] && frm.elements['order[phone][city]'] && frm.elements['order[phone][phone]'] )
						phone = '+' + frm.elements['order[phone][country]'].value.replace(/[-|+]/g, "") + '-' + frm.elements['order[phone][city]'].value.replace(/-/g, "") + '-' + frm.elements['order[phone][phone]'].value.replace(/-/g, "");
						else phone = frm.elements['order[phone]'].value;

					if (phone == "+--" || phone == "--" || !phone)
					{
						err += m + '\n';
					}
					else if (!/^[+]\d{1,3}-\d{1,4}-\d{1,15}/.test(phone))
					{
						err += m + ' | Правильный формат: + код страны - код города - номер абонента' + '\n';
					}
				}
				
				if (i == 'order[phone1]')
				{
					if ( frm.elements['order[phone1][country]'] && frm.elements['order[phone1][city]'] && frm.elements['order[phone1][phone]'] )
						phone = '+' + frm.elements['order[phone1][country]'].value.replace(/[-|+]/g, "") + '-' + frm.elements['order[phone1][city]'].value.replace(/-/g, "") + '-' + frm.elements['order[phone1][phone]'].value.replace(/-/g, "");
						else phone = frm.elements['order[phone1]'].value;

					if (phone == "+--" || phone == "--" || !phone)
					{
						err += m + '\n';
					}
					else if (!/^[+]\d{1,3}-\d{1,4}-\d{1,15}/.test(phone))
					{
						err += m + ' | Правильный формат: + код страны - код города - номер абонента' + '\n';
					}
				}
				
				if (i == 'order[phone2]')
				{
					if ( frm.elements['order[phone2][country]'] && frm.elements['order[phone2][city]'] && frm.elements['order[phone2][phone]'] )
						phone = '+' + frm.elements['order[phone2][country]'].value.replace(/[-|+]/g, "") + '-' + frm.elements['order[phone2][city]'].value.replace(/-/g, "") + '-' + frm.elements['order[phone2][phone]'].value.replace(/-/g, "");
						else phone = frm.elements['order[phone2]'].value;
					if (phone == "+--" || phone == "--" || !phone) 
					{
					}
					else if (!/^[+]\d{1,3}-\d{1,4}-\d{1,15}/.test(phone))
					{
						err += m + ' | Правильный формат: + код страны - код города - номер абонента' + '\n';
					}
				}
				
				if (i == 'order[phone3]')
				{
					if ( frm.elements['order[phone3][country]'] && frm.elements['order[phone3][city]'] && frm.elements['order[phone3][phone]'] )
						phone = '+' + frm.elements['order[phone3][country]'].value.replace(/[-|+]/g, "") + '-' + frm.elements['order[phone3][city]'].value.replace(/-/g, "") + '-' + frm.elements['order[phone3][phone]'].value.replace(/-/g, "");
						else phone = frm.elements['order[phone3]'].value;

					if (phone == "+--" || phone == "--" || !phone)
					{
					}
					else if (!/^[+]\d{1,3}-\d{1,4}-\d{1,15}/.test(phone))
					{
						err += m + ' | Правильный формат: + код страны - код города - номер абонента' + '\n';
					}
				}
		  }
		  
		  if (err == '')
		  { 
			return true;
		  }
		  else
		  {
			try { el.focus(); } catch(e) {}
			if (!mes) mes = 'Пожалуйста заполните обязательные поля';
			alert(mes + ':\n\n' + err + '');
			return false;
		  }
		  
		}
		
		function _updateSelect (selectId, optValue, fs)
		{
			if (!xmlHttp)
				return false;
			if (optValue == 0)
			{
				disableSelect(fs);
			}

			sid = document.getElementById(selectId);
			sid.options.length = 0;
			sid.disabled = true;
			sid.options[sid.options.length] = new Option("Подождите, идет загрузка...", 0, false, false);
			var params = "value=" + optValue + "&language=";
			xmlHttp.open("GET","cargo/city.php?" + params, true);



			xmlHttp.onreadystatechange = function()
			{

				if (xmlHttp.readyState == 4)
				{		
						var from_php = xmlHttp.responseText;
						sid.options.length = 0;
						sid.innerHTML="";
						if (from_php == "" || from_php == null || from_php == 0) {

									sid.options[sid.options.length] = new Option("---Города не найдены---", 0, false, true);
									updateinput(fs,"none");
						} else {
						sid.options[sid.options.length] = new Option("--- не имеет значения ---", 0, false, false);
						var array_work = from_php.split("|");
	
						for(var i = 0; i < array_work.length; i++)
						{
							if (i == 0 || is_int(i/2)) {
										var region = document.createElement("optgroup");
										region.label = array_work[i];
							} else {
									var array_city = array_work[i].split(",");
									for(var j = 0; j < array_city.length; j++)
									{
											var city_data = array_city[j].split("^");
											var city = document.createElement("option");
											city.value = city_data[1];
											city.appendChild(document.createTextNode(city_data[0]));
											region.appendChild(city);
									}

							}
							if ( region != "" && region.hasChildNodes()) { sid.appendChild(region); }

						}
						sid.disabled = false;
						}
				}
			}
			xmlHttp.send(null);
		}