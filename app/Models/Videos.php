<?php

namespace App\Models;

use App\Enums\SystemsModuleType;
use Illuminate\Database\Eloquent\Model;

class Videos extends Model
{
    protected $table = 'videos';

    protected $guarded = ['id'];

    public function alias(){

        return $this->hasOne(Alias::Class,'type_id')->whereType(SystemsModuleType::VIDEO);
    }
    public function comments(){

        return $this->morphMany(Comment::class,'comment');
    }
    public function user(){
        return $this->belongsTo(User::class,'user_id');
    }

    public function language(){
        return $this->belongsTo(Lang::class,'lang','value');
    }

    public function post_lang(){
        return $this->hasMany(PostLang::class,'post_id')->whereType(SystemsModuleType::VIDEO);
    }

    public function post_langs(){
        return $this->hasMany(PostLang::class,'post_lang_id')->whereType(SystemsModuleType::VIDEO);
    }

    public function tags(){
        return $this->hasMany(Tags::Class,'type_id')->whereType(SystemsModuleType::VIDEO);
    }

    public static function boot(){
        parent::boot();

        static::created(function($video){

            Alias::create([
                'alias' => $video->alias,
                'type' =>  SystemsModuleType::VIDEO,
                'type_id' => $video->id
            ]);
            $video->title_seo = $video->title_seo ? $video->title_seo : $video->title;
            $video->description_seo = $video->description_seo ? $video->description_seo : $video->title;
            $video->keyword_seo = $video->keyword_seo ? $video->keyword_seo : $video->title;
        });

        static::updating(function($video){
            $video->tags()->delete();
            $video->alias()->update(['alias' => $video->alias]);
        });

        static::deleting(function($video){
            $video->alias()->delete();
            $video->tags()->delete();
            $video->post_lang()->delete();
            $video->post_langs()->delete();
        });
    }

}
