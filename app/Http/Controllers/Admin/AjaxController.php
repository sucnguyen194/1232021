<?php namespace App\Http\Controllers\Admin;

use App\Enums\ProductSessionType;
use App\Enums\SystemsModuleType;
use App\Http\Controllers\Controller;
use App\Models\Attribute;
use App\Models\Category;
use App\Models\Import;
use App\models\Photo;
use App\Models\Post;
use App\Models\Product;
use App\Models\ProductSession;
use App\Models\Support;
use App\Models\SystemsModule;
use App\Models\User;
use App\Models\UserAgency;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Request,Session,Cart,Mail, Image;

class AjaxController extends Controller {

    public function removePhoto($id){
        $photo = Photo::find($id);

        if($photo->pic == 1){
            $update = Photo::whereSort(1)->first();
            $update->update(['pic' => 1]);
            $update->product()->update([
               'image' => $update->image,
               'thumb' => $update->thumb
            ]);
        }
        if(file_exists($photo->image)){
            unlink($photo->image);
        }
        if(file_exists($photo->thumb)){
            unlink($photo->thumb);
        }
        $photo->delete();
        return response()->json('success');
    }

    public function uploadPhoto($id, $type){
        $files = request()->file('files');
        $count = count($files);
        $data = Product::find($id);
        $sort = $data->photos->count();

        for($i=0 ; $i < $count ; $i++){
            $file = request()->file('files')[$i];
            $file->store('photo');
            $path = $file->hashName('photo/thumb');
            $resizeThumb = Image::make($file);
            $resizeThumb->fit(375, 375, function ($constraint) {
                $constraint->aspectRatio();
            });
            Storage::put($path, (string) $resizeThumb->encode());
            $thumb = "storage/".$path;
            $image = "storage/".$file->hashName('photo');

            Photo::create([
                'name' => $data->name ?? $data->title,
                'image' => $image,
                'thumb' => $thumb,
                'type_id' => $data->id,
                'type' => $type,
                'user_id' => \Auth::id(),
                'public' => 1,
                'sort' => $i + $sort,
                'lang' => $data->lang
            ]);
        }
        if(empty($data->image)){
            $photo = Photo::whereSort(0)->whereType($type)->whereTypeId($id)->first();
            if($photo){
                $data->update([
                    'thumb' => $photo->thumb,
                    'image' => $photo->image
                ]);
            }
        }

        return response()->json($data);
    }
    public function setAltPhoto($id, $alt){
        $photo = Photo::find($id);
        $photo->update(['name' => $alt]);
        return response()->json('ok');
    }

    public function getAltPhoto($id){
        $photo = Photo::find($id);
        $data['image'] = asset($photo->image);
        $data['name'] = $photo->name;
        $data['id'] = $photo->id;
        return response()->json($data);
    }

    public function setPositionPhoto($json){
        $photo = json_decode($json);
        foreach ($photo as $key => $id){
            $photo = Photo::find($id);
            $photo->update(['sort' => $key,'pic' => 0]);
            if($photo->sort == 0){
                $photo->product()->update([
                    'image' => $photo->image,
                    'thumb' => $photo->thumb,
                ]);
            }
        }
        return response()->json('ok');
    }

    public function getSystemsModule($type){
        switch ($type){
            case SystemsModuleType::PRODUCT:
                $data = Product::find(request()->id);
                break;
            case SystemsModuleType::CATEGORY:
                $data = Category::find(request()->id);
                break;
            case SystemsModuleType::POST:
                $data = Post::find(request()->id);
                break;
            case SystemsModuleType::SUPPORT:
                $data = Support::find(request()->id);
                break;
            case SystemsModuleType::PHOTO:
                $data = Photo::find(request()->id);
                break;
            case SystemsModuleType::SYSTEMS_MODULE:
                $data = SystemsModule::find(request()->id);
                break;
            case SystemsModuleType::ATTRIBUTE:
                $data = Attribute::find(request()->id);
                break;

            default;
        }
        return $data;
    }

    public function getEditDataSort(){
        $data = $this->getSystemsModule(request()->type);
        $data->update(['sort' => request()->num,'user_edit' => Auth::id()]);
        return $data;
    }

    public function getEditDataStatus(){
        $data = $this->getSystemsModule(request()->type);
        $status = $data->status == 1 ? 0 : 1;
        $data->update(['status' => $status,'user_edit' => \Auth::id()]);
        return $data;
    }

    public function getEditDataPublic(){
        $data = $this->getSystemsModule(request()->type);
        $public = $data->public == 1 ? 0 : 1;
        $data->update(['public' => $public,'user_edit' => \Auth::id()]);
        return $data;
    }

    public function getEditMenuSort(){
        $menu = request()->val;
        $menu = json_decode($menu);
        menu_update_position($menu);
    }

    //Import
    public function setImportProduct($id, $amount, $price){
        $product = Product::find($id);
        $weight = request()->weight ?? 0;
        $qty = $amount ?? 1;

        $cart = Cart::instance('import')->content()->where('id',$id);
        if(count($cart)){
            Cart::instance('import')->update($cart->first()->rowId,[
                'id'=>$product->id,
                'name'=>$product->name,
                'price'=> $price,
                'weight'=> $weight,
                'qty'=>$cart->first()->qty + $qty,
                'options' => [
                    'sort' => time()
                ],
            ]);
        }else{
            Cart::instance('import')->add([
                'id'=>$product->id,
                'name'=>$product->name,
                'price'=> $price,
                'weight'=> $weight,
                'qty'=>$qty,
                'options' => [
                    'sort' => time()
                ],
            ]);
        }

        $data['cart'] = Cart::instance('import')->content()->sort();
        $data['total'] = Cart::instance('import')->subtotal(0);
        $data['count'] = Cart::instance('import')->count();

        return response()->json($data);
    }
    public function getImportProduct(){
        $id = request()->id;
        $product = Product::find($id);
        session()->put('import_product', $id);
        $agency = UserAgency::find(request()->agency);
        $price = $agency->imports()->where('product_id', $product->id)->latest()->take(1)->pluck('price_in');
        $price_in = ProductSession::where('product_id',$product->id)->latest()->take(1)->pluck('price_in');
        $data['product'] = $product;

        $data['price_in'] = $price_in->count() ? $price_in : "Chưa nhập giá";
        $data['price'] = $price->count() ? $price : "Nhập lần đầu";

        return response()->json($data);
    }
    public function getImportAgency()
    {
        $id = request()->id;
        $agency = UserAgency::find($id);
        session()->put('agency', $id);

        $product = Product::find(request()->product);
        $price = $agency->imports()->where('product_id', $product->id)->latest()->take(1)->pluck('price_in');
        $price_in = ProductSession::where('product_id',$product->id)->latest()->take(1)->pluck('price_in');

        $data['price_in'] = $price_in->count() ? $price_in : "Chưa nhập giá";
        $data['price'] = $price->count() ? $price : "Nhập lần đầu";
        $data['agency'] = $agency->id;
        $data['product'] = $product;

        return response()->json($data);
    }

    public function getItemImport($rowId){
        $item = Cart::instance('import')->get($rowId);
        return $item;
    }

    public function setDestroyItemImport($rowId){
        Cart::instance('import')->remove($rowId);
        $data['cart'] = Cart::instance('import')->content();
        $data['total'] = Cart::instance('import')->subtotal(0);
        return response()->json($data);
    }
    public function setUpdateItemImport($rowId, $amount, $price){
        Cart::instance('import')->update($rowId, ['qty'=> $amount ,'price' => $price]);
        $data['cart'] = Cart::instance('import')->content();
        $data['total'] = Cart::instance('import')->subtotal(0);
        return response()->json($data);
    }
    ////////////
    //End Import
    ////////////

    //Export

    //Add Cart
    public function setExportProduct($id, $qty, $price, $revenue){

        $product = Product::find($id);
        $sesions = $product->imports()->latest()->take(1)->first();
        $weight = request()->weight ?? 0;
        $amount = 0;

        $cart = Cart::instance('export')->content()->where('id',$id);
        foreach($cart as $item):
            $amount = $item->qty;
        endforeach;

        if($amount + $qty > $product->amount)
            return response()->json('max');

        if($amount > 0 && $amount + $qty <= $product->amount){
            $revenue = $this->getRevenueSession($id, $amount + $qty, $price)->original;
            Cart::instance('export')->update($cart->first()->rowId,[
               'id' => $id,
                'name' => $product->name,
                'price' => $price,
                'weight'=> $weight,
                'qty'=> $amount + $qty,
                'options' => [
                    'revenue' => $revenue,
                    'price_in' => $sesions ? $sesions->price_in : null,
                    'amount' => $product->amount,
                    'sort' => time()
                ]
            ]);
        }else{
            Cart::instance('export')->add([
                'id'=>$product->id,
                'name'=>$product->name,
                'price'=>$price,
                'weight'=> $weight,
                'qty'=>$qty,
                'options' => [
                    'revenue' => $revenue,
                    'price_in' => $sesions ? $sesions->price_in : null,
                    'amount' => $product->amount,
                    'sort' => time()
                ]
            ]);
        }
        $data['revenue'] = $revenue;
        $data['cart'] = Cart::instance('export')->content()->sort();
        $data['total'] = str_replace(',','',Cart::instance('export')->subtotal(0)) ;
        $data['count'] = Cart::instance('export')->count();
        $data['max'] = $amount;

        return response()->json($data);

    }

    public function getExportProduct(){
        $id = request()->id;
        $product = Product::find($id);
        Session::put('export_product', $id);
        $user = User::find(request()->user);
        //lấy price trong order_sessions
        $price = $user->exports()->where('product_id',$product->id)->latest()->take(1)->pluck('price');
        $price_in = ProductSession::where('product_id',$product->id)->latest()->take(1)->pluck('price_in');

        $data['product'] = $product;
        $data['price_in'] = $price_in->count() ? $price_in : "Chưa nhập";
        $data['price'] = $price->count() ? $price : "Mua lần đầu";

        return response()->json($data);
    }
    public function getExportUser()
    {
        $id = request()->id;
        session()->put('customer', $id);
        if($id > 0)
        $user = User::find($id);
        $product = Product::find(request()->product);
        $price_in = 0;
        $price = 0;
        if($user && $product){
            $price = $user->exports()->where('product_id',$product->id)->latest()->take(1)->pluck('price');
            $price_in = ProductSession::where('product_id',$product->id)->latest()->take(1)->pluck('price_in');

            $price_in = isset($user) && $price_in->count() ? $price_in : 'Chưa nhập';
            $price = isset($user) && $price->count() ? $price : 'Mua lần đầu';
        }
        $data['customer'] = $user ? $user->id : 0;
        $data['product'] = $product;
        $data['price_in']  = $price_in;
        $data['price']  = $price;
        $data['products'] = Product::selectRaw('id,name,amount')->public()->get();

        return response()->json($data);

    }
    public function setUpdateProduct($id, $amount,$price,$checkout,$agency,$customer){
        $agency = UserAgency::find($agency);
        $product = Product::find($id);

        if($amount < 1)
            return "Số lượng phải lớn hơn 0";

        //Tạo đơn nhập hàng

        $import = Import::create([
            'user_id' => Auth::id(),
            'agency_id' => $agency->id,
            'total' => $price*$amount,
            'checkout' => $checkout,
            'debt' => $price*$amount - $checkout,
        ]);

       // Tạo lịch sử nhập hàng của sản phẩm
       $session = ProductSession::create([
           'agency_id' => $agency->id,
           'user_create' => Auth::id(),
           'import_id' => $import->id,
           'product_id' => $product->id,
           'amount' => $amount,
           'price_in' => $price,
           'type' => ProductSessionType::getKey(ProductSessionType::import),
       ]);
       //Update số lượng sản phẩm trong table product
        $amounts = $session->product->amount;
        $amounts = $amounts + $amount;
        $session->product()->update(['amount' => $amounts]);

        //Update công nợ của NCC
        $debt = $import->agency->debt;
        $debt = $debt + $import->debt;
        $import->agency->increaseBalance($import->debt,'Nhập mới sản phẩm #'.$session->product->id.' - đơn hàng #'.$session->import->id, $session->import);
        $import->agency()->update(['debt' => $debt]);
        $user = User::find($customer);
        $product = Product::findOrFail($id);
        $price = $user->imports()->latest()->take(1)->pluck('price');
        $price_in = ProductSession::where('product_id',$product->id)->latest()->take(1)->pluck('price_in');

        $data['product'] = $product;
        $data['lists'] = Product::selectRaw('id, name, amount')->public()->get();
        $data['price_in'] = $price_in->count() ? $price_in : 'Chưa nhập';
        $data['price'] = $price->count() ? $price : 'Mua lần đầu';

        return $data;
    }

    public function getItemExport($rowId){
        $item = Cart::instance('export')->get($rowId);
        $data['item'] = $item;
        $data['product'] = Product::find($item->id);
        return $data;
    }
    public function setDestroyItemExport($rowId){
        Cart::instance('export')->remove($rowId);
        $data['cart'] = Cart::instance('export')->content();
        $data['total'] = str_replace(',','',Cart::instance('export')->subtotal(0)) ;
        return response()->json($data);
    }
    public function setUpdateItemExport($rowId, $amount, $price,$revenue){

        $cart = Cart::instance('export')->get($rowId);
        $product = Product::find($cart->id);
        if($amount > $product->amount)
            return response()->json('error');

        Cart::instance('export')->update($rowId, [
            'qty'=> $amount ,
            'price' => $price,
            'options'=> [
                'revenue' => $revenue,
                'price_in' => $cart->options->price_in,
                'amount' => $cart->options->amount,
                'sort' => time()
            ]
        ]);
        $data['cart'] = Cart::instance('export')->content()->sort();
        $data['total'] = Cart::instance('export')->subtotal(0);
        return response()->json($data);
    }

    public function getProductUpdate($id){
        $data['product'] = Product::find($id);
        $data['session'] = ProductSession::where('product_id',$id)->latest()->first();
        return response()->json($data);
    }

    public function getDataPrint($customer){
        $data['customer'] = User::find($customer);
        $data['time'] = date('d/m/Y H:i', time());
        return response()->json($data);
    }

    public function getRevenueSession($id,$quantity,$price){
        $item = ProductSession::where('amount','>','amount_export')->whereProductId($id)->whereType('import')->oldest()->first();

        $amount = $item->amount - abs($quantity);
        if($amount >= 0){
            $revenue = $price * $quantity - $item->price_in *  $quantity;
        }else{
            $revenue = $price * $item->amount - $item->price_in *  $item->amount;
            $revenue += $this->sumRevenueSession($item,abs($amount),$price);
        }
        return response()->json($revenue);
    }

    public function sumRevenueSession($item, $quantity, $price){
        $session = $item->where('id','>',$item->id)->whereProductId($item->product_id)->whereType('import')->oldest()->first();
        $amount = $session->amount - $quantity;
        if($amount >= 0) {
            $revenue = $price * abs($quantity) - $session->price_in *  abs($quantity);
        }else{
            $revenue = $price * $session->amount - $session->price_in *  $session->amount;
            $revenue += $this->sumRevenueSession($session, abs($amount),$price);
        }
        return $revenue;
    }

    public function getRenvenueAfter($id, $quatity,$price){

        $order = ProductSession::find($id);
        $session = ProductSession::whereProductId($order->product_id)->whereType('import')->latest()->first();

        if($price == $order->price){
            $revenue_order = $order->revenue;
            $amount_order = $order->amount - $quatity;
            $amount = $session->amount_export - $amount_order;
            if($amount >= 0){
                $revenue = $revenue_order - (($amount_order * $price) - ($amount_order * $session->price_in));
            }else{
                $revenue_session = ($session->amount_export * $price) - ($session->amount_export * $session->price_in);
                $revenue_session += $this->sumRevenueAfter($session, abs($amount), $price);
                $revenue = $revenue_order - $revenue_session;
            }
        }else{
            $revenue = $this->getRevenueSession(12, $quatity,$price)->original;
        }

        return response()->json($revenue);
    }
    public function sumRevenueAfter( $item,$quantity, $price)
    {
        $session = ProductSession::where('id','<',$item->id)->whereProductId($item->product_id)->whereType('import')->latest()->first();
        $amount = $session->amount_export - abs($quantity);
        if($amount >= 0) {
            $revenue = ($quantity * $price) - ($quantity * $session->price_in);
        }else{
            $revenue = ($session->amount_export * $price) - ($session->amount_export * $session->price_in);
            $revenue += $this->sumRevenueAfter($session, abs($amount), $price);
        }
        return  $revenue;
    }
    //End Export

    //////////////////////////////////////////////////////////////////
}
