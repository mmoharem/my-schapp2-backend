<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStudPaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stud_payments', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('grade_name');
            $table->decimal('fees');
//            $table->decimal('tot_fees');
            $table->decimal('total_payments')->nullable();
            $table->decimal('old_unpaid')->nullable();
            $table->decimal('to_pay_fees')->nullable();
            $table->decimal('discount')->default(0);
            $table->boolean('discount_verify')->default(false);
            $table->boolean('block')->default(false);
            $table->boolean('permit')->default(false);
            $table->boolean('isOldFees')->nullable();
            $table->text('note')->nullable();
            $table->integer('student_id')->unsigned()->index()->nullable();
            $table->integer('sch_year_id')->unsigned()->nullable()->index();
            $table->timestamps();
            $table->foreign('student_id')->references('id')->on('students');
            $table->foreign('sch_year_id')->references('id')->on('school_years');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('stud_payments');
    }
}
