<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFeedbacksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('exam_feedback', function (Blueprint $table) {
            $table->id('id');
            $table->bigInteger('user_id')->unsigned()->nullable();
            $table->string('feedbackable_type')->nullable();
            $table->bigInteger('feedbackable_id')->unsigned()->nullable();
            $table->double('rating')->default(0);
            $table->string('feedback')->nullable();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
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
        Schema::dropIfExists('exam_feedback');
    }
}
