<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class QuesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ques', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('q_id');
            $table->char('q_quetype', 1)->default('');
            $table->string('q_quetxt', 2000)->default('');
            $table->string('q_qm_src', 100)->default('');
            $table->string('q_qm_name', 255)->default('');
            $table->string('q_qs_src', 100)->default('');
            $table->string('q_qs_name', 255)->default('');
            $table->string('q_ans', 50)->default();
            $table->string('q_anstxt', 2000)->default('');
            $table->string('q_am_src', 100)->default('');
            $table->string('q_am_name', 255)->default('');
            $table->string('q_as_src', 100)->default('');
            $table->string('q_as_name', 255)->default('');
            $table->string('q_av_src', 100)->default('');
            $table->string('q_av_name', 255)->default('');
            $table->string('q_owner', 20);
            $table->char('q_degree', 1);
            $table->integer('q_gra');
            $table->integer('q_subj');
            $table->integer('q_chap');
            $table->integer('q_created_at');
            $table->integer('q_updated_at');
            $table->string('q_keyword', 50)->default('');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('ques');
    }
}
