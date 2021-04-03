<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('company')->nullable();
            $table->string('path')->nullable();
            $table->string('slogan')->nullable();
            $table->string('logo')->nullable();
            $table->string('favicon')->nullable();
            $table->string('og_image')->nullable();
            $table->string('watermark')->nullable();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();

            $table->string('facebook')->nullable();
            $table->string('zalo')->nullable();
            $table->string('facebook_app_ip')->nullable();
            $table->string('facebook_app_secret')->nullable();
            $table->string('re_captcha_key')->nullable();
            $table->string('re_captcha_secret')->nullable();
            $table->string('messenger')->nullable();
            $table->string('google')->nullable();
            $table->string('skype')->nullable();
            $table->string('youtube')->nullable();
            $table->string('twitter')->nullable();
            $table->string('ins')->nullable();
            $table->string('lin')->nullable();
            $table->string('pin')->nullable();

            $table->string('address')->nullable();
            $table->string('hotline')->nullable();
            $table->string('time_open')->nullable();
            $table->string('fax')->nullable();
            $table->longText('contact')->nullable();
            $table->longText('footer')->nullable();
            $table->text('map')->nullable();
            $table->string('numbercall')->nullable();
            $table->longText('remarketing_header')->nullable();
            $table->longText('remarketing_footer')->nullable();
            $table->text('description_seo')->nullable();
            $table->text('keyword_seo')->nullable();
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
        Schema::dropIfExists('settings');
    }
}
