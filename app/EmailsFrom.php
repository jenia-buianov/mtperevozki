<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EmailsFrom extends Model
{
    protected $table = 'emailsFrom';
    protected $fillable = ['type','host','login','password','security'];
}
