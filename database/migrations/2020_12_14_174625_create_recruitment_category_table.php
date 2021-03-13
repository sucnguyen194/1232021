<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRecruitmentCategoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('recruitment_category', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('title');
            $table->string('alias')->unique();
            $table->integer('parent_id')->default(0);
            $table->string('image')->nullable();
            $table->string('thumb')->nullable();
            $table->integer('user_id');
            $table->integer('editer')->nullable();
            $table->text('description')->nullable();
            $table->integer('public')->default(1);
            $table->integer('status')->default(0);
            $table->string('title_seo')->nullable();
            $table->string('description _seo')->nullable();
            $table->string('keyword_seo')->nullable();
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
        Schema::dropIfExists('recruitment_category');
    }
}
