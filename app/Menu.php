<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Menu extends Model
{
    use HasTranslations;
    protected $table = 'site_menu';
    protected $fillable = ['titleKey','order','parent'];
    public $translatable = ['titleKey'];
}
