<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderSessionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
//        Schema::create('order_sessions', function (Blueprint $table) {
//            $table->bigIncrements('id');
//            $table->integer('order_id');
//            $table->integer('user_id');
//            $table->integer('user_create');
//            $table->integer('product_id');
//            $table->integer('amount')->default(0);
//            $table->integer('price')->default(0);
//            $table->integer('price_in')->default(0);
//            $table->integer('revenue')->default(0);
//            $table->integer('status')->default(0);
//            $table->timestamps();
//        });

        Schema::table('orders',function (Blueprint $table){
           $table->integer('user_create')->after('user_id');
           $table->integer('checkout')->after('total')->default(0);
           $table->integer('debt')->after('checkout')->default(0);

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
//        Schema::dropIfExists('order_sessions');

        Schema::table('orders',function (Blueprint $table){
           $table->dropColumn('user_create');
           $table->dropColumn('checkout');
           $table->dropColumn('debt');
        });
    }
}
