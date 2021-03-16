<?php

namespace App\Models;

use App\Enums\ActiveDisable;
use App\Enums\AliasType;
use App\Enums\SystemsModuleType;
use Illuminate\Database\Eloquent\Model;

class CategoryProduct extends Model
{
    protected $table = "product_category";

    protected $guarded = ['id'];

    public function comments(){

        return $this->morphMany(Comment::class,'comment');
    }

    public function product(){

        return $this->belongsTo(Product::class,'category_id');
    }

    public function alias(){
        return $this->belongsTo(Alias::class,'alias','alias');
    }

    public function categorys(){
        return $this->hasMany(CategoryToProduct::class,'category_id');
    }

    public function language(){
        return $this->hasMany(Lang::class,'lang','value');
    }

    public function post_lang(){
        return $this->hasMany(PostLang::class,'post_id')->whereType(SystemsModuleType::PRODUCT_CATEGORY);
    }

    public function post_langs(){
        return $this->hasMany(PostLang::class,'post_lang_id')->whereType(SystemsModuleType::PRODUCT_CATEGORY);
    }

    public function parents(){
        return $this->belongsTo(CategoryProduct::class,'parent_id');
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

        static::created(function($category){

            $category->user_id = \Auth::id();

            Alias::create([
                'alias' => $category->alias,
                'type' =>  AliasType::PRODUCT_CATEGORY,
                'type_id' => $category->id
            ]);
            $category->title_seo = $category->title_seo ? $category->title_seo : $category->name;
            $category->description_seo = $category->description_seo ? $category->description_seo : $category->name;
            $category->keyword_seo = $category->keyword_seo ? $category->keyword_seo : $category->name;
        });

        static::updating(function($category){
            $category->user_edit = \Auth::id();
            $category->alias()->update(['alias' => $category->alias]);
        });

        static::deleting(function($category){

            $category->post_lang()->delete();
            $category->post_langs()->delete();
            $category->categorys()->delete();

            if(file_exists($category->image))
                unlink($category->image);

            if(file_exists($category->thumb))
                unlink($category->thumb);

            if(file_exists($category->background))
                unlink($category->background);

            if($category->parents())
            $category->parents()->update(['parent_id' => 0]);

            if($category->product())
            $category->product()->update(['category_id' => 0]);

        });
    }
}
