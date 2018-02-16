<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Emails extends Model
{
    protected $table = 'emails';
    protected $fillable = ['template_id','type','email_fk'];

    public function template(){
        return $this->belongsTo('App\Templates','template_id','id');
    }

    public function email_from(){
        return $this->belongsTo('App\EmailsFrom','email_fk','id');
    }
}
