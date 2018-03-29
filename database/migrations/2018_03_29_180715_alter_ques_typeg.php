<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
//增加題組類 題目父id
class AlterQuesTypeg extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ques', function (Blueprint $table) {
            $table->integer('q_pid')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ques', function (Blueprint $table) {
            $table->dropColumn('q_pid');
        });
    }
}
