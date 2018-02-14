<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ExamDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //學生考卷明細
        Schema::create('exam_details', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('ed_id');
            $table->integer('s_id'); //試卷id
            $table->integer('ed_eid')->default(0); //學生卷id
            $table->integer('ed_sort'); //題號
            $table->integer('ed_qid'); //題目id
            $table->string('ed_ans',50)->default(''); //答案
            $table->integer('ed_times')->default(0); //作答時間
            $table->integer('ed_right')->default(0); //正確或錯誤
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('exam_details');
    }
}
