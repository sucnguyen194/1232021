<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductSession extends Model
{
    protected $guarded = ['id'];

    public function product(){
        return $this->belongsTo(Product::class,'product_id');
    }

    public function import(){
        return $this->belongsTo(Import::class,'import_id');
    }

    public function export(){
        return $this->belongsTo(Order::class,'order_id');
    }

    public function agency(){
        return $this->belongsTo(UserAgency::class,'agency_id');
    }

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function admin(){
        return $this->belongsTo(User::class,'user_create');
    }
    public function updateAmountSessionAfter($id, $quantity){
        $session = new ProductSession();
        $session = $session->whereType('import')->whereProductId($id)->latest()->first();
        $amount =  $session->amount_export - abs($quantity);
        if($amount >= 0){
            $session->update([
                'amount_export' => $amount,
            ]);
        }else{
            $session->update([
                'amount_export' => 0
            ]);
            $this->subUpdateAmountSessionAfter($session,abs($amount));

        }
    }

    public function subUpdateAmountSessionAfter($item, $quantity){

        $session = $item->where('id','<',$item->id)->whereType('import')->whereProductId($item->product_id)->latest()->first();

        $amount = $session->amount - $quantity;
        if($amount >= 0) {
            $session->update([
                'amount_export' => $amount,
            ]);
        }else{
            $session->update([
                'amount_export' => 0
            ]);
            return $this->subUpdateAmountSessionAfter($session,abs($amount));

        }
    }
    public function updateAmountSession($id,$quantity){

        $session = new ProductSession();
        $session = $session->whereType('import')->whereProductId($id)->oldest()->get();

        $session->each(function($item,$key) use ($quantity){
            if($item->amount >  $item->amount_export){
                $amount = $item->amount - $item->amount_export - abs($quantity);
                if($amount >= 0){
                    $item->update([
                        'amount_export' => $item->amount_export + $quantity
                    ]);
                }else{
                    $item->update([
                        'amount_export' => $item->amount
                    ]);
                    $this->subUpdateAmountSession($item,abs($amount));
                }
                return false;
            }
        });
    }
    public function subUpdateAmountSession($item, $quantity){

        $session = $item->where('id','>',$item->id)->whereType('import')->whereProductId($item->product_id)->oldest()->first();

        $amount = $session->amount - $quantity;
        if($amount >= 0) {
            $session->update([
                'amount_export' => $session->amount_export + $quantity
            ]);
        }else{
            $session->update([
                'amount_export' => $session->amount
            ]);
            return $this->subUpdateAmountSession($session,abs($amount));
        }
    }
}
