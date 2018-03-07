<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class PubCas extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pubcas', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('pc_id'); //測驗id
            $table->integer('p_id'); //試卷id
            $table->integer('pc_class')->default(0); //班級
            $table->integer('pc_classa')->default(0); //班別
            $table->integer('pc_webid')->default(0); //考卷id
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('pubcas');
    }
}
