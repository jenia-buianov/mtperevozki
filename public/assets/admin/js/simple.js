function  deleteFile(e) {
    var id = $(e).attr('data-id');
    var module = $(e).attr('data-module');
    sendRequest({'mod':module,'id':id},'admin/delete/');
}

function sendRequest(data,url) {
    data._token = $('#_token').val();
    $.ajax({
        url: url,
        type: 'post',
        contentType: false, // важно - убираем форматирование данных по умолчанию
        processData: false, // важно - убираем преобразование строк по умолчанию
        data: data,
        async: true,
        dataType: "json",
        success: function(json){
            if (typeof json.js!=='undefined'){
                $('body').append('<script>'+json.js+'</script>');
            }
        }
    });
}

function  submitForm(e){
    event.preventDefault();
    var $that = $(e),
        formData = new FormData($that.get(0));
    if ($('[name="text_ru"]').length){
        formData.delete("text_ru");
        formData.append("text_ru",CKEDITOR.instances.text_ru.getData())
    }
    $.ajax({
        url: $that.attr('action'),
        type: 'post',
        contentType: false, // важно - убираем форматирование данных по умолчанию
        processData: false, // важно - убираем преобразование строк по умолчанию
        data: formData,
        async: true,
        dataType: "json",
        success: function(json){
            if (typeof json.js!=='undefined'){
                $('body').append('<script>'+json.js+'</script>');
            }
        }
    });
}

function checkUser(id) {
    event.preventDefault();
    formData = new FormData();
    formData.append('id',id);
    formData.append('_token',$('meta[name="csrf-token"]').attr('content'));
    $.ajax({
        url: 'http://'+window.location.hostname+'/admin/users/check',
        type: 'post',
        contentType: false, // важно - убираем форматирование данных по умолчанию
        processData: false, // важно - убираем преобразование строк по умолчанию
        data: formData,
        async: true,
        dataType: "json",
        success: function(json){
            if (typeof json.js!=='undefined'){
                $('body').append('<script>'+json.js+'</script>');
            }
        }
    });
}


function checkLang(id) {
    event.preventDefault();
    formData = new FormData();
    formData.append('id',id);
    formData.append('_token',$('meta[name="csrf-token"]').attr('content'));
    $.ajax({
        url: 'http://'+window.location.hostname+'/admin/languages/check',
        type: 'post',
        contentType: false, // важно - убираем форматирование данных по умолчанию
        processData: false, // важно - убираем преобразование строк по умолчанию
        data: formData,
        async: true,
        dataType: "json",
        success: function(json){
            if (typeof json.js!=='undefined'){
                $('body').append('<script>'+json.js+'</script>');
            }
        }
    });
}

function  submitDelete(e){
    event.preventDefault();
    id = $(e).attr('id');
    if (!confirm('Вы точно хотите удалить '+$('#'+id+' button:eq(0)').attr('data-info')+'?'))
        return false;
    var $that = $(e),
        formData = new FormData($that.get(0));
    $.ajax({
        url: $that.attr('action'),
        type: 'post',
        contentType: false, // важно - убираем форматирование данных по умолчанию
        processData: false, // важно - убираем преобразование строк по умолчанию
        data: formData,
        async: true,
        dataType: "json",
        success: function(json){
            if (typeof json.js!=='undefined'){
                $('body').append('<script>'+json.js+'</script>');
            }
        }
    });
}

function viewCountry(e) {
    country = $(e).html();
    full = [];
    for(i=0;i<$('.'+country).length;i++){
        full.push([$('.'+country+' td:eq(0)').html(),$('.'+country+' td:eq(1)').html()]);
    }
    new Chartkick.GeoChart("cities_users_week", full,{library:{region:country, displayMode: "markers", colorAxis: {colors: ["#e37b33", "#e37b33"]}},download:true});
}