<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSeoInVideoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('videos', function (Blueprint $table) {
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
        Schema::table('videos', function (Blueprint $table) {
            $table->dropColumn('title_seo');
            $table->dropColumn('description_seo');
            $table->dropColumn('keyword_seo');
        });
    }
}
