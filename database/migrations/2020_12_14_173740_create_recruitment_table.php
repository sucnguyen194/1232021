<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRecruitmentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('recruitment', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('title');
            $table->string('alias')->unique();
            $table->integer('amount')->default(0);
            $table->decimal('experience')->default(0);
            $table->integer('category_id');
            $table->text('salary')->nullable();
            $table->text('diploma')->nullable();
            $table->text('benefit')->nullable();
            $table->text('profile')->nullable();
            $table->longText('requirement')->nullable();
            $table->string('address')->nullable();
            $table->string('image')->nullable();
            $table->string('thumb')->nullable();
            $table->string('time_out');
            $table->string('time_work');
            $table->integer('jobs')->default(0);
            $table->integer('partner')->default(0);
            $table->integer('public')->default(1);
            $table->integer('status')->default(0);
            $table->string('title_seo')->nullable();
            $table->string('description _seo')->nullable();
            $table->string('keyword_seo')->nullable();
            $table->string('lang');
            $table->integer('sort')->default(9999);
            $table->integer('view')->default(0);
            $table->integer('user_id');
            $table->integer('editer')->nullable();
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
        Schema::dropIfExists('recruitment');
    }
}
