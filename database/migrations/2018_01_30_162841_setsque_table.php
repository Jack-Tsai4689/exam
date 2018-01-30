<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class SetsqueTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('setsque', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('sq_id');
            $table->integer('sq_sid')->default(0);
            $table->integer('sq_part')->default(0);
            $table->integer('sq_qid')->default(0);
            $table->integer('sq_sort')->default(1);
            $table->string('sq_owner',20);
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
        //
    }
}
