<?php

namespace App\Http\Controllers\Admin\Menu;

use App\Enums\SystemsModuleType;
use App\Http\Controllers\Controller;
use App\Models\CategoryProduct;
use App\Models\Menu;
use App\Models\NewsCategory;
use App\Models\Pages;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Session;

class MenuController extends Controller
{

    public function __construct()
    {
        parent::__construct();

        if(!Session::has('menu_position')){
            Session::put('menu_position','top');
        }
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(check_admin_systems(SystemsModuleType::MENU))

            $news_category = NewsCategory::langs()->public()->get();

            $product_category = CategoryProduct::langs()->public()->get();

            $pages = Pages::langs()->public()->get();

            $menus = Menu::position()->sort()->get();

            return view('Admin.Menu.menu', compact('news_category','product_category','pages','menus'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if(check_admin_systems(SystemsModuleType::MENU))

         $news_category = NewsCategory::langs()->public()->get();

        $product_category = CategoryProduct::langs()->public()->get();

        $pages = Pages::langs()->public()->get();

        $menus = Menu::position()->sort()->get();

        return view('Admin.Menu.add', compact('news_category','product_category','pages','menus'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if(check_admin_systems(SystemsModuleType::MENU))

         $menu = new Menu();

         $menu->forceFill($request->data);
        if($request->unlink){
            $menu->image = null;
        }elseif($request->hasFile('image')){
            $request->validate([
                'image' => 'image|mimes:jpeg,png,jpg,gif,svg',
            ]);
            $file = $request->file('image');
            $file->store('menus');

            $menu->image = "storage/".$file->hashName('menus');
        }else{
            $menu->image = null;
        }

        $menu->lang = Session::get('lang');
        $menu->position = Session::get('menu_position');

        $menu->save();

        return redirect()->back()->withInput()->with(['message' => 'Thêm mới thành công']);

    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return  abort(404);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Menu $menu)
    {
        if(check_admin_systems(SystemsModuleType::MENU))

            if($menu->lang <> Session::get('lang'))
                return redirect()->route('admin.menus.index');

        $news_category = NewsCategory::langs()->public()->get();

        $product_category = CategoryProduct::langs()->public()->get();

        $pages = Pages::langs()->public()->get();

        $menus = Menu::position()->sort()->get();

        return view('Admin.Menu.edit', compact('news_category','product_category','pages','menus','menu'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Menu $menu)
    {
        if(check_admin_systems(SystemsModuleType::MENU))

        $menu->forceFill($request->data);

        if($request->unlink){
            if(file_exists($menu->image))
                unlink($menu->image);

            $menu->image = null;
        }elseif($request->hasFile('image')){
            $request->validate([
                'image' => 'image|mimes:jpeg,png,jpg,gif,svg',
            ]);
            $file = $request->file('image');
            $file->store('menus');

            $menu->image = "storage/".$file->hashName('menus');
        }else{
            $menu->image = $menu->image;
        }

        $menu->lang = Session::get('lang');
        $menu->position = Session::get('menu_position');

        $menu->save();

        return redirect()->back()->withInput()->with(['message' => 'Cập nhật thành công!']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Menu $menu)
    {

        if(check_admin_systems(SystemsModuleType::MENU))

            $menu->delete();

        return redirect()->back()->withInput()->with(['message' => 'Xóa thành công!' ]);
    }

    public function position(Request $request){

        Session::put('menu_position',$request->position);

        return redirect()->back()->withInput()->with(['message' => 'Cập nhật thành công!']);

    }

    public function ajax_add_menu(Request $request){

       $menu = Menu::create([
            'name' => $request->name,
            'url' => $request->url,
            'icon' => $request->image,
            'thumb' => $request->thumb,
            'parent_id' =>0,
            'position' => Session::get('menu_position'),
            'lang' => Session::get('lang'),
        ]);

        $menu->update([
            'sort' => $menu->id
        ]);

        $icon = '<i class="fa fa-star" aria-hidden="true"></i>';

        $append = '<li class="dd-item" data-id="'.$menu->id.'">';

        $append .= '<div class="dd-handle"><span class="pr-1">'.$icon.'</span> '.$menu->name.'</div>';
        $append .= '<div class="menu_action">';
        $append .= '<a href="'.route('admin.menus.edit',$menu).'" title="Sửa" class="btn btn-purple waves-effect waves-light"><i class="fe-edit-2"></i></a> ';
        $append .= '<form method="post" action="'.route('admin.menus.destroy',$menu).'" class="d-inline-block">';
        $append .= '<input type="hidden" name="_method" value="DELETE">';
        $append .= '<input type="hidden" name="_token" value="'.csrf_token().'">';
        $append .= '<button type="submit" onclick="return confirm(\'Bạn chắc chắn muốn xóa?\')" class="btn btn-warning waves-effect waves-light"><i class="fe-x"></i></button>';
        $append .= '</form>';
        $append .= '</div>';

        echo $append;

    }
}
