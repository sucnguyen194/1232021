<?php

namespace App\Models;

use App\Enums\MediaType;
use Illuminate\Database\Eloquent\Model;

class Media extends Model
{
    protected $table = 'media';

    protected $guarded = ['id'];

    public function gallery(){
        return $this->belongsTo(Gallerys::class,'type_id');
    }
    public function product(){
        return $this->belongsTo(Product::class,'type_id');
    }
    public function scopePublic($q){
        $q->wherePublic(1);
    }
}
