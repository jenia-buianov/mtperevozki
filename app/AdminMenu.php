<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class AdminMenu extends Model
{
    use HasTranslations;
    protected $table = 'acp_menu';
    protected $fillable = ['titleKey','group_id'];
    public $translatable = ['titleKey'];
}
