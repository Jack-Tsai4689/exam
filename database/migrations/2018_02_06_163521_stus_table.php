<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class StusTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stus', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('st_id');
            $table->string('st_no',10);
            $table->string('st_name', 20);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('stus');
    }
}
