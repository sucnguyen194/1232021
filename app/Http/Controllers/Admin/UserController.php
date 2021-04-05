<?php

namespace App\Http\Controllers\Admin;

use App\Enums\SystemsModuleType;
use App\Enums\Upload;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\System;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public $type = SystemsModuleType::USER;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        check_admin_systems($this->type);

            $user = User::when(request()->id,function ($q,$id){
                $q->where('id',$id);
            })
            ->where('lever','>=',Auth::user()->lever)
            ->get();

            return view('Admin.User.list',compact('user'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        check_admin_systems($this->type);
            $systems = System::orderby('sort','asc')->get();

            return view('Admin.User.add',compact('systems'));

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreUserRequest $request)
    {
        check_admin_systems($this->type);
        $user = new User();
        $user->forceFill($request->data);

        if($request->hasFile('image')){
            upload_file_image($user,$request->file('image'), null,null, Upload::avata);
        }
        $password = $request->password;
        $re_password = Hash::make($request->re_password);
        if(!Hash::check($password, $re_password))
            return flash('Mất khẩu không khớp!', 3);

        $user->password = sha1(md5($password));
        $user->save();

        $user->systems()->attach($request->system);

        return flash('Thêm tài khoản thành công!', 1 , route('admin.users.index'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        check_admin_systems($this->type);
        $transaction = Transaction::whereUserId($user->id)
            ->when(date_range(),function ($q, $date){
                $q->whereBetween('created_at', [$date['from']->startOfDay(), $date['to']->endOfDay()]);
            })
            ->orderByDesc('created_at')
            ->get();

        return  view('Admin.User.transaction',compact('user','transaction'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        check_admin_systems($this->type);

            if($user->lever < Auth::user()->lever)
                return flash('Lỗi', 3);

                $systems = System::orderby('sort','asc')->get();

            return view('Admin.User.edit',compact('systems','user'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateUserRequest $request, User $user)
    {
       check_admin_systems($this->type);

            if($user->lever < Auth::user()->lever)
                return flash('Lỗi', 0, route('admin.users.index'));

            $user->forceFill($request->data);
            if($request->hasFile('image')){
                File::delete($user->avata);
                upload_file_image($user, $request->input('image'), null,null, Upload::avata);
            }
            if($request->password){
                $password = $request->password;
                $re_password = Hash::make($request->re_password);

                if(!Hash::check($password, $re_password))
                    return flash('Mật khẩu không khớp', 3);

                $user->password = sha1(md5($request->password));
            }
           $user->save();

           if(auth()->id() == $user->id){
               Auth::login($user, true);
           }

           $user->systems()->sync($request->system);

           return  flash('Cập nhật thông tin thành công!', 1);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        check_admin_systems($this->type);
        $user->delete();
        return flash('Xóa thành công', 1);
    }

}
