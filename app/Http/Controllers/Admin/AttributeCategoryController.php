<?php

namespace App\Http\Controllers\Admin\Product;

use App\Enums\SystemsModuleType;
use App\Http\Controllers\Controller;
use App\Models\AttributeCategory;
use Illuminate\Http\Request;

class AttributeCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        check_admin_systems(SystemsModuleType::ATTRIBUTE_CATEGORY);

        $category = AttributeCategory::orderby('sort','asc')->get();

        return view('Admin.Product.Attribute.Category.list',compact('category'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        check_admin_systems(SystemsModuleType::ATTRIBUTE_CATEGORY);

        return view('Admin.Product.Attribute.Category.add');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        check_admin_systems(SystemsModuleType::ATTRIBUTE_CATEGORY);

        $request->validate([
           'data.name' => 'required|unique:attribute_categorys,name',
        ]);

        $category = new AttributeCategory();
        $category->forceFill($request->data);
        $category->save();

        return redirect()->route('admin.attribute_categorys.index')->with(['message' => 'Thêm mới thành công!']);
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
    public function edit(AttributeCategory $attribute_category)
    {
        check_admin_systems(SystemsModuleType::ATTRIBUTE_CATEGORY);

        return view('Admin.Product.Attribute.Category.edit',compact('attribute_category'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, AttributeCategory $attribute_category)
    {
        check_admin_systems(SystemsModuleType::ATTRIBUTE_CATEGORY);

        $request->validate([
            'data.name' => 'required|unique:attribute_categorys,name,'.$attribute_category->id,
        ]);

        $attribute_category->forceFill($request->data);
        $attribute_category->save();

        return redirect()->route('admin.attribute_categorys.index')->with(['message' => 'Cập nhật thành công!']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(AttributeCategory $attribute_category)
    {
        check_admin_systems(SystemsModuleType::ATTRIBUTE_CATEGORY);

        $attribute_category->delete();

        return redirect()->back()->with(['message' => 'Xóa thành công!']);
    }
}
