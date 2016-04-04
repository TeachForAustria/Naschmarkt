<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AdaptConcreteDocumentForFilesystemStorage extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('concrete_documents', function($table) {
            $table->uuid('uuid')->unique();
            $table->string('extension', 50);

            $table->dropColumn('content');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('concrete_documents', function($table) {
            $table->dropColumn('uuid');
            $table->dropColumn('extension');

            $table->binary('content');
        });
    }
}
