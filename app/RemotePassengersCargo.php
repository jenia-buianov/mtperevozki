<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RemotePassengersCargo extends Model
{
    protected $table = 'movers_cargo_passengers';
    protected $connection = 'remote';
    public $timestamps = false;

    protected $fillable = ['export','export_city','import','import_city'];

    public function transport_type()
    {
        $lang = 'transport_type_'.app()->getLocale();
        return $this->belongsTo('App\RemotePassengersTransportType','type','id')->firstOrFail()->$lang;
    }

    public function name(){
        $lang = 'cargo_type_'.app()->getLocale();
        $relation = $this->belongsTo('App\RemotePassengersCargoType','name','id')->first();
        if (!$relation) return "";
        return $relation->$lang;
    }

    public function import_city(){
        $lang = 'city_name_'.app()->getLocale();
        return $this->belongsTo('App\RemoteCity','import_city','id_city')->firstOrFail()->$lang;
    }

    public function import_country(){
        $lang = 'country_name_'.app()->getLocale();
        return $this->belongsTo('App\RemoteCountry','import','id_country')->firstOrFail()->$lang;
    }

    public function export_city(){
        $lang = 'city_name_'.app()->getLocale();
        return $this->belongsTo('App\RemoteCity','export_city','id_city')->firstOrFail()->$lang;
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
