<?php

namespace App\Http\Controllers\Admin\Module;

use App\Enums\SystemsModuleType;
use App\Http\Controllers\Controller;
use App\Models\Modules;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Schema;

class ModulesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(check_admin_systems(SystemsModuleType::ADD_MODULE))

        $modules = Modules::orderByDesc('id')->get();

        return view('Admin.Module.list',compact('modules'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if(check_admin_systems(SystemsModuleType::ADD_MODULE))

        return view('Admin.Module.add');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if(check_admin_systems(SystemsModuleType::ADD_MODULE))

        $request->validate([
            'module' => 'required',
            'table' => 'required',
            'column' => 'required|numeric|min:1',
        ]);

        if(Schema::hasTable($request->table))
            return redirect()->back()->withErrors('Tên bảng đã tồn tại');

        Schema::create($request->table, function(Blueprint $table) use ($request) {
            $column = $request->column;
            $table->increments('id');
            for($i=1;$i<=$column;$i++){
                $str_name = 'name'.$i;
                $name[$i] = $request->$str_name;
                $str_type = 'type'.$i;
                $type[$i] = $request->$str_type;
                $str_length = 'length'.$i;
                $length[$i] = $request->$str_length;
                if(isset($name[$i])){
                    if($type[$i] == 0 || $type[$i] == 3)
                        $table->text($name[$i]);
                    if($type[$i] == 1)
                        $table->biginteger($name[$i])->length($length[$i])->unsigned();
                    if($type[$i] == 2)
                        $table->string($name[$i],$length[$i]);
                    if ($type[$i] == 4)
                        $table->date($name[$i]);
                }
            }

        });

        $column = $request->column;
        $fields = [];
        for($i =1;$i<=$column;$i++){
            $str_name = 'name'.$i;
            $name = $request->$str_name;
            $str_display_name = 'display_name'.$i;
            $display_name = $request->$str_display_name;
            $str_type = 'type'.$i;
            $type = $request->$str_type;
            if($type == 0): $type = 3; endif;
            $str_length = 'length'.$i;
            $length = $request->$str_length;
            $str_display_type = 'display_type'.$i;
            $display_type = $request->$str_display_type;
            $str_option = 'option'.$i;
            $option = isset($request->$str_option) ? $request->$str_option : "";
            if($name && $display_name)
                $fields[$i] = ['name' => $name,'display_name' => $display_name,'type' => $type,'display_type' => $display_type, 'option' => $option, 'length' => $length];
        }
        Modules::create([
            'name' => $request->module,
            'table' => $request->table,
            'collumn' => count($fields),
            'fields' => json_encode($fields),
        ]);

        return redirect()->route('admin.modules.index')->with(['message' => 'Thêm mới thành công']);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Modules $module)
    {
        if(check_admin_systems(SystemsModuleType::ADD_MODULE))

        return view('Admin.Module.show',compact('module'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        return abort(404);
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
        return abort(404);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Modules $module)
    {
        if(check_admin_systems(SystemsModuleType::ADD_MODULE))

        if(Schema::hasTable($module->table)){
            Schema::drop($module->table);

            Storage::deleteDirectory($module->table);
        }
        $module->delete();

        return redirect()->route('admin.modules.index')->with(['message' => 'Xóa thành công']);
    }

    public function actionIndex($table){

        if(check_admin_systems(SystemsModuleType::ADD_MODULE))

        $module = Modules::whereTable($table)->firstOrFail();

        $data = \DB::table($table)->orderby('id','DESC')->get();

        return view('Admin.Module.detail.list',compact('data','module'));
    }

    public function createAction($table){
        if(check_admin_systems(SystemsModuleType::ADD_MODULE))

        $module = Modules::whereTable($table)->firstOrFail();


        return view('Admin.Module.detail.add',compact('module'));
    }

    public function storeAction(Request $request, $table) {
        if(check_admin_systems(SystemsModuleType::ADD_MODULE));

        $module = Modules::whereTable($table)->firstOrFail();

        foreach(json_decode($module->fields) as $key => $items){
            if(isset($_POST[$items->name])){
                $arr[$items->name] = $_POST[$items->name];
                if($arr[$items->name] == 'on'){
                    $arr[$items->name] = 1;
                }
            }elseif(isset($_FILES[$items->name])){
                if(isset($_POST['unlink_'.$items->name])){
                    $arr[$items->name] = "";
                }else{
                    $file = $request->file($items->name);
                    $file->store($table);
                    $arr[$items->name] = 'storage/'.$file->hashName($table);
                }
            }else{
                $arr[$items->name] = "";
            }
        }
        \DB::table($table)->insert($arr);

        return redirect()->route('admin.action.module.index',$table)->with(['message' => 'Thêm mới thành công']);
    }

    public function editAction($table, $id){
        if(check_admin_systems(SystemsModuleType::ADD_MODULE));

        $module = Modules::whereTable($table)->firstOrFail();

        $old = \DB::table($table)->where(['id' => $id])->first();

        return view('Admin.Module.detail.edit',compact('module','old'));

    }

    public function updateAction(Request $request, $table, $id){
        if(check_admin_systems(SystemsModuleType::ADD_MODULE));

        $module = Modules::whereTable($table)->firstOrFail();

        $old = \DB::table($table)->where(['id' => $id])->first();

        foreach(json_decode($module->fields) as $key => $items){

            $name = $items->name;

            if(isset($_POST[$name])){
                $arr[$name] = $_POST[$name];
                if($arr[$name] == 'on'){
                    $arr[$name] = 1;
                }

            }elseif(isset($_FILES[$name])){

                    if(isset($_POST['unlink_'.$name])){
                        if(file_exists($old->$name))
                            unlink($old->$name);
                        $arr[$name] = "";
                    }
                    if($_FILES[$name]['size'] == 0 && empty($_POST['unlink_'.$name])){
                        $arr[$name] = $old->$name;
                    }

                    if($_FILES[$name]['size'] > 0 && empty($_POST['unlink_'.$name])){
                        if(file_exists($old->$name))
                            unlink($old->$name);
                        $file = $request->file($name);
                        $file->store($table);
                        $arr[$name] = 'storage/'.$file->hashName($table);
                    }

            }else{
                $arr[$name] = "";
            }
        }

        \DB::table($table)->where(['id' => $id])->update($arr);

        return redirect()->route('admin.action.module.index',$table)->with(['message' => 'Sửa thành công']);
    }

    public function detroyAction($table, $id){
        if(check_admin_systems(SystemsModuleType::ADD_MODULE));

        $module = Modules::whereTable($table)->firstOrFail();

        $old = \DB::table($table)->where(['id' => $id])->first();

        foreach(json_decode($module->fields) as $key => $items){
            if($items->display_type == 5){
                $name = $items->name;
                if(file_exists($old->$name)){
                    unlink($old->$name);
                }
            }
        }
        \DB::table($table)->where(['id' => $id])->delete();

        return redirect()->route('admin.action.module.index',$table)->with(['message' => 'Xóa thành công']);
    }
}
