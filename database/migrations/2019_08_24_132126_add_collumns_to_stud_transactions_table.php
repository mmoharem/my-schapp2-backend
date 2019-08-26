<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCollumnsToStudTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('stud_transactions', function (Blueprint $table) {
            $table->integer('sch_year_id')->unsigned()->nullable()->index();
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
        Schema::table('stud_transactions', function (Blueprint $table) {
            //
        });
    }
}
