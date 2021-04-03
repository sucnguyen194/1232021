<?php

namespace App\Http\Controllers\Admin;

use App\Enums\SystemsModuleType;
use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Menu;
use App\Models\Pages;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
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

            $categories = Category::langs()->public()->get();

            $pages = Post::whereType(SystemsModuleType::PAGE)->langs()->public()->get();

            $menus = Menu::position()->sort()->get();

            return view('Admin.Menu.menu', compact('categories','pages','menus'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if(check_admin_systems(SystemsModuleType::MENU))

        $categories = Category::langs()->public()->get();

        $pages = Post::whereType(SystemsModuleType::PAGE)->langs()->public()->get();

        $menus = Menu::position()->sort()->get();

        return view('Admin.Menu.add', compact('categories','pages','menus'));
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
        if(!$request->unlink && $request->hasFile('image')){
            upload_file_image($menu, $request->file('image'));
        }
        $menu->lang = Session::get('lang');
        $menu->position = Session::get('menu_position');

        $menu->save();

        return  flash('Thêm mới thành công!', 1, route('admin.menus.index'));
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

        $categories = Category::langs()->public()->get();

        $pages = Post::whereType(SystemsModuleType::PAGE)->langs()->public()->get();

        $menus = Menu::position()->sort()->get();

        return view('Admin.Menu.edit', compact('categories','pages','menus','menu'));
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
            if(File::exists($menu->image))
                File::delete($menu->image);
            $menu->image = null;
        }
        $menu->position = Session::get('menu_position');
        $menu->save();

        return flash('Cập nhật thành công', 1);
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
            if(File::exists($menu->image))
                File::delete($menu->image);
            $menu->delete();

        return flash('Xóa thành công', 1);
    }

    public function position(Request $request){
        Session::put('menu_position',$request->position);
        return flash('Cập nhật thành công', 1);

    }
    public function add(Request $request){
       $menu = Menu::create([
            'name' => $request->name,
            'url' => $request->url,
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
        $append .= '<a href="'.route('admin.menus.edit',$menu).'" title="Sửa" class="btn btn-primary waves-effect waves-light"><i class="fe-edit-2"></i></a> ';
        $append .= '<form method="post" action="'.route('admin.menus.destroy',$menu).'" class="d-inline-block">';
        $append .= '<input type="hidden" name="_method" value="DELETE">';
        $append .= '<input type="hidden" name="_token" value="'.csrf_token().'">';
        $append .= '<button type="submit" onclick="return confirm(\'Bạn chắc chắn muốn xóa?\')" class="btn btn-warning waves-effect waves-light"><i class="fe-x"></i></button>';
        $append .= '</form>';
        $append .= '</div>';

        echo $append;

    }
}
