<?php

namespace App\Http\Controllers\Admin\Module;
use App\Enums\SystemsModuleType;
use App\Http\Controllers\Controller;
use App\Http\Requests\SystemsRequest;
use App\Models\SystemsModule;

class SystemsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public $type = SystemsModuleType::SYSTEMS_MODULE;

    public function index()
    {
        check_admin_systems($this->type);
        $systems = SystemsModule::orderby('sort','asc')->get();
        return view('Admin.Module.systems.list',compact('systems'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if(check_admin_systems($this->type))
            $systems = SystemsModule::where('parent_id',0)->orderby('sort','asc')->get();

        return view('Admin.Module.systems.add',compact('systems'));

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(SystemsRequest $request)
    {
        if(check_admin_systems($this->type))

            if($request->send == 'save'){

                SystemsModule::create([
                    'name' => $request->name,
                    'route' => $request->route,
                    'var' => $request->var,
                    'type' => $request->type,
                    'icon' => $request->icon,
                    'parent_id' => $request->parent_id,
                    'position' => $request->position,
                ]);

                return redirect()->route('admin.systems.index')->with(['message'=>'Thêm mới thành công!']);
            }
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
    public function edit(SystemsModule $system)
    {
        if(check_admin_systems($this->type))

            $systems = SystemsModule::where('parent_id',0)->orderby('sort','asc')->get();

        return view('Admin.Module.systems.edit',compact('system','systems'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(SystemsRequest $request, SystemsModule $system)
    {
        if(check_admin_systems($this->type))

            if($request->send == 'update'){

                $system->update([
                    'name' => $request->name,
                    'route' => $request->route,
                    'var' => $request->var,
                    'type' => $request->type,
                    'icon' => $request->icon,
                    'parent_id' => $request->parent_id,
                    'position' => $request->position,
                ]);

                return redirect()->route('admin.systems.index')->with(['message','Sửa thành công!']);
            }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(SystemsModule $system)
    {
        if(check_admin_systems($this->type))

            $system->delete();

        return redirect()->route('admin.systems.index')->with(['message'=>'Xóa thành công!']);
    }
}
