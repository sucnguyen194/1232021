<?php

namespace App\Models;

use App\Enums\AliasType;
use App\Enums\MediaType;
use App\Enums\ProductSessionType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Session;

class Product extends Model
{
    protected $table = "product";

    protected $guarded = [];

    protected $casts = [
        'options' => 'array',
    ];

    public function comments(){

        return $this->morphMany(Comment::class,'comment');
    }

    public function category(){
        return  $this->belongsTo(CategoryProduct::class,'category_id');
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

    public function categorys(){
        return $this->hasMany(CategoryToProduct::class,'product_id');
    }
    public function language(){
        return $this->belongsTo(Lang::class,'lang','value');
    }

    public function post_lang(){
        return $this->hasMany(PostLang::class, 'post_id');
    }

    public function post_langs(){
        return $this->hasMany(PostLang::class, 'post_lang_id');
    }

    public function alias(){
        return $this->belongsTo(Alias::class,'alias','alias');
    }
    public function photos(){
        return $this->hasMany(Media::class,'type_id')->where('type',MediaType::PRODUCT);
    }

    public function tags(){
        return $this->hasMany(Tags::class,'type_id')->where('type',AliasType::PRODUCT);
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
            $product->post_lang()->delete();
            $product->post_langs()->delete();
            $product->alias()->delete();
            $product->tags()->delete();
            $product->categorys()->delete();

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
