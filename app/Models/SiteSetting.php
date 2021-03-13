<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SiteSetting extends Model
{
    protected $table = 'site_option';

   protected $guarded = ['id'];

   public function lang(){
       $this->belongsTo(Lang::class,'lang');
   }

   public function scopeLangs($q){
       $q->whereLang(\Session::get('lang'));
   }
}
