<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePostsTableAddCommentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::rename('documents', 'posts');

        Schema::create('documents', function($table) {
            $table->increments('id');
            $table->integer('post_id')->unsigned();
            $table->string('name');
        });

        Schema::rename('concrete_documents', 'document_versions');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('documents');
        Schema::rename('posts', 'documents');
    }
}
