<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Companies extends Model
{
    protected $table = 'companies';
    protected $fillable = ['title'];

    public function users(){
        return $this->hasManyThrough('App\User','App\UsersCompanies', 'company_id','id','id','user_id');
    }
}
