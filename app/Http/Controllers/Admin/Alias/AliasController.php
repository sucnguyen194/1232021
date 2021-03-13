<?php

namespace App\Http\Controllers\Admin\Alias;

use App\Enums\SystemsModuleType;
use App\Http\Controllers\Controller;
use App\Models\Alias;
use Illuminate\Http\Request;

class AliasController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(!check_admin_systems(SystemsModuleType::ALIAS))
            return redirect()->back()->withErrors(['message'=>'Bạn không thể thực hiện hành động này!']);

        $alias = Alias::orderByDesc('id')->get();

        return view('Admin.Alias.list',compact('alias'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if(!check_admin_systems(SystemsModuleType::ALIAS))
            return redirect()->back()->withErrors(['message'=>'Bạn không thể thực hiện hành động này!']);

        return view('Admin.Alias.add');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if(!check_admin_systems(SystemsModuleType::ALIAS))
            return redirect()->back()->withErrors(['message'=>'Bạn không thể thực hiện hành động này!']);

        $request->validate([
            'alias' => 'required|string',
            'type' => 'required|string',
            'id' => 'integer'
        ]);

        if(Alias::whereAlias($request->alias)->count())
            return redirect()->back()->withInput()->withErrors(['message' => 'Đường dẫn đã tồn tại']);

        Alias::create([
            'alias' => $request->alias,
            'type' => $request->type,
            'type_id' => $request->type_id,
        ]);

        return  redirect()->route('admin.alias.index')->with(['message' => 'Thêm mới thành công']);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Alias $alias)
    {
        if(!check_admin_systems(SystemsModuleType::ALIAS))
            return redirect()->back()->withErrors(['message'=>'Bạn không thể thực hiện hành động này!']);

        return view('Admin.Alias.edit',compact('alias'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Alias $alias)
    {
        if(!check_admin_systems(SystemsModuleType::ALIAS))
            return redirect()->back()->withErrors(['message'=>'Bạn không thể thực hiện hành động này!']);

        $request->validate([
            'alias' => 'required|string',
            'type' => 'required|string',
            'id' => 'integer'
        ]);

        if(Alias::whereAlias($request->alias)->count())
            return redirect()->back()->withInput()->withErrors(['message' => 'Đường dẫn đã tồn tại']);

        $alias->model()->update([
           'alias' => $request->alias,
        ]);

        $alias->update([
            'alias' => $request->alias,
            'type' => $request->type,
            'type_id' => $request->type_id,
        ]);

        return  redirect()->route('admin.alias.index')->with(['message' => 'Sửa thành công']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Alias $alias)
    {
        if(!check_admin_systems(SystemsModuleType::ALIAS))
            return redirect()->back()->withErrors(['message'=>'Bạn không thể thực hiện hành động này!']);

        $alias->delete();

        return  redirect()->route('admin.alias.index')->with(['message' => 'Xóa thành công']);
    }
}
