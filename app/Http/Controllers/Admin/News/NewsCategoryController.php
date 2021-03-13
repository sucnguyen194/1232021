<?php

namespace App\Http\Controllers\Admin\News;

use App\Enums\AliasType;
use App\Enums\SystemsModuleType;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreNewsCategoryRequest;
use App\Http\Requests\UpdateNewsCategoryRequest;
use App\Models\Alias;
use App\Models\Lang;
use App\Models\NewsCategory;
use App\Models\PostLang;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Image,Session;

class NewsCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        check_admin_systems(SystemsModuleType::NEWS_CATEGORY);

            $lang = isset(request()->lang) ? request()->lang : Session::get('lang');

            $category = NewsCategory::with(['post_lang','parents'])->where('lang',$lang)
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

            $lang =  \App\Models\Lang::get();

            $type = \App\Enums\SystemsModuleType::NEWS_CATEGORY;

            $user = User::where('lever','>=',\Auth::user()->lever)->get();

            return view('Admin.News.category.list',compact('category','type','lang','user'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        check_admin_systems(SystemsModuleType::NEWS_CATEGORY);

        $category = NewsCategory::langs()->orderByDesc('id')->get();

        return view('Admin.News.category.add',compact('category'));
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreNewsCategoryRequest $request)
    {

        check_admin_systems(SystemsModuleType::NEWS_CATEGORY);

        if($request->send == 'save'){

            $check_alias = Alias::whereAlias($request->alias)->count();

            if($check_alias > 0)
                return redirect()->back()->withInput()->withErrors(['message' => 'Đường dẫ đã tồn tại']);

            if($request->unlink ){
                $image = null;
                $thumb = null;
            }elseif($request->hasFile('image')){
                $request->validate([
                    'image' => 'image|mimes:jpeg,png,jpg,gif,svg',
                ]);
                $file = $request->file('image');
                $file->store('news_category');
                $path = $file->hashName('news_category/thumb');
                $resizeThumb = Image::make($file);
                $resizeThumb->fit(375, 375, function ($constraint) {
                    $constraint->aspectRatio();
                });
                Storage::put($path, (string) $resizeThumb->encode());

                $thumb = "storage/".$path;
                $image = "storage/".$file->hashName('news_category');
            }else{
                $image = null;
                $thumb = null;
            }
            if($request->unlink_bg){
                $background = null;
            }elseif($request->hasFile('background')){
                $request->validate([
                    'background' => 'image|mimes:jpeg,png,jpg,gif,svg',
                ]);
                $file = $request->file('background');
                $file->store('news_category/background');
                $path = $file->hashName('news_category/background');
                $background = "storage/".$path;
            }else{
                $background = null;
            }

            NewsCategory::create([
                'title' => $request->title,
                'alias' => $request->alias,
                'image' => $image,
                'thumb' => $thumb,
                'background' => $background,
                'description' => $request->description,
                'position' => $request->position,
                'parent_id' => $request->category,
                'user' => \Auth::id(),
                'status' => isset($request->status) ? 1 : 2,
                'public' => isset($request->public) ? 1 : 2,
                'title_seo' => $request->title_seo ? $request->title_seo : $request->title,
                'description_seo' => $request->description_seo ? $request->description_seo : $request->title,
                'keyword_seo' => $request->keyword_seo ? $request->keyword_seo : $request->title,
                'lang' => Session::get('lang'),
            ]);

            return  redirect()->route('admin.news_category.index')->with(['message' => 'Thêm mới thành công']);
        }
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


    public function edit(NewsCategory $news_category)
    {
        check_admin_systems(SystemsModuleType::NEWS_CATEGORY);

        $lang = $news_category->lang;

        $categorys = NewsCategory::whereLang($lang)->where('id','<>', $news_category->id)->orderByDesc('id')->get();

        $type = SystemsModuleType::NEWS_CATEGORY;

        return view('Admin.News.category.edit',compact('news_category','categorys','type'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateNewsCategoryRequest $request, NewsCategory $news_category)
    {
        if(!check_admin_systems(SystemsModuleType::NEWS_CATEGORY))
            return redirect()->back()->withErrors(['message'=>'Errors']);

        if($request->send == 'update'){

            $alias = Alias::whereAlias($news_category->alias)->first();
            $check_alias = Alias::whereAlias($request->alias)->whereNotIn('id',[$alias->id])->count();

            if($check_alias > 0)
                return redirect()->back()->withInput()->withErrors(['message' => 'Đường dẫ đã tồn tại']);


            if($request->unlink){
                if(file_exists($news_category->image))
                    unlink($news_category->image);

                if(file_exists($news_category->thumb))
                    unlink($news_category->thumb);

                $image = null;
                $thumb = null;

            }elseif($request->hasFile('image')){
                $request->validate([
                    'image' => 'image|mimes:jpeg,png,jpg,gif,svg',
                ]);
                $file = $request->file('image');
                $file->store('news_category');
                $path = $file->hashName('news_category/thumb');
                $resizeThumb = Image::make($file);
                $resizeThumb->fit(375, 375, function ($constraint) {
                    $constraint->aspectRatio();
                });
                Storage::put($path, (string) $resizeThumb->encode());

                $thumb = "storage/".$path;
                $image = "storage/".$file->hashName('news_category');
            }else{
                $image = $news_category->image;
                $thumb = $news_category->thumb;
            }
            if($request->unlink_bg){

                if(file_exists($news_category->background))
                    unlink($news_category->background);

                $background = null;

            }elseif($request->hasFile('background')){
                $request->validate([
                    'background' => 'image|mimes:jpeg,png,jpg,gif,svg',
                ]);
                $file = $request->file('background');
                $file->store('news_category/background');
                $path = $file->hashName('news_category/background');
                $background = "storage/".$path;
            }else{
                $background = $news_category->background;
            }


            $news_category->update([
                'title' => $request->title,
                'alias' => $request->alias,
                'image' => $image,
                'thumb' => $thumb,
                'background' => $background,
                'description' => $request->description,
                'position' => $request->position,
                'parent_id' => $request->category,
                'user_edit' => \Auth::id(),
                'status' => isset($request->status) ? 1 : 0,
                'public' => isset($request->public) ? 1 : 0,
                'title_seo' => $request->title_seo ? $request->title_seo : $request->title,
                'description_seo' => $request->description_seo ? $request->description_seo : $request->title,
                'keyword_seo' => $request->keyword_seo ? $request->keyword_seo : $request->title,

            ]);

            return  redirect()->route('admin.news_category.index')->with(['message' => 'Sửa thành công']);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function destroy($id)
    {
        if(!check_admin_systems(SystemsModuleType::NEWS_CATEGORY))
            return redirect()->back()->withErrors(['message'=>'Lỗi']);

        $category = NewsCategory::findOrFail($id);

        if(file_exists($category->image))
            unlink($category->image);

        if(file_exists($category->thumb))
            unlink($category->thumb);

        if(file_exists($category->background))
            unlink($category->background);

        $category->delete();

        return redirect()->route('admin.news_category.index')->with(['message'=>'Xóa thành công']);
    }

    public function delMulti(Request $request)
    {
        if(!check_admin_systems(SystemsModuleType::NEWS_CATEGORY))
            return redirect()->back()->withErrors(['message'=>'Errors']);
        if($request->delall == 'delete'){

            $count = count($request->check_del);
                for($i=0;$i<$count;$i++){
                    $id = $request->check_del[$i];

                    $category = NewsCategory::findOrFail($id);

                    if(file_exists($category->image))
                        unlink($category->image);

                    if(file_exists($category->thumb))
                        unlink($category->thumb);

                    if(file_exists($category->background))
                        unlink($category->background);

                    $category->delete();

                }

            return redirect()->route('admin.news_category.index')->with(['message'=>'Xóa thành công']);
        }
        return redirect()->route('admin.news_category.index')->withErrors(['message'=>'LỖi']);
    }


    public function createLang($language="empty", $id=0)
    {
        if(!check_admin_systems(SystemsModuleType::NEWS_CATEGORY))
            return redirect()->back()->withErrors(['message'=>'Lỗi']);

        $lang = $language == 'empty' ? Session::get('lang') : $language;

        $check_lang = Lang::whereValue($lang)->count();

        if($check_lang == 0)
            return redirect()->route('admin.news_category.index')->withErrors(['message' => 'Ngôn ngữ chưa được cấu hình']);

        $cate = NewsCategory::findOrFail($id);

        $category = NewsCategory::whereLang($lang)->orderByDesc('id')->get();

        return view('Admin.News.category.lang',compact('category','lang','cate'));
    }

    public function storeLang(StoreNewsCategoryRequest $request, $language = 'empty', $id=0)
    {

        if(!check_admin_systems(SystemsModuleType::NEWS_CATEGORY))
            return redirect()->back()->withErrors(['message'=>'Errors']);

        $lang = $language == 'empty' ? Session::get('lang') : $language;

        $check_lang = Lang::whereValue($lang)->count();

        if($check_lang == 0)
            return redirect()->route('admin.news_category.index')->withErrors(['message' => 'Ngôn ngữ chưa được cấu hình']);

        if($request->send == 'save'){

            $check_alias = Alias::whereAlias($request->alias)->count();

            if($check_alias > 0)
                return redirect()->back()->withInput()->withErrors(['message' => 'Đường dẫ đã tồn tại']);

            if($request->unlink ){
                $image = null;
                $thumb = null;
            }elseif($request->hasFile('image')){
                $request->validate([
                    'image' => 'image|mimes:jpeg,png,jpg,gif,svg',
                ]);
                $file = $request->file('image');
                $file->store('news_category');
                $path = $file->hashName('news_category/thumb');
                $resizeThumb = Image::make($file);
                $resizeThumb->fit(375, 375, function ($constraint) {
                    $constraint->aspectRatio();
                });
                Storage::put($path, (string) $resizeThumb->encode());

                $thumb = "storage/".$path;
                $image = "storage/".$file->hashName('news_category');
            }else{
                $image = null;
                $thumb = null;
            }
            if($request->unlink_bg){
                $background = null;
            }elseif($request->hasFile('background')){
                $request->validate([
                    'background' => 'image|mimes:jpeg,png,jpg,gif,svg',
                ]);
                $file = $request->file('background');
                $file->store('news_category/background');
                $path = $file->hashName('news_category/background');
                $background = "storage/".$path;
            }else{
                $background = null;
            }

            $category = NewsCategory::create([
                'title' => $request->title,
                'alias' => $request->alias,
                'image' => $image,
                'thumb' => $thumb,
                'background' => $background,
                'description' => $request->description,
                'position' => $request->position,
                'parent_id' => $request->category,
                'user' => \Auth::id(),
                'status' => isset($request->status) ? 1 : 0,
                'public' => isset($request->public) ? 1 : 0,
                'title_seo' => $request->title_seo ? $request->title_seo : $request->title,
                'description_seo' => $request->description_seo ? $request->description_seo : $request->title,
                'keyword_seo' => $request->keyword_seo ? $request->keyword_seo : $request->title,
                'lang' => $lang,
            ]);

            if($id > 0) {
                $old = NewsCategory::findOrFail($id);
                add_post_lang($id,$category,$old,AliasType::NEWS_CATEGORY,$lang);
            }
            return  redirect()->route('admin.news_category.index')->with(['message' => 'Thêm mới thành công']);
        }
    }
}
