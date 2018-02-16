<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Permissions extends Model
{
    use HasTranslations;

    protected $table = 'permisions';
    protected $fillable = ['titleKey','key'];
    public $translatable = ['titleKey'];

    public function pages(){
        return $this->hasMany('App\PermissionsPage');
    }

}
