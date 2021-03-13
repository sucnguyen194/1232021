<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserModuleSystems extends Model
{
    protected $table = 'user_module_systems';

    protected $fillable = ['user_id','type'];

    public function systems (){
        return $this->belongsTo(SystemsModule::class,'type','type');
    }

    public function user (){
        return $this->belongsTo(User::class,'user_id');
    }
}
