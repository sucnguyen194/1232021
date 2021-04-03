<?php

namespace App\Http\Controllers\Admin;

use App\Enums\AliasType;
use App\Enums\CategoryType;
use App\Enums\Upload;
use App\Http\Controllers\Controller;
use App\Enums\SystemsModuleType;
use App\Models\Alias;
use App\Models\Category;
use App\Models\Lang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'data.name' => 'required',
            'data.type' => 'required'
        ]);

        $category =  new Category();
        $category->forceFill($request->data);
        if(Alias::whereAlias($request->input('data.alias'))->count())
            return flash('Đường dẫn đã tồn tại', 3);

        if(!$request->has('unlink') && $request->hasFile('image')){
            upload_file_image($category, $request->file('image'), 375, 375);
        }
        if(!$request->has('unlink_bg') && $request->hasFile('background')){
            upload_file_image($category, $request->file('background'),null, null, Upload::background);
        }

        $category->save();

        return flash('Thêm mới danh mục thành công!', 1, $category->route);
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
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Category $category)
    {
        $request->validate([
            'data.name' => 'required',
            'data.alias' => 'required',
        ]);

        if(Alias::whereAlias($request->input('data.alias'))->whereNotIn('id',[$category->slug->id])->count())
            return flash('Đường dẫn đã tồn tại', 3);

        $category->forceFill($request->data);

        if($request->unlink){
            if(file_exists($category->image))
                unlink($category->image);

            if(file_exists($category->thumb))
                unlink($category->thumb);

            $category->image = null;
            $category->thumb = null;
        }elseif($request->hasFile('image')){
            upload_file_image($category, $request->file('image'), 375, 375);
        }
        if($request->unlink_bg){
            if(file_exists($category->background))
                unlink($category->background);
            $category->background = null;
        }elseif($request->hasFile('background')){
            upload_file_image($category, $request->file('background'),null, null, Upload::background);
        }
        $category->public = $request->has('public') ? 1 : 0;
        $category->status = $request->has('status') ? 1 : 0;
        $category->user_edit = Auth::id();
        $category->save();

        return flash('Cập nhật thông tin thành công', 1);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function remove($id){
        $category = Category::findOrFail($id);
        $category->delete();
        return flash('Xóa thành công',1);
    }

    public function delete(Request $request){
        if($request->destroy == 'delete'){
            $count = count($request->check_del);
            for($i=0;$i<$count;$i++){
                $id = $request->check_del[$i];
                $category = Category::findOrFail($id);
                $category->delete();
            }
        }
        return flash('Xóa thành công', 1);
    }

    public function lang($language, $id){
        if(!Lang::whereValue($language)->count())
            return flash('Ngôn ngữ chưa được cấu hình', 3);

        $category = Category::findOrFail($id);
        $categories = Category::whereType(CategoryType::POST_CATEGORY)->whereLang($language)->orderbyDesc('id')->get();
        return view('Admin.Category.lang', compact('category','categories','language'));
    }
    public function add(Request $request , $language, $id)
    {
        $request->validate([
            'data.name' => 'required',
            'data.type' => 'required'
        ]);
        $old = Category::findOrFail($id);
        $category =  new Category();
        $category->forceFill($request->data);
        if(Alias::whereAlias($request->input('data.alias'))->count())
            return flash('Đường dẫn đã tồn tại', 3);

        if(!$request->has('unlink') && $request->hasFile('image')){
            upload_file_image($category, $request->file('image'), 375, 375);
        }
        if(!$request->has('unlink_bg') && $request->hasFile('background')){
            upload_file_image($category, $request->file('background'),null, null, Upload::background);
        }
        $category->lang = $language;
        $category->save();

        if($old) {
            add_post_lang($id,$category,$old,$old->type,$language);
        }

        return flash('Thêm mới danh mục thành công!', 1, $category->route);
    }

    public function destroy($id)
    {
        //
    }
}
