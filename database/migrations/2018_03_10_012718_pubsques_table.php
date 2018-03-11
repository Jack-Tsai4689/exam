<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class PubsquesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pubsque', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('pq_id');
            $table->integer('pq_pid');
            $table->integer('pq_part');
            $table->integer('pq_sort');
            $table->integer('pq_qid');
            $table->string('pq_ans', 50)->default('');
            $table->integer('pq_num');
            $table->char('pq_quetype', 1);
            $table->string('pq_quetxt', 2000)->default('');
            $table->string('pq_qm_src', 100)->default('');
            $table->string('pq_qm_name', 260)->default('');
            $table->string('pq_qs_src', 100)->default('');
            $table->string('pq_qs_name', 260)->default('');
            $table->string('pq_anstxt', 2000)->default('');
            $table->string('pq_am_src', 100)->default('');
            $table->string('pq_am_name', 260)->default('');
            $table->string('pq_as_src', 100)->default('');
            $table->string('pq_as_name', 260)->default('');
            $table->string('pq_av_src', 100)->default('');
            $table->string('pq_av_name', 260)->default('');
            $table->char('pq_degree',1);
            $table->integer('pq_gra');
            $table->integer('pq_subj');
            $table->integer('pq_chap');
            $table->integer('pq_created_at');
            $table->integer('pq_updated_at');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('pubsque');
    }
}
