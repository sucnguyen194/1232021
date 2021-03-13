<?php

namespace App\Http\Controllers\Admin\Support;

use App\Enums\SystemsModuleType;
use App\Http\Controllers\Controller;
use App\Models\Support;
use App\Models\User;
use Illuminate\Http\Request;
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
        if(!check_admin_systems(SystemsModuleType::SUPPORT))
            return redirect()->back()->withErrors(['message'=>'Bạn không thể thực hiện hành động này!']);

        $lang = isset(request()->lang) ? request()->lang : Session::get('lang');

        $support = Support::where('lang',$lang)
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

        $user = User::where('lever','>=',\Auth::user()->lever)->get();

        return view('Admin.Support.list',compact('support','lang','user'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if(!check_admin_systems(SystemsModuleType::SUPPORT))
            return redirect()->back()->withErrors(['message'=>'Bạn không thể thực hiện hành động này!']);

        return view('Admin.Support.add');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if(!check_admin_systems(SystemsModuleType::SUPPORT))
            return redirect()->back()->withErrors(['message'=>'Bạn không thể thực hiện hành động này!']);

        $request->validate([
            'name' => 'required',
            'description' => 'required',
        ]);

        if($request->unlink){
            $image = null;
        }elseif($request->hasFile('image')){
            $request->validate([
                'image' => 'image|mimes:jpeg,png,jpg,gif,svg',
            ]);
            $file = $request->file('image');
            $file->store('support');
            $image = "storage/".$file->hashName('support');
        }else{
            $image = null;
        }
        Support::create([
            'name' => $request->name,
            'image' => $image,
            'description' => $request->description,
            'job' => $request->job,
            'hotline' => $request->phone,
            'email' => $request->email,
            'address' => $request->address,
            'skype' => $request->skype,
            'zalo' => $request->zalo,
            'facebook' => $request->facebook,
            'twitter' => $request->twitter,
            'instagram' => $request->instagram,
            'youtube' => $request->youtube,
            'user_id' => \Auth::id(),
            'lang' => Session::get('lang'),
            'public' => $request->public ? 1 : 0,
            'status' => $request->status ? 1: 0,
        ]);
        return redirect()->route('admin.support.index')->with(['message' => 'Thêm mới thành công!']);
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
        if(!check_admin_systems(SystemsModuleType::CUSTOMER_COMMENT))
            return redirect()->back()->withErrors(['message'=>'Bạn không thể thực hiện hành động này!']);

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
        if(!check_admin_systems(SystemsModuleType::SUPPORT))
            return redirect()->back()->withErrors(['message'=>'Bạn không thể thực hiện hành động này!']);

        $request->validate([
            'name' => 'required',
            'description' => 'required',
        ]);

        if($request->unlink){
            if(file_exists($support->image))
                unlink($support->image);
            $image = null;
        }elseif($request->hasFile('image')){
            $request->validate([
                'image' => 'image|mimes:jpeg,png,jpg,gif,svg',
            ]);
            $file = $request->file('image');
            $file->store('support');
            $image = "storage/".$file->hashName('support');
        }else{
            $image = $support->image;
        }

        $support->update([
            'name' => $request->name,
            'image' => $image,
            'description' => $request->description,
            'job' => $request->job,
            'hotline' => $request->phone,
            'email' => $request->email,
            'address' => $request->address,
            'skype' => $request->skype,
            'zalo' => $request->zalo,
            'facebook' => $request->facebook,
            'twitter' => $request->twitter,
            'instagram' => $request->instagram,
            'youtube' => $request->youtube,
            'user_edit' => \Auth::id(),
            'lang' => Session::get('lang'),
            'public' => $request->public ? 1 : 0,
            'status' => $request->status ? 1: 0,
        ]);

        return redirect()->route('admin.support.index')->with(['message' => 'Sửa thành công!']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if(!check_admin_systems(SystemsModuleType::SUPPORT))
            return redirect()->back()->withErrors(['message'=>'Bạn không thể thực hiện hành động này!']);

        $support = Support::findOrFail($id);

        if(file_exists($support->image))
            unlink($support->image);

        $support->delete();

        return redirect()->route('admin.support.index')->with(['message'=>'Xóa thành công']);
    }

    public function delMulti(Request $request)
    {
        if(!check_admin_systems(SystemsModuleType::CUSTOMER_COMMENT))
            return redirect()->back()->withErrors(['message'=>'Errors']);
        if($request->delall == 'delete'){

            $count = count($request->check_del);
            for($i=0;$i<$count;$i++){
                $id = $request->check_del[$i];

                $support = Support::findOrFail($id);

                if(file_exists($support->image))
                    unlink($support->image);

                $support->delete();

            }
            return redirect()->route('admin.support.index')->with(['message'=>'Xóa thành công']);
        }
        return redirect()->route('admin.support.index')->withErrors(['message'=>'LỖi']);
    }
}
