<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddUserInGalleryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('gallery', function (Blueprint $table) {
            $table->integer('user_id')->after('category_id')->nullable();
            $table->integer('user_edit')->after('user_id')->nullable();
            $table->string('title_seo')->after('user_edit')->nullable();
            $table->string('description_seo')->after('title_seo')->nullable();
            $table->string('keyword_seo')->after('description_seo')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('gallery', function (Blueprint $table) {
            $table->dropColumn('user_id');
            $table->dropColumn('user_edit');
            $table->dropColumn('title_seo');
            $table->dropColumn('description_seo');
            $table->dropColumn('keyword_seo');
        });
    }
}
