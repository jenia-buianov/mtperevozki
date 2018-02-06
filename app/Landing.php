<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Landing extends Model
{
    protected $table = 'landing';
    protected $fillable = ['order','title','view','active'];
}
