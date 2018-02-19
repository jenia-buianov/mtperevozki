<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UsersCompanies extends Model
{
    protected $table = 'users_companies';
    protected $fillable = ['user_id','company_id'];
}
