<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSalariesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('salaries', function (Blueprint $table) {
            $table->bigIncrements('salary_no');
            $table->unsignedBigInteger('emp_no');
            $table->unsignedDouble('amount');
            $table->date('from_date')->nullable();
            $table->date('to_date')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('emp_no')->references('emp_no')->on('employees')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('salaries');
    }
}
