<?php

namespace App\Models;

use App\Enums\ActiveDisable;
use App\Enums\SystemsModuleType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Post extends Model
{
    protected $guarded = ['id'];

    protected $casts = [
        'tags' => 'array'
    ];

    public function comments(){
        return $this->morphMany(Comment::class,'comment');
    }

    public function user(){
        return $this->belongsTo(User::class,'user_id');
    }

    public function category(){
        return $this->belongsTo(Category::class);
    }

    public function categories(){
        return $this->belongsToMany(Category::class);
    }

    public function language(){
        return $this->belongsTo(Lang::class,'lang','value');
    }

    public function slug(){
        return $this->hasOne(Alias::Class,'type_id')->whereType($this->type);
    }

    public function tags(){
        return $this->hasMany(Tags::Class,'type_id')->whereType($this->type);
    }
    public function getRouteAttribute(){
        switch ($this->type){
            case SystemsModuleType::PAGE:
                return route('admin.posts.pages.index');
                break;
            case SystemsModuleType::POST:
                return route('admin.posts.index');
        }
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
    public function postLangsAfter(){
        return $this->hasMany(PostLang::class,'post_id')->whereType($this->type);
    }

    public function postLangsBefore(){
        return $this->hasMany(PostLang::class,'post_lang_id')->whereType($this->type);
    }

    public static function boot(){
        parent::boot();

        static::saving(function($post){
            $post->title_seo = $post->title_seo ? $post->title_seo : $post->title;
            $post->description_seo = $post->description_seo ? $post->description_seo : $post->title;
            $post->keyword_seo = $post->keyword_seo ? $post->keyword_seo : $post->title;
            $post->lang = $post->lang ? $post->lang : session()->get('lang');
            $post->user_id = $post->user_id ? $post->user_id : Auth::id();
            $post->slug()->update(['alias' => $post->alias]);
        });
        static::created(function($post){
            Alias::create([
                'alias' => $post->alias,
                'type' =>  SystemsModuleType::POST,
                'type_id' => $post->id
            ]);
        });
        static::deleting(function($post){
            $post->comments()->delete();
            $post->categories()->delete();
            $post->slug()->delete();
            $post->tags()->delete();
            $post->postLangsAfter()->delete();
            $post->postLangsAfter()->delete();
        });
    }
}
