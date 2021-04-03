<?php namespace App\Http\Controllers\Admin\Login;
use App\Enums\LeverUser;
use App\Http\Requests\FirstUserRequest;
use App\Models\User;
use Auth;
use App\Http\Requests\UserRequest;
use App\Http\Controllers\Controller;
use Schema,DB,Artisan;


class AdminController extends Controller {

	public function getLogin(){

	    if(User::count() == 0)
	        return redirect()->route('admin.first.user');

	    if(!Auth::check())
	        return view('Admin.Login.login');

	    if(Auth::user()->lever <= \App\Enums\LeverUser::ADMIN)

	        return redirect()->route('admin.dashboard');
	}

	public function postLogin(UserRequest $request){

		if($request->account){
			$account = $request->account;
			$password = sha1(md5($request->password));

			if(!User::whereAccount($account)->wherePassword($password)->count()){

				if(!User::whereEmail($account)->wherePassword($password)->count()){
                    return flash('Sai tên đăng nhập hoặc mật khẩu!', 3);
				}else{
				    $user = User::whereEmail($account)->first();
					Auth::login($user,true);

					if(Auth::user()->lever > LeverUser::ADMIN){
                        return  flash('Đăng nhập thành công!', 1,route('home') );
                    }
                    return flash('Đăng nhập thành công!', 1);
				}
			}else{

                $user = User::whereAccount($account)->first();
                if(isset($request->remember)){
                    Auth::login($user, true);
                }else{
                    Auth::login($user);
                }
                if(Auth::user()->lever > LeverUser::ADMIN){
                    return  flash('Đăng nhập thành công!', 1,route('home') );
                }
                return flash('Đăng nhập thành công!', 1);
			}
            return  flash('Sai tên đăng nhập hoặc mật khẩu!', 3);
		}
	}
	public function logout(){

		Auth::logout();
		return redirect()->route('admin.login');
	}
	public function getFirstUse(){
		if(Schema::hasTable('users')){
            return redirect()->route('admin.login');
		}else{
			return view('Admin.Login.firstUse');
		}
	}
	public function postFirstUse(FirstUserRequest $request){

		$password = $request->password;
		$re_password = $request->re_password;

		if($password != $re_password){
            return  flash('Mật khẩu nhập lại không đúng!', 3);

			$password = sha1(md5($password));
			if(Schema::hasTable('module')){
				$module = DB::table('module')->get();
				if(count($module) > 0){
					foreach($module as $items){
						if(Schema::hasTable($items->table_name)){
							Schema::drop($items->table_name);
						}
						if(is_dir('public/upload/'.$items->table_name)){
							rmdir('public/upload/'.$items->table_name);
						}
					}
				}
			}

			$exitCode = Artisan::call('migrate:refresh', ['--force' => true,]);
			$migrate = Artisan::call('migrate', ['--path' => 'database/migrations']);
			$db_seed = Artisan::call('db:seed');

			User::create([
                'account' => $request->name,
                'name' => $request->account,
                'password' => $password,
                'level' => LeverUser::SUPPERADMIN,
                'email' => $request->email,
            ]);

			$user = User::whereAccount($request->account)->first();
			Auth::login($user, true);

			return redirect()->route('admin.generate');
		}
	}
}
