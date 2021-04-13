<?php

namespace App\Http\Controllers\Admin;

use App\Enums\Upload;
use App\Http\Controllers\Controller;
use App\Enums\SystemsModuleType;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;

class SettingController extends Controller
{
    public $type = SystemsModuleType::SETTING;

    public function index(){
        authorize($this->type);
        return view('Admin.Setting.index');
    }

    public function update(Request $request){
        authorize($this->type);

        Validator::make($request->data,[
            'name' => 'required',
        ])->validate();

        $setting = Setting::langs()->firstOrFail();
        $setting->forceFill($request->data);

        if($request->unlink_logo){
            File::delete($setting->logo);
            $setting->logo =  null;
        }elseif($request->hasFile(Upload::logo)){
          File::delete($setting->logo);
          $file = $request->file(Upload::logo);
          upload_file_image($setting, $file,null, null, Upload::logo);
        }
        if($request->unlink_og){
            File::delete($setting->og_image);
            $setting->unlink_og =  null;
        }elseif($request->hasFile(Upload::og_image)){
            File::delete($setting->og_image);
            $file = $request->file(Upload::og_image);
            upload_file_image($setting, $file,null, null, Upload::og_image);
        }
        if($request->unlink_favicon){
            File::delete($setting->favicon);
            $setting->favicon =  null;
        }elseif($request->hasFile(Upload::favicon)){
            File::delete($setting->favicon);
            $file = $request->file(Upload::favicon);
            upload_file_image($setting, $file,null, null, Upload::favicon);
        }
        $setting->lang = $setting->lang ? $setting->lang : session()->get('lang');
        $setting->save();
        session()->put('setting', $setting);

     return  flash('Cập nhật thành công!',1);
    }
}
