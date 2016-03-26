<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddForeignKeyToUser extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('social_logins', function($table)
        {
            $table->increments('id')->change();
            $table->string('provider_id', 200)->unique();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('social_logins', function ($table) {
            $table->bigInteger('id')->change();
            $table->dropColumn('provider_id');
        });
    }
}
