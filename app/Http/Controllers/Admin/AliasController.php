<?php

namespace App\Http\Controllers\Admin;

use App\Enums\SystemsModuleType;
use App\Http\Controllers\Controller;
use App\Models\Alias;
use Illuminate\Http\Request;

class AliasController extends Controller
{
    public  $type = SystemsModuleType::ALIAS;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        check_admin_systems($this->type);

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
        check_admin_systems($this->type);

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
        check_admin_systems($this->type);

        $request->validate([
            'alias' => 'required|string',
            'type' => 'required|string',
            'type_id' => 'integer'
        ]);

        if(Alias::whereAlias($request->alias)->count())
            return flash('Đường dẫn đã tồn tại', 3);

        Alias::create([
            'alias' => $request->alias,
            'type' => $request->type,
            'type_id' => $request->type_id,
        ]);
        return flash('Thêm mới thành công', 1, route('admin.alias.index'));
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
        check_admin_systems($this->type);

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
        check_admin_systems($this->type);

        $request->validate([
            'alias' => 'required|string',
            'type' => 'required|string',
            'type_id' => 'integer'
        ]);

        if(Alias::whereAlias($request->alias)->whereNotIn('type_id', $alias->id)->count())
          return  flash('Đường dẫn đã tồn tại', 3);

        $alias->model()->update([
           'alias' => $request->alias,
        ]);

        $alias->update([
            'alias' => $request->alias,
            'type' => $request->type,
            'type_id' => $request->type_id,
        ]);

        return flash('Cập nhật thành công!', 1);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Alias $alias)
    {
        check_admin_systems($this->type);

        $alias->delete();

        return flash('Xóa thành công!', 1);
    }
}
