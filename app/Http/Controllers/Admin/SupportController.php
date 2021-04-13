<?php

namespace App\Http\Controllers\Admin;

use App\Enums\SystemsModuleType;
use App\Http\Controllers\Controller;
use App\Models\Lang;
use App\Models\Support;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;
use Session;

class SupportController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $type = SystemsModuleType::SUPPORT;
        authorize($type);

        $lang = isset(request()->lang) ? request()->lang : Session::get('lang');
        $supports = Support::where('lang',$lang)->whereType($type)
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

        $langs =  Lang::get();

        $users = User::where('lever','>=',\Auth::user()->lever)->get();

        return view('Admin.Support.index',compact('supports','langs','users'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        authorize(SystemsModuleType::SUPPORT);

        return view('Admin.Support.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        authorize(SystemsModuleType::SUPPORT) || authorize(SystemsModuleType::CUSTOMER);

        Validator::make($request->data, [
            'name' => 'required',
            'description' => 'required',
        ])->validate();
        $support = new Support();
        $support->forceFill($request->data);
        if(!$request->unlink && $request->hasFile('image')){
            upload_file_image($support, $request->file('image'));
        }

        $support->save();

        return flash('Thêm mới thành công!',1, $support->route);
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
    public function edit(Support $support)
    {
        authorize(SystemsModuleType::CUSTOMER);

        return view('Admin.Support.edit',compact('support'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Support $support)
    {
        authorize(SystemsModuleType::SUPPORT) || authorize(SystemsModuleType::CUSTOMER);

         Validator::make($request->data, [
             'name' => 'required',
             'description' => 'required',
         ])->validate();

        if($request->unlink){
            if(File::exists($support->image))
                File::delete($support->image);
        }elseif($request->hasFile('image')){
            if(File::exists($support->image))
                File::delete($support->image);
            upload_file_image($support, $request->file('image'));
        }
        $support->public = $request->has('public') ? 1 : 0;
        $support->status = $request->has('status') ? 1 : 0;
        $support->user_edit = auth()->id();
        $support->save();

        return flash('Cập nhật thành công!', 1);
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
    public function remove($id){
        authorize(SystemsModuleType::SUPPORT) || authorize(SystemsModuleType::CUSTOMER);
        $support = Support::findOrFail($id);
        $support->delete();

        return flash('Xóa thành công!', 1);
    }

    public function delete(Request $request)
    {
        authorize(SystemsModuleType::SUPPORT) || authorize(SystemsModuleType::CUSTOMER);

        if($request->destroy == 'delete'){
            $count = count($request->check_del);
            for($i=0;$i<$count;$i++){
                $id = $request->check_del[$i];
                $support = Support::findOrFail($id);
                $support->delete();
            }
            return flash('Xóa thành công!', 1);
        }
    }
}
