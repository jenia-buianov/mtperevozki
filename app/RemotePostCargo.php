<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RemotePostCargo extends Model
{
    protected $table = 'movers_cargo_post';
    protected $connection = 'remote';
    public $timestamps = false;

    protected $fillable = ['export','export_city','import','import_city'];
}
