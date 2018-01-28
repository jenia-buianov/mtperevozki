Cufon.replace("h1");
Cufon.replace("address");
window.addEvent('domready', function(){
	new Accordion(
		$$('.right .verticalMenu h3'), 
		$$('.right .verticalMenu ul.accordeon'), 
		{
			duration: 300,
			opacity: false,
			alwaysHide: true,
			display: -1
		}
	);
	
	$$('select#export', 'select#import').addEvent('change', function()
	{
		getCity(this);
	});
	
	$$('select#export', 'select#import').each(function(el)
	{
		getCity(el);
	});
	
	$$('.rsform-field-calendar input.rsform-calendar-box').addEvent('click', function()
	{
		$calendar = this.getNext().getNext();
		showHideCalendar($calendar.id);
	});

	$$('.rsform-field-calendar input.rsform-calendar-box', '.rsform-field-calendar input.rsform-calendar-button').addEvent('click', function()
	{
		this.getParent().getElementsBySelector('.rsform-calendar-box').setProperty('value', '');
	});	
	
});
function refreshCaptcha(){
	document.getElementById('mtpCaptcha').src = '/kcaptcha/index.php?n='+new Date().getTime();
	return false;
}

function getCity(element)
{
	//target
	$parent_el = element.getParent().getParent().getParent();
	$target_el = $parent_el.getElementsBySelector('#' + element.id + '_city');
	$options = $parent_el.getElementsBySelector('#' + element.id + '_city option');
	
	//params + get data
	$id_country = element.value;
	$language = "$lang" in window ? $lang : "ru";

	$target_el.setProperty('disabled', true);
	$target_el.empty();
	$target_el.adopt(new Element('option', {'value': $options[0].value}).setText($options[0].text));
	$target_el.adopt(new Element('option', {'value': $options[1].value}).setText($options[1].text));
	
	if($id_country > 0)
	{
		var jSonRequest = new Ajax('/city.php',{
			method: 'post',
			data:	'&id_country=' + $id_country + '&language=' + $language,
			onComplete: function(response)
			{
				var data = Json.evaluate(response);
					
				if(!empty(data))
				{
					$target_el.adopt(new Element('option', {'value': ''}).setText('-----------------------------------------'));
					$each(data, function(value, key)
					{
						$target_el.adopt(new Element('option', {'value': key}).setText(value));
					});

				}
					
				$target_el.setProperty('disabled', false);
					
			}
		}).request();
	}
	else
	{
		$target_el.setProperty('disabled', false);
	}
}

function empty(o)
{
    for(var i in o) 
      if(o.hasOwnProperty(i))
        return false;
 
    return true;
}