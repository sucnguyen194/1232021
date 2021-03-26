<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddReCatchaInSiteOptionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('site_option', function (Blueprint $table) {
            $table->string('facebook_app_secret')->after('facebook_app_ip')->nullable();
            $table->string('re_captcha_key')->after('facebook_app_secret')->nullable();
            $table->string('re_captcha_secret')->after('re_captcha_key')->nullable();
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
            $table->dropColumn('facebook_app_secret');
            $table->dropColumn('re_captcha_key');
            $table->dropColumn('re_captcha_secret');
        });
    }
}
