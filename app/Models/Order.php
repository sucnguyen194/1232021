<?php

namespace App\Models;

use App\Enums\ProductSessionType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Cart;
use Illuminate\Support\Facades\Session;

class Order extends Model
{
    protected $guarded = ['id'];

    protected $table = 'orders';

    protected $casts = [
        'content' => 'array'
    ];

    public function user(){
        return $this->belongsTo(User::class,'user_create');
    }

    public function customer(){
        return $this->belongsTo(User::class,'user_id');
    }
    public function sessions(){
        return $this->hasMany(ProductSession::class)->whereType(ProductSessionType::getKey(ProductSessionType::export));
    }

    public static function boot()
    {
        parent::boot(); // TODO: Change the autogenerated stub

        static::created(function($order){
            $sessions = new ProductSession();
            foreach(Cart::instance('export')->content() as $cart):
                $sessions->updateAmountSession($cart->id, $cart->qty);

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
            //update công nọ user (khách hàng)
            $debt = $order->customer->debt + $order->debt;
            $order->customer->increaseBalance($order->debt,'Tạo mới đơn hàng #'.$order->id, $order);
            $order->customer()->update(['debt' => $debt]);
            //update doanh thu order
            $revenue = $order->sessions()->sum('revenue');
            $revenue = $revenue - $order->discount;
            $order->update(['revenue' => $revenue]);

            Session::forget('customer');
            Session::forget('export_product');
            Cart::instance('export')->destroy();
        });
    }
}
