<?php namespace App\Http\Controllers\Admin\SiteSetting;

use App\Enums\SystemsModuleType;
use App\Http\Controllers\Controller;
use App\Models\SiteSetting;
use Illuminate\Http\Request;

class SiteOptionController extends Controller {

	public function index(){
      check_admin_systems(SystemsModuleType::SITE_SETTING);

        return view('Admin.SiteSetting.index');

	}
	public function post(Request $request){

        check_admin_systems(SystemsModuleType::SITE_SETTING);

        $request->validate([
            'name' => 'required',
        ]);
        $setting = SiteSetting::whereLang(\Session::get('lang'))->firstOrFail();
        if($request->unlink_logo){
            if(file_exists($setting->logo))
             unlink($setting->logo);
            $logo = null;
        }elseif($request->hasFile('logo')){
            $request->validate([
                'logo' => 'image|mimes:jpeg,png,jpg,gif,svg',
            ]);
            $file = $request->file('logo');
            $file->store('setting/logo');

            $logo = "storage/".$file->hashName('setting/logo');
        }else{
            $logo = $setting->logo;
        }

        if($request->unlink_og){
            if(file_exists($setting->og_image))
                unlink($setting->og_image);
            $og_image = null;
        }elseif($request->hasFile('og_image')){
            $request->validate([
                'og_image' => 'image|mimes:jpeg,png,jpg,gif,svg',
            ]);
            $file = $request->file('og_image');
            $file->store('setting/og_image');

            $og_image = "storage/".$file->hashName('setting/og_image');
        }else{
            $og_image = $setting->og_image;
        }

        if($request->unlink_favicon){
            if(file_exists($setting->favicon))
                unlink($setting->favicon);
            $favicon = null;
        }elseif($request->hasFile('favicon')){
            $request->validate([
                'favicon' => 'image|mimes:jpeg,png,jpg,gif,svg',
            ]);
            $file = $request->file('favicon');
            $file->store('setting/favicon');

            $favicon = "storage/".$file->hashName('setting/favicon');
        }else{
            $favicon = $setting->favicon;
        }

        if($request->unlink_watermark){
            if(file_exists($setting->watermark))
                unlink($setting->watermark);
            $watermark = null;
        }elseif($request->hasFile('watermark')){
            $request->validate([
                'watermark' => 'image|mimes:jpeg,png,jpg,gif,svg',
            ]);
            $file = $request->file('watermark');
            $file->store('setting/watermark');

            $watermark = "storage/".$file->hashName('setting/watermark');
        }else{
            $watermark = $setting->watermark;
        }

        $setting->update([
            'name' => $request->name,
            'company' => $request->company,
            'path' => $request->path,
            'slogan' => $request->slogan,
            'logo' => $logo,
            'og_image' => $og_image,
            'favicon' => $favicon,
            'watermark' => $watermark,
            'email' => $request->email,
            'phone' => $request->phone,
            'facebook' => $request->facebook,
            'facebook_app_ip' => $request->facebook_app_ip,
            'facebook_app_secret' => $request->facebook_app_secret,
            're_captcha_key' => $request->re_captcha_key,
            're_captcha_secret' => $request->re_captcha_secret,
            'messenger' => $request->messenger,
            'google' => $request->google,
            'skype' => $request->skype,
            'youtube' => $request->youtube,
            'twitter' => $request->twitter,
            'ins' => $request->instagram,
            'lin' => $request->linkedin,
            'pin' => $request->pinterest,
            'zalo' => $request->zalo,
            'address' => $request->address,
            'hotline' => $request->hotline,
            'time_open' => $request->time_open,
            'fax' => $request->fax,
            'contact' => $request->contact,
            'footer' => $request->footer,
            'map' => $request->map,
            'numbercall' => $request->numbercall,
            'remarketing_header' => $request->remarketing_header,
            'remarketing_footer' => $request->remarketing_footer,
            'keyword_seo' => $request->keyword_seo,
            'description_seo' => $request->description_seo,
            'lang' => \Session::get('lang'),
        ]);

        return redirect()->route('admin.site.setting')->with(['message' => 'Cập nhật thành công']);
	}
}
