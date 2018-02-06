<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RemoteCargoVolume extends Model
{
    protected $table = 'cargo_volume';
    protected $connection = 'remote';
    public $timestamps = false;

    protected $fillable = ['order','split','cargo_volume_ru','cargo_volume_ro','cargo_volume_en'];


    public function orders()
    {
        return $this->hasMany('App\RemoteAutoTransport');
    }
}
