<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class PubsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pubs', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('p_id'); //測驗id
            $table->integer('s_id'); //試卷id
            $table->string('p_name', 50);
            $table->string('p_intro', 100);
            $table->string('p_owner',20);
            $table->string('p_begtime',20)->default('');
            $table->string('p_endtime',20)->default('');
            $table->integer('p_created_at');
            $table->integer('p_updated_at');
            $table->string('p_limtime',10)->default('01:00:00');
            $table->char('p_status')->default('N');
            $table->integer('p_again')->default(0);
            $table->integer('p_gra');
            $table->integer('p_subj');
            $table->integer('p_part')->default(0);
            $table->integer('p_sub');
            $table->integer('p_pid');
            $table->integer('p_percen');
            $table->char('p_page')->default('Y');
            $table->integer('p_pass_score')->default(0);
            $table->integer('p_sum')->default(0);
        });
    }
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('pubs');
    }
}