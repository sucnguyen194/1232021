<?php

namespace App\Http\Controllers\Admin;
use App\Enums\CategoryType;
use App\Enums\SystemsModuleType;
use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Lang;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProductCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        authorize(SystemsModuleType::PRODUCT_CATEGORY);
        $lang = isset(request()->lang) ? request()->lang : session()->get('lang');

        $categories = Category::whereType(CategoryType::PRODUCT_CATEGORY)->whereLang($lang)
            ->when(request()->user,function($q, $user){
                $q->where('user',$user);
            })
            ->when(request()->status,function($q, $status){
                $status = request()->status == 'true' ? 1 : 0 ;
                $q->where('status',$status);
            })
            ->when(request()->public, function($q, $public){
                $public = request()->public == 'true' ? 1 : 0 ;
                $q->where('public',$public);
            })
            ->orderByDesc('id')->get();

        $langs =  Lang::all();
        $users = User::where('lever','>=', Auth::user()->lever)->get();

        return view('Admin.Product.category.index',compact('categories','langs','users'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        authorize(SystemsModuleType::PRODUCT_CATEGORY);
        $categories = Category::whereType(CategoryType::PRODUCT_CATEGORY)->langs()->orderByDesc('id')->get();

        return view('Admin.Product.category.create',compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Category $category){
        authorize(SystemsModuleType::PRODUCT_CATEGORY);
        $lang = $category->lang;
        $type = SystemsModuleType::PRODUCT_CATEGORY;
        $categories = Category::whereLang($lang)->whereType($type)->whereNotIn('id',[$category->id])->orderByDesc('id')->get();

        //lang
        if($category->postLangsBefore){
            $id = array_unique($category->postLangsBefore->pluck('post_id')->toArray());
            $lists = Category::whereIn('id',$id)->get()->load('language');
            $langs = Lang::whereNotIn('value',$lists->pluck('lang'))->whereNotIn('value',[$category->lang])->get();
        }else{
            $lists = null;
            $langs = Lang::whereNotIn('value',[$category->lang])->get();
        }
        return view('Admin.Product.category.edit',compact('categories','category','type','lang','langs','lists'));
    }

    public function lang($language, $id){
        if(!Lang::whereValue($language)->count())
            return flash('Ngôn ngữ chưa được cấu hình', 3);

        $category = Category::findOrFail($id);
        $categories = Category::whereType(CategoryType::PRODUCT_CATEGORY)->whereLang($language)->orderbyDesc('id')->get();
        return view('Admin.Product.category.lang', compact('category','categories','language'));
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
