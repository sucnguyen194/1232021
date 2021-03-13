<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddUserInSupportTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('support', function (Blueprint $table) {
            $table->string('address')->after('email')->nullable();
            $table->string('facebook')->after('zalo')->nullable();
            $table->string('twitter')->after('facebook')->nullable();
            $table->string('instagram')->after('twitter')->nullable();
            $table->string('youtube')->after('instagram')->nullable();
            $table->string('user_id')->after('youtube')->nullable();
            $table->string('user_edit')->after('user_id')->nullable();
            $table->string('public')->after('user_edit')->nullable();
            $table->string('status')->after('public')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('support', function (Blueprint $table) {
            $table->dropColumn('address');
            $table->dropColumn('facebook');
            $table->dropColumn('twitter');
            $table->dropColumn('instagram');
            $table->dropColumn('youtube');
            $table->dropColumn('user_id');
            $table->dropColumn('user_edit');
            $table->dropColumn('public');
            $table->dropColumn('status');
        });
    }
}
