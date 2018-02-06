<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ExamsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //學生考卷
        Schema::create('exams', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('e_id');
            $table->string('e_stu',7)->default(''); //學號
            $table->integer('s_id'); //試卷id
            $table->integer('e_pid')->default(0); //大題id
            $table->integer('e_sub')->default(0); //有大題
            $table->integer('e_begtime_at'); //開始測驗時間
            $table->integer('e_endtime_at')->default(0); //結束測驗時間
            $table->integer('e_used_time')->default(0); //使用秒數
            $table->integer('e_rnum')->default(0); //答對題數
            $table->integer('e_wnum')->default(0); //答錯題數
            $table->integer('e_nnum')->default(0); //未答題數
            $table->decimal('e_score', 3, 2)->default(0.0); //總分
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('exams');
    }
}
