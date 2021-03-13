<?php

namespace App\Http\Controllers\Admin\Module;

use App\Enums\SystemsModuleType;
use App\Http\Controllers\Controller;
use App\Models\Modules;
use Illuminate\Http\Request;

class ActionModulesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return  abort(404);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if(!check_admin_systems(SystemsModuleType::ADD_MODULE))
            return redirect()->back()->withErrors(['message'=>'Bạn không thể thực hiện hành động này!']);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if(!check_admin_systems(SystemsModuleType::ADD_MODULE))
            return redirect()->back()->withErrors(['message'=>'Bạn không thể thực hiện hành động này!']);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($table)
    {
        if(!check_admin_systems(SystemsModuleType::ADD_MODULE))
            return redirect()->back()->withErrors(['message'=>'Bạn không thể thực hiện hành động này!']);

        return $table;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if(!check_admin_systems(SystemsModuleType::ADD_MODULE))
            return redirect()->back()->withErrors(['message'=>'Bạn không thể thực hiện hành động này!']);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        if(!check_admin_systems(SystemsModuleType::ADD_MODULE))
            return redirect()->back()->withErrors(['message'=>'Bạn không thể thực hiện hành động này!']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
