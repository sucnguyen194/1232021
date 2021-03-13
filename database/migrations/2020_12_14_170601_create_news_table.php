<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNewsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('news', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('title');
            $table->string('alias')->unique();
            $table->string('image')->nullable();
            $table->string('thumb')->nullable();
            $table->text('description')->nullable();
            $table->longText('content')->nullable();
            $table->integer('category_id')->default(0);
            $table->integer('user_id')->default(0);
            $table->integer('view')->default(0);
            $table->string('title_seo')->nullable();
            $table->string('description_seo')->nullable();
            $table->string('keyword_seo')->nullable();
            $table->string('lang');
            $table->integer('status')->default(0);
            $table->integer('public')->default(1);
            $table->integer('sort')->default(9999);
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
        Schema::dropIfExists('news');
    }
}
