<?php

namespace App\Models;

use App\Enums\ActiveDisable;
use App\Enums\AliasType;
use App\Enums\SystemsModuleType;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $guarded = ['id'];

    public function comments(){

        return $this->morphMany(Comment::class,'comment');
    }

    public function products(){
        return $this->hasMany(Product::class);
    }
    public function parent(){
        return $this->belongsTo(Category::class,'parent_id');
    }

    public function language(){
        return $this->belongsTo(Lang::class,'lang','value');
    }

    public function slug(){
        return $this->hasOne(Alias::Class,'type_id')->whereType($this->type);
    }

    public function postLangsAfter(){
        return $this->hasMany(PostLang::class,'post_id')->whereType($this->type);
    }

    public function postLangsBefore(){
        return $this->hasMany(PostLang::class,'post_lang_id')->whereType($this->type);
    }
    public function getUrlAttribute(){
        switch ($this->type){
            case \App\Enums\CategoryType::POST_CATEGORY:
                return route('admin.posts.categories.edit',$this);
                break;
            case \App\Enums\CategoryType::PRODUCT_CATEGORY:
                return route('admin.products.categories.edit',$this);
        }
    }

    public function getRouteAttribute(){
        switch ($this->type){
            case \App\Enums\CategoryType::POST_CATEGORY:
                return route('admin.posts.categories.index');
                break;
            case \App\Enums\CategoryType::PRODUCT_CATEGORY:
                return route('admin.products.categories.index');
        }
    }

    public function scopePublic($q) {
        $q->wherePublic(ActiveDisable::ACTIVE);
    }

    public function scopeStatus($q) {

        $q->whereStatus(ActiveDisable::ACTIVE);
    }

    public function scopeLangs($q)
    {
        $q->whereLang(\Session::get('lang'));
    }
    public static function boot(){

        parent::boot();

        static::saving(function($category){
            $category->lang = $category->lang ? $category->lang : session()->get('lang');
            $category->title_seo = $category->title_seo ? $category->title_seo : $category->name;
            $category->description_seo = $category->description_seo ? $category->description_seo : $category->name;
            $category->keyword_seo = $category->keyword_seo ? $category->keyword_seo : $category->name;
            $category->user_id = $category->user_id ? $category->user_id : auth()->id();
            $category->slug()->update(['alias' => $category->alias]);
        });
        static::created(function($category){
            Alias::create([
                'alias' => $category->alias,
                'type' =>  $category->type,
                'type_id' => $category->id
            ]);
        });

        static::deleting(function($category){
            $category->comments()->delete();
            $category->slug()->delete();
            $category->postLangsAfter()->delete();
            $category->postLangsBefore()->delete();

            if(file_exists($category->image))
                unlink($category->image);

            if(file_exists($category->thumb))
                unlink($category->thumb);

            if(file_exists($category->background))
                unlink($category->background);

            if($category->parent())
                $category->parent()->update(['parent_id' => 0]);

            if($category->products())
                $category->products()->update(['category_id' => 0]);

        });
    }
}
