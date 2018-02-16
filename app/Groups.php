<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Groups extends Model
{
    protected $table = 'groups';
    protected $fillable = ['titleKey'];

    public function user(){
        return $this->hasMany('App\User');
    }

    public function permission(){
        return $this->hasManyThrough('App\Permissions','App\PermissionsGroups','group_id','id','id','permission_id');
    }

    public function permission_pages(){
        return $this->hasManyThrough('App\PermissionsPage','App\PermissionsGroups','group_id','id','id','permission_id');
    }

    public function hasAccess($key){
        return (boolean)$this->hasManyThrough('App\Permissions','App\PermissionsGroups','group_id','id','id','permission_id')->where('key',$key)->count();
    }

    public function hasAccessByURL($url){
        return (boolean)$this->hasManyThrough('App\PermissionsPage','App\PermissionsGroups','group_id','id','id','permission_id')->where('url',$url)->count();
    }


}
