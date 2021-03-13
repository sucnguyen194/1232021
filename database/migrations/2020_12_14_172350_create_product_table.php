<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('alias')->unique();
            $table->integer('price')->default(0);
            $table->integer('price_sale')->default(0);
            $table->string('image')->nullable();
            $table->string('thumb')->nullable();
            $table->integer('amount')->default(0);
            $table->string('code')->nullable();
            $table->text('description')->nullable();
            $table->longText('content')->nullable();
            $table->integer('category_id')->default(0);
            $table->integer('user_id');
            $table->integer('view')->default(0);
            $table->integer('public')->default(1);
            $table->integer('status')->default(0);
            $table->integer('sort')->default(9999);
            $table->string('lang');
            $table->string('title_seo')->nullable();
            $table->string('description_seo')->nullable();
            $table->string('keyword_seo')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('product');
    }
}
