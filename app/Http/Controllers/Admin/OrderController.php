<?php

namespace App\Http\Controllers\Admin;

use App\Enums\LeverUser;
use App\Enums\ProductSessionType;
use App\Enums\SystemsModuleType;
use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\ProductSession;
use App\Models\User;
use App\Models\UserAgency;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Cart,Session;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        authorize(SystemsModuleType::EXPORT);

        $orders = Order::with(['user','customer','sessions'])
            ->when(\request()->status,function($q, $status){
                $status = $status == 'true' ? 1 : 0;
                $q->whereStatus($status);
            })
            ->when(\request()->user_create,function ($q, $user){
                $q->where('user_create',$user);
            })
            ->when(\request()->customer,function ($q, $customer){
                $q->where('user_id',$customer);
            })
            ->when(date_range(), function ($q, $range){
                $q->whereBetween('created_at', [$range['from']->startOfDay(), $range['to']->endOfDay()]);
            })
            ->get();

        $users = User::where('lever', '>', LeverUser::SUPPERADMIN)->get();

        return view('Admin.Order.list',compact('orders','users'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        authorize(SystemsModuleType::EXPORT);

        $products = Product::selectRaw('id, name, amount')->public()->orderby('name', 'asc')->get();
        $users = User::selectRaw('id,name,account,phone')->whereLever(LeverUser::USER)->orderByDesc('id')->get();
        $agencys = UserAgency::selectRaw('id, name, phone')->status()->orderByDesc('id')->get();

        $selected = Session::has('customer') ? Session::get('customer') : 0;

        $customer = User::find(Session::get('customer'));

        if($customer){
            $price = $customer->exports()->where('product_id', Session::get('export_product'))->latest()->take(1)->pluck('price');
            $price_in = ProductSession::where('product_id',Session::get('export_product'))->latest()->take(1)->pluck('price_in');
        }

        $price_in = isset($customer) &&  $price_in->count() ? $price_in[0] : "Chưa nhập giá";
        $price = isset($customer) && $price->count() ? $price[0] : "Mua lần đầu";

        $product = Product::find(Session::get('export_product')) ?? null;

        if($product){
            $array = $product->pluck('id')->toArray();
            foreach(Cart::instance('export')->content() as $cart){
                if(!in_array($cart->id,$array))
                    Cart::instance('export')->destroy($cart->rowId);
            }
        }

        return view('Admin.Order.add',compact('products','users','customer','agencys','selected','price','price_in','product'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        authorize(SystemsModuleType::EXPORT);

        switch ($request->send){
            case "cancel":
                Cart::instance('export')->destroy();
                Session::forget('user');
                Session::forget('export_product');
                return flash('Hủy đơn thành công',1);
                break;

            case "save":
                $request->validate([
                    'checkout' => 'integer',
                    'user' => 'required|min:1',
                ]);
                $transport = $request->transport;
                $discount = $request->discount;
                $total = str_replace(',','',Cart::instance('export')->subtotal(0));
                $checkout = $request->checkout;
                $user = User::find($request->user);
                Order::create([
                    'user_id' => $user->id,
                    'user_create' => Auth::id(),
                    'address' => $user->address ?? "Chưa nhập địa chỉ",
                    'phone' => $user->phone ?? 'Chưa nhập số điện thoại',
                    'email' => $user->email,
                    'content' => json_encode(Cart::instance('export')->content()),
                    'total' => $total,
                    'checkout' => $checkout,
                    'transport' => $transport,
                    'discount' => $discount,
                    'debt' => $total + $transport - $checkout - $discount,
                    'note' => $request->note,
                ]);
                return flash('Tạo đơn hàng thành công!',1,route('admin.orders.index'));
                break;

            default;
                return flash('Lỗi',0);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Order $order)
    {
        authorize(SystemsModuleType::EXPORT);
        return view('Admin.Order.edit',compact('order'));

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Order $order)
    {
        authorize(SystemsModuleType::EXPORT);

        $users = User::selectRaw('id,name,account')->whereLever(LeverUser::ADMIN)->get();
        $customers = User::selectRaw('id,name,account,phone')->whereLever(LeverUser::USER)->get();
        $agencys = UserAgency::status()->get()->pluck('name','id','phone');
        $products = Product::selectRaw('id,name,amount, price')->whereNotIn('id', $order->sessions()->pluck('product_id')->toArray())->public()->orderby('name', 'asc')->get();
        $array = $products->pluck('id')->toArray();

        foreach(Cart::instance('export_'.$order->id)->content() as $cart){
            if(!in_array($cart->id,$array))
                Cart::instance('export_'.$order->id)->destroy($cart->rowId);
        }

        if(in_array(Session::get('export_product'), $order->sessions()->get()->pluck('product_id')->toArray())){
            Session::forget('export_product');
        }

        $selected = Session::has('customer') ? Session::get('customer') : 0;

        $customer = User::find($order->user_id);

        if($customer){
            $price = $customer->imports()->where('product_id', Session::get('export_product'))->latest()->take(1)->pluck('price_in');
            $price_in = ProductSession::where('product_id',Session::get('export_product'))->latest()->take(1)->pluck('price_in');
        }

        $price_in = isset($customer) &&  $price_in->count() ? $price_in[0] : "Chưa nhập giá";
        $price = isset($customer) && $price->count() ? $price[0] : "Mua lần đầu";

        $product = Product::find(session()->get('export_product'));

        return view('Admin.Order.edit',compact('order','users','customers','products','selected','price','price_in','product','agencys'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Order $order)
    {
        authorize(SystemsModuleType::EXPORT);

        switch ($request->send){
            case "cancel":
                Cart::instance('export')->destroy();
                Session::forget('user');
                return flash('Hủy đơn hàng thành công',1);
                break;

            case 'update':
                $request->validate([
                    'user' => 'required|integer',
                    'customer' => 'required|integer',
                ]);
                $user = User::find($request->user);
                $customer = User::find($request->customer);
                if(!$user || !$customer)
                    return flash('Thông tin chưa được cập nhật!',3);

                if($customer->id  <> $order->customer->id){
                    $order->customer->increaseBalance(-$order->debt,'Thay đổi thông tin khách hàng - đơn hàng #'.$order->id, $order);
                    $order->customer()->update([
                        'debt' => $order->customer->debt - $order->debt,
                    ]);
                    $customer->increaseBalance($order->debt,'Thay đổi thông tin khách hàng - đơn hàng #'.$order->id, $order);
                    $customer->update([
                        'debt' => $customer->debt + $order->debt,
                    ]);
                }

                $order->update([
                    'user_id' => $customer->id,
                    'user_create' => $user->id,
                    'note' => $request->note
                ]);
                break;

            case 'checkout':
                $request->validate([
                    'checkout' => 'integer|min:0',
                    'transport' => 'integer|min:0',
                ]);

                $checkout = $request->checkout;
                $transport = $request->transport;
                $discount = $request->discount;
                $total = str_replace(',','',Cart::instance('export_'.$order->id)->subtotal(0));

                foreach(Cart::instance('export_'.$order->id)->content() as $cart):
                    $session = ProductSession::create([
                        'order_id' => $order->id,
                        'user_id' => $order->user_id,
                        'user_create' => Auth::id(),
                        'product_id' => $cart->id,
                        'amount' => $cart->qty,
                        'price' => $cart->price,
                        'price_in' => $cart->options->price_in,
                        'revenue' => $cart->options->revenue,
                        'type' => ProductSessionType::getKey(ProductSessionType::export)
                    ]);

                    //update số lượng sản phẩm được nhập
                    $amount = $session->product->amount;
                    $amount = $amount - $cart->qty;
                    $session->product()->update(['amount' => $amount]);
                    endforeach;

                    $total = $order->total + $total;
                    $debt = $total + $transport - $checkout - $discount;
                    //update công nọ user (khách hàng)
                    $debt_customer = $order->customer->debt + ($debt - $order->debt);
                    $order->customer->increaseBalance($debt - $order->debt,'Cập nhật đơn hàng #'.$order->id, $order);
                    $order->customer()->update(['debt' => $debt_customer]);

                    //update doanh thu order
                    $revenue = $order->sessions()->sum('revenue');
                    //$debt = $order->debt + $order->discount + $order->transport + $order->checkout;
                    $order->update([
                        'total' => $total,
                        'checkout' => $checkout,
                        'transport' => $transport,
                        'discount' => $discount,
                        'debt' => $debt,
                        'revenue' => $revenue - $discount
                    ]);

                    Session::forget('customer');
                    Session::forget('export_product');
                    Cart::instance('export_'.$order->id)->destroy();


//                $debt = ($order->customer->debt - $order->debt) + ($order->total - $transport - $checkout);
                //update công nợ của khách hàng
//                $order->customer()->update([
//                    'debt' => $debt,
//                ]);
                //$order->customer->increaseBalance(,'Cập nhật đơn hàng #'.$order->id, $order);
                //update orders
//                $revenue = $order->sessions()->sum('revenue');
//                $order->update([
//                   'checkout' => $checkout,
//                   'transport' => $transport,
//                   'debt' => $order->total - $checkout,
//                   'revenue' => $revenue
//                ]);
                return flash('Cập nhật thành công!',1);
                break;
            case 'save':
//                $request->validate([
//                    'checkout' => 'integer',
//                    'transport' => 'integer',
//                ]);
//                $transport = $request->transport;
//                $total = str_replace(',','',Cart::instance('export')->subtotal(0));
//                $checkout = $request->checkout;
//
                break;
        }
        return flash('Cập nhật thành công!',1);
    }
    public function getItemSession($id){
        authorize(SystemsModuleType::EXPORT);
        $session = ProductSession::find($id);
        if (!$session) return 'error';
        return $session->load('product');
    }
    public function updateItemSession($id, $amount, $price, $revenue){
        authorize(SystemsModuleType::EXPORT);
        $product_session = new ProductSession();
        $session = ProductSession::find($id);
        $product_session->updateAmountSessionAfter($session->product_id, $amount);

        if(!$session) return 'error';

        //update số lượng sản phẩm
        $session->product()->update([
           'amount' =>  $session->product->amount + ($session->amount - $amount)
        ]);
        //update công nợ
        $debt_old = $session->user->debt - $session->export->debt;
        $total = $session->export->total - ($session->amount * $session->price) + ($amount * $price);
        $debt = $total - $session->export->checkout;
        $session->user->increaseBalance($debt - $session->export->debt,'Cập nhật sản phẩm #'.$session->product->id.' - đơn hàng #'.$session->export->id, $session->export);
        $session->user()->update([
            'debt' => $debt_old + $debt
        ]);
        //$revenue = $price * $amount - $session->price_in * $amount;
        $revenue_export = $session->export->revenue - $session->revenue + $revenue;
        //update orders
        $session->export()->update([
            'total' => $total,
            'debt' => $debt,
            'revenue' => $revenue_export,
        ]);
        //update sessions
        $session->update([
            'amount' => $amount,
            'price' => $price,
            'revenue' => $revenue
        ]);
        $data['order'] = $session->export()->first();
        $data['session'] = $session->export->sessions()->with('product')->get();

        return $data;
    }
    public function destroyItemSession($id){
        authorize(SystemsModuleType::EXPORT);

        $session = ProductSession::find($id);
        if(!$session) return 'error';

        $product_session = new ProductSession();
        $product_session->updateAmountSessionAfter($session->product_id, $session->amount);
        //update số lượng sản phẩm
        if($session->product){
            $session->product()->update([
                'amount' => $session->amount + $session->product->amount
            ]);
        }
        $total = $session->export->total - ($session->amount * $session->price);
        $debt = $total - $session->export->checkout;
        $revenue = $session->export->revenue - $session->revenue;
        $debt_user = $session->user->debt - $session->export->debt + $debt;
        //update công nợ của khách hàng
        if($session->user){
            $session->user->increaseBalance($debt - $session->export->debt,'Hủy sản phẩm #'.$session->product_id.' - đơn hàng #'.$session->order_id, $session->export);
            $session->user()->update([
                'debt' =>  $debt_user ,
            ]);
        }
        //update orders

        $session->export()->update([
            'total' => $total,
            'debt' => $debt,
            'revenue' => $revenue,
        ]);
        $session->delete();

        $data['order'] = $session->export()->first();
        $data['session'] = $session->export->sessions()->with('product')->get();

        return $data;
    }
    public function addItemCart($order, $id,$qty, $price, $revenue){
        $order = Order::find($order);
        $product = Product::find($id);
        $sesions = $product->imports()->latest()->take(1)->first();
        $weight = request()->weight ?? 0;
        $amount = 0;
        $cart = Cart::instance('export_'.$order->id)->content()->where('id',$id);

        foreach($cart as $item):
            $amount = $item->qty;
        endforeach;

        if($amount + $qty > $product->amount)
            return response()->json('max');

        if($amount > 0 && $amount + $qty <= $product->amount){
            $revenue = $this->getRevenueSession($id, $amount + $qty, $price)->original;
            Cart::instance('export_'.$order->id)->update($cart->first()->rowId,[
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
            Cart::instance('export_'.$order->id)->add([
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

        $data['cart'] = Cart::instance('export_'.$order->id)->content()->sort();
        $data['total'] = Cart::instance('export_'.$order->id)->subtotal(0);
        $data['count'] = Cart::instance('export_'.$order->id)->count();
        $data['max'] = $amount;

        return response()->json($data);

    }
    public function getItemCart($order, $rowId){
        authorize(SystemsModuleType::EXPORT);
        $order = Order::find($order);
        $item = Cart::instance('export_'.$order->id)->get($rowId);
        $data['item'] = $item;
        $data['product'] = Product::find($item->id);
        return $data;
    }
    public function updateItemCart($order, $rowId, $amount, $price, $revenue){

        authorize(SystemsModuleType::EXPORT);
        $order = Order::find($order);
        $cart = Cart::instance('export_'.$order->id)->get($rowId);

        $product = Product::find($cart->id);
        if($amount > $product->amount)
            return response()->json('error');
        Cart::instance('export_'.$order->id)->update($rowId, [
            'qty'=> $amount ,
            'price' => $price,
            'options'=> [
                'revenue' => $revenue,
                'price_in' => $cart->options->price_in,
                'amount' => $cart->options->amount,
                'sort' => time()
            ]
        ]);

        $data['cart'] = Cart::instance('export_'.$order->id)->content()->sort();
        $data['total'] = Cart::instance('export_'.$order->id)->subtotal(0);
        return response()->json($data);
    }
    public function destroyItemCart($order, $rowId){
        authorize(SystemsModuleType::EXPORT);
        $order = Order::find($order);
        Cart::instance('export_'.$order->id)->remove($rowId);
        $data['cart'] = Cart::instance('export_'.$order->id)->content()->sort();
        $data['total'] = Cart::instance('export_'.$order->id)->subtotal(0);
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
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Order $order)
    {
        authorize(SystemsModuleType::EXPORT);
        if($order->sessions->count())
            return flash('Bạn không thể thực hiện hành động này!',3);
        $order->delete();
        return flash('Xóa thành công!',1);
    }
}
