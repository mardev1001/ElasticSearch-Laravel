<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDocumentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('documents', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('document_type_id')->unsigned();//Fk
            $table->integer('user_id')->unsigned();//FK
            $table->integer('version');
            $table->string('name');
            $table->integer('owner_user_id')->unsigned()->nullable();//FK
            $table->integer('document_status_id')->unsigned();//FK
            $table->string('search_tags');
            $table->text('summary');
            $table->timestamp('date_published');
            $table->timestamp('date_expired');
            $table->integer('version_parent');
            $table->integer('document_group_id');
            $table->integer('iso_category_id')->unsigned();//FK
            $table->string('upload_filename');
            $table->boolean('show_name');
            $table->integer('adressat_id')->unsigned();//FK
            $table->string('betreff');
            $table->integer('document_replaced_id');
            $table->timestamp('date_approved');
            $table->boolean('email_approval');
            $table->boolean('approval_all_roles');
            $table->boolean('approval_all_mandants');
            $table->boolean('pdf_upload');
            $table->timestamps();
            $table->softDeletes();
            
            
            $table->foreign('user_id')
                  ->references('id')
                  ->on('users')
                  ->onUpdate('cascade')
                  ->onDelete('cascade');
            
           /* $table->foreign('owner_user_id')
                  ->references('id')
                  ->on('users')
                  ->onUpdate('cascade')
                  ->onDelete('cascade');*/
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
        Schema::drop('documents');
        DB::statement('SET FOREIGN_KEY_CHECKS = 1');
    }
}
