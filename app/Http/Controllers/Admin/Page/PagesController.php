<?php
namespace App\Http\Controllers\Admin\Page;

use App\Enums\AliasType;
use App\Http\Controllers\Controller;
use App\Enums\SystemsModuleType;
use App\Http\Requests\StorePagesRequest;
use App\Http\Requests\UpdatePagesRequest;
use App\Models\Alias;
use App\Models\Lang;
use App\Models\Pages;
use App\Models\PostLang;
use App\Models\Tags;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use session,Image;

class PagesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(check_admin_systems(SystemsModuleType::PAGES))

        $lang = isset(request()->lang) ? request()->lang : Session::get('lang');

        $pages = Pages::with(['language'])->where('lang',$lang)
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
            ->orderByDesc('id')->get();

        $langs =  \App\Models\Lang::where('value','<>',$lang)->get();

        $lang =  \App\Models\Lang::get();

        $type = \App\Enums\SystemsModuleType::PAGES;

        $user = User::where('lever','>=',\Auth::user()->lever)->get();

        return view('Admin.Page.list',compact('pages','langs','type','lang','user'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if(check_admin_systems(SystemsModuleType::ADD_PAGES))

        return view('Admin.Page.add');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StorePagesRequest $request)
    {
        if(check_admin_systems(SystemsModuleType::ADD_PAGES))

        if($request->send == 'save'){

            $check_alias = Alias::whereAlias($request->alias)->count();

            if($check_alias > 0)
                return redirect()->back()->withInput()->withErrors(['message' => 'Đường dẫ đã tồn tại']);

            if($request->unlink){
                $image = null;
                $thumb = null;
            }elseif($request->hasFile('image')){
                $request->validate([
                    'image' => 'image|mimes:jpeg,png,jpg,gif,svg',
                ]);
                $file = $request->file('image');
                $file->store('pages');
                $path = $file->hashName('pages/thumb');
                $resizeThumb = Image::make($file);
                $resizeThumb->fit(375, 375, function ($constraint) {
                    $constraint->aspectRatio();
                });
                Storage::put($path, (string) $resizeThumb->encode());

                $thumb = "storage/".$path;
                $image = "storage/".$file->hashName('pages');
            }else{
                $image = null;
                $thumb = null;
            }
            Pages::create([
                'title' => $request->title,
                'alias' => $request->alias,
                'image' => $image,
                'thumb' => $thumb,
                'description' => $request->description,
                'content' => $request->note,
                'user' => \Auth::id(),
                'tags' =>  $request->tags,
                'status' => isset($request->status) ? 1 : 0,
                'public' => isset($request->public) ? 1 : 0,
                'title_seo' => $request->title_seo ? $request->title_seo : $request->title,
                'description_seo' => $request->description_seo ? $request->description_seo : $request->title,
                'keyword_seo' => $request->keyword_seo ? $request->keyword_seo : $request->title,
                'lang' => Session::get('lang'),
            ]);

            $pages = Pages::where('alias',$request->alias)->first();
            if(isset($request->tags)){
                foreach(explode(',',$request->tags) as $items){
                    $alias = Str::slug($items, '-');
                    $check_alias = Tags::where('alias',$alias)->where('type',SystemsModuleType::PAGES)->where('type_id',$pages->id)->count();

                    if($check_alias == 0 ){
                        Tags::create([
                            'name' => $items,
                            'alias' => $alias,
                            'type' => SystemsModuleType::PAGES,
                            'type_id' => $pages->id,
                            'lang' => $pages->lang,
                        ]);
                    }
                }
            }

            return  redirect()->route('admin.pages.index')->with(['message' => 'Thêm mới thành công']);
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
    public function edit(Pages $page)
    {
        if(check_admin_systems(SystemsModuleType::ADD_PAGES))

        $type = SystemsModuleType::PAGES;

        return view('Admin.Page.edit',compact('page','type'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdatePagesRequest $request, Pages $page)
    {
        if(check_admin_systems(SystemsModuleType::ADD_PAGES))

        $alias = Alias::whereAlias($page->alias)->first();

        if($alias){
            if(Alias::whereAlias($request->alias)->whereNotIn('id',[$alias->id])->count())
                return redirect()->back()->withInput()->withErrors(['message' => 'Đường dẫ đã tồn tại']);
        }

        if($request->unlink){
            if(file_exists($page->image))
                unlink($page->image);

            if(file_exists($page->thumb))
                unlink($page->thumb);
            $image = null;
            $thumb = null;
        }elseif($request->hasFile('image')){
            $request->validate([
                'image' => 'image|mimes:jpeg,png,jpg,gif,svg',
            ]);
            $file = $request->file('image');
            $file->store('pages');
            $path = $file->hashName('pages/thumb');
            $resizeThumb = Image::make($file);
            $resizeThumb->fit(375, 375, function ($constraint) {
                $constraint->aspectRatio();
            });
            Storage::put($path, (string) $resizeThumb->encode());

            $thumb = "storage/".$path;
            $image = "storage/".$file->hashName('pages');
        }else{
            $image = $page->image;
            $thumb = $page->thumb;
        }
        $page->update([
            'title' => $request->title,
            'alias' => $request->alias,
            'image' => $image,
            'thumb' => $thumb,
            'description' => $request->description,
            'content' => $request->note,
            'user_edit' => \Auth::id(),
            'tags' =>  $request->tags,
            'status' => isset($request->status) ? 1 : 0,
            'public' => isset($request->public) ? 1 : 0,
            'title_seo' => $request->title_seo ? $request->title_seo : $request->title,
            'description_seo' => $request->description_seo ? $request->description_seo : $request->title,
            'keyword_seo' => $request->keyword_seo ? $request->keyword_seo : $request->title,
            'lang' => $page->lang,
        ]);

        $page->tags()->delete();
        if(isset($request->tags)){
            foreach(explode(',',$request->tags) as $items){
                $alias = Str::slug($items, '-');
                $check_alias = Tags::where('alias',$alias)->where('type',SystemsModuleType::PAGES)->where('type_id',$page->id)->count();

                if($check_alias == 0 ){
                    Tags::create([
                        'name' => $items,
                        'alias' => $alias,
                        'type' => SystemsModuleType::PAGES,
                        'type_id' => $page->id,
                        'lang' => $page->lang,
                    ]);
                }
            }
        }
        return  redirect()->route('admin.pages.index')->with(['message' => 'Sửa thành công']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if(!check_admin_systems(SystemsModuleType::PAGES))
            return redirect()->back()->withErrors(['message'=>'Bạn không thể thực hiện hành động này!']);

        $pages = Pages::findOrFail($id);

        if(file_exists($pages->image))
            unlink($pages->image);

        if(file_exists($pages->thumb))
            unlink($pages->thumb);

        $pages->delete();

        return redirect()->route('admin.pages.index')->with(['message'=>'Xóa thành công']);
    }

    public function delMulti(Request $request)
    {
        if(!check_admin_systems(SystemsModuleType::PAGES))
            return redirect()->back()->withErrors(['message'=>'Bạn không thể thực hiện hành động này!']);
        if($request->delall == 'delete'){

            $count = count($request->check_del);
            for($i=0;$i<$count;$i++){
                $id = $request->check_del[$i];

                $pages = Pages::findOrFail($id);

                if(file_exists($pages->image))
                    unlink($pages->image);

                if(file_exists($pages->thumb))
                    unlink($pages->thumb);

                $pages->delete();

            }
            return redirect()->route('admin.pages.index')->with(['message'=>'Xóa thành công']);
        }
        return redirect()->route('admin.pages.index')->withErrors(['message'=>'LỖi']);
    }

    public function createLang($language="empty", $id=0)
    {
        if(!check_admin_systems(SystemsModuleType::ADD_PAGES))
            return redirect()->back()->withErrors(['message'=>'Bạn không thể thực hiện hành động này!']);

        $lang = $language == 'empty' ? Session::get('lang') : $language;

        $check_lang = Lang::whereValue($lang)->count();

        if($check_lang == 0)
            return redirect()->route('admin.pages.index')->withErrors(['message' => 'Ngôn ngữ chưa được cấu hình']);

        $pages = Pages::findOrFail($id);

        return view('Admin.Page.lang',compact('lang','pages'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function storeLang(StorePagesRequest $request, $language = 'empty', $id=0)
    {

        if(!check_admin_systems(SystemsModuleType::ADD_PAGES))
            return redirect()->back()->withErrors(['message'=>'Bạn không thể thực hiện hành động này!']);

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
                $file->store('pages');
                $path = $file->hashName('pages/thumb');
                $resizeThumb = Image::make($file);
                $resizeThumb->fit(375, 375, function ($constraint) {
                    $constraint->aspectRatio();
                });
                Storage::put($path, (string) $resizeThumb->encode());

                $thumb = "storage/".$path;
                $image = "storage/".$file->hashName('pages');
            }else{
                $image = null;
                $thumb = null;
            }

            $pages = Pages::create([
                'title' => $request->title,
                'alias' => $request->alias,
                'image' => $image,
                'thumb' => $thumb,
                'description' => $request->description,
                'content' => $request->note,
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
                    $check_alias = Tags::where('alias',$alias)->where('type',SystemsModuleType::PAGES)->where('type_id',$pages->id)->count();

                    if($check_alias == 0 ){
                        Tags::create([
                            'name' => $items,
                            'alias' => $alias,
                            'type' => SystemsModuleType::PAGES,
                            'type_id' => $pages->id,
                            'lang' => $pages->lang,
                        ]);
                    }
                }
            }

            if($id > 0) {
                $old = Pages::findOrFail($id);
                add_post_lang($id,$pages,$old,AliasType::PAGES,$lang);
            }

            return  redirect()->route('admin.pages.index')->with(['message' => 'Thêm mới thành công']);
        }
    }
}
