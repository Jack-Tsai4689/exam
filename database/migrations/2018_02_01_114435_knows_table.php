<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class KnowsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('knows', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('k_id');
            $table->string('k_name', 50);
            $table->string('k_pic', 100)->default('');
            $table->integer('k_gra');
            $table->integer('k_subj');
            $table->integer('k_chap');
            $table->string('k_content', 1000)->default('');
            $table->string('k_owner', 20);
            $table->integer('created_at');
            $table->integer('updated_at');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('knows');
    }
}
