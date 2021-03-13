<?php

namespace App\Http\Controllers\Admin\Product;

use App\Enums\SystemsModuleType;
use App\Http\Controllers\Controller;
use App\Models\Attribute;
use App\Models\AttributeCategory;
use Illuminate\Http\Request;

class AttributeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        check_admin_systems(SystemsModuleType::ATTRIBUTE);
        $category = AttributeCategory::public()->get();
        $attribute = Attribute::with(['category'])
            ->when(\request()->category,function($q){
                $category = \request()->category == -1 ? 0 : \request()->category;
                $q->where('category_id',$category);
            })
            ->orderby('category_id','desc')
            ->orderby('sort','asc')
            ->get();

        return view('Admin.Product.Attribute.list',compact('attribute','category'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        check_admin_systems(SystemsModuleType::ATTRIBUTE);

        $category = AttributeCategory::public()->get();

        return view('Admin.Product.Attribute.add',compact('category'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        check_admin_systems(SystemsModuleType::ATTRIBUTE);

        $request->validate([
            'data.name' => 'required|unique:attributes,name',
        ]);

        $attribute = new Attribute();
        $attribute->forceFill($request->data);
        $attribute->save();

        return redirect()->route('admin.attributes.index')->with(['message' => 'Thêm mới thành công!']);
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
    public function edit(Attribute $attribute)
    {
        check_admin_systems(SystemsModuleType::ATTRIBUTE);

        $category = AttributeCategory::public()->get();

        return view('Admin.Product.Attribute.edit',compact('category','attribute'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Attribute $attribute)
    {
        check_admin_systems(SystemsModuleType::ATTRIBUTE);

        $request->validate([
            'data.name' => 'required|unique:attributes,name,'.$attribute->id,
        ]);
        $attribute->forceFill($request->data);
        $attribute->save();

        return back()->with(['message' => 'Cập nhật thành công!']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Attribute $attribute)
    {
        check_admin_systems(SystemsModuleType::ATTRIBUTE);
        $attribute->delete();

        return back()->with(['message' => 'Xóa thành công!']);
    }
}
