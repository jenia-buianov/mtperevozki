<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Subscribes extends Model
{
    protected $table = 'subscribes';
    protected $fillable = ['user_id','url','model','new_count'];
}
