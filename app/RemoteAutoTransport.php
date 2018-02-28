<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\Facades\DB;

class RemoteAutoTransport extends Model
{
    protected $table = 'movers_order';
    protected $connection = 'remote';
    public $timestamps = false;

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        DB::enableQueryLog();
    }

    protected $fillable = [
        'face','phone','export','export_city','import','import_city','volume','source','email','export_city_port','import_city_port',
        'skype','company','phone1','phone2','phone3','date_from','date_to', 'type','description','order_type','count','hidden','weight', 'container_type',
        'movers_site', 'hide_contact', 'status', 'documents', 'by_admin', 'by_admin_time', 'id_contact', 'permanent', 'supplementary','car_number',
        'tir','cmr','adr','door_to_door_delivery','airport_to_door_delivery','date_sort','packing','temperature','insurance',
        'warehouse', 'length','width','height','seats','comstill','comstil_id','order_date','free','name'
    ];


    public function transport_type()
    {
        $lang = 'transport_type_'.app()->getLocale();
        return $this->belongsTo('App\RemoteTransportType','type','id')->firstOrFail()->$lang;
    }

    public function volume()
    {
        $lang = 'cargo_volume_'.app()->getLocale();
        $relation = $this->belongsTo('App\RemoteCargoVolume','volume','id')->first();
        if (!$relation) return "";
        return $relation->$lang;
    }

    public function import_city(){
        $lang = 'city_name_'.app()->getLocale();
        $relation = $this->belongsTo('App\RemoteCity','import_city','id_city')->first();
        if (!$relation) return "";
        return $relation->$lang;
    }

    public function import_country(){
        $lang = 'country_name_'.app()->getLocale();
        return $this->belongsTo('App\RemoteCountry','import','id_country')->firstOrFail()->$lang;
    }

    public function export_city(){
        $lang = 'city_name_'.app()->getLocale();
        $relation = $this->belongsTo('App\RemoteCity','export_city','id_city')->first();
        if (!$relation) return "";
        return $relation->$lang;
    }

    public function export_country(){
        $lang = 'country_name_'.app()->getLocale();
        return $this->belongsTo('App\RemoteCountry','export','id_country')->firstOrFail()->$lang;
    }

    public function import_country_to(){
        $lang = 'country_name_ru_to';
        return $this->belongsTo('App\RemoteCountry','import','id_country')->firstOrFail()->$lang;
    }

    public function export_country_from(){
        $lang = 'country_name_ru_from';
        return $this->belongsTo('App\RemoteCountry','export','id_country')->firstOrFail()->$lang;
    }


    public function export_flag(){
        if (!empty($this->export)&&is_numeric($this->export)){
            return $this->belongsTo('App\RemoteCountry','export','id_country')->firstOrFail()->alpha3;
        }
        return null;
    }

    public function import_flag(){
        if (!empty($this->import)&&is_numeric($this->import)){
            return $this->belongsTo('App\RemoteCountry','import','id_country')->firstOrFail()->alpha3;
        }
        return null;
    }

    public function import(){

        if ((int)$this->import_city)
            return '<h3 style="display:inline">'.$this->import_country().'<h3>, <h4 style="display:inline">'.$this->import_city().'</h4>';
        elseif (!(int)$this->import_city&&!empty($this->import_city))
            return '<h3  style="display:inline">'.$this->import_country().'</h3>, <h4 style="display:inline">'.$this->import_city.'</h4>';
        elseif(!(int)$this->import_city&&empty($this->import_city)&&(int)$this->import)
            return '<h3 style="display:inline">'.$this->import_country().'</h3>';
        elseif(!(int)$this->import_city&&empty($this->import_city)&&!(int)$this->import)
            return '<h3 style="display:inline">'.$this->import.'</h3>';
    }

    public function export(){

        if ((int)$this->export_city>0)
            return '<h3 style="display:inline">'.$this->export_country().'</h3>, <h4 style="display:inline">'.$this->export_city().'</h4>';
        elseif ((int)$this->export_city<0&&!empty($this->import_city))
            return '<h3 style="display:inline">'.$this->export_country().'</h3>, <h4 style="display:inline">'.$this->export_city.'</h4>';
        elseif(!(int)$this->export_city&&empty($this->export_city)&&(int)$this->export)
            return '<h3 style="display:inline">'.$this->export_country().'</h3>';
        elseif(!(int)$this->export_city&&empty($this->export_city)&&!(int)$this->export)
            return '<h3 style="display:inline">'.$this->export.'</h3>';
    }

}
