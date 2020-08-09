<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDeptEmpTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dept_emps', function (Blueprint $table) {
            $table->bigIncrements('dept_emp_no');
            $table->unsignedBigInteger('dept_no');
            $table->unsignedBigInteger('emp_no');
            $table->date('from_date')->nullable();
            $table->date('to_date')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('dept_no')->references('dept_no')->on('departments')->onDelete('cascade');
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
        Schema::dropIfExists('dept_emp');
    }
}
