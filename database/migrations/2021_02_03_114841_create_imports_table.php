<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateImportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('imports', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('agency_id')->nullable();
            $table->integer('total')->default(0);
            $table->integer('checkout')->default(0);
            $table->integer('debt')->default(0);
            $table->longText('note')->nullable();
            $table->integer('status')->default(0);
            $table->timestamps();
        });

//        Schema::create('import_sessions', function (Blueprint $table) {
//            $table->bigIncrements('id');
//            $table->integer('import_id')->nullable();
//            $table->integer('user_id')->default(0);
//            $table->integer('agency_id')->default(0);
//            $table->integer('product_id')->default(0);
//            $table->integer('amount')->default(0);
//            $table->integer('price')->default(0);
//            $table->timestamps();
//        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('imports');
//        Schema::dropIfExists('import_sessions');
    }
}
