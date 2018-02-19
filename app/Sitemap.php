<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Sitemap extends Model
{
    protected $table = 'sitemap';
    protected $fillable = ['url','title','parent'];

    public function relation(){
        return $this->hasOne('App\PagesSitemap');
    }
}
