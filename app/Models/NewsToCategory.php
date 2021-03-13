<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NewsToCategory extends Model
{
    protected  $table = 'news_to_category';

    protected $fillable = ['news_id','category_id'];
}
