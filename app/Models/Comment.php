<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    protected $guarded = ['id'];

    public function comment(){
        return $this->morphTo();
    }

    public function user(){
        return $this->belongsTo(User::class);
    }
    public function admin(){
        return $this->belongsTo(User::class,'admin_id');
    }

    public function scopeWithnameUser($q){
        $q->addSelect([
           'user_name' => User::selectRaw('id')->whereColumn('id','comments.user_id')->take(1)
        ]);
    }

    function find_class($type){
        switch ($type){
            case 'products':
                return new Product();
                break;
            case 'news':
                return new News();
                break;
            case 'pages':
                return new Pages();
                break;
            case 'videos':
                return new Videos();
                break;
            case 'gallerys':
                return new Gallerys();
                break;
        }
    }
}
