<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RemotePostTransport extends Model
{
    protected $table = 'movers_order_passengers';
    protected $connection = 'remote';
    public $timestamps = false;

    protected $fillable = ['export','export_city','import','import_city'];
}
