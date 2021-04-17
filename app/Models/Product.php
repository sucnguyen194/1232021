<?php

namespace App\Models;

use App\Enums\AliasType;
use App\Enums\ProductSessionType;
use App\Enums\SystemsModuleType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Session;

class Product extends Model
{
    protected $guarded = ['id'];

    protected $casts = [
        'options' => 'array',
        'tags' => 'array',
    ];

    public function comments(){

        return $this->morphMany(Comment::class,'comment');
    }

    public function category(){
        return  $this->belongsTo(Category::class);
    }
    public function categories(){
        return $this->belongsToMany(Category::class);
    }
    public function attributes(){
        return $this->belongsToMany(Attribute::class);
    }

    public function sessions(){
        return $this->hasMany(ProductSession::class,'product_id');
    }

    public function imports(){
        return $this->hasMany(ProductSession::class,'product_id')->whereType(ProductSessionType::getKey(ProductSessionType::import));
    }

    public function exports(){
        return $this->hasMany(ProductSession::class,'product_id')->whereType(ProductSessionType::getKey(ProductSessionType::export));
    }

    public function user(){
        return  $this->belongsTo(User::class);
    }

    public function language(){
        return $this->belongsTo(Lang::class,'lang','value');
    }

    public function postLangsAfter(){
        return $this->hasMany(PostLang::class, 'post_id');
    }

    public function postLangsBefore(){
        return $this->hasMany(PostLang::class, 'post_lang_id');
    }

    public function slug(){
        return $this->belongsTo(Alias::class,'alias','alias');
    }
    public function photos(){
        return $this->hasMany(Photo::class,'type_id')->whereType($this->type);
    }

    public function tags(){
        return $this->hasMany(Tags::class,'type_id')->whereType($this->type);
    }

    public function getRouteAttribute(){
        switch ($this->type){
            case SystemsModuleType::PRODUCT:
                return route('admin.products.index');
                break;
            case SystemsModuleType::VIDEO:
                return route('admin.products.videos.index');
                break;
            case SystemsModuleType::GALLERY:
                return route('admin.products.galleries.index');
                break;
        }
    }

    public function scopePublic($q){
        $q->wherePublic(1);
    }
    public function scopeStatus($q){
        $q->whereStatus(1);
    }
    public function scopeLangs($q){
        $q->whereLang(Session::get('lang'));
    }

    public static function boot()
    {
        parent::boot(); // TODO: Change the autogenerated stub

        static::saving(function($product){
            $product->title_seo = $product->title_seo ? $product->title_seo : $product->name;
            $product->description_seo = $product->description_seo ? $product->description_seo : $product->name;
            $product->keyword_seo = $product->keyword_seo ? $product->keyword_seo : $product->name;
            $product->lang = $product->lang ? $product->lang : Session::get('lang');
            $product->user_id = $product->user_id ? $product->user_id : Auth::id();
            $product->slug()->update(['alias' => $product->alias]);
        });
        static::created(function($product){
            Alias::create([
                'alias' => $product->alias,
                'type' => AliasType::PRODUCT,
                'type_id' => $product->id,
            ]);
        });

        static::deleting(function($product){
            $product->comments()->delete();
            $product->postLangsBefore()->delete();
            $product->postLangsAfter()->delete();
            $product->slug()->delete();
            $product->tags()->delete();

            if($product->photos){
                foreach ($product->photos()->get() as $item):
                    if(file_exists($item->image))
                        unlink($item->image);
                    if(file_exists($item->thumb))
                        unlink($item->thumb);
                    $product->photos()->delete();
                endforeach;
            }

        });
    }

}
