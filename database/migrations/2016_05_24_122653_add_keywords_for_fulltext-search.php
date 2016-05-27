<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddKeywordsForFulltextSearch extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // keywords table
        Schema::create('keywords', function($table) {
            $table->increments('id');
            $table->string('value', 255)->unique();
        });

        // keywords table
        Schema::create('document_keyword', function($table) {
            $table->integer('document_id')->unsigned();
            $table->integer('keyword_id')->unsigned();
            $table->primary(['document_id', 'keyword_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('keywords');
        Schema::drop('document_keyword');
    }
}
