<?php

namespace App\Http\Controllers\Admin\Gallery;

use App\Enums\AliasType;
use App\Enums\MediaType;
use App\Enums\SystemsModuleType;
use App\Http\Controllers\Controller;
use App\Models\Alias;
use App\Models\Gallerys;
use App\Models\Media;
use App\Models\NewsCategory;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
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
        if(check_admin_systems(SystemsModuleType::GALLERY))
            $lang = isset(request()->lang) ? request()->lang : Session::get('lang');

            $gallery = Gallerys::with('language')->whereLang($lang)
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
                ->orderByDesc('id')
                ->get();



        $lang =  \App\Models\Lang::get();

        $type = \App\Enums\SystemsModuleType::GALLERY;

        $user = User::where('lever','>=',\Auth::user()->lever)->get();

        return view('Admin.Gallery.list',compact('gallery','lang','type','user'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if(check_admin_systems(SystemsModuleType::GALLERY))
            $category = [];
            if(Schema::hasTable('gallery_category'))
                $category = \DB::table('gallery_category')->get();

        return view('Admin.Gallery.add',compact('category'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if(check_admin_systems(SystemsModuleType::GALLERY))

       $request->validate([
           'title' => 'required',
           'alias' => 'required',
       ]);

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
            $file->store('gallery');
            $path = $file->hashName('gallery/thumb');
            $resizeThumb = Image::make($file);
            $resizeThumb->fit(375, 375, function ($constraint) {
                $constraint->aspectRatio();
            });
            Storage::put($path, (string) $resizeThumb->encode());

            $thumb = "storage/".$path;
            $image = "storage/".$file->hashName('gallery');
        }else{
            $image = null;
            $thumb = null;
        }

        $gallery = Gallerys::create([
            'title' => $request->title,
            'alias' => $request->alias,
            'description' => $request->description,
            'image' => $image,
            'thumb' => $thumb,
            'category_id' => $request->category,
            'user_id' => \Auth::id(),
            'status' => 0,
            'public' => 1,
            'lang' => Session::get('lang')
        ]);

        if($request->hasFile('photo')){

            $count = count($request->file('photo'));

            for ($i = 0; $i < $count; $i++) {
                $file = $request->file('photo')[$i];
                $file->store('gallery/photo');
                $path = $file->hashName('gallery/photo/thumb');
                $resizeThumb = Image::make($file);
                $resizeThumb->fit(375, 375, function ($constraint) {
                    $constraint->aspectRatio();
                });
                Storage::put($path, (string) $resizeThumb->encode());

                $thumb = "storage/".$path;
                $image = "storage/".$file->hashName('gallery/photo');

                Media::create([
                    'name' => $gallery->title,
                    'image' => $image,
                    'thumb' => $thumb,
                    'type_id' => $gallery->id,
                    'type' => MediaType::GALLERY,
                    'user_id' => \Auth::id(),
                    'public' => 1,
                    'lang' => $gallery->lang
                ]);

            }
        }

        return redirect()->route('admin.gallerys.index')->with(['message' => 'Thêm mới thành công']);
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
    public function edit(Gallerys $gallery)
    {
        if(check_admin_systems(SystemsModuleType::GALLERY))

        $category = [];

        $photo = Media::whereType(MediaType::GALLERY)->where('type_id',$gallery->id)->get();

        $langs = \App\Models\Lang::whereNotIn('value',[$gallery->lang])->get();

        $type = SystemsModuleType::GALLERY;

        if(Schema::hasTable('gallery_category'))
            $category = \DB::table('gallery_category')->get();

        return view('Admin.Gallery.edit',compact('category','gallery','photo','langs','type'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Gallerys $gallery)
    {
        if(check_admin_systems(SystemsModuleType::GALLERY))

            $request->validate([
                'title' => 'required',
                'alias' => 'required',
            ]);

        $alias = Alias::whereAlias($gallery->alias)->first();
        $check_alias = Alias::whereAlias($request->alias)->whereNotIn('id',[$alias->id])->count();

        if($check_alias > 0)
            return redirect()->back()->withInput()->withErrors(['message' => 'Đường dẫ đã tồn tại']);

        if($request->unlink){
            if(file_exists($gallery->image))
                unlink($gallery->image);

            if(file_exists($gallery->thumb))
                unlink($gallery->thumb);
            $image = null;
            $thumb = null;
        }elseif($request->hasFile('image')){
            $request->validate([
                'image' => 'image|mimes:jpeg,png,jpg,gif,svg',
            ]);
            $file = $request->file('image');
            $file->store('gallery');
            $path = $file->hashName('gallery/thumb');
            $resizeThumb = Image::make($file);
            $resizeThumb->fit(375, 375, function ($constraint) {
                $constraint->aspectRatio();
            });
            Storage::put($path, (string) $resizeThumb->encode());

            $thumb = "storage/".$path;
            $image = "storage/".$file->hashName('gallery');
        }else{

            $image = $gallery->image;
            $thumb = $gallery->thumb;
        }

        $gallery->update([
            'name' => $request->title,
            'image' => $image,
            'thumb' => $thumb,
            'description' => $request->description,
            'type_id' => $gallery->id,
            'type' => MediaType::GALLERY,
            'user_edit' => \Auth::id(),
            'title_seo' => $request->title_seo ? $request->title_seo : $request->title,
            'description_seo' => $request->description_seo ? $request->description_seo : $request->title,
            'keyword_seo' => $request->keyword_seo ? $request->keyword_seo : $request->title,
            'lang' => \Session::get('lang')
        ]);

        if($request->hasFile('photo')){

            $count = count($request->file('photo'));

            for ($i = 0; $i < $count; $i++) {
                $file = $request->file('photo')[$i];
                $file->store('gallery/photo');
                $path = $file->hashName('gallery/photo/thumb');
                $resizeThumb = Image::make($file);
                $resizeThumb->fit(375, 375, function ($constraint) {
                    $constraint->aspectRatio();
                });
                Storage::put($path, (string) $resizeThumb->encode());

                $thumb = "storage/".$path;
                $image = "storage/".$file->hashName('gallery/photo');

                Media::create([
                    'name' => $request->title,
                    'image' => $image,
                    'thumb' => $thumb,
                    'description' => $request->description,
                    'type_id' => $gallery->id,
                    'type' => MediaType::GALLERY,
                    'user_id' => \Auth::id(),
                    'public' => 1,
                    'lang' => \Session::get('lang')
                ]);

            }
        }
            return redirect()->route('admin.gallerys.index')->with(['message' => 'Sửa thành công']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {

    }

    public function delMulti(Request $request)
    {
        if(check_admin_systems(SystemsModuleType::VIDEO))

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
            return redirect()->route('admin.gallerys.index')->with(['message'=>'Xóa thành công']);
        }
        return redirect()->route('admin.gallerys.index')->withErrors(['message'=>'LỖi']);
    }

    public function createLang($language="empty", $id=0)
    {
        if(check_admin_systems(SystemsModuleType::GALLERY))
            $lang = $language == 'empty' ? Session::get('lang') : $language;
            $gallery = Gallerys::findOrFail($id);
            $category = [];
        if(Schema::hasTable('gallery_category'))
            $category = \DB::table('gallery_category')->get();

        return view('Admin.Gallery.lang',compact('category','lang','gallery'));
    }

    public function storeLang(Request $request, $language = 'empty', $id=0)
    {
        if(check_admin_systems(SystemsModuleType::GALLERY))

            $request->validate([
                'title' => 'required',
                'alias' => 'required',
            ]);
        $lang = $language == 'empty' ? Session::get('lang') : $language;

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
            $file->store('gallery');
            $path = $file->hashName('gallery/thumb');
            $resizeThumb = Image::make($file);
            $resizeThumb->fit(375, 375, function ($constraint) {
                $constraint->aspectRatio();
            });
            Storage::put($path, (string) $resizeThumb->encode());

            $thumb = "storage/".$path;
            $image = "storage/".$file->hashName('gallery');
        }else{
            $image = null;
            $thumb = null;
        }

        $gallery = Gallerys::create([
            'title' => $request->title,
            'alias' => $request->alias,
            'description' => $request->description,
            'image' => $image,
            'thumb' => $thumb,
            'category_id' => $request->category,
            'user_id' => \Auth::id(),
            'status' => 0,
            'public' => 1,
            'lang' => $lang
        ]);

        if($request->hasFile('photo')){

            $count = count($request->file('photo'));

            for ($i = 0; $i < $count; $i++) {
                $file = $request->file('photo')[$i];
                $file->store('gallery/photo');
                $path = $file->hashName('gallery/photo/thumb');
                $resizeThumb = Image::make($file);
                $resizeThumb->fit(375, 375, function ($constraint) {
                    $constraint->aspectRatio();
                });
                Storage::put($path, (string) $resizeThumb->encode());

                $thumb = "storage/".$path;
                $image = "storage/".$file->hashName('gallery/photo');

                Media::create([
                    'name' => $request->title,
                    'image' => $image,
                    'thumb' => $thumb,
                    'description' => $request->description,
                    'type_id' => $gallery->id,
                    'type' => MediaType::GALLERY,
                    'user_id' => \Auth::id(),
                    'title_seo' => $request->title_seo ? $request->title_seo : $request->title,
                    'description_seo' => $request->description_seo ? $request->description_seo : $request->title,
                    'keyword_seo' => $request->keyword_seo ? $request->keyword_seo : $request->title,
                    'public' => 1,
                    'lang' => \Session::get('lang')
                ]);

            }
        }

        if($id > 0) {
            $old = Gallerys::findOrFail($id);
            add_post_lang($id,$gallery,$old,AliasType::GALLERY,$lang);
        }
        return redirect()->route('admin.gallerys.index')->with(['message' => 'Thêm mới thành công']);
    }
}
