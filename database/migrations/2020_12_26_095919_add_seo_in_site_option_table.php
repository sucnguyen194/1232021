<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSeoInSiteOptionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('site_option', function (Blueprint $table) {
            $table->text('description_seo')->after('remarketing_footer')->nullable();
            $table->text('keyword_seo')->after('remarketing_footer')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('site_option', function (Blueprint $table) {
            $table->dropColumn('description_seo');
            $table->dropColumn('keyword_seo');
        });
    }
}
