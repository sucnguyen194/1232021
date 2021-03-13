<?php

namespace App\Http\Controllers\Admin\User;

use App\Enums\SystemsModuleType;
use App\Http\Controllers\Controller;
use App\Enums\LeverUser;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\Import;
use App\Models\Order;
use App\Models\SystemsModule;
use App\Models\Transaction;
use App\Models\User;
use App\Models\UserModuleSystems;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;

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
        if(check_admin_systems($this->type))

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
        if(check_admin_systems($this->type))
            $systems = SystemsModule::orderby('sort','asc')->get();

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
        if(check_admin_systems($this->type)){

            $avata = NULL;
            if($request->hasFile('image')){
                $request->validate([
                    'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg',
                ]);

                $file = $request->file('image');
                $file->store('user');
                $avata = "storage/".$file->hashName('user');
            }
            $password = sha1(md5($request->password));
            $re_password = sha1(md5($request->re_password));

            if($password != $re_password)

                return redirect()->back()->withInput()->withErrors(['message' => 'Mật khẩu không khớp!']);

            $user = User::create([
                'name' => $request->name,
                'account' => $request->account,
                'email' => $request->email,
                'lever' => $request->lever,
                'phone' => $request->phone,
                'address' => $request->address,
                'avata' => $avata,
                'password' => $password,
            ]);

            $type = $request->type;

            if(isset($type) && sizeof($type) > 0 && $type){
                for($i = 0; $i < sizeof($type); $i++){
                    UserModuleSystems::create([
                        'user_id' =>$user->id,
                        'type' => $type[$i],
                    ]);
                }
            }
        return redirect()->route('admin.user.index')->with(['message'=>'Thêm người dùng thành công!']);
        }
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
            if(check_admin_systems($this->type))

            if($user->lever < Auth::user()->lever)
                return redirect()->route('admin.user.index')->withErrors('Lỗi');

                $systems = SystemsModule::orderby('sort','asc')->get();

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
                return redirect()->route('admin.user.index')->withErrors('Lỗi');

            $avata = $user->avata;
            if($request->hasFile('image')){

                if(file_exists($user->avata))
                    unlink($user->avata);

                $request->validate([
                    'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg',
                ]);
                $file = $request->file('image');
                $file->store('user');
                $avata = "storage/".$file->hashName('user');
            }

            $password = $request->password ? sha1(md5($request->password)) : $user->password;
            $re_password = $request->re_password ? sha1(md5($request->re_password)) : $user->password;

            if($password != $re_password)
                return redirect()->back()->withInput()->withErrors(['message'=>'Mật khẩu không khớp!']);

            $user->update([
                'name' => $request->name,
                'account' => $request->account,
                'email' => $request->email,
                'lever' => $request->lever,
                'phone' => $request->phone,
                'address' => $request->address,
                'avata' => $avata,
                'password' => $password,
            ]);
            $user->systemsModule()->delete();
            if(isset($request->type) && sizeof($request->type) > 0){
                for($i = 0; $i < sizeof($request->type); $i++){
                    UserModuleSystems::create([
                        'user_id' =>$user->id,
                        'type' => $request->type[$i],
                    ]);
                }
            }
            return redirect()->route('admin.user.index')->with(['message'=>'Cập nhật thông tin thành công!']);
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

        return redirect()->route('admin.user.index')->with(['message'=>'Xóa thành công!']);
    }

}
