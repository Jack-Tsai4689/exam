<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
//增加題庫檔案外連 選擇性
class AlterQuesUrl extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ques', function (Blueprint $table) {
            $table->string('q_qm_url', 300)->default('');
            $table->string('q_qs_url', 300)->default('');
            $table->string('q_am_url', 300)->default('');
            $table->string('q_as_url', 300)->default('');
            $table->string('q_av_url', 300)->default('');
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
            $table->dropColumn('q_qm_url');
            $table->dropColumn('q_qs_url');
            $table->dropColumn('q_am_url');
            $table->dropColumn('q_as_url');
            $table->dropColumn('q_av_url');
        });
    }
}
