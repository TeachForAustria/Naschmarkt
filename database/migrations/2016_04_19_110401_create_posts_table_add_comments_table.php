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
            $table->timestamps();
        });

        Schema::rename('concrete_documents', 'document_versions');
        Schema::rename('document_tag', 'post_tag');

        Schema::table('post_tag', function ($table) {
            $table->renameColumn('document_id', 'post_id');
        });
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
        Schema::rename('document_versions', 'concrete_documents');
        Schema::rename('post_tag', 'document_tag');

        Schema::table('document_tag', function ($table) {
            $table->renameColumn('post_id', 'document_id');
        });
    }
}
