<?php namespace App\Http\Controllers;

use App\Enums\ActiveDisable;
use App\Models\Lang;
use App\Models\Media;
use App\Models\Menu;
use App\Models\News;
use App\Models\NewsCategory;
use App\Models\Pages;
use App\Models\SiteSetting;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesCommands;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Session,Cart,Whois,Schema;


abstract class Controller extends BaseController {

    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

	public function __construct(){
		if(Schema::hasTable('users')){
			if(!Session::has('lang')){
			    $lang = Lang::whereStatus(ActiveDisable::ACTIVE)->first();
				$value = $lang ? $lang->value : config('app.locale');
				Session::put('lang',$value);
			}
            $setting = SiteSetting::langs()->first();
            view()->share('setting',$setting);
		}
	}
}
