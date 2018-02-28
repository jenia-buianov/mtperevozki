<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RemotePassengersTransportType extends Model
{
    protected $table = 'transport_type_passengers';
    protected $connection = 'remote';
    public $timestamps = false;

    protected $fillable = ['order','split','transport_type_ru','transport_type_ro','transport_type_en'];

    public function passengers()
    {
        return $this->hasMany('App\RemotePassengersTransport');
    }
}
