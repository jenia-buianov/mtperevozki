<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Templates extends Model
{
    use HasTranslations;
    protected $table = 'templates';
    protected $fillable = ['title','path','params'];
    public $translatable = ['title'];
}
