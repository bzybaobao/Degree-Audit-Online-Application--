<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CompletedCourse extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('completed_courses', function (Blueprint $table) {
            $table->integer('uid');
            $table->integer('cid');
            $table->foreign('uid')->references('id')->on('users');
            $table->foreign('cid')->references('id')->on('courses');
            $table->double('grade',15,8);
            $table->primary(['uid','cid']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
