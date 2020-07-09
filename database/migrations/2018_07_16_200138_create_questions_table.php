<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateQuestionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('questions', function (Blueprint $table) {
            $table->id('id');
            $table->bigInteger('parent_id')->unsigned()->nullable();
            $table->foreignId('user_id')->nullable()->constrained('users', 'id')->onDelete('set null');
            $table->string('type');
            $table->string('questionable_type')->nullable();
            $table->bigInteger('questionable_id')->nullable();
            $table->text('title')->nullable();
            $table->text('options')->nullable();
            $table->text('answer')->nullable();
            $table->string('review_type', 30)->default('auto');
            $table->text('explanation')->nullable();
            $table->text('data')->nullable();
            $table->tinyInteger('total_mark')->unsigned()->default(5);
            $table->string('hints')->nullable();
            $table->string('answer_type', 50)->default('single')->nullable();

            $table->foreign('parent_id')->references('id')->on('questions')->onDelete('cascade');

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
        Schema::dropIfExists('questions');
    }
}
