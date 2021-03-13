<?php

namespace App\Http\Controllers\Admin\Video;

use App\Enums\AliasType;
use App\Enums\SystemsModuleType;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreVideoRequest;
use App\Http\Requests\UpdateVideosRequest;
use App\Models\Alias;
use App\Models\Lang;
use App\Models\PostLang;
use App\Models\Tags;
use App\Models\User;
use App\Models\Videos;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use session,Image;

class VideosController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(!check_admin_systems(SystemsModuleType::VIDEO))
            return redirect()->back()->withErrors(['message'=>'Bạn không thể thực hiện hành động này!']);


        $lang = isset(request()->lang) ? request()->lang : Session::get('lang');

        $videos = Videos::with('language')->where('lang',$lang)
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

        $lang =  \App\Models\Lang::get();

        $type = \App\Enums\SystemsModuleType::PAGES;

        $user = User::where('lever','>=',\Auth::user()->lever)->get();

        return view('Admin.Video.list',compact('videos','type','lang','user'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if(!check_admin_systems(SystemsModuleType::VIDEO))
            return redirect()->back()->withErrors(['message'=>'Bạn không thể thực hiện hành động này!']);

        return view('Admin.Video.add');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreVideoRequest $request)
    {
        if(!check_admin_systems(SystemsModuleType::VIDEO))
            return redirect()->back()->withErrors(['message'=>'Bạn không thể thực hiện hành động này!']);

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
                $file->store('video');
                $path = $file->hashName('video/thumb');
                $resizeThumb = Image::make($file);
                $resizeThumb->fit(375, 375, function ($constraint) {
                    $constraint->aspectRatio();
                });
                Storage::put($path, (string) $resizeThumb->encode());

                $thumb = "storage/".$path;
                $image = "storage/".$file->hashName('video');
            }else{
                $image = null;
                $thumb = null;
            }
            Videos::create([
                'title' => $request->title,
                'alias' => $request->alias,
                'image' => $image,
                'thumb' => $thumb,
                'video' => str_replace('https://www.youtube.com/watch?v=','',$request->video),
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

            $video = Videos::where('alias',$request->alias)->first();
            if(isset($request->tags)){
                foreach(explode(',',$request->tags) as $items){
                    $alias = Str::slug($items, '-');
                    $check_alias = Tags::where('alias',$alias)->where('type',AliasType::VIDEO)->where('type_id',$video->id)->count();

                    if($check_alias == 0 ){
                        Tags::create([
                            'name' => $items,
                            'alias' => $alias,
                            'type' => AliasType::VIDEO,
                            'type_id' => $video->id,
                            'lang' => $video->lang,
                        ]);
                    }
                }
            }

            return  redirect()->route('admin.videos.index')->with(['message' => 'Thêm mới thành công']);
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
    public function edit(Videos $video)
    {
        if(!check_admin_systems(SystemsModuleType::VIDEO))
            return redirect()->back()->withErrors(['message'=>'Bạn không thể thực hiện hành động này!']);

        $langs = \App\Models\Lang::whereNotIn('value',[$video->lang])->get();
        $type = SystemsModuleType::PAGES;

        return view('Admin.Video.edit',compact('video','langs','type'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateVideosRequest $request, Videos $video)
    {

        if(!check_admin_systems(SystemsModuleType::VIDEO))
            return redirect()->back()->withErrors(['message'=>'Bạn không thể thực hiện hành động này!']);

        $alias = Alias::whereAlias($video->alias)->first();

        $check_alias = Alias::whereAlias($request->alias)->whereNotIn('id',[$alias->id])->count();
        if($check_alias > 0)
            return redirect()->back()->withInput()->withErrors(['message' => 'Đường dẫ đã tồn tại']);

        if($request->unlink){
            if(file_exists($video->image))
                unlink($video->image);

            if(file_exists($video->thumb))
                unlink($video->thumb);
            $image = null;
            $thumb = null;
        }elseif($request->hasFile('image')){
            $request->validate([
                'image' => 'image|mimes:jpeg,png,jpg,gif,svg',
            ]);
            $file = $request->file('image');
            $file->store('video');
            $path = $file->hashName('video/thumb');
            $resizeThumb = Image::make($file);
            $resizeThumb->fit(375, 375, function ($constraint) {
                $constraint->aspectRatio();
            });
            Storage::put($path, (string) $resizeThumb->encode());

            $thumb = "storage/".$path;
            $image = "storage/".$file->hashName('video');
        }else{
            if(file_exists($video->image))
                unlink($video->image);

            if(file_exists($video->thumb))
                unlink($video->thumb);
            $image = null;
            $thumb = null;
        }
        $video->update([
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
            'lang' => $video->lang,
        ]);

        if(isset($request->tags)){
            foreach(explode(',',$request->tags) as $items){
                $alias = Str::slug($items, '-');
                $check_alias = Tags::where('alias',$alias)->where('type',AliasType::VIDEO)->where('type_id',$video->id)->count();

                if($check_alias == 0 ){
                    Tags::create([
                        'name' => $items,
                        'alias' => $alias,
                        'type' => AliasType::VIDEO,
                        'type_id' => $video->id,
                        'lang' => $video->lang,
                    ]);
                }
            }
        }
        return  redirect()->route('admin.videos.index')->with(['message' => 'Sửa thành công']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if(!check_admin_systems(SystemsModuleType::VIDEO))
            return redirect()->back()->withErrors(['message'=>'Bạn không thể thực hiện hành động này!']);

        $video = Videos::findOrFail($id);

        if(file_exists($video->image))
            unlink($video->image);

        if(file_exists($video->thumb))
            unlink($video->thumb);

        $video->delete();

        return redirect()->route('admin.videos.index')->with(['message'=>'Xóa thành công']);
    }

    public function delMulti(Request $request)
    {
        if(!check_admin_systems(SystemsModuleType::VIDEO))
            return redirect()->back()->withErrors(['message'=>'Errors']);

        if($request->delall == 'delete'){

            $count = count($request->check_del);
            for($i=0;$i<$count;$i++){
                $id = $request->check_del[$i];

                $video = Videos::findOrFail($id);

                if(file_exists($video->image))
                    unlink($video->image);

                if(file_exists($video->thumb))
                    unlink($video->thumb);

                $video->delete();

            }
            return redirect()->route('admin.videos.index')->with(['message'=>'Xóa thành công']);
        }
        return redirect()->route('admin.videos.index')->withErrors(['message'=>'LỖi']);
    }

    public function createLang($language="empty", $id=0)
    {
        if(!check_admin_systems(SystemsModuleType::VIDEO))
            return redirect()->back()->withErrors(['message'=>'Bạn không thể thực hiện hành động này!']);

        $lang = $language == 'empty' ? Session::get('lang') : $language;

        $check_lang = Lang::whereValue($lang)->count();

        if($check_lang == 0)
            return redirect()->route('admin.videos.index')->withErrors(['message' => 'Ngôn ngữ chưa được cấu hình']);

        $video = Videos::findOrFail($id);

        return view('Admin.Video.lang',compact('lang','video'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function storeLang(StoreVideoRequest $request, $language = 'empty', $id=0)
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
                $file->store('video');
                $path = $file->hashName('video/thumb');
                $resizeThumb = Image::make($file);
                $resizeThumb->fit(375, 375, function ($constraint) {
                    $constraint->aspectRatio();
                });
                Storage::put($path, (string) $resizeThumb->encode());

                $thumb = "storage/".$path;
                $image = "storage/".$file->hashName('video');
            }else{
                $image = null;
                $thumb = null;
            }

            $video = Videos::create([
                'title' => $request->title,
                'alias' => $request->alias,
                'image' => $image,
                'thumb' => $thumb,
                'video' => str_replace('https://www.youtube.com/watch?v=','',$request->video),
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
                    $check_alias = Tags::where('alias',$alias)->where('type',AliasType::VIDEO)->where('type_id',$video->id)->count();

                    if($check_alias == 0 ){
                        Tags::create([
                            'name' => $items,
                            'alias' => $alias,
                            'type' => AliasType::VIDEO,
                            'type_id' => $video->id,
                            'lang' => $video->lang,
                        ]);
                    }
                }
            }

            if($id > 0) {
                $old = Videos::findOrFail($id);
                add_post_lang($id,$video,$old,AliasType::VIDEO,$lang);
            }

            return  redirect()->route('admin.videos.index')->with(['message' => 'Thêm mới thành công']);
        }
    }
}
