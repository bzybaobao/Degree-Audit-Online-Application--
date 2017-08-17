<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CoreCourse extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('core_courses', function (Blueprint $table) {
            $table->integer('mid');
            $table->integer('cid');
            $table->foreign('mid')->references('id')->on('majors');
            $table->foreign('cid')->references('id')->on('courses');
            $table->primary(['mid','cid']);
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
