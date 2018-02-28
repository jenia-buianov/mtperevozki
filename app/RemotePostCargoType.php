<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RemotePostCargoType extends Model
{
    protected $table = 'cargo_type_post';
    protected $connection = 'remote';
    public $timestamps = false;

    protected $fillable = ['order','split','cargo_type_ru','cargo_type_ro','cargo_type_en'];

    public function cargo(){
        return $this->hasMany('App\RemotePostCargo');
    }
}
