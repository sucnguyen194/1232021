<?php

namespace App\Models;

use App\Enums\ActiveDisable;
use App\Enums\AliasType;
use App\Enums\SystemsModuleType;
use Illuminate\Database\Eloquent\Model;

class NewsCategory extends Model
{
    protected  $table = 'news_category';

    protected $guarded = ['id'];

    public function news(){
        return $this->belongsTo(News::class,'category_id');
    }

    public function categorys(){
        return $this->hasMany(NewsToCategory::class,'category_id');
    }
    public function comments(){

        return $this->morphMany(Comment::class,'comment');
    }
    public function parents(){
        return $this->belongsTo(NewsCategory::class,'parent_id');
    }

    public function language()
    {
        return $this->belongsTo(Lang::class,'lang','value');
    }

    public function alias(){

        return $this->hasOne(Alias::Class,'type_id')->whereType(SystemsModuleType::NEWS_CATEGORY);
    }

    public function post_lang(){
        return $this->hasMany(PostLang::class,'post_id')->whereType(SystemsModuleType::NEWS_CATEGORY);
    }

    public function post_langs(){
        return $this->hasMany(PostLang::class,'post_lang_id')->whereType(SystemsModuleType::NEWS_CATEGORY);
    }

    public function getParentAttribute(){
        return NewsCategory::where('parent_id',$this->id)->get();
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

    public static function boot (){

        parent::boot();

        static::created(function($category){

            Alias::create([
                'alias' => $category->alias,
                'type' =>  AliasType::NEWS_CATEGORY,
                'type_id' => $category->id
            ]);
            $category->title_seo = $category->title_seo ? $category->title_seo : $category->title;
            $category->description_seo = $category->description_seo ? $category->description_seo : $category->title;
            $category->keyword_seo = $category->keyword_seo ? $category->keyword_seo : $category->title;
        });

        static::updating(function($category){
            $category->alias()->update(['alias' => $category->alias]);
        });

        static::deleting(function($category){

            $category->categorys()->delete();
            $category->alias()->delete();

            $category->post_lang()->delete();
            $category->post_langs()->delete();

            foreach($category->parent as $item){
                $item->update([
                    'parent_id' => 0
                ]);
            }
            if($category->news())
            $category->news()->update([
                'category_id' => 0
            ]);

        });

    }

}
