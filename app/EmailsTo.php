<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EmailsTo extends Model
{
    protected $table = 'emailsTo';
    protected $fillable = ['type','host','login','password','security'];
}
