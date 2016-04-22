<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEditorVariantsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('editor_variants', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('document_id')->unsigned();
            $table->integer('document_status_id')->unsigned();
            $table->text('inhalt');
            $table->string('pdf_upload_filename');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS = 0');
        Schema::drop('editor_variants');
        DB::statement('SET FOREIGN_KEY_CHECKS = 1');
    }
}
