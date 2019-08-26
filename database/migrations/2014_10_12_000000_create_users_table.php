<?php

use Illuminate\Support\Facades\Schema;
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
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->bigInteger('social_id');
            $table->string('firstName');
            $table->string('lastName');
            $table->text('address');
            $table->string('gender');
            $table->date('birthDate');
            $table->string('email')->unique();
            $table->bigInteger('phoneNumber');
            $table->bigInteger('mobilePhone');
            $table->text('medicalState')->nullable();
            $table->text('notes')->nullable();
            // $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
}
