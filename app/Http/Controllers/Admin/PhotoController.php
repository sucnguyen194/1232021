<?php

namespace App\Http\Controllers\Admin;

use App\Enums\MediaType;
use App\Enums\SystemsModuleType;
use App\Http\Controllers\Controller;
use App\models\Photo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class PhotoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        authorize(SystemsModuleType::PHOTO);

        $photos = Photo::whereType(MediaType::PHOTO)->when(\request()->position, function($q, $position){
            $q->wherePosition($position);
        })->public()->get();

        $positions = [];
        if(Schema::hasTable('position_image'))
            $positions = \DB::table('position_image')->orderby('id','desc')->get();

        return  view('Admin.Photo.index',compact('photos','positions'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        authorize(SystemsModuleType::PHOTO);
        $positions = [];
        if(Schema::hasTable('position_image'))
            $positions = \DB::table('position_image')->orderby('id','desc')->get();

        return  view('Admin.Photo.create',compact('positions'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        authorize(SystemsModuleType::PHOTO);
        $checkFile = $request->checkFile ?? null;
        if($request->hasFile('image') && $checkFile){
            $count = count($request->file('image'));
            for($i=0; $i < $count; $i++){
                $file = $request->file('image')[$i];
                $name = $file->getClientOriginalName();
                if(in_array($name, $checkFile)){
                    $photo = new Photo();
                    $photo->forceFill($request->data);
                    upload_file_image($photo,$file, 375, 375);
                    $photo->public = 1;
                    $photo->save();
                }
            }
        }else{
            $photo = new Photo();
            $photo->forceFill($request->data);
            $photo->public = 1;
            $photo->save();
        }
      return flash('Thêm mới thành công!', 1, route('admin.photos.index'));
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
    public function edit(Photo $photo)
    {
        authorize(SystemsModuleType::PHOTO);
        $positions = [];
        if(Schema::hasTable('position_image'))
            $positions = \DB::table('position_image')->orderby('id','desc')->get();
        return  view('Admin.Photo.edit',compact('photo','positions'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Photo $photo)
    {
        authorize(SystemsModuleType::PHOTO);

        $photo->forceFill($request->data);
        if($request->hasFile('image')){
            upload_file_image($photo, $request->file('image'), 375, 375);
        }
        $photo->user_edit = auth()->id();
        $photo->save();

        return flash('Cập nhật thành công', 1);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Photo $photo)
    {
        authorize(SystemsModuleType::PHOTO);
        $photo->delete();
        return flash('Xóa thành công!', 1);
    }
}
