<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class PostTablesAndFolderTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('projects', function ($table){
            $table->increments('id');
            $table->integer('owner_id');
            $table->string('name', 200);
            $table->timestamps();
        });

        Schema::create('folders', function ($table){
            $table->increments('id');
            $table->string('name', 200);
            $table->integer('project_id');
            $table->timestamps();
        });

        Schema::create('folder_post', function ($table){
            $table->integer('folder_id');
            $table->integer('post_id');
            $table->primary(['folder_id', 'post_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('folder_post');
        Schema::drop('folders');
        Schema::drop('projects');
    }
}
