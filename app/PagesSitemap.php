<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PagesSitemap extends Model
{
    protected $table = 'pages_sitemap';
    protected $fillable = ['page_id','sitemap_id'];

    public function pages(){
        return $this->belongsTo('App\Pages');
    }
    public function sitemap(){
        return $this->belongsTo('App\Sitemap');
    }
}
