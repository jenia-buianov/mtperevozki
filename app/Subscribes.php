<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Subscribes extends Model
{
    protected $table = 'subscribes';
    protected $fillable = ['user_id','url','model','new_count','transport_type','title','import','export'];

    public function emails(){
        return $this->belongsTo('App\SubscribesEmails','subscribe_id','id');
    }

    public function total_count(){
        $class = 'App\\'.$this->model;
        return  $class::where('import',$this->import)->where('export',$this->export)->count();
    }

    public function new_count(){
        $class = 'App\\'.$this->model;
        return  $class::where('import',$this->import)->where('export',$this->export)->where('order_date','>',$this->updated_at)->count();
    }
}
