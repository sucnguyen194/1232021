<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductSessionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_sessions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('agency_id')->nullable();
            $table->integer('user_id')->nullable();
            $table->integer('user_create')->nullable();
            $table->integer('import_id')->nullable();
            $table->integer('order_id')->nullable();
            $table->integer('product_id')->nullable();
            $table->integer('amount')->nullable();
            $table->integer('price')->nullable();
            $table->integer('price_in')->nullable();
            $table->integer('revenue')->nullable();
            $table->string('type')->nullable();
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
        Schema::dropIfExists('product_sessions');
    }
}
