<?php

namespace App\Models;

use App\Enums\SystemsModuleType;
use Illuminate\Database\Eloquent\Model;

class Gallerys extends Model
{
    protected $table = 'gallery';

    protected $guarded = ['id'];

    public function alias(){

        return $this->belongsTo(Alias::Class,'type_id')->whereType(SystemsModuleType::GALLERY);
    }
    public function comments(){

        return $this->morphMany(Comment::class,'comment');
    }
    public function user(){
        return $this->belongsTo(User::class,'user_id');
    }

    public function language()
    {
        return $this->belongsTo(Lang::class,'lang','value');
    }

    public function post_lang(){
        return $this->hasMany(PostLang::class,'post_id')->whereType(SystemsModuleType::GALLERY);
    }

    public function post_langs(){
        return $this->hasMany(PostLang::class,'post_lang_id')->whereType(SystemsModuleType::GALLERY);
    }

    public static function boot(){
        parent::boot();

        static::created(function($gallery){

            Alias::create([
                'alias' => $gallery->alias,
                'type' =>  SystemsModuleType::GALLERY,
                'type_id' => $gallery->id
            ]);
            $gallery->title_seo = $gallery->title_seo ? $gallery->title_seo : $gallery->title;
            $gallery->description_seo = $gallery->description_seo ? $gallery->description_seo : $gallery->title;
            $gallery->keyword_seo = $gallery->keyword_seo ? $gallery->keyword_seo : $gallery->title;
        });

        static::updating(function($gallery){
            $gallery->alias()->update(['alias' => $gallery->alias]);
        });

        static::deleting(function($gallery){
            $gallery->comments()->delete();
            $gallery->alias()->delete();
            $gallery->post_lang()->delete();
            $gallery->post_langs()->delete();
        });
    }
}
