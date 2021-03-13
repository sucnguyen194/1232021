<?php namespace App\Http\Controllers\Frontend;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use DB,Session,Cart,Whois,Mail,Socialite,Image;
use App\Models\FHomeModel;
use App\Models\FProductModel;
use App\Models\FNewsModel;
use Illuminate\Http\Request;
class SearchController extends Controller {

    public function index(Request $request){
        return view('Search.search');
    }
}
