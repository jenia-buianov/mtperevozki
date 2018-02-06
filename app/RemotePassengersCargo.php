<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RemotePassengersCargo extends Model
{
    protected $table = 'movers_cargo_passengers';
    protected $connection = 'remote';
    public $timestamps = false;

    protected $fillable = ['export','export_city','import','import_city'];
}
