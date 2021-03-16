<?php

namespace App\Models;

use App\Enums\ActiveDisable;
use App\Enums\SystemsModuleType;
use Illuminate\Database\Eloquent\Model;

class News extends Model
{
    protected $table = 'news';

   protected $guarded = ['id'];

    public function category()
    {
        return $this->belongsTo(NewsCategory::class);
    }
    public function comments(){

        return $this->morphMany(Comment::class,'comment');
    }
    public function user(){
        return $this->belongsTo(User::class,'user_id');
    }

    public function categorys(){
        return $this->hasMany(NewsToCategory::class,'news_id');
    }

    public function language(){
        return $this->belongsTo(Lang::class,'lang','value');
    }

    public function alias(){

        return $this->hasOne(Alias::Class,'type_id')->whereType(SystemsModuleType::NEWS);
    }

    public function post_lang(){
        return $this->hasMany(PostLang::class,'post_id')->whereType(SystemsModuleType::NEWS);
    }

    public function post_langs(){
        return $this->hasMany(PostLang::class,'post_lang_id')->whereType(SystemsModuleType::NEWS);
    }

    public function tags(){
        return $this->hasMany(Tags::Class,'type_id')->whereType(SystemsModuleType::NEWS);
    }

    public function scopePublic($q){
        return $q->wherePublic(ActiveDisable::ACTIVE);
    }

    public function scopeStatus($q){
        return $q->whereStatus(ActiveDisable::ACTIVE);
    }

    public function scopeLangs($q){
        return $q->whereLang(\Session::get('lang'));
    }

    public function scopeCategoryId($q){
        return $q->whereCategoryId($this->category_id);
    }
    public function scopeNewsNotId($q){
        return $q->whereNotIn('id', [$this->id]);
    }

    public static function boot(){
        parent::boot();

        static::created(function($news){

            Alias::create([
                'alias' => $news->alias,
                'type' =>  SystemsModuleType::NEWS,
                'type_id' => $news->id
            ]);
            $news->title_seo = $news->title_seo ? $news->title_seo : $news->title;
            $news->description_seo = $news->description_seo ? $news->description_seo : $news->title;
            $news->keyword_seo = $news->keyword_seo ? $news->keyword_seo : $news->title;
        });

        static::updating(function($news){
            $news->alias()->update(['alias' => $news->alias]);
        });

        static::deleting(function($news){
            $news->comments()->delete();
            $news->categorys()->delete();
            $news->alias()->delete();
            $news->tags()->delete();

            $news->post_lang()->delete();
            $news->post_langs()->delete();
        });
    }
}
