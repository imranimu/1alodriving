<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCourseLessonsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('course_lessons', function (Blueprint $table) {
            $table->id();
            $table->integer('course_id');
            $table->integer('module_id');
            $table->string('title',255)->nullable();
            $table->string('lesson_type', 20);
            $table->string('video', 200)->nullable();
            $table->string('audio', 200)->nullable();
            $table->string('text_pdf', 200)->nullable();
            $table->integer('sort')->default(0);
            $table->enum('status',['0','1'])->default(1)->comment('0 = Inactive, 1 = Active');
            $table->integer('created_by')->default(0);
            $table->integer('updated_by')->default(0);
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
        Schema::dropIfExists('course_lessons');
    }
}
