<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSiteOptionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('site_option', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('company')->nullable();
            $table->string('path')->nullable();
            $table->string('slogan')->nullable();
            $table->string('logo')->nullable();
            $table->string('favicon')->nullable();
            $table->string('watermark')->nullable();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();

            $table->string('facebook')->nullable();
            $table->string('facebook_app_ip')->nullable();
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
        Schema::dropIfExists('site_option');
    }
}
