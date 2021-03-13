<?php

namespace App\Models;

use App\Enums\ActiveDisable;
use App\Enums\SystemsModuleType;
use Illuminate\Database\Eloquent\Model;

class Pages extends Model
{
    protected $table = "pages";

    protected $fillable = ['title','alias','image','thumb','description','content','user_id','editer','title_seo','description_seo','keyword_seo','public','status','lang','sort','tags'];

    public function alias(){

        return $this->hasOne(Alias::Class,'type_id')->whereType(SystemsModuleType::PAGES);
    }

    public function user(){
        return $this->belongsTo(User::class,'user_id');
    }

    public function language(){
        return $this->belongsTo(Lang::class,'lang','value');
    }

    public function post_lang(){
        return $this->hasMany(PostLang::class,'post_id')->whereType(SystemsModuleType::PAGES);
    }

    public function post_langs(){
        return $this->hasMany(PostLang::class,'post_lang_id')->whereType(SystemsModuleType::PAGES);
    }

    public function tags(){
        return $this->hasMany(Tags::Class,'type_id')->whereType(SystemsModuleType::PAGES);
    }

    public function scopePublic($q){
        $q->wherePublic(ActiveDisable::ACTIVE);
    }

    public function scopeStatus($q){
        $q->whereStatus(ActiveDisable::ACTIVE);
    }

    public function scopeLangs($q){
        $q->whereLang(\Session::get('lang'));
    }

    public static function boot(){
        parent::boot();

        static::created(function($page){

            Alias::create([
                'alias' => $page->alias,
                'type' =>  SystemsModuleType::PAGES,
                'type_id' => $page->id
            ]);
            $page->title_seo = $page->title_seo ? $page->title_seo : $page->title;
            $page->description_seo = $page->description_seo ? $page->description_seo : $page->title;
            $page->keyword_seo = $page->keyword_seo ? $page->keyword_seo : $page->title;
        });

        static::updating(function($pages){

            $pages->alias()->update(['alias' => $pages->alias]);
        });

        static::deleting(function($pages){
            $pages->alias()->delete();
            $pages->tags()->delete();
            $pages->post_lang()->delete();
            $pages->post_langs()->delete();
        });
    }
}
