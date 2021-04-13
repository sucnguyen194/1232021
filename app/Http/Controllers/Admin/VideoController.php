<?php namespace App\Http\Controllers\Admin;

use App\Enums\CategoryType;
use App\Enums\SystemsModuleType;
use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Lang;
use App\Models\Product;
use App\Models\User;
use Session;

class VideoController extends Controller {

	public function index(){
	    $type = SystemsModuleType::VIDEO;
        authorize($type);
        $lang = isset(request()->lang) ? request()->lang : Session::get('lang');
        $videos = Product::with('category')->whereType($type)->where('lang',$lang)
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
//            ->when(\request()->category,function($q){
//                $category = \request()->category == -1 ? 0 : \request()->category;
//                $q->where('category_id',$category)
//                    ->orwhereHas('categories')->when(\request()->category,function($q, $category){
//                        $q->where('category_id',$category);
//                    });
//            })
            ->orderByDesc('created_at')->get();
        //$categories = Category::whereLang($lang)->whereType(CategoryType::PRODUCT_CATEGORY)->public()->get();
        $langs =  Lang::get();
        $users = User::where('lever','>=',\Auth::user()->lever)->get();

        return view('Admin.Video.index',compact('videos','langs','users'));
	}

	public function create(){
        authorize(SystemsModuleType::VIDEO);

        return view('Admin.Video.create');
    }

	public function edit(Product $video){
        authorize(SystemsModuleType::VIDEO);
        if($video->postLangsBefore){
            $id = array_unique($video->postLangsBefore->pluck('post_id')->toArray());
            $posts = Product::whereIn('id',$id)->get()->load('language');
            $langs = Lang::whereNotIn('value',$posts->pluck('lang'))->where('value','<>',$video->lang)->get();
        }else{
            $posts = null;
            $langs = Lang::where('value','<>',$video->lang)->get();
        }
       return view('Admin.Video.edit', compact('video','posts','langs'));
    }

    public function lang($lang, $id){
        authorize(SystemsModuleType::VIDEO);
        if(!Lang::whereValue($lang)->count())
            return flash('Ngôn ngữ chưa được cấu hình', 3);
        $video = Product::findOrFail($id);

        return view('Admin.Video.lang',compact('video', 'lang'));
    }
}
