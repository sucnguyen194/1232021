<?php namespace App\Http\Controllers;

use App\Enums\AliasType;
use App\Http\Controllers\Controller;
use App\Models\Alias;
use App\Models\Category;
use App\Models\PostLang;
use App\Models\Product;
use PHPUnit\Util\Type;
use Request,Session,Cart,Helper;
class AjaxController extends Controller {

    public function change_lang($slug, $lang){

        Session::put('lang',$lang);
        $alias = Alias::whereAlias($slug)->first();
        switch (true){
            case $alias:
                $post = PostLang::wherePostId($alias->type_id)->whereType($alias->type)->whereLang($lang)->first();
                $id = $post ? $post->post_lang_id : $alias->type_id;
                $slugs = Alias::whereType($alias->type)->whereTypeId($id)->first();
                return response()->json(route('alias',$slugs->alias));
                break;
            default;
                switch ($slug){
                    case AliasType::CONTACT:
                        return response()->json(route('contact.index'));
                        break;
                    case AliasType::HOMEVIDEO:
                        return response()->json(route('video.index'));
                        break;
                    case AliasType::HOMEGALLERY:
                        return response()->json(route('gallery.index'));
                        break;
                    default;
                        return response()->json(route('home'));
                }
        }
    }

    public function addShoppingCart($id){
        $product = Product::find($id);
        $category = Category::find($product->category_id);
        $qty = $request->qty ?? 1;
        $weight = $request->weight ?? 0;
        $slug = route('shop.product.show',$product->slug);
        if($product->price > $product->price_sale && $product->price_sale > 0){
            Cart::add([
                'id'=>$product->id,
                'name'=>$product->name,
                'price'=>$product->price_sale,
                'weight'=> $weight,
                'qty'=>$qty,
                'options'=>[
                    'price_old' => $product->price,
                    'image'=>$product->image,
                    'slug'=> $slug,
                    'category_id'=>$category->id,
                    'category_name'=>$category->name
                ]
            ]);
        }else{
            Cart::add([
                'id'=>$product->id,
                'name'=>$product->name,
                'price'=>$product->price,
                'weight'=> $weight,
                'qty'=>$qty,
                'options'=>[
                    'price_old' => $product->price_sale,
                    'image'=>$product->image,
                    'slug'=> $slug,
                    'category_id'=>$category->id,
                    'category_name'=>$category->name
                ]
            ]);
        }
        $data['cart'] = Cart::Content();
        $data['count'] = Cart::Content()->count();
        $data['total'] = Cart::Total();
        $data['product'] = $product;

        return response()->json($data);
    }

    public function updateShoppingCart($rowId,$num){
        Cart::update($rowId,$num);
        $getItem = Cart::get($rowId);
        $data['price'] = $getItem->price*$getItem->qty;
        $data['total'] = Cart::total();
        return response()->json($data);
    }
    public function removeItemShoppingCart($rowId){
        Cart::remove($rowId);
        $data['cart'] = Cart::Content();
        $data['count'] = Cart::content()->count();
        $data['total'] = Cart::total();
        return response()->json($data);
    }
    public function destroyShoppingCart(){
        Cart::destroy();
        $data['count'] = Cart::content()->count();
        $data['total'] = Cart::total();
        return response()->json($data);
    }


}
