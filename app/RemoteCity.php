<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RemoteCity extends Model
{
    protected $table = 'city';
    protected $connection = 'remote';
    protected $primaryKey = 'id_city';
    public $timestamps = false;

    protected $fillable = ['id_region','id_country','city_name_ru','city_name_ro','city_name_en','old'];

    public function country()
    {
        return $this->belongsTo('App\RemoteCountry','id_country','id_country');
    }

    public function orders(){
        return $this->hasMany('App\RemoteAutoTransport');
    }
}
