<?php

namespace App\Http\Controllers\Admin\Product;

use App\Enums\AliasType;
use App\Enums\MediaType;
use App\Enums\ProductSessionType;
use App\Enums\SystemsModuleType;
use App\Http\Controllers\Controller;
use App\Models\Alias;
use App\Models\CategoryProduct;
use App\Models\CategoryToProduct;
use App\Models\Lang;
use App\Models\Media;
use App\Models\News;
use App\Models\Product;
use App\Models\ProductSession;
use App\Models\Tags;
use App\Models\User;
use App\Models\UserAgency;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Session, Image;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        check_admin_systems(SystemsModuleType::PRODUCT);
        $lang = isset(request()->lang) ? request()->lang : Session::get('lang');
        $products = Product::with(['categorys','language','category'])->where('lang',$lang)
            ->when(request()->user,function($q, $user){
                $q->where('user_id',$user);
            })
            ->when(request()->status,function($q){
                $status = request()->status == 'true' ? 1 : 0 ;
                $q->where('status',$status);
            })
            ->when(request()->public, function($q){
                $public = request()->public == 'true' ? 1 : 0 ;
                $q->where('public',$public);
            })
            ->when(\request()->category,function($q){
                $category = \request()->category == -1 ? 0 : \request()->category;
                $q->where('category_id',$category)->orwhereHas('categorys')->when(\request()->category,function($q, $category){
                    $q->where('category_id',$category);
                });
            })
            ->orderByDesc('created_at')->get();
        $category = CategoryProduct::whereLang($lang)->public()->get();
        $langs =  \App\Models\Lang::get();
        $user = User::where('lever','>=',\Auth::user()->lever)->get();

        return view('Admin.Product.list',compact('products','category','langs','user'));
    }
    public function stock(){
        check_admin_systems(SystemsModuleType::STOCK);

        $products = Product::public()->orderByDesc('created_at')->get();

        $sessions = ProductSession::with(['product', 'agency','user','admin'])
            ->when(date_range(),function($q, $date){
                $q->whereBetween('created_at',[$date['from']->startOfDay(),$date['to']->endOfDay()]);
            })
//            ->when(request()->user_create,function ($q, $user){
//                $q->where('user_create',$user);
//            })
//            ->when(request()->user,function ($q, $user){
//                $q->where('user_id',$user);
//            })
//            ->when(request()->agency,function ($q, $agency){
//                $q->where('agency_id',$agency);
//            })
            ->when(\request()->product,function($q, $product){
                $q->where('product_id', $product);
            })
            ->orderByDesc('created_at')
            ->get();

        return view('Admin.Product.stock',compact('sessions','products'));
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        check_admin_systems(SystemsModuleType::ADD_PRODUCT);

        $category = CategoryProduct::public()->langs()->orderByDesc('id')->get();

        return view('Admin.Product.add', compact('category'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        check_admin_systems(SystemsModuleType::ADD_PRODUCT);

        $request->validate([
            'data.name' => 'required',
            'data.alias' => 'required',
            'data.price' => 'integer|min:0',
            'data.price_sale' => 'integer|min:0',
            'data.amount' => 'integer|min:0',
        ]);

        $check_alias = \App\Models\Alias::whereAlias($request->input('data.alias'))->count();

        if($check_alias > 0)
            return redirect()->back()->withInput()->withErrors(['message' => 'Đường dẫn đã tồn tại']);

        $product = new Product;

        $product->fill($request->data);

        if($request->unlink){
            $image = null;
            $thumb = null;
        }elseif($request->hasFile('image')){
            $request->validate([
                'image' => 'image|mimes:jpeg,png,jpg,gif,svg',
            ]);
            $file = $request->file('image');
            $file->store('product');
            $path = $file->hashName('product/thumb');
            $resizeThumb = Image::make($file);
            $resizeThumb->fit(375, 375, function ($constraint) {
                $constraint->aspectRatio();
            });
            Storage::put($path, (string) $resizeThumb->encode());

            $thumb = "storage/".$path;
            $image = "storage/".$file->hashName('product');
        }else{
            $image = null;
            $thumb = null;
        }

        if ($request->input('fields.0.name')){
            $fields = [];
            foreach ($request->fields as $field){
                if ($field['name']){
                    $fields[] = $field;
                }
            }
            $product->option = $fields;
        }

        $product->lang = Session::get('lang');
        $product->image = $image;
        $product->thumb = $thumb;
        $product->user_id = Auth::id();

        $product->save();

        if($request->hasFile('photo')){

            $count = count($request->file('photo'));

            for ($i = 0; $i < $count; $i++) {
                $file = $request->file('photo')[$i];
                $file->store('product/photo');
                $path = $file->hashName('product/photo/thumb');
                $resizeThumb = Image::make($file);
                $resizeThumb->fit(375, 375, function ($constraint) {
                    $constraint->aspectRatio();
                });
                Storage::put($path, (string) $resizeThumb->encode());

                $thumb = "storage/".$path;
                $image = "storage/".$file->hashName('product/photo');

                Media::create([
                    'name' => $product->name,
                    'image' => $image,
                    'thumb' => $thumb,
                    'type_id' => $product->id,
                    'type' => MediaType::PRODUCT,
                    'user_id' => \Auth::id(),
                    'public' => 1,
                    'lang' => $product->lang
                ]);

            }
        }

        if($request->input('data.tags')){
            foreach(explode(',',$request->input('data.tags')) as $items){
                $alias = Str::slug($items, '-');
                $check_alias = Tags::where('alias',$alias)->where('type',SystemsModuleType::PRODUCT)->where('type_id',$product->id)->count();

                if($check_alias == 0 ){
                    Tags::create([
                        'name' => $items,
                        'alias' => $alias,
                        'type' => SystemsModuleType::PRODUCT,
                        'type_id' => $product->id,
                        'lang' => $product->lang,
                    ]);
                }
            }
        }

        if(isset($request->category_id) && sizeof($request->category_id) > 0) {
            for ($i = 0 ; $i < sizeof($request->category_id); $i++){
                CategoryToProduct::create([
                    'product_id' => $product->id,
                    'category_id' => $request->category_id[$i]
                ]);
            }
        }

        return redirect()->route('admin.products.index')->with(['message' => 'Thếm mới thành công!']);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product)
    {
        check_admin_systems(SystemsModuleType::ADD_PRODUCT);
        $user = User::where('lever','>=',\Auth::user()->lever)->get();

        $sessions = $product->sessions()
            ->when(date_range(),function($q, $date){
                $q->whereBetween('created_at',[$date['from']->startOfDay(),$date['to']->endOfDay()]);
            })
            ->when(request()->user_create,function ($q, $user){
                $q->where('user_create',$user);
            })
            ->when(request()->user,function ($q, $user){
                $q->where('user_id',$user);
            })
            ->when(request()->agency,function ($q, $agency){
                $q->where('agency_id',$agency);
            })
            ->when(\request()->type,function($q, $type){
                $q->where('type', $type);
            })

        ->get();

        $agencys = UserAgency::status()->get();

        return view('Admin.Product.show',compact('sessions','product','user','agencys'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Product $product)
    {
        check_admin_systems(SystemsModuleType::ADD_PRODUCT);

        $category = CategoryProduct::public()->langs()->orderByDesc('id')->get();
        $photo = $product->photos()->get();

        return view('Admin.Product.edit',compact('product','category','photo'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Product $product)
    {
        check_admin_systems(SystemsModuleType::ADD_PRODUCT);

        $request->validate([
            'data.name' => 'required',
            'data.alias' => 'required',
            'data.price' => 'integer|min:0',
            'data.price_sale' => 'integer|min:0',
            'data.amount' => 'integer|min:0',
        ]);

        $alias = Alias::whereAlias($product->alias)->first();
        $check_alias = Alias::whereAlias($request->input('data.alias'))->whereNotIn('id',[$alias->id])->count();

        if($check_alias > 0)
            return redirect()->back()->withInput()->withErrors(['message' => 'Đường dẫn đã tồn tại']);

        $product->fill($request->data);

        if($request->unlink){
            if(file_exists($product->image))
                unlink($product->image);
            if(file_exists($product->thumb))
                unlink($product->thumb);
            $image = null;
            $thumb = null;
        }elseif($request->hasFile('image')){
            $request->validate([
                'image' => 'image|mimes:jpeg,png,jpg,gif,svg',
            ]);
            $file = $request->file('image');
            $file->store('product');
            $path = $file->hashName('product/thumb');
            $resizeThumb = Image::make($file);
            $resizeThumb->fit(375, 375, function ($constraint) {
                $constraint->aspectRatio();
            });
            Storage::put($path, (string) $resizeThumb->encode());

            $thumb = "storage/".$path;
            $image = "storage/".$file->hashName('product');
        }else{
            $image = $product->image;
            $thumb = $product->thumb;
        }

        if ($request->input('fields.0.name')){
            $fields = [];
            foreach ($request->fields as $field){
                if ($field['name']){
                    $fields[] = $field;
                }
            }
            $product->option = $fields;
        }

        $product->image = $image;
        $product->thumb = $thumb;
        $product->user_edit = Auth::id();

        $product->save();

        if($request->hasFile('photo')){

            $count = count($request->file('photo'));

            for ($i = 0; $i < $count; $i++) {
                $file = $request->file('photo')[$i];
                $file->store('product/photo');
                $path = $file->hashName('product/photo/thumb');
                $resizeThumb = Image::make($file);
                $resizeThumb->fit(375, 375, function ($constraint) {
                    $constraint->aspectRatio();
                });
                Storage::put($path, (string) $resizeThumb->encode());

                $thumb = "storage/".$path;
                $image = "storage/".$file->hashName('product/photo');

                Media::create([
                    'name' => $product->name,
                    'image' => $image,
                    'thumb' => $thumb,
                    'type_id' => $product->id,
                    'type' => MediaType::PRODUCT,
                    'user_id' => \Auth::id(),
                    'public' => 1,
                    'lang' => $product->lang
                ]);

            }
        }
        $product->tags()->delete();
        if($request->input('data.tags')){
            foreach(explode(',',$request->input('data.tags')) as $items){
                $alias = Str::slug($items, '-');
                $check_alias = Tags::where('alias',$alias)->where('type',SystemsModuleType::PRODUCT)->where('type_id',$product->id)->count();

                if($check_alias == 0 ){
                    Tags::create([
                        'name' => $items,
                        'alias' => $alias,
                        'type' => SystemsModuleType::PRODUCT,
                        'type_id' => $product->id,
                        'lang' => $product->lang,
                    ]);
                }
            }
        }
        $product->categorys()->delete();
        if(isset($request->category_id) && sizeof($request->category_id) > 0) {
            for ($i = 0 ; $i < sizeof($request->category_id); $i++){
                CategoryToProduct::create([
                    'product_id' => $product->id,
                    'category_id' => $request->category_id[$i]
                ]);
            }
        }

        return redirect()->back()->withInput()->with(['message' => 'Cập nhật nội dung thành công!']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        check_admin_systems(SystemsModuleType::PRODUCT);

        $product = Product::findOrFail($id);
        $product->delete();

        return redirect()->back()->withInput()->with(['message'=>'Xóa thành công']);
    }

    public function delMulti(Request $request)
    {
        check_admin_systems(SystemsModuleType::PRODUCT);

            if($request->delall == 'delete'){

                $count = count($request->check_del);
                for($i=0;$i<$count;$i++){
                    $id = $request->check_del[$i];

                    $product = Product::findOrFail($id);

                    if(file_exists($product->image))
                        unlink($product->image);

                    if(file_exists($product->thumb))
                        unlink($product->thumb);

                    $product->delete();

                }
                return redirect()->back()->withInput()->with(['message'=>'Xóa thành công']);
            }
        return redirect()->route('admin.products.index')->withErrors(['message'=>'LỖi']);
    }

    public function createLang($language="empty", $id=0)
    {
        check_admin_systems(SystemsModuleType::ADD_PRODUCT);
        $lang = $language == 'empty' ? Session::get('lang') : $language;
        if(!Lang::whereValue($lang)->count())
            return redirect()->route('admin.products.index')->withErrors(['message' => 'Ngôn ngữ chưa được cấu hình']);
        $product = Product::find($id);
        $category = CategoryProduct::public()->whereLang($lang)->langs()->orderByDesc('id')->get();

        return view('Admin.Product.lang', compact('category','product','lang'));
    }

    public function storeLang(Request $request , $language = 'empty', $id=0)
    {
        check_admin_systems(SystemsModuleType::ADD_PRODUCT);

        $request->validate([
            'data.name' => 'required',
            'data.alias' => 'required',
            'data.price' => 'integer|min:0',
            'data.price_sale' => 'integer|min:0',
            'data.amount' => 'integer|min:0',
        ]);

        $check_alias = \App\Models\Alias::whereAlias($request->input('data.alias'))->count();
        if($check_alias > 0)
            return redirect()->back()->withInput()->withErrors(['message' => 'Đường dẫn đã tồn tại']);
        $lang = $language == 'empty' ? Session::get('lang') : $language;

        $product = new Product;
        $product->fill($request->data);

        if($request->unlink){
            $image = null;
            $thumb = null;
        }elseif($request->hasFile('image')){
            $request->validate([
                'image' => 'image|mimes:jpeg,png,jpg,gif,svg',
            ]);
            $file = $request->file('image');
            $file->store('product');
            $path = $file->hashName('product/thumb');
            $resizeThumb = Image::make($file);
            $resizeThumb->fit(375, 375, function ($constraint) {
                $constraint->aspectRatio();
            });
            Storage::put($path, (string) $resizeThumb->encode());

            $thumb = "storage/".$path;
            $image = "storage/".$file->hashName('product');
        }else{
            $image = null;
            $thumb = null;
        }

        if ($request->input('fields.0.name')){
            $fields = [];
            foreach ($request->fields as $field){
                if ($field['name']){
                    $fields[] = $field;
                }
            }
            $product->option = $fields;
        }

        $product->lang = Session::get('lang');
        $product->image = $image;
        $product->thumb = $thumb;
        $product->user_id = Auth::id();
        $product->lang = $lang;
        $product->save();

        if($request->hasFile('photo')){

            $count = count($request->file('photo'));

            for ($i = 0; $i < $count; $i++) {
                $file = $request->file('photo')[$i];
                $file->store('product/photo');
                $path = $file->hashName('product/photo/thumb');
                $resizeThumb = Image::make($file);
                $resizeThumb->fit(375, 375, function ($constraint) {
                    $constraint->aspectRatio();
                });
                Storage::put($path, (string) $resizeThumb->encode());

                $thumb = "storage/".$path;
                $image = "storage/".$file->hashName('product/photo');

                Media::create([
                    'name' => $product->name,
                    'image' => $image,
                    'thumb' => $thumb,
                    'type_id' => $product->id,
                    'type' => MediaType::PRODUCT,
                    'user_id' => \Auth::id(),
                    'public' => 1,
                    'lang' => $product->lang
                ]);

            }
        }

        if($request->input('data.tags')){
            foreach(explode(',',$request->input('data.tags')) as $items){
                $alias = Str::slug($items, '-');
                $check_alias = Tags::where('alias',$alias)->where('type',SystemsModuleType::PRODUCT)->where('type_id',$product->id)->count();

                if($check_alias == 0 ){
                    Tags::create([
                        'name' => $items,
                        'alias' => $alias,
                        'type' => SystemsModuleType::PRODUCT,
                        'type_id' => $product->id,
                        'lang' => $product->lang,
                    ]);
                }
            }
        }

        if(isset($request->category_id) && sizeof($request->category_id) > 0) {
            for ($i = 0 ; $i < sizeof($request->category_id); $i++){
                CategoryToProduct::create([
                    'product_id' => $product->id,
                    'category_id' => $request->category_id[$i]
                ]);
            }
        }
        if($id > 0) {
            $old = Product::findOrFail($id);
            add_post_lang($id,$product,$old,AliasType::PRODUCT,$lang);
        }

        return redirect()->route('admin.products.index')->with(['message' => 'Thếm mới thành công!']);
    }
}
