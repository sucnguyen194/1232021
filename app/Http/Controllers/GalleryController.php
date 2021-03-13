<?php namespace App\Http\Controllers\Frontend;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\FHomeModel;
use Session,DB;
use Illuminate\Http\Request;

class GalleryController extends Controller {

	public function index(){
		$user = new FHomeModel();
		$data['list_gallery'] = $user->getData('gallery',['lang'=>Session::get('lang')],'id','desc');
		//dd($data['list_video']);
		return view('frontend.gallery.home',$data);
	}

	}
