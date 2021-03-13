<?php

namespace App\Http\Controllers\Admin\Product;

use App\Enums\AliasType;
use App\Enums\SystemsModuleType;
use App\Http\Controllers\Controller;
use App\Models\Alias;
use App\Models\CategoryProduct;
use App\Models\Lang;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Session, Image;

class ProductCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(check_admin_systems(SystemsModuleType::PRODUCT_CATEGORY))

            $lang = isset(request()->lang) ? request()->lang : Session::get('lang');

            $category = CategoryProduct::with(['parents'])->where('lang',$lang)

            ->when(request()->user,function($q, $user){
                $q->where('user_id',$user)->orWhere('user_edit',$user);
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

        $lang =  \App\Models\Lang::get();

        $type = \App\Enums\SystemsModuleType::NEWS_CATEGORY;

        $user = User::where('lever','>=',\Auth::user()->lever)->get();

            return view('Admin.Product.Category.index',compact('category','type','lang','user'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if(check_admin_systems(SystemsModuleType::PRODUCT_CATEGORY))

        $category = CategoryProduct::langs()->public()->orderByDesc('id')->get();

        return view('Admin.Product.Category.create',compact('category'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if(check_admin_systems(SystemsModuleType::PRODUCT_CATEGORY))

            $request->validate([
               'data.name' => 'required',
               'data.alias' => 'required',
            ]);

        if($check_alias = Alias::whereAlias($request->alias)->count())
            return redirect()->back()->withInput()->withErrors(['message' => 'Đường dẫ đã tồn tại']);

        $category = new CategoryProduct();

        $category->Fill($request->data);

        if($request->unlink ){
            $category->image = null;
            $category->thumb = null;
        }elseif($request->hasFile('image')){
            $request->validate([
                'image' => 'image|mimes:jpeg,png,jpg,gif,svg,ico',
            ]);
            $file = $request->file('image');
            $file->store('product_category');
            $path = $file->hashName('product_category/thumb');
            $resizeThumb = Image::make($file);
            $resizeThumb->fit(375, 375, function ($constraint) {
                $constraint->aspectRatio();
            });
            Storage::put($path, (string) $resizeThumb->encode());

            $category->thumb = "storage/".$path;
            $category->image = "storage/".$file->hashName('product_category');
        }else{
            $category->image = null;
            $category->thumb = null;
        }
        if($request->unlink_bg){
            $category->background = null;
        }elseif($request->hasFile('background')){
            $request->validate([
                'background' => 'image|mimes:jpeg,png,jpg,gif,svg,ico',
            ]);
            $file = $request->file('background');
            $file->store('product_category/background');
            $path = $file->hashName('product_category/background');
            $category->background = "storage/".$path;
        }else{
            $category->background = null;
        }

        $category->status = isset($request->status) ? 1 : 0;
        $category->public = isset($request->public) ? 1 : 0;
        $category->title_seo = $request->input('data.title_seo') ? $request->input('data.title_seo') : $request->input('data.name');
        $category->description_seo = $request->input('data.description_seo') ? $request->input('data.description_seo') : $request->input('data.name');
        $category->keyword_seo = $request->input('data.keyword_seo') ? $request->input('data.keyword_seo') : $request->input('data.name');
        $category->lang = Session::get('lang');

        $category->save();

        return redirect()->route('admin.product_categorys.index')->with(['message' => 'Thêm mới thành công!']);
    }

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
    public function edit(CategoryProduct $product_category)
    {
        if(check_admin_systems(SystemsModuleType::PRODUCT_CATEGORY))

            $lang = $product_category->lang;

        $categorys = CategoryProduct::whereLang($lang)->where('parent_id', 0)->where('id','<>', $product_category->id)->orderByDesc('id')->get();

        $categorysub = CategoryProduct::whereLang($lang)->where('parent_id','>', 0)->where('id','<>', $product_category->id)->orderByDesc('id')->get();

        $langs = \App\Models\Lang::whereNotIn('value',[$product_category->lang])->get();

        $type = SystemsModuleType::PRODUCT_CATEGORY;

            return view('Admin.Product.Category.edit',compact('product_category','categorys','categorysub','langs','type'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, CategoryProduct $product_category)
    {
        if(check_admin_systems(SystemsModuleType::PRODUCT_CATEGORY))

            $request->validate([
                'data.name' => 'required',
                'data.alias' => 'required',
            ]);

         $alias = Alias::whereAlias($product_category->alias)->first();

         if($alias){
             if(Alias::whereAlias($request->alias)->whereNotIn('id',[$alias->id])->count())
                 return redirect()->back()->withInput()->withErrors(['message' => 'Đường dẫ đã tồn tại']);
         }

        $product_category->forceFill($request->data);

        if($request->unlink){
            if(file_exists($product_category->image))
                unlink($product_category->image);

            if(file_exists($product_category->thumb))
                unlink($product_category->thumb);

            $image = null;
            $thumb = null;

        }elseif($request->hasFile('image')){
            $request->validate([
                'image' => 'image|mimes:jpeg,png,jpg,gif,svg,ico',
            ]);
            $file = $request->file('image');
            $file->store('product_category');
            $path = $file->hashName('product_category/thumb');
            $resizeThumb = Image::make($file);
            $resizeThumb->fit(375, 375, function ($constraint) {
                $constraint->aspectRatio();
            });
            Storage::put($path, (string) $resizeThumb->encode());

            $product_category->thumb = "storage/".$path;
            $product_category->image = "storage/".$file->hashName('product_category');
        }else{
            $image = $product_category->image;
            $thumb = $product_category->thumb;
        }
        if($request->unlink_bg){

            if(file_exists($product_category->background))
                unlink($product_category->background);

            $background = null;

        }elseif($request->hasFile('background')){
            $request->validate([
                'background' => 'image|mimes:jpeg,png,jpg,gif,svg,ico',
            ]);
            $file = $request->file('background');
            $file->store('product_category/background');
            $path = $file->hashName('product_category/background');
            $product_category->background = "storage/".$path;
        }else{
            $background = $product_category->background;
        }

        $product_category->image = $image;
        $product_category->thumb = $thumb;
        $product_category->background = $background;

        $product_category->status = isset($request->status) ? 1 : 0;
        $product_category->public = isset($request->public) ? 1 : 0;

        $product_category->user_edit = \Auth::id();

        $product_category->save();

        return redirect()->back()->withInput()->with(['message' => 'Cập nhật thành công']);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if(check_admin_systems(SystemsModuleType::PRODUCT_CATEGORY))

            $category = CategoryProduct::findOrFail($id);

            $category->delete();

        return redirect()->route('admin.product_categorys.index')->with(['message'=>'Xóa thành công']);
    }

    public function delMulti(Request $request){

        if(check_admin_systems(SystemsModuleType::PRODUCT_CATEGORY))

            if($request->delall == 'delete'){

                $count = count($request->check_del);
                for($i=0;$i<$count;$i++){
                    $id = $request->check_del[$i];

                    $category = CategoryProduct::findOrFail($id);

                    if(file_exists($category->image))
                        unlink($category->image);

                    if(file_exists($category->thumb))
                        unlink($category->thumb);

                    if(file_exists($category->background))
                        unlink($category->background);

                    $category->delete();

                }

                return redirect()->route('admin.product_categorys.index')->with(['message'=>'Xóa thành công']);
            }
    }

    public function createLang($language="empty", $id=0)
    {
        if(check_admin_systems(SystemsModuleType::PRODUCT_CATEGORY))

        $lang = $language == 'empty' ? Session::get('lang') : $language;

        $check_lang = Lang::whereValue($lang)->count();

        if($check_lang == 0)
            return redirect()->route('admin.product_categorys.index')->withErrors(['message' => 'Ngôn ngữ chưa được cấu hình']);

        $cate = CategoryProduct::findOrFail($id);

        $category = CategoryProduct::langs()->orderByDesc('id')->get();

        return view('Admin.Product.Category.lang',compact('category','lang','cate'));
    }

    public function storeLang(Request $request, $language = 'empty', $id=0){

        if(check_admin_systems(SystemsModuleType::PRODUCT_CATEGORY))

            $lang = $language == 'empty' ? Session::get('lang') : $language;

        $check_lang = Lang::whereValue($lang)->count();

        if($check_lang == 0)
            return redirect()->route('admin.product_categorys.index')->withErrors(['message' => 'Ngôn ngữ chưa được cấu hình']);

        $request->validate([
            'data.name' => 'required',
            'data.alias' => 'required',
        ]);

        if($check_alias = Alias::whereAlias($request->alias)->count())
            return redirect()->back()->withInput()->withErrors(['message' => 'Đường dẫ đã tồn tại']);

        $category = new CategoryProduct();

        $category->Fill($request->data);

        if($request->unlink ){
            $category->image = null;
            $category->thumb = null;
        }elseif($request->hasFile('image')){
            $request->validate([
                'image' => 'image|mimes:jpeg,png,jpg,gif,svg',
            ]);
            $file = $request->file('image');
            $file->store('product_category');
            $path = $file->hashName('product_category/thumb');
            $resizeThumb = Image::make($file);
            $resizeThumb->fit(375, 375, function ($constraint) {
                $constraint->aspectRatio();
            });
            Storage::put($path, (string) $resizeThumb->encode());

            $category->thumb = "storage/".$path;
            $category->image = "storage/".$file->hashName('product_category');
        }else{
            $category->image = null;
            $category->thumb = null;
        }
        if($request->unlink_bg){
            $category->background = null;
        }elseif($request->hasFile('background')){
            $request->validate([
                'background' => 'image|mimes:jpeg,png,jpg,gif,svg',
            ]);
            $file = $request->file('background');
            $file->store('product_category/background');
            $path = $file->hashName('product_category/background');
            $category->background = "storage/".$path;
        }else{
            $category->background = null;
        }

        $category->status = isset($request->status) ? 1 : 0;
        $category->public = isset($request->public) ? 1 : 0;
        $category->title_seo = $request->input('data.title_seo') ? $request->input('data.title_seo') : $request->input('data.name');
        $category->description_seo = $request->input('data.description_seo') ? $request->input('data.description_seo') : $request->input('data.name');
        $category->keyword_seo = $request->input('data.keyword_seo') ? $request->input('data.keyword_seo') : $request->input('data.name');
        $category->lang = $lang;

        $category->save();


        if($id > 0) {
            $old = CategoryProduct::findOrFail($id);
            add_post_lang($id,$category,$old,AliasType::PRODUCT_CATEGORY,$lang);
        }

        return redirect()->route('admin.product_categorys.index')->with(['message' => 'Thêm mới thành công!']);

    }
}
