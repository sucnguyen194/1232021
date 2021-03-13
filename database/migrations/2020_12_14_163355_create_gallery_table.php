<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGalleryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gallery', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('title');
            $table->string('alias')->unique();
            $table->longText('description')->nullable();
            $table->string('image')->nullable();
            $table->string('thumb')->nullable();
            $table->integer('category_id')->nullable();
            $table->integer('view')->default(0);
            $table->integer('sort')->default(9999);
            $table->integer('status')->default(0);
            $table->integer('public')->default(1);
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
        Schema::dropIfExists('gallery');
    }
}
