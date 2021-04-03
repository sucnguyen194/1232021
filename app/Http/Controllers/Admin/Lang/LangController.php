<?php

namespace App\Http\Controllers\Admin\Lang;

use App\Enums\SystemsModuleType;
use App\Http\Controllers\Controller;
use App\Models\Lang;
use App\Models\Setting;
use App\Models\SiteSetting;
use Illuminate\Http\Request;
use Session;

class LangController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(!check_admin_systems(SystemsModuleType::LANG))
            return redirect()->back()->withErrors(['message'=>'Bạn không thể thực hiện hành động này!']);

        $lang = Lang::orderByDesc('id')->get();

        return view('Admin.Lang.list',compact('lang'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if(!check_admin_systems(SystemsModuleType::LANG))
            return redirect()->back()->withErrors(['message'=>'Bạn không thể thực hiện hành động này!']);

        $lang = Lang::orderByDesc('id')->get();

        return view('Admin.Lang.add',compact('lang'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if(!check_admin_systems(SystemsModuleType::LANG))
            return redirect()->back()->withErrors(['message'=>'Bạn không thể thực hiện hành động này!']);

        $request->validate([
            'name' => 'required|string|unique:lang',
            'value' => 'required|string|min:2|max:2|unique:lang',
        ]);

        Lang::create([
            'name' => $request->name,
            'value' => $request->value
        ]);

        Setting::create([
            'name' => 'Site Setting',
            'lang' => $request->value
        ]);

        return redirect()->route('admin.lang.index')->with(['message' => 'Thêm mới thành công']);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return abort(404);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Lang $lang)
    {
        if(check_admin_systems(SystemsModuleType::LANG))

        $langs = Lang::orderByDesc('id')->get();

        return view('Admin.Lang.edit',compact('lang','langs'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Lang $lang)
    {
        if(check_admin_systems(SystemsModuleType::LANG))

        $request->validate([
            'name' => 'required|string',
            'value' => 'required|string|min:2|max:2',
        ]);

        $check = Lang::whereName($lang->name)->whereValue($lang->value)->whereNotIn('id',[$lang->id])->count();

        if($check > 0)
            return redirect()->back()->withInput()->withErrors(['messsage' => 'Tên ngôn ngữ hoặc giá trị đã tồn tại']);

        $lang->update([
            'name' => $request->name,
            'value' => $request->value
        ]);

        return redirect()->route('admin.lang.index')->with(['message' => 'Sửa thành công']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Lang $lang)
    {
        if(check_admin_systems(SystemsModuleType::LANG))

            if(Lang::count() == 1)
                return redirect()->back()->withErrors(['message' => 'Bạn không thể thực hiện hành động này!']);

        $setting = Setting::whereLang($lang->value)->first();

        if(file_exists($setting->logo))
            unlink($setting->logo);
        if(file_exists($setting->favicon))
            unlink($setting->favicon);
        if(file_exists($setting->watermark))
            unlink($setting->watermark);

        $setting->delete();

        $lang->delete();

        return redirect()->route('admin.lang.index')->with(['message' => 'Xóa thành công']);
    }

    public function active($id){
        if(check_admin_systems(SystemsModuleType::LANG))

        $lang = Lang::findOrFail($id);
        $list = Lang::whereNotIn('id',[$id])->get();
        foreach ($list as $item){
            $item->update(['status' => 0]);
        }
        $lang->update(['status' => 1]);

        \Session::put('lang', $lang->value);

        return redirect()->route('admin.lang.index')->with(['message' => 'Cập nhật thành công']);
    }

    public function change($lang){
        if(check_admin_systems(SystemsModuleType::LANG))

        Session::put('lang',$lang);
        return  redirect()->back()->withInput()->with(['message' => 'Cập nhật thành công']);
    }
}
