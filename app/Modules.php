<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Modules extends Model
{
    protected $table = 'acp_modules';
    protected $fillable = ['group_id','title'];
    //
}
