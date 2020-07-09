<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreaeExamUserAnswerTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('exam_user_answer', function (Blueprint $table) {
            $table->id('id');
            $table->bigInteger('exam_user_id')->unsigned();
            $table->bigInteger('question_id')->unsigned();
            $table->text('answer')->nullable();
            $table->tinyInteger('status')->default(0);
            $table->integer('spent_time')->default(0)->nullable();
            $table->foreign('exam_user_id')->references('id')->on('exam_user')->onDelete('cascade');
            $table->foreign('question_id')->references('id')->on('questions')->onDelete('cascade');
            $table->timestamps();;
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('exam_user_answer');
    }
}
