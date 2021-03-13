<?php

namespace App\Http\Controllers;

use App\Enums\AliasType;
use App\Models\Alias;
use App\Models\News;
use App\Models\NewsCategory;
use App\Models\Pages;
use App\Models\Tags;
use Illuminate\Http\Request;
use Session;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
//    public function __construct()
//    {
//        $this->middleware('auth');
//    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('Layouts.home');
    }

    public function getAlias($alias)
    {
        $object = Alias::whereAlias($alias)->firstOrFail();

        switch ($object->type) {
            case (AliasType::PRODUCT);
                return view('Product.product');
                break;
            case (AliasType::PRODUCT_CATEGORY);
                return view('Product.category');
                break;
            case (AliasType::NEWS);
                $news = News::whereAlias($alias)->public()->firstOrFail();
                $data['news'] = $news;
                $data['cate'] = NewsCategory::find($news->category_id);
                $data['related'] = $news->categoryid()->newsnotid()->public()->langs()->take(6)->orderByDesc('updated_at')->get();
                $data['tags'] = Tags::whereType(AliasType::NEWS)->whereTypeId($news->id)->get();
                $data['prev'] = News::where('id','<',$news->id)->whereCategoryId($news->category_id)->langs()->public()->first();
                $data['next'] = News::where('id','>',$news->id)->whereCategoryId($news->category_id)->langs()->public()->first();

                if(Session::get(AliasType::NEWS) <> $news->id)
                    $news->update(['view' => $news->view + 1]);
                Session::put(AliasType::NEWS, $news->id);

                return view('News.news',$data);
                break;
            case (AliasType::NEWS_CATEGORY);
                $data['cate'] = NewsCategory::whereAlias($alias)->public()->firstOrFail();
                $data['news'] = News::with(['category','categorys','user'])->orwhereHas('categorys',function($q) use ($data) {
                    $q->where('category_id',$data['cate']->id);
                })->orWhere('category_id',$data['cate']->id)->public()->langs()->paginate(20);

                return view('News.category', $data);
                break;
            case (AliasType::PAGES);
                $page = Pages::whereAlias($alias)->public()->firstOrFail();
                $data['page'] = $page;
                $data['related'] = Pages::public()->where('id', '<>',$page->id)->langs()->take(6)->orderByDesc('updated_at')->get();
                $data['prev'] = $page->where('id','<',$data['page']->id)->public()->langs()->first();
                $data['next'] = $page->where('id','>',$data['page']->id)->public()->langs()->first();

                if(Session::get(AliasType::PAGES) <> $page->id)
                    $page->update(['view' => $page->view + 1]);
                Session::put(AliasType::PAGES, $page->id);

                return view('Page.page',$data);
                break;
            case (AliasType::RECRUITMENT);
                return view('Recruitment.recruitment');
                break;
            case (AliasType::RECRUITMENT_CATEGORY);
                return view('Recruitment.category');
                break;
            case (AliasType::VIDEO);
                return view('Video.video');
                break;
            case (AliasType::GALLERY);
                return view('Gallery.gallery');
                break;
            default;
                return abort(404);
        }

    }
}
