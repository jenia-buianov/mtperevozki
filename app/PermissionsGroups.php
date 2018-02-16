<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PermissionsGroups extends Model
{
    protected $table = 'permissions_groups';
    protected $fillable = ['group_id','permission_id'];
}
