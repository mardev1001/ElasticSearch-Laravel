<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('username')->unique();
            $table->string('username_sso')->unique();
            $table->string('password');
            $table->timestamp('last_login');
            $table->string('title', 50);
            $table->string('first_name', 200);
            $table->string('last_name', 200);
            $table->string('short_name', 50);
            $table->string('email',200)->unique();
            $table->boolean('email_reciever');
            $table->string('picture', 200);
            $table->boolean('active');
            $table->timestamp('active_from');
            $table->timestamp('active_to');
            $table->rememberToken();
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
        Schema::drop('users');
        DB::statement('SET FOREIGN_KEY_CHECKS = 1');
    }
}
