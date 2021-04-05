<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class UserDebt extends Model
{
    protected $guarded = ['id'];

    public function transactions(){
        return $this->hasMany(Transaction::class,'user_debt');
    }
    public function increaseBalance($amount, $note='', $model = null){
        $transaction = new Transaction();
        $transaction->amount = $amount;
        $transaction->note = $note;
        $transaction->admin_id = Auth::id();
        if ($model instanceof Model){
            $transaction->source()->associate($model);
        }

        $transaction->balance = $this->debt + $amount;

        DB::transaction(function () use ($transaction){
            $this->transactions()->save($transaction);
        });

        return $this;
    }

}
