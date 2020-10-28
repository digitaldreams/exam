<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTokenAndIpColumnToExamUserTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('exam_user', function (Blueprint $table) {
            $table->string('token', 190)->nullable();
            $table->string('ip', 100)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('exam_user', function (Blueprint $table) {
            $table->dropColumn(['token', 'ip']);
        });
    }
}
