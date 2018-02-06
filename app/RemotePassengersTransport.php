<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RemotePassengersTransport extends Model
{
    protected $table = 'movers_order_post';
    protected $connection = 'remote';
    public $timestamps = false;

    protected $fillable = ['export','export_city','import','import_city'];
}
