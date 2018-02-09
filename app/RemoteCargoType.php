<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RemoteCargoType extends Model
{
    protected $table = 'cargo_type';
    protected $connection = 'remote';
    public $timestamps = false;

    protected $fillable = ['order','split','cargo_type_ru','cargo_type_ro','cargo_type_en'];

    public function cargo(){
        return $this->hasMany('App\RemoteAutoCargo');
    }
}
