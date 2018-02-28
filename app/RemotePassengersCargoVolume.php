<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RemotePassengersCargoVolume extends Model
{
    protected $table = 'cargo_volume_passengers';
    protected $connection = 'remote';
    public $timestamps = false;

    protected $fillable = ['order','split','cargo_volume_ru','cargo_volume_ro','cargo_volume_en'];


    public function orders()
    {
        return $this->hasMany('App\RemotePassengersTransport');
    }
}
