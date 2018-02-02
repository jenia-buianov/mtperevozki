<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Category extends Model
{
    use HasTranslations;
    protected $table = 'categories_cargo';
    protected $fillable = ['titleKey','order','link','image'];
    public $translatable = ['titleKey'];
}
