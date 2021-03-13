<?php

namespace App\Http\Controllers\Admin\News;

use App\Enums\ActiveDisable;
use App\Enums\AliasType;
use App\Enums\SystemsModuleType;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreNewsRequest;
use App\Http\Requests\UpdateNewsRequest;
use App\Models\Alias;
use App\Models\Lang;
use App\Models\News;
use App\Models\NewsCategory;
use App\Models\NewsToCategory;
use App\Models\PostLang;
use App\Models\Tags;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use session,Image;

class NewsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index()
    {
        if(check_admin_systems(SystemsModuleType::LIST_NEWS))

        $lang = isset(request()->lang) ? request()->lang : Session::get('lang');

        $news = News::with(['category', 'language'])->where('lang',$lang)
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
            ->when(\request()->category,function($q){
                $category = \request()->category == -1 ? 0 : \request()->category;
                $q->where('category_id',$category)->orwhereHas('categorys')->when(\request()->category,function($q, $category){
                    $q->where('category_id',$category);
                });
            })
            ->orderByDesc('id')->get();

        $category = NewsCategory::whereLang($lang)->public()->get();

        $langs =  \App\Models\Lang::get();

        $user = User::where('lever','>=',\Auth::user()->lever)->get();

        return view('Admin.News.list',compact('news','langs','user','category'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if(check_admin_systems(SystemsModuleType::ADD_NEWS))

        $category = NewsCategory::wherePublic(ActiveDisable::ACTIVE)->whereLang(Session::get('lang'))->orderByDesc('id')->get();

        return view('Admin.News.add',compact('category'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    public function store(StoreNewsRequest $request)
    {
        if(check_admin_systems(SystemsModuleType::ADD_NEWS))

            if($request->send == 'save'){

                $check_alias = Alias::whereAlias($request->alias)->count();

                if($check_alias > 0)
                    return redirect()->back()->withInput()->withErrors(['message' => 'Đường dẫn đã tồn tại']);

                if($request->unlink){
                    $image = null;
                    $thumb = null;
                }elseif($request->hasFile('image')){
                    $request->validate([
                        'image' => 'image|mimes:jpeg,png,jpg,gif,svg',
                    ]);
                    $file = $request->file('image');
                    $file->store('news');
                    $path = $file->hashName('news/thumb');
                    $resizeThumb = Image::make($file);
                    $resizeThumb->fit(450, 450, function ($constraint) {
                        $constraint->aspectRatio();
                    });
                    Storage::put($path, (string) $resizeThumb->encode());

                    $thumb = "storage/".$path;
                    $image = "storage/".$file->hashName('news');
                }else{
                    $image = null;
                    $thumb = null;
                }

                $news = News::create([
                    'title' => $request->title,
                    'alias' => $request->alias,
                    'image' => $image,
                    'thumb' => $thumb,
                    'description' => $request->description,
                    'content' => $request->body,
                    'category_id' => $request->category,
                    'user' => \Auth::id(),
                    'tags' =>  $request->tags,
                    'status' => isset($request->status) ? 1 : 0,
                    'public' => isset($request->public) ? 1 : 0,
                    'title_seo' => $request->title_seo ? $request->title_seo : $request->title,
                    'description_seo' => $request->description_seo ? $request->description_seo : $request->title,
                    'keyword_seo' => $request->keyword_seo ? $request->keyword_seo : $request->title,
                    'lang' => Session::get('lang'),
                ]);

                if(isset($request->tags)){
                    foreach(explode(',',$request->tags) as $items){
                        $alias = Str::slug($items, '-');
                        $check_alias = Tags::where('alias',$alias)->where('type',SystemsModuleType::NEWS)->where('type_id',$news->id)->count();

                        if($check_alias == 0 ){
                            Tags::create([
                                'name' => $items,
                                'alias' => $alias,
                                'type' => SystemsModuleType::NEWS,
                                'type_id' => $news->id,
                                'lang' => $news->lang,
                            ]);
                        }
                    }
                }

                if(isset($request->category_id) && sizeof($request->category_id) > 0) {
                    for ($i = 0 ; $i < sizeof($request->category_id); $i++){
                        NewsToCategory::create([
                            'news_id' => $news->id,
                            'category_id' => $request->category_id[$i]
                        ]);
                    }
                }

                return  redirect()->route('admin.news.index')->with(['message' => 'Thêm mới thành công']);
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
    public function edit(News $news)
    {
        if(check_admin_systems(SystemsModuleType::NEWS))

        $category = NewsCategory::whereLang($news->lang)->public()->orderByDesc('id')->get();

        $type = SystemsModuleType::NEWS;

        return view('Admin.News.edit',compact('news','category','type'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateNewsRequest $request, News $news)
    {
        if(check_admin_systems(SystemsModuleType::ADD_NEWS))

        if($request->send == 'update'){

            $alias = Alias::whereAlias($news->alias)->first();
            $check_alias = Alias::whereAlias($request->alias)->whereNotIn('id',[$alias->id])->count();

            if($check_alias > 0)
                return redirect()->back()->withInput()->withErrors(['message' => 'Đường dẫn đã tồn tại']);

            if($request->unlink){
                if(file_exists($news->image))
                    unlink($news->image);

                if(file_exists($news->thumb))
                    unlink($news->thumb);
                $image = null;
                $thumb = null;
            }elseif($request->hasFile('image')){
                $request->validate([
                    'image' => 'image|mimes:jpeg,png,jpg,gif,svg',
                ]);
                $file = $request->file('image');
                $file->store('news');
                $path = $file->hashName('news/thumb');
                $resizeThumb = Image::make($file);
                $resizeThumb->fit(450, 450, function ($constraint) {
                    $constraint->aspectRatio();
                });
                Storage::put($path, (string) $resizeThumb->encode());

                $thumb = "storage/".$path;
                $image = "storage/".$file->hashName('news');
            }else{
                $image = $news->image;
                $thumb = $news->thumb;
            }

            $news->update([
                'title' => $request->title,
                'alias' => $request->alias,
                'image' => $image,
                'thumb' => $thumb,
                'description' => $request->description,
                'content' => $request->body,
                'category_id' => $request->category,
                'user_edit' => \Auth::id(),
                'tags' =>  $request->tags,
                'status' => isset($request->status) ? 1 : 0,
                'public' => isset($request->public) ? 1 : 0,
                'title_seo' => $request->title_seo ? $request->title_seo : $request->title,
                'description_seo' => $request->description_seo ? $request->description_seo : $request->title,
                'keyword_seo' => $request->keyword_seo ? $request->keyword_seo : $request->title,
                'lang' => $news->lang,
            ]);

            $news->tags()->delete();

            if(isset($request->tags)){
                foreach(explode(',',$request->tags) as $items){
                    $alias = Str::slug($items, '-');
                    $check_alias = Tags::where('alias',$alias)->where('type',SystemsModuleType::NEWS)->where('type_id',$news->id)->count();

                    if($check_alias == 0 ){
                        Tags::create([
                            'name' => $items,
                            'alias' => $alias,
                            'type' => SystemsModuleType::NEWS,
                            'type_id' => $news->id,
                            'lang' => $news->lang,
                        ]);
                    }
                }
            }
            $news->categorys()->delete();

            if(isset($request->category_id) && sizeof($request->category_id) > 0) {
                for ($i = 0 ; $i < sizeof($request->category_id); $i++){
                    NewsToCategory::create([
                        'news_id' => $news->id,
                        'category_id' => $request->category_id[$i]
                    ]);
                }
            }
            $categorys = NewsToCategory::whereNewsId($news->id)->whereCategoryId($news->category_id)->count();

            if($categorys == 0){
                NewsToCategory::create([
                    'news_id' => $news->id,
                    'category_id' => $news->category_id
                ]);
            }

            return  redirect()->route('admin.news.index')->with(['message' => 'Sửa thành công']);
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
        if(check_admin_systems(SystemsModuleType::NEWS))

        $news = News::findOrFail($id);

        if(file_exists($news->image))
            unlink($news->image);

        if(file_exists($news->thumb))
            unlink($news->thumb);

        $news->delete();

        return redirect()->back()->withInput()->with(['message'=>'Xóa thành công']);
    }

    public function delMulti(Request $request)
    {
        if(check_admin_systems(SystemsModuleType::NEWS))

        if($request->delall == 'delete'){

            $count = count($request->check_del);
            for($i=0;$i<$count;$i++){
                $id = $request->check_del[$i];

                $news = News::findOrFail($id);

                if(file_exists($news->image))
                    unlink($news->image);

                if(file_exists($news->thumb))
                    unlink($news->thumb);

                $news->delete();

            }
            return redirect()->back()->withInput()->with(['message'=>'Xóa thành công']);
        }
        return redirect()->route('admin.news.index')->withErrors(['message'=>'LỖi']);
    }

    public function createLang($language="empty", $id=0)
    {
        check_admin_systems(SystemsModuleType::ADD_NEWS);

        $lang = $language == 'empty' ? Session::get('lang') : $language;

        $check_lang = Lang::whereValue($lang)->count();

        if($check_lang == 0)
            return redirect()->route('admin.news.index')->withErrors(['message' => 'Ngôn ngữ chưa được cấu hình']);

        $news = News::findOrFail($id);

        $category = NewsCategory::wherePublic(ActiveDisable::ACTIVE)->whereParentId(0)->whereLang($lang)->orderByDesc('id')->get();

        return view('Admin.News.lang',compact('category','lang','news'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function storeLang(StoreNewsRequest $request, $language = 'empty', $id=0)
    {
        if(check_admin_systems(SystemsModuleType::ADD_NEWS))

        if($request->send == 'save'){

            $check_alias = Alias::whereAlias($request->alias)->count();

            if($check_alias > 0)
                return redirect()->back()->withInput()->withErrors(['message' => 'Đường dẫ đã tồn tại']);

            $lang = $language == 'empty' ? Session::get('lang') : $language;

            $check_lang = Lang::whereValue($lang)->count();
            if($check_lang == 0)
                return redirect()->route('admin.news.index')->withErrors(['message' => 'Ngôn ngữ chưa được cấu hình']);

            if($request->unlink){
                $image = null;
                $thumb = null;
            }elseif($request->hasFile('image')){
                $request->validate([
                    'image' => 'image|mimes:jpeg,png,jpg,gif,svg',
                ]);
                $file = $request->file('image');
                $file->store('news');
                $path = $file->hashName('news/thumb');
                $resizeThumb = Image::make($file);
                $resizeThumb->fit(450, 450, function ($constraint) {
                    $constraint->aspectRatio();
                });
                Storage::put($path, (string) $resizeThumb->encode());

                $thumb = "storage/".$path;
                $image = "storage/".$file->hashName('news');
            }else{
                $image = null;
                $thumb = null;
            }

            $news = News::create([
                'title' => $request->title,
                'alias' => $request->alias,
                'image' => $image,
                'thumb' => $thumb,
                'description' => $request->description,
                'content' => $request->body,
                'category_id' => $request->category,
                'user' => \Auth::id(),
                'tags' =>  $request->tags,
                'status' => isset($request->status) ? 1 : 0,
                'public' => isset($request->public) ? 1 : 0,
                'title_seo' => $request->title_seo ? $request->title_seo : $request->title,
                'description_seo' => $request->description_seo ? $request->description_seo : $request->title,
                'keyword_seo' => $request->keyword_seo ? $request->keyword_seo : $request->title,
                'lang' => $lang,
            ]);

            if(isset($request->tags)){
                foreach(explode(',',$request->tags) as $items){
                    $alias = Str::slug($items, '-');
                    $check_alias = Tags::where('alias',$alias)->where('type',SystemsModuleType::NEWS)->where('type_id',$news->id)->count();

                    if($check_alias == 0 ){
                        Tags::create([
                            'name' => $items,
                            'alias' => $alias,
                            'type' => SystemsModuleType::NEWS,
                            'type_id' => $news->id,
                            'lang' => $news->lang,
                        ]);
                    }
                }
            }

            if(isset($request->category_id) && sizeof($request->category_id) > 0) {
                for ($i = 0 ; $i < sizeof($request->category_id); $i++){
                    NewsToCategory::create([
                        'news_id' => $news->id,
                        'category_id' => $request->category_id[$i]
                    ]);
                }
            }

            if($id > 0) {

                $old = News::findOrFail($id);
                add_post_lang($id,$news,$old,AliasType::NEWS,$lang);
            }

            return  redirect()->route('admin.news.index')->with(['message' => 'Thêm mới thành công']);
        }
    }

}
