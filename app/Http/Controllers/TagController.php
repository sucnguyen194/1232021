<?php namespace App\Http\Controllers\Frontend;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\FNewsModel;
use Illuminate\Http\Request;

class TagController extends Controller {

	public function index($alias){
		$user = new FNewsModel();
		$data['news'] = $user->getNewsByTags($alias);
		$data['product'] = $user->getProductByTags($alias);
		return view('frontend.search.tags',$data);
	}

}
