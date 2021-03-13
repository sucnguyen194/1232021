<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CategoryToProduct extends Model
{
    protected $table = "product_to_category";

    protected $guarded = ['id'];

    public function product(){
        $this->hasMany(Product::class);
    }
}
