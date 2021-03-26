<?php namespace App\Http\Controllers;
use App\Enums\LeverUser;
use App\Models\SiteSetting;
use App\Models\SocialIdentity;
use App\Models\User;
use Auth;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cookie;
use Laravel\Socialite\Facades\Socialite;
use Psy\Util\Str;
use Session,Schema,DB,Artisan,Mail;
use Illuminate\Http\Request;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UserController extends Controller {
	public function getLogin(){

		if(Auth::check())
			return redirect()->route('home');

        return view('User.login');
	}
	public function getInfo(){
	    if(Auth::check())
            return view('User.info');

        return redirect()->route('user.login')->withInput('message','Vui lòng đăng nhập trước khi kiểm tra thông tin');
	}
	public function postEditUser(Request $request){
		$account = $request->account;
		$password = $request->password;
		$re_password = $request->re_password;
		$email = $request->email;
		$name = $request->name;
		$phone = $request->phone;
		$address = $request->address;
		if($account){

		    $getUser = User::where('account',$account)->orwhere('email',$account)->first();

			if(!User::where('account',$account)->whereNotIn('id',$getUser->id)->count())
				return redirect()->back()->withInput()->withErrors(['message' => 'Tài khoản đã tồn tại']);

			if(!User::where('email',$account)->whereNotIn('id',$getUser->id)->count())
                return redirect()->back()->withInput()->withErrors(['message' => 'Email đã tồn tại']);

				if($password == NULL && $re_password== NULL){
					$password = $getUser->password;
					$re_password = $getUser->password;
				}else{
					$password = sha1(md5($request->password));
					$re_password = sha1(md5($request->re_password));
				}
				if($password != $re_password){
                    return redirect()->back()->withInput()->withErrors(['message' => 'Mật khẩu không khớp']);
				}else{

				$user = User::update([
                        'account' => $account,
                        'password' => $password,
                        'name' => $name,
                        'address' => $address,
                        'phone' => $phone,
                        'email' => $email,
                    ]);

					Auth::login($user);
				}
				return redirect()->back()->withInput()->with(['message' => 'Sửa thông tin thành công']);
			}
	}
	public function getRegister(){
		if(Auth::check())
            return redirect('user');
        return view('User.register');
	}
	public function postRegister(Request $request){
		if($request->account){
			$account = $request->email;
			$name = $request->name;
			$check = User::whereAccount($account)->orWhereEmail($account)->count();
			if($check >0){
                return redirect()->back()->withInput()->withErrors(['message' => 'Tài khoản hoặc email đã tồn tại']);
			}else{
				$password = sha1(md5($request->password));
				$re_password = sha1(md5($request->re_password));
				if($password != $re_password){
					return redirect()->back()->withInput()->withErrors(['message' => 'Mật khẩu không khớp']);
				}
				User::create([
				    'name' => $name,
                    'account' => $account,
                    'password' => $password,
                    'email' => $account,
                    'level' => LeverUser::USER,
                ]);
				return redirect()->route('user.login')->withInput()->with(['message' => 'Sửa thông tin thành công']);
			}
		}
	}
	public function postLogin(Request $request){
        if($request->account){
            $account = $request->account;
            $password = sha1(md5($request->password));

            if(!User::whereAccount($account)->wherePassword($password)->count()){

                if(!User::whereEmail($account)->wherePassword($password)->count()){

                    return redirect()->back()->withInput()->withErrors(['message'=> 'Sai tên đăng nhập hoặc mật khẩu!']);
                }else{
                    $user = User::whereEmail($account)->first();
                    Auth::login($user,true);

                    return redirect()->back()->withInput()->with(['message' => 'Đăng nhập thành công!']);
                }
            }else{

                $user = User::whereAccount($account)->first();
                if(isset($request->remember)){
                    Auth::login($user, true);
                }else{
                    Auth::login($user);
                }
                return redirect()->back()->with(['message' => 'Đăng nhập thành công!']);
            }
            return redirect()->back()->withInput()->withErrors(['message' => 'Sai tên đăng nhập hoặc mật khẩu!']);
        }
	}
	public function getForgetUser(){
		return view('User.forget');
	}
	public function postForgetUser(Request $request){
	    $request->validate([
	        'email' => 'required|email',
        ]);
		$email = $request->email;
		$setting = SiteSetting::langs()->first();
		if($email){
            $user = User::whereEmail($email)->first();
			if($user){
				$code = Str::upper(Str::random(10));
				$time = time();
				$password = sha1(md5($code));
                $user->update([
                    'email' => $email,
                    '_token' => $password,
                ]);
				$arr = [
					'code' =>$code,
					'time' =>$time,
					'password' =>$password,
					'email' =>$email,
				];
				Mail::send('Emails.password',$arr,function($msg) use ($email, $setting){
					$msg->from(env('MAIL_USERNAME'),'Thông báo');
					$msg->to($email)->subject($setting->name);
				});
				return back()->with(['message' => 'Mật khẩu mới đã được gửi tới email'.$email.'. Quý khách vui lòng kiểm tra email.' ]);
			}else{
                return back()->withErrors(['message' => 'Lỗi! Email không chính xác. Vui lòng kiểm tra lại thông tin']);
			}
		}else{
            return back()->withErrors(['message' => 'Lỗi! Vui lòng kiểm tra lại thông tin']);
		}
		return view('User.forget');
	}
	public function getPasswordReset(Request $request){
		$email = $request->email;
		$password = $request->_token;
		if($email && $password){
		    $user = User::whereEmail($email)->where('_token', $password)->first();
            $code = sha1(md5(Str::upper(Str::random(10))));
			if($user){
			    $user->update([
                    'email' => $email,
                    'password' => $password,
                    '_token'=> $code
                ]);
				Auth::login($user, true);
				return redirect()->back()->with(['message' => 'Xin chào '.$email.' Mật khẩu mới đã được cấp nhật thành công!']);
			}else{
				return redirect()->back()->withErrors(['message' => 'Lỗi! Vui lòng kiểm tra lại thông tin']);
			}
		}else{
            return redirect()->back()->withErrors(['message' => 'Lỗi! Vui lòng kiểm tra lại thông tin']);
		}
	}

	public function logout(){
		Auth::logout();
		return redirect()->route('user.login');
	}

    public function redirect($provider)
    {
        return Socialite::driver($provider)->redirect();
    }

    public function callback($provider)
    {
        try {
            $info = Socialite::driver($provider)->user();
        } catch (\Exception $e) {
            return redirect()->route('user.login');
        }
        if(!$info->getEmail())
            return redirect()->route('user.register')->withErrors(['message' => 'Tài khoản chưa có email. Vui lòng đăng ký tại đây!']);
        $user = $this->createUser($info,$provider);
        Auth::login($user, true);

        if(Auth::user()->lever <= 1)
            return  redirect()->route('admin.dashboard')->withInput()->with(['message' => 'Đăng nhập thành công!']);

        return redirect()->route('home')->with(['message' => 'Đăng nhập thành công!']);
    }

    public function createUser($info,$provider){

        $account = SocialIdentity::whereProviderName($provider)
            ->whereProviderId($info->getId())
            ->latest()->first();

        if ($account) {
            return $account->user;
        } else {
            $email = $info->getEmail();
            $user = User::whereEmail($email)->first();
            if(!$user){
                $user = new User();
                $user = $user->forceFill(
                    [
                        'email' => $email,
                        'name' => $info->getName(),
                        'lever' => LeverUser::USER,
                        'email_verified_at' => now(),
                    ]
                );
                $user->save();
            }
            $user->identities()->updateOrCreate(
                [
                    'provider_id' => $info->getId(),
                    'provider_name' => $provider,
                ]
            );

            return $user;
        }
    }
}
