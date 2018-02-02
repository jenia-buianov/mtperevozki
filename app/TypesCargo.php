<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class TypesCargo extends Model
{
    use HasTranslations;
    protected $table = 'types_cargo';
    protected $fillable = ['titleKey','link','image','order'];
    public $translatable = ['titleKey'];

}
