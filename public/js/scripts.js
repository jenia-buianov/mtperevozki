
function scroll_to(clicked_link, nav_height) {
	var element_class = clicked_link.attr('href').replace('#', '.');
	var scroll_to = 0;
	if(element_class != '.top-content') {
		element_class += '-container';
		scroll_to = $(element_class).offset().top - nav_height;
	}
	if($(window).scrollTop() != scroll_to) {
		$('html, body').stop().animate({scrollTop: scroll_to}, 1000);
	}
}


jQuery(document).ready(function() {
	
	/*
	    Navigation
	*/	
	$('a.scroll-link').on('click', function(e) {
		e.preventDefault();
		scroll_to($(this), $('nav').height());
	});
	// toggle "navbar-no-bg" class
	$('.c-form-1-box').waypoint(function() {
		$('nav').toggleClass('fadeInUp navbar-no-bg');
	});

    new WOW().init();
    
    /*
	    Modals
	*/
	$('.launch-modal').on('click', function(e){
		e.preventDefault();
		$( '#' + $(this).data('modal-id') ).modal();
	});

	
});



function  addPhone(e) {
	event.preventDefault();
	form = $(e).parents('form');
	id = $(form).attr('id');
	if ($('#'+id+' .add_phone input').length<2) {
	    count_ = $('#'+id+' .add_phone input').length+1;
        $('#' + id + ' .add_phone').append('<div class="row"><div class="col-xs-12 col-md-5">\n' +
            '                                                <label>Телефон</label>\n' +
            '                                            </div><div class="col-xs-12 col-md-7">\n' +
            '                                                <div class="input-group">\n' +
            '                                                    <span class="input-group-addon">+</span><input type="text" name="phone'+count_+'" class="form-control" value="" required>\n' +
            '                                                </div>\n' +
            '                                            </div></div>');
        $('#' + id + ' .add_phone').css('display','block');
    }
    if ($('#'+id+' .add_phone input').length==2){
		$('#'+id+' .add_phone_link').css('display','none');
		$('#'+id+' .dell_phone_link').css('display','inline');
	}
}

function  dellPhone(e) {
    event.preventDefault();
    form = $(e).parents('form');
    id = $(form).attr('id');
    if ($('#'+id+' .add_phone input').length>0) {
        $('#' + id + ' .add_phone .row:last-child').remove();
    }
    if ($('#'+id+' .add_phone input').length==0){
        $('#'+id+' .add_phone_link').css('display','inline');
        $('#'+id+' .dell_phone_link').css('display','none');
        $('#' + id + ' .add_phone').css('display','none');
    }
}

function  sendForm(e,event){
    event.preventDefault();
    var $that = $(e);
        formData = new FormData(e);
    // formData = $(e).serialize();
    // console.log(formData);

    $.ajax({
        url: $that.attr('action'),
        type: 'post',
       contentType: false, // важно - убираем форматирование данных по умолчанию
       processData: false, // важно - убираем преобразование строк по умолчанию
        data: formData,
       async: true,
        dataType: "json",
        success: function(json){
        	console.log(json);
            if (typeof json.js!=='undefined'){
            	console.log('here');
                $('body').append('<script>'+json.js[0]+'</script>');
            }
        }
    });
}

function  setCity(e,event){
    event.preventDefault();
    form = $(e).parents('form');
    FormId = $(form).attr('id');

    formData = new FormData();
    formData.append('country',$(e).val());
    formData.append('id',$(e).attr('name'));
    formData.append('_token',$('#'+FormId+' [name="_token"]').val());

    $.ajax({
        url: lang+'/city',
        type: 'post',
        contentType: false, // важно - убираем форматирование данных по умолчанию
        processData: false, // важно - убираем преобразование строк по умолчанию
        data: formData,
        async: true,
        dataType: "json",
        success: function(json){
            $('#'+FormId+' [name="'+$(e).attr('name')+'_city"]').html(json.cities);
            $('#'+FormId+' [name="'+$(e).attr('name')+'_city"]').prop('disabled',false);
        }
    });
}

function cargoType(e) {
    val = $(e).val();
    form = $(e).parents('form');
    formId = $(form).attr('id');
    // console.log(formId);
    if (val=='own'){
        $('#'+formId+' #cargo_name').css('display','block');
    }
    else $('#'+formId+' #cargo_name').css('display','none');
}
