<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SubscribesEmails extends Model
{
    protected $table = 'subscribes_emails';
    protected $fillable = ['subscribe_id','email_id'];
}
