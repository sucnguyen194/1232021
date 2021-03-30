<?php

namespace App\Http\Controllers\Admin\Media;

use App\Enums\MediaType;
use App\Enums\SystemsModuleType;
use App\Http\Controllers\Controller;
use App\Models\Media;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Image, Schema;

class MediaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(!check_admin_systems(SystemsModuleType::MEDIA))
            return redirect()->back()->withErrors(['message'=>'Bạn không thể thực hiện hành động này!']);

        $media = Media::whereType(MediaType::MEDIA)
            ->when(\request()->position,function($q, $position){
            $q->where('position',$position);
            })
            ->orderByDesc('updated_at')
            ->get();
        $position = [];
        if(Schema::hasTable('position_image'))
            $position = \DB::table('position_image')->orderby('id','desc')->get();

        return view('Admin.Image.list',compact('media','position'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if(check_admin_systems(SystemsModuleType::MEDIA))

            $position = [];
        if(Schema::hasTable('position_image'))
            $position = \DB::table('position_image')->orderby('id','desc')->get();
            $media = Media::whereType(MediaType::MEDIA)
                ->when(\request()->position,function($q, $position){
                    $q->where('position',$position);
                })
                ->orderByDesc('updated_at')
                ->get();
        return view('Admin.Image.add',compact('position','media'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        if(check_admin_systems(SystemsModuleType::MEDIA))

        $checkFile = $request->checkFile ?? null;
        $count = $count = count($request->file('image'));

        if($request->hasFile('image') && $checkFile){
            for ($i = 0; $i < $count; $i++) {
                $file = $request->file('image')[$i];
                $name = $file->getClientOriginalName();
                if(in_array($name, $checkFile)){
                    $file->store('media');
                    $path = $file->hashName('media/thumb');
                    $resizeThumb = Image::make($file);
                    $resizeThumb->fit(375, 375, function ($constraint) {
                        $constraint->aspectRatio();
                    });
                    Storage::put($path, (string) $resizeThumb->encode());

                    $thumb = "storage/".$path;
                    $image = "storage/".$file->hashName('media');
                    Media::create([
                        'name' => $request->name,
                        'path' => $request->path,
                        'image' => $image,
                        'thumb' => $thumb,
                        'position' => $request->position,
                        'description' => $request->description,
                        'type_id' => 0,
                        'type' => MediaType::MEDIA,
                        'user_id' => \Auth::id(),
                        'public' => $request->has('public') ? 1 : 0,
                        'lang' => \Session::get('lang')
                    ]);
                }
            }
        }else{
            Media::create([
                'name' => $request->name,
                'path' => $request->path,
                'image' => null,
                'thumb' => null,
                'position' => $request->position,
                'description' => $request->description,
                'type_id' => 0,
                'type' => MediaType::MEDIA,
                'user_id' => \Auth::id(),
                'public' => 1,
                'lang' => \Session::get('lang')
            ]);
        }

        return redirect()->route('admin.media.index')->with(['message' => 'Thêm mới thành công']);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return  abort(404);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if(check_admin_systems(SystemsModuleType::MEDIA))

            $media = Media::findOrFail($id);

            $list = Media::whereType(MediaType::MEDIA)
            ->where('id', '<>',$id)
            ->orderByDesc('id')
            ->get();

        $position = [];
        if(Schema::hasTable('position_image'))
            $position = \DB::table('position_image')->orderby('id','desc')->get();

        return view('Admin.Image.edit',compact('position','media','list'));
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
        if(check_admin_systems(SystemsModuleType::MEDIA))

            $media = Media::findOrFail($id);

        if($request->hasFile('image')){
            if (file_exists($media->image)) {
                unlink($media->image);
            }
            if (file_exists($media->thumb)) {
                unlink($media->thumb);
            }
            $file = $request->file('image');
            $file->store('media');
            $path = $file->hashName('media/thumb');
            $resizeThumb = Image::make($file);
            $resizeThumb->fit(375, 375, function ($constraint) {
                $constraint->aspectRatio();
            });
            Storage::put($path, (string) $resizeThumb->encode());

            $thumb = "storage/".$path;
            $image = "storage/".$file->hashName('media');
        }else{
            $thumb = $media->thumb;
            $image = $media->image;
        }
        $media->update([
            'name' => $request->name,
            'path' => $request->path,
            'image' => $image,
            'thumb' => $thumb,
            'public' => $request->has('public') ? 1 : 0,
            'position' => $request->position,
            'description' => $request->description,
            'user_edit' => \Auth::id(),
        ]);
        switch ($media->type) {
            case MediaType::GALLERY:
                return  redirect()->route('admin.gallerys.edit',$media->type_id)->with(['message' => 'Sửa thành công']);
            break;

            default:
                return  redirect()->route('admin.media.index')->with(['message' => 'Sửa thành công']);
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
        if(check_admin_systems(SystemsModuleType::MEDIA))

            $media = Media::findOrFail($id);

            if(file_exists($media->image))
                unlink($media->image);
            if (file_exists($media->thumb))
                unlink($media->thumb);

            $media->delete();

            return redirect()->back()->withInput()->with(['message' => 'Xóa thành công']);
    }
}
