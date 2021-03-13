<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    protected $table = "contact";

    protected $fillable = ['name','email','phone','address','gender','note','user_id','user_edit','status'];

    public function user(){

        return $this->belongsTo(User::class,'user_edit');

    }
}
