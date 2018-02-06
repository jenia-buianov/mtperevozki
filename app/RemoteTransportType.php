<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RemoteTransportType extends Model
{
    protected $table = 'transport_type';
    protected $connection = 'remote';
    public $timestamps = false;

    protected $fillable = ['order','split','transport_type_ru','transport_type_ro','transport_type_en'];


    public function orders()
    {
        return $this->hasMany('App\RemoteAutoTransport');
    }
}
