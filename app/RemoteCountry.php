<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RemoteCountry extends Model
{
    protected $table = 'country';
    protected $connection = 'remote';
    protected $primaryKey = 'id_country';
    public $timestamps = false;

    protected $fillable = ['old','alpha3','iso5','country_name_ru','country_name_ro','country_name_en','country_hidden','country_group','country_name_ru_from','country_name_ru_to'];

    public function cities()
    {
        return $this->hasMany('App\RemoteCity');
    }

    public function orders()
    {
        return $this->hasMany('App\RemoteAutoTransport');
    }
}
