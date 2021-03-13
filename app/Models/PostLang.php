<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PostLang extends Model
{
    protected  $table = 'post_lang';

    protected $fillable = ['post_id','post_lang_id','type','lang'];

    public function langs(){
        return $this->belongsTo(Lang::class,'lang','value');
    }
}
