<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddObtainMarkColumnInExamUserAnswerTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('exam_user_answer', function (Blueprint $table) {
            $table->dropColumn('spent_time');
            $table->tinyInteger('obtain_mark')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('exam_user_answer', function (Blueprint $table) {
            $table->integer('spent_time')->default(0)->nullable();
            $table->dropColumn('obtain_mark');
        });
    }
}
