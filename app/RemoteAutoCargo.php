<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RemoteAutoCargo extends Model
{
    protected $table = 'movers_cargo';
    protected $connection = 'remote';
    public $timestamps = false;

    protected $fillable = ['company','face','phone','export','export_city','import','import_city','volume'];
}
