<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Pages extends Model
{
    protected $table = 'pages';
    protected $fillable = ['title','url','metatitle','metakey','metadesc','content'];


    public function sitemap(){
        return $this->hasManyThrough('App\Sitemap','App\PagesSitemap','page_id','id','id','sitemap_id');
    }
}
