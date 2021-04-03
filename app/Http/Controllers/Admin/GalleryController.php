<?php

namespace App\Http\Controllers\Admin;

use App\Enums\SystemsModuleType;
use App\Http\Controllers\Controller;
use App\Models\Lang;
use App\Models\Product;
use App\Models\User;
use Image, Session, Schema;

class GalleryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $type = SystemsModuleType::GALLERY;
        check_admin_systems($type);
        $lang = isset(request()->lang) ? request()->lang : Session::get('lang');
        $galleries = Product::with('category')->whereType($type)->where('lang',$lang)
            ->when(request()->user,function($q, $user){
                $q->where('user_id',$user);
            })
            ->when(request()->status,function($q){
                $status = request()->status == 'true' ? 1 : 0 ;
                $q->where('status',$status);
            })
            ->when(request()->public, function($q){
                $public = request()->public == 'true' ? 1 : 0 ;
                $q->where('public',$public);
            })
//            ->when(\request()->category,function($q){
//                $category = \request()->category == -1 ? 0 : \request()->category;
//                $q->where('category_id',$category)
//                    ->orwhereHas('categories')->when(\request()->category,function($q, $category){
//                        $q->where('category_id',$category);
//                    });
//            })
            ->orderByDesc('created_at')->get();
        //$categories = Category::whereLang($lang)->whereType(CategoryType::PRODUCT_CATEGORY)->public()->get();
        $langs =  Lang::get();
        $users = User::where('lever','>=',\Auth::user()->lever)->get();

        return view('Admin.Gallery.index',compact('galleries','langs','users'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if(check_admin_systems(SystemsModuleType::GALLERY))

        return view('Admin.Gallery.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return abort(404);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Product $gallery)
    {
        $type = SystemsModuleType::GALLERY;
        if(check_admin_systems($type))
        $photos = $gallery->photos()->orderby('sort','asc')->get();
        if($gallery->postLangsBefore){
            $id = array_unique($gallery->postLangsBefore->pluck('post_id')->toArray());
            $posts = Product::whereIn('id',$id)->get()->load('language');
            $langs = Lang::whereNotIn('value',$posts->pluck('lang'))->where('value','<>',$gallery->lang)->get();
        }else{
            $posts = null;
            $langs = Lang::where('value','<>',$gallery->lang)->get();
        }
        return view('Admin.Gallery.edit',compact('gallery','photos','langs','type','posts'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function lang($lang, $id)
    {
        if(check_admin_systems(SystemsModuleType::GALLERY))
            if(!Lang::whereValue($lang)->count())
                return flash('Ngôn ngữ chưa được cấu hình', 3);
            $gallery = Product::findOrFail($id);

        return view('Admin.Gallery.lang',compact('lang','gallery'));
    }
}
