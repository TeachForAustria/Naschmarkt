<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

use Illuminate\Support\Facades\Schema;

class AddInitalDataStructure extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // add the is_staff attribute to users
        Schema::table('users', function($table) {
            $table->boolean('is_staff')->default(false);
        });

        // create document table
        Schema::create('documents', function($table) {
            $table->increments('id');
            $table->string('name', 200);
            $table->text('description')->default('');
            $table->integer('access_count')->unsigned()->default(0);
            $table->integer('owner_id')->unsigned();
            $table->timestamps();
        });

        // tags table
        Schema::create('tags', function($table) {
            $table->increments('id');
            $table->string('value', 50)->unique();
        });

        // n:n relationship between document and tag
        Schema::create('document_tags', function($table) {
            $table->integer('document_id')->unsigned();
            $table->integer('tag_id')->unsigned();
            $table->primary(['document_id', 'tag_id']);
        });

        // n:n relationship between document and tag
        Schema::create('concrete_document', function($table) {
            $table->increments('id');

            $table->integer('version')->unsigned()->default(0);
            $table->integer('document_id')->unsigned();
            $table->unique(['document_id', 'version']);

            $table->binary('content');

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
        // drop created tables
        Schema::dropIfExists('concrete_document');
        Schema::dropIfExists('document_tags');
        Schema::dropIfExists('tags');
        Schema::dropIfExists('documents');

        // remove is_staff column
        Schema::table('users', function ($table) {
            $table->dropColumn('is_staff');
        });
    }
}
