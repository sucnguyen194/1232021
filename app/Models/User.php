<?php

namespace App\Models;

use App\Enums\ProductSessionType;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = "users";

    protected $fillable = [
        'name', 'email', 'password','account','avata','address','phone','lever'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function getGravatarAttribute(){
        return 'https://www.gravatar.com/avatar/'.md5(strtolower($this->email ?? $this->account)).'.jpg?s=200&d=identicon';
    }

    public function systems(){
        return $this->belongsToMany(System::class);
    }
    public function sessions(){
        return $this->hasMany(ProductSession::class,'user_id');
    }

    public function imports(){
        return $this->hasMany(ProductSession::class,'user_id')->whereType(ProductSessionType::getKey(ProductSessionType::import));
    }

    public function exports(){
        return $this->hasMany(ProductSession::class,'user_id')->whereType(ProductSessionType::getKey(ProductSessionType::export));
    }

    public function transactions(){
        return $this->hasMany(Transaction::class);
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

    public static function boot(){
        parent::boot();

        static::deleting(function($user){
            File::delete($user->avata);
            $user->transactions()->delete();
        });
    }
}
