<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExamsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('exams', function (Blueprint $table) {
            $table->id('id');
            $table->foreignId('category_id')->nullable()->constrained('categories', 'id')->onDelete('set null');
            $table->foreignId('user_id')->nullable()->constrained('users', 'id')->onDelete('set null');
            $table->string('status', 20)->default('pending');
            $table->enum('visibility', ['public', 'private'])->default('public');
            $table->string('title');
            $table->string('slug')->unique()->nullable();
            $table->string('description')->nullable();
            $table->string('show_answer', 30)->default('instantly');
            $table->tinyInteger('duration')->nullable();
            $table->string('must_completed')->nullable();

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
        Schema::dropIfExists('exams');
    }
}
