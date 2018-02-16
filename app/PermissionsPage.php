<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PermissionsPage extends Model
{
    protected $table = 'permissions_page';
    protected $fillable = ['permission_id','url'];

    public function permission(){
        return $this->belongsTo('App\Permissions','permission_id','id');
    }
}
