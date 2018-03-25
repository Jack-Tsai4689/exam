<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterQuesTypec extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ques', function (Blueprint $table) {
            $table->string('q_cgroup', 100)->default('');
            $table->string('q_cans', 255)->default('');
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
            $table->dropColumn('q_cgroup');
            $table->dropColumn('q_cans');
        });
    }
}
