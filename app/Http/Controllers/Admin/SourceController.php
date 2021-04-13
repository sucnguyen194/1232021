<?php

namespace App\Http\Controllers\Admin;

use App\Enums\SystemsModuleType;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SourceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(!authorize(SystemsModuleType::SOURCE))
            flash('Bạn không thể thực hiện hành động này!', 3);

        return view('Admin.Source.list');
    }

    public function load(Request $request){
        if(!authorize(SystemsModuleType::SOURCE))
            flash('Bạn không thể thực hiện hành động này!', 3);

        $path = $request->path;
        $file = file_get_contents($path);
        return $file;
    }

    public function push(Request $request){
        authorize(SystemsModuleType::SOURCE);
        $content = $request->put_file;
        $dir = $request->dir;
        file_put_contents($dir,$content);
        $time = time();
        return $time;
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return abort(404);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        return abort(404);
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
    public function destroy($id)
    {
        return abort(404);
    }
}
