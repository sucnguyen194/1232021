<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNewsCategoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('news_category', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('title');
            $table->string('alias')->unique();
            $table->string('image')->nullable();
            $table->string('thumb')->nullable();
            $table->longText('description')->nullable();
            $table->integer('parent_id')->nullable();
            $table->integer('status')->default(0);
            $table->integer('public')->default(1);
            $table->text('title_seo')->nullable();
            $table->text('description_seo')->nullable();
            $table->text('keyword_seo')->nullable();
            $table->string('lang');
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
        Schema::dropIfExists('news_category');
    }
}
