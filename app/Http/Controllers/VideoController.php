<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\FHomeModel;
use Session,DB;
use Illuminate\Http\Request;

class VideoController extends Controller {

	public function index(){
		$user = new FHomeModel();
		$data['list_video'] = $user->getData('video',['lang'=>Session::get('lang')],'id','desc');
		//dd($data['list_video']);
		return view('frontend.video.home_video',$data);
	}
	public function home(){
		$user = new FHomeModel();
		$data['list_cate'] = $user->getData('product_category',['lang'=>Session::get('lang')],'sort','desc');
		//dd($data['list_video']);
		return view('frontend.product.product_home_category',$data);
	}

	public function category(){

    }
}
