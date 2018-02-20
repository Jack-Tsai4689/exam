<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class SetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sets', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('s_id');
            $table->string('s_name',50)->default('');
            $table->string('s_intro',100)->default('');
            $table->string('s_owner',20);
            $table->string('s_begtime',20)->default('');
            $table->string('s_endtime',20)->default('');
            $table->integer('created_at');
            $table->integer('updated_at');
            $table->string('s_limtime',10)->default('1:00:00');
            $table->integer('s_finish')->default(0);
            $table->integer('s_again')->default(0);
            $table->integer('s_gra');
            $table->integer('s_subj');
            $table->integer('s_pass_score')->default(60);
            $table->integer('s_sum')->default(100);
            $table->integer('s_part')->default(0);;
            $table->integer('s_sub');
            $table->integer('s_pid');
            $table->integer('s_percen')->default(100);
            $table->char('s_page')->default('Y');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('sets');
    }
}
